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
			$past_comments = Yii::app()->db->createCommand()->select('code,time,comment,sequence,username')->from('comment t1')->where('subject_id ='.$live_subject['subject_id_2'])
			->leftJoin('country t2', 'country_id=t2.id')
			->leftJoin('user t3', 'user_id=t3.id')->order('time ASC')->queryAll();
			echo "<br>gggg";print_r($past_comments);
			$i = 0;
			foreach($past_comments as $past_comment){
				$i++;
				$country_code = ($past_comment['code']) ? $past_comment['code'] : "WW";
				$command->insert('live_comment',array('username'=>$past_comment['username'],'subject_id'=>$live_subject['subject_id_2'], 'comment_country'=>$country_code,'comment_time'=>$past_comment['time'],'comment_text'=>$past_comment['comment'],'comment_sequence'=>$i));//we neet to use our own sequence because there might be repeated numbers
			}
			if($i > 0)$command->update('live_subject', array('last_comment_number'=>Yii::app()->db->getLastInsertID(),'comment_sequence'=>$i,));
			
			
		
		
		Subject::model()->updateByPk($next_subject_id_2, array('show_time'=>SiteLibrary::utc_time()));
		
		//Notify subject owner via email that his subject its gonna get LIVE
		$subject = Subject::model()->findByPk($next_subject_id_2);
		$user = User::model()->findByPk($subject->user_id);
		if($user->id <> 1 and $user->notify_subject == 1){
			$headers="From: Samesub Contact <".Yii::app()->params['contactEmail'].">\r\nReply-To: ".Yii::app()->params['contactEmail'];
			$mail_message = "Hi {$user->username}, \n\n";
			$mail_message .= "We are writing to notify you that your subject got approved and that it is\n";
			$mail_message .= "going to be placed in the live stream(Homepage) in the next 5 minutes.\n\n";
			$mail_message .= "Details\n\n";
			$mail_message .= "Subject Title: {$subject->title}\n";
			$mail_message .= "Uploaded time: ".date("Y/m/d H:i", $subject->time_submitted)." UTC\n";
			$mail_message .= "Current time: ".date("Y/m/d H:i", SiteLibrary::utc_time())." UTC (time of this message)\n";
			$mail_message .= "Estimated time: ".date("Y/m/d H:i", (SiteLibrary::utc_time()+300))." UTC (about 5 minutes)\n\n";
			$mail_message .= "It is even more cool if you chat with your friends about your upcomming subject.\n";
			$mail_message .= "So, invite them to go to samesub.com now, you still have 4 minutes.\n\n";
			$mail_message .= "If you do not want to receive this type of notification you can update the settings in\n";
			$mail_message .= "your user profile anytime you want.\n\n";
			$mail_message .= "Thanks\n\n";
			$mail_message .= "Sincerely\n";
			$mail_message .= "Samesub Team\n";
			$mail_message .= "www.samesub.com";				

			if(@mail($user->email,"Your subject is going LIVE",$mail_message,$headers)){
				echo "An email has been sent.";
			}else{
				echo "Email could not be sent.";
			}
		}
		echo 'Done setting next subject_id_2 : '.$next_subject_id_2;
		
	}

	/**
	 * Executed by cron to update the country_id column in the log table
	 * (because doing so in each record insertion would impact user navigation performance)
	 * so we use a cron instead.
	 */
	public function actionUpdateCountryLog()
	{
		if($_SERVER['REMOTE_ADDR'] <> '127.0.0.1') die();//Only allow to run this locally
		$this->no_log = true;//This is just to control the logging functionality
		$cached_ips = array();
		$cached_country = array();
		$ip = '';
		$command =Yii::app()->db->createCommand();
		$country_logs = Yii::app()->db->createCommand()->select('id, client_ip, request_ip, client_country_id, request_country_id')
		->from('log_detail')->where("cronned = 0 AND request_ip <> '127.0.0.1'")
		->order('id ASC')->limit(100)->queryAll();
		foreach($country_logs as $country_log){
			
			if(strlen($country_log['request_ip']) > 7){//4x# 4x. minimum valid ip
				
				$ip = $country_log['request_ip'];
				if (! in_array($ip, $cached_ips)){//don't overuse this service unnecessary
					$cached_ips[] = $ip;
					if($country = SiteLibrary::get_country_from_ip($ip)){
						$command->update('log_detail', array('cronned'=>1,'request_country_id'=>$country->id)
						,'id=:id', array(':id'=>$country_log['id']));
						$cached_country[$ip] = $country->id;
					}
				}else{
					$command->update('log_detail', array('cronned'=>1,'request_country_id'=>$cached_country[$ip])
						,'id=:id', array(':id'=>$country_log['id']));
				}
			}
			
			if(strlen($country_log['client_ip']) > 7){//4x# 4x. minimum valid ip 
				
				if(strstr($country_log['client_ip'], ',')){
					$country_log['client_ip'] = str_replace(' ', '', $country_log['client_ip']);
					$ips = explode(",", $country_log['client_ip']);
					reset($ips);//get the first ip, is supposed to be where the request originated from
					$ip = current($ips);
				}else{
					$ip = $country_log['client_ip'];
				}
				if (! in_array($ip, $cached_ips)) {//don't overuse this service unnecessary
					$cached_ips[] = $ip;
					
					if($country = SiteLibrary::get_country_from_ip($ip)){
						
						$command->update('log_detail', array('cronned'=>1,'client_country_id'=>$country->id)
						,'id=:id', array(':id'=>$country_log['id']));
						$cached_country[$ip] = $country->id;
					}
				}else{
					
					$command->update('log_detail', array('cronned'=>1,'client_country_id'=>$cached_country[$ip])
						,'id=:id', array(':id'=>$country_log['id']));
				}
			}
			//Wether country was updated or not we should mark it as cronned so we don't process this record again
			$command->update('log_detail', array('cronned'=>1),'id=:id', array(':id'=>$country_log['id']));
			
		}
		
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