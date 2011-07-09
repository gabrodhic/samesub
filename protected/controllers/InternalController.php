<?php

class InternalController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{

		
		//This script generates and stores RBAC data in the database for future consumption bye the AuthManager component of the yii framework.
		//This script needs to be run only once. If the content is changed needs to be runned again to update the data in the databse.
		//Shema of the database table: yii\framework\web\auth\schema.sql
		// Now yii is ready to be used so we start defining our RBAC list
		$auth=Yii::app()->authManager;



		//1. User operations

		//2. Subject operations
		//we use most of the operations names by using $this->action->Id(action name) AND  $this->id(controller name) in the controller ;
		$auth->createOperation('subject_update','update a Subject');
		$auth->createOperation('subject_manage','manage a Subject');
		$auth->createOperation('subject_moderate','moderate a Subject');
		$auth->createOperation('subject_authorize','authorize a Subject');
		$auth->createOperation('subject_delete','delete a Subject');

		//We need to check for the subject_update operation instead of subject_updateown
		//since it will pass automatically by the subject_updateown validation and if true then will go to subject_update
		//Only owner user and super can subject_update
		$bizRule='return Yii::app()->user->id==$params["Subject"]["user_id"];';
		$task=$auth->createTask('subject_updateown','update a Subject by creator user himself',$bizRule);
		$task->addChild('subject_update');

		// Define all roles and their respective operations or tasks
		$role=$auth->createRole('user');
		$role->addChild('subject_updateown');

		$role=$auth->createRole('moderator');
		$role->addChild('subject_moderate');
		$role->addChild('subject_manage');
		$role->addChild('user');

		$role=$auth->createRole('authorizer');
		$role->addChild('subject_authorize');
		$role->addChild('subject_manage');
		$role->addChild('user');

		$role=$auth->createRole('administrator');
		$role->addChild('user');
		$role->addChild('moderator');
		$role->addChild('authorizer');


		$role=$auth->createRole('super');
		$role->addChild('administrator');
		$role->addChild('subject_update');
		$role->addChild('subject_delete');




		// Assign roles to specific users
		$auth->assign('user','3');
		$auth->assign('user','1');
		$auth->assign('moderator','editorC');
		$auth->assign('administrator','authorB');
		$auth->assign('super','2');
		
		echo 'Done updating RBAC';
	}
	
	
	/**
	 * This is a cron that sets the next subject to be showed 
	 * and the next subject to be cached(subject_id_1 and subject_id_2)
	 * 
	 */
	public function actionSetNextSubject()
	{
		if($_SERVER['REMOTE_ADDR'] <> '127.0.0.1') die();//Only allow to run this locally
		
		$command =Yii::app()->db->createCommand();
		//If the table its empty by any reason(initial import), insert something to make the UPDATE work
		if(! $command->select('count(*) as num')->from('live_subject')->queryScalar()){
			 $command->insert('live_subject', array('subject_id_1'=>0,'subject_id_2'=>0));
		}

 		//Find authorized subjects that hasn't been shown
		$un_shown_subject =  Yii::app()->db->createCommand()
		->select('*')
		->from('subject')
		->where('approved=:approved AND authorized=:authorized AND show_time=:show_time AND disabled=0',
		array(':approved'=>1,':authorized'=>1,':show_time'=>0))
		->order('priority_id DESC , time_submitted ASC')
		->queryRow(); //print_r($un_shown_subjects);
		//Subject::model()->findAll(
		//array( 	'condition'=>'approved=:approved AND authorized=:authorized AND show_time=:show_time',
			//	'params'=>array(':approved'=>1,':authorized'=>1,':show_time'=>0),
				//'order'=>'id,priority_id DESC'
			//));
		

		if(! $un_shown_subject){
			echo 'No un shown subjects.<br>';
			
			$live_subject = Yii::app()->db->createCommand()->select('*')->from('live_subject')->queryRow();
			$shown_subject =  Yii::app()->db->createCommand()->select('*')->from('subject')
			->where('id<>:id1 AND id<>:id2 AND show_time>:show_time AND authorized=:authorized AND disabled=0', 
			array(':id1'=>$live_subject['subject_id_1'], ':id2'=>$live_subject['subject_id_2'],':show_time'=>0, 'authorized'=>1))
			->order('show_time ASC')
			->queryRow();//we take the first, thats the oldest one that has been shown
//			Subject::model()->findAll(
//				array( 	'condition'=>array('AND','id<>:id1','id<>:id2','show_time>:show_time'),
//				'params'=>array(':id1'=>$live_subject->subject_id_1,':id2'=>$live_subject->subject_id_2,':show_time'=>0),
//				'order'=>'id ASC'
//			));			
			print_r($shown_subject);

			$next_subject_id_2 = $shown_subject['id'];

		}else{
			echo 'Yes unshown subjects.<br>';
			$next_subject_id_2 = $un_shown_subject['id'];
		}


		
		
		$live_subject = Yii::app()->db->createCommand()->select('*')->from('live_subject')->queryRow();
		
		
			$command->delete('live_comment');
			$command->update('live_subject', array(
			'last_comment_number'=>0,
			'comment_sequence'=>0,
			));
		
			$command->update('live_subject', array(
			'subject_id_1'=>$live_subject['subject_id_2'],
			'subject_id_2'=>$next_subject_id_2,
			));
			//TEMPORAL:Refill the live_comments table with old comments about this subject if this subject is repeated
			$past_comments = Yii::app()->db->createCommand()->select('code,time,comment,sequence')->from('comment t1')->where('subject_id ='.$live_subject['subject_id_2'])
			->leftJoin('country t2', 'country_id=t2.id')->order('time ASC')->queryAll();
			echo "<br>gggg";print_r($past_comments);
			$i = 0;
			foreach($past_comments as $past_comment){
				$i++;
				$country_code = ($past_comment['code']) ? $past_comment['code'] : "WW";
				$command->insert('live_comment',array('subject_id'=>$live_subject['subject_id_2'], 'comment_country'=>$country_code,'comment_time'=>$past_comment['time'],'comment_text'=>$past_comment['comment'],'comment_sequence'=>$i));//we neet to use our own sequence because there might be repeated numbers
			}
			if($i > 0)$command->update('live_subject', array('last_comment_number'=>Yii::app()->db->getLastInsertID(),'comment_sequence'=>$i,));
			
			
		
		
		Subject::model()->updateByPk($next_subject_id_2, array('show_time'=>SiteLibrary::utc_time()));
		echo 'Done setting next subject_id_2 : '.$next_subject_id_2;
		
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
	    if($error=Yii::app()->errorHandler->error)
	    {
	    	if(Yii::app()->request->isAjaxRequest)
	    		echo $error['message'];
	    	else
	        	$this->render('error', $error);
	    }
	}

	
}