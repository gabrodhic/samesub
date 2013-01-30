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
	 * @param string $term the text to search for
	 */
	public function actionGettags($term='',$limit='')
	{
		global $arr_response;
		$term = $_REQUEST['term'];
		$limit = $_REQUEST['limit'];
				
		$tags = ($limit) ? Subject::getTags($term,(int)$limit) : Subject::getTags($term);
		$arr_response = $tags;//Notice we are altering the arr_response completely, thus errasing any previous values stored. ie: on a taglist autocomplete json request so that results dont get contaminated
		//TODO:Implemento some kind of condition that if $_REQUEST['response_info'] param is received, response_code,response_message, etc should be in the response either way
	}
	
	/**
	 * Get the list of subject categories
	 */
	public function actionGetcategories()
	{
		global $arr_response;
				
		$categories = Subject::getCategories();
		$arr_response = $categories;//Notice we are altering the arr_response completely, thus errasing any previous values stored. ie: on a taglist autocomplete json request so that results dont get contaminated
		
	}
	
	public function actionAdd()
	{
		global $arr_response;
		$model=new Subject;
		$model->scenario='add';
		
		

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
			$arr_response['response_message'] = Yii::t('subject','Subject successfully saved.');
		}else{
			$arr_response['response_code'] = 409;
			$arr_response['response_message'] = Yii::t('subject','Subject could not be saved.');
			$arr_response['response_details'] = $model->getErrors(); 
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
			$subject_count = Subject::add_vote($subject_id, $vote, Yii::app()->user->id);
			if($subject_count['success']==true){				
				$arr_response['subject_vote'] = SiteHelper::subject_vote($subject_count['subject_id'], $subject_count['likes'], $subject_count['dislikes'], true);
				$arr_response['response_message'] = Yii::t('subject','Vote sent.');
			}else{
				$arr_response['response_code'] = 409;
				$arr_response['response_message'] = $subject_count['message'];
			}
		}else{
			$arr_response['response_code'] = 401;
			$arr_response['response_message'] = Yii::t('subject','Sorry, you must log in to be able to vote.');
		}	
	}
	


}
