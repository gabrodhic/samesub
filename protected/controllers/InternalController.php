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
	 * and the next subject to be cached(subject_id and subject_id_2)
	 * 
	 */
	public function actionSetNextSubject()
	{
		if($_SERVER['REMOTE_ADDR'] <> '127.0.0.1') die();//Only allow to run this locally
		
		$command =Yii::app()->db->createCommand();
		//If the table its empty by any reason(initial import), insert something to make the UPDATE work
		if(! $command->select('count(*) as num')->from('live_subject')->queryScalar()){
			 $command->insert('live_subject', array('subject_id'=>0,'subject_id_2'=>0));
		}
		
		//Position all subs on its time
		Subject::reschedule_positions();
		$round_utc_time = SiteLibrary::utc_time_interval();
		
		
		//Remote case: This update is just in case cron didn't run in x times of interva(s)
		//This frees up subs that never were used because they were fixed position but cron failed to run and time passed by
		Subject::model()->updateAll(array('position'=>'0','user_position'=>'0','manager_position'=>'0'), 'position < '.$round_utc_time .' AND user_position < '.$round_utc_time.' AND manager_position < '.$round_utc_time);
		
		$subject = Subject::model()->find(array('condition'=>'position >= '.$round_utc_time.' AND approved=1 AND authorized=1 AND disabled=0 AND deleted=0', 'order'=>'position ASC'));
		

		$live_subject = Yii::app()->db->createCommand()->select('*')->from('live_subject')->queryRow();
		
		
		$command->delete('live_comment');
		$command->update('live_subject', array(
		'comment_id'=>0,
		'comment_number'=>0,
		));
	

		//TEMPORAL:Refill the live_comments table with old comments about this subject if this subject is repeated
		$past_comments = Yii::app()->db->createCommand()->select('t1.id,code,time,comment,comment_number,username,likes,dislikes')->from('comment t1')->where('subject_id ='.$subject->id)
		->leftJoin('country t2', 'country_id=t2.id')
		->leftJoin('user t3', 'user_id=t3.id')->order('time ASC')->queryAll();
		echo "<br>gggg";print_r($past_comments);
		$i = 0;
		foreach($past_comments as $past_comment){
			$i++;
			$country_code = ($past_comment['code']) ? $past_comment['code'] : "WW";
			$command->insert('live_comment',array('comment_id'=>$past_comment['id'],'username'=>$past_comment['username'],'subject_id'=>$subject->id, 'comment_country'=>$country_code,'comment_time'=>$past_comment['time'],'comment_text'=>$past_comment['comment'],'comment_number'=>$i,'likes'=>$past_comment['likes'],'dislikes'=>$past_comment['dislikes']));//we neet to use our own sequence because there might be repeated numbers
			$comment_id = $past_comment['id'];
		}
		if($i > 0)$command->update('live_subject', array('comment_id'=>$comment_id,'comment_number'=>$i,));
		$command->update('live_subject', array(
		'subject_id'=>$subject->id,
		'scheduled_time'=>SiteLibrary::utc_time_interval(),
		'subject_data'=>serialize($subject),
		));

		//Reset position as subject is going to live now
		Subject::model()->updateByPk($subject->id, array('show_time'=>SiteLibrary::utc_time(),'user_position'=>0,'manager_position'=>0));
		
		//Notify subject owner via email that his subject its gonna get LIVE
		$user = User::model()->findByPk($subject->user_id);
		if($user->id <> 1 and $user->notify_subject_live == 1){		
			$mail_message = Yii::t('subject',"Hi {username}, 
We are writing to notify you that your subject got approved and that it is
going to be placed in the live stream(Homepage) in the next 5 minutes.
Details
Subject Title: {title}
Uploaded time: {uploaded_time} UTC
Current time: {current_time} UTC (time of this message)
Estimated time: {estimated_time} UTC (about 5 minutes)
It is even more cool if you chat with your friends about your upcomming subject.
So, invite them to go to samesub.com now, you still have 4 minutes.
If you do not want to receive this type of notification you can update the settings in
your user profile anytime you want.",array('{username}'=>$user->username,'{title}'=>$subject->title,'{uploaded_time}'=>date("Y/m/d H:i", $subject->time_submitted), '{current_time}'=>date("Y/m/d H:i", SiteLibrary::utc_time()),'{estimated_time}'=>date("Y/m/d H:i", (SiteLibrary::utc_time()+300))));

$mail_message .= "\n\n";		
$mail_message .= Yii::t('site',"Thanks
Sincerely
Samesub Team
www.samesub.com");				

			if(SiteLibrary::send_email($user->email,"Your subject is going LIVE",$mail_message)){
				echo "An email has been sent.";
			}else{
				echo "Email could not be sent.";
			}
		}
		echo 'Done setting next subject_id_2 : '.$subject->id;
		
		//There are some pages that need to be refreshed from the cache such as /subject/index, so that it content reflects the updated data.
		//Make a request(from this localhost) to the /subject/index page with a no-cache RequestHeader to force mod_cache to refresh the last cache state of this url.
		//NOTE: The httpd.conf is configured to only allow a localhost address to be able to send a no-cache RequestHeader		
		$ch = curl_init(Yii::app()->getRequest()->getBaseUrl(true)."/subject/index");
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('User-Agent:'.Yii::app()->params['no_cache_agent'],'Cache-Control:no-cache',' Pragma:no-cache'));
		curl_exec($ch);		
		curl_close($ch);				
		
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