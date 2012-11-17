<?php

class SubjectController extends ApiController
{
	
	//Notice that default filters are defined in ApiController class
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
		);
	}

	/**
	 * Searches for a tag suggestion
	 * @param string $tag the text to search tags
	 */
	public function actionGettags($tag='')
	{
		global $arr_response;
		$tag = $_REQUEST['tag'];
				
		$tags = Subject::getTags($tag);
		if ($tags)$arr_response = $tags;
		
	}
	
	/**
	 * Get the list of subject categories
	 */
	public function actionGetcategories()
	{
		global $arr_response;
				
		$categories = Subject::getCategories();
		$arr_response = $categories;
		
	}
	
	public function actionAdd()
	{
		global $arr_response;
		$model=new Subject;
		$model->scenario='add';
		
		

		if($_REQUEST)
		{
			$_REQUEST['content_type_id'] = ContentType::model()->find('name=:name', array(':name'=>$_REQUEST['content_type']))->id;
			$_REQUEST['country_id'] = Country::model()->find('code=:code', array(':code'=>$_REQUEST['country_code']))->id;
			$_REQUEST['priority_id'] = Priority::model()->find('name=:name', array(':name'=>$_REQUEST['priority']))->id;
			if($_REQUEST['time']){
				if(date("l", $_REQUEST['time'])){//if its a valid timestamp	
					$_REQUEST['user_position_ymd'] = date("Y",$_REQUEST['time'])."/".date("m",$_REQUEST['time'])."/".date("d",$_REQUEST['time']);
					$_REQUEST['user_position_hour'] = date("H",$_REQUEST['time']);
					$_REQUEST['user_position_minute'] = date("i",$_REQUEST['time']);
				}
			}else{
				$_REQUEST['user_position_anydatetime'] = 1;
			}
						
			$model->attributes=$_REQUEST;
			
			//NOTICE that we are creating a new record, so model its not loaded, ($model->content_type its juest the received $_REQUEST parameter)
			//print_r($model);
			
			//$model->content_type_id = $content_type->id;
			if($model->save()){
				$arr_response['ok_message'] = 'Subject successfully added.';
			}else{
				$arr_response['error'] = 77300;
				$arr_response['error_message'] = 'Subject could not be saved.';
				$arr_response['error_details'] = $model->getErrors(); 
			}
		}else{
			$arr_response['error'] = 77401;
			$arr_response['error_message'] = "No subject parameters received.";
		}

	}
	
	/**
	 * Adds one vote to the subject votes count, a like or dislike.
	 * @param integer $vote_id the ID of the subject
	 * @param string $vote the vote: either like or dislike
	 */
	public function actionVote($subject_id, $vote="")
	{
		global $arr_response;
		if(! Yii::app()->user->isGuest){
			if($subject_count = Subject::add_vote($subject_id, $vote, Yii::app()->user->id)){				
				$arr_response['subject_vote'] = SiteHelper::subject_vote($subject_count['subject_id'], $subject_count['likes'], $subject_count['dislikes'], true);
				$arr_response['ok_message'] = 'Vote sent.';
			}else{
				$arr_response['error'] = 77301;
				$arr_response['error_message'] = "Can not add this vote. You can only vote once.";
			}
		}else{
			$arr_response['error'] = 77302;
			$arr_response['error_message'] = "Sorry, you must log in to be able to vote.";
		}	
	}
	


}
