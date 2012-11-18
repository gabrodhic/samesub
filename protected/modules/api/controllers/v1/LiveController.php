<?php

class LiveController extends ApiController
{
	
	//Notice that default filters are defined in ApiController class

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow', 
				'actions'=>array('index','getall','sendcomment'),
				'users'=>array('*'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Redirect to getall method which is the main method of the Live API section
	 */
	public function actionIndex()
	{		
		//$this->redirect(array('getall'));
		$this->forward('getall');//Note: use foward instead of redirect to prevent 2 requests instead of just one
	}

	public function actionGetall($subject_id=0,$comment_id=0)
	{
		global $arr_response;
		//in case params are sent via POST
		$subject_id = $_REQUEST['subject_id'];
		$comment_id = $_REQUEST['comment_id'];
		$width = $_REQUEST['width'];
		$height = $_REQUEST['height'];
		$keepratio = isset($_REQUEST['keepratio']) ? $_REQUEST['keepratio'] : 1;//Default to true
		//PHP casts any string to 0. so its ok. We need to cast in case we receive a string.
		$subject_id =  (int)$subject_id;
		$comment_id = (int)$comment_id;
		$width = (int)$width;
		$height = (int)$height;
		$keepratio = ($keepratio) ? true : false;
		$arr_response = array_merge($arr_response ,Subject::getLiveData($subject_id,$comment_id,$width,$height,$keepratio));
		
		//TODO: Add log/counter statistics for API requests
	}
	
	public function actionSendcomment($text="")
	{
		global $arr_response;
		$model=new Comment;

		if($_REQUEST['text'])
		{
			$model->comment = $_REQUEST['text'];
			$model->update_live = true;

			if(Comment::save_comment($model)){
				$arr_response['response_message'] = 'Comment sent.';
			}else{
				$arr_response['response_code'] = 409;
				$arr_response['response_message'] = Yii::t('comment','Comment could not be saved.');;
			}
		}else{
			$arr_response['response_code'] = 409;
			$arr_response['response_message'] =  Yii::t('comment',"No 'text' parameter received.");
		}

	}
	


}
