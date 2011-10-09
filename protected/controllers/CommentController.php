<?php

class CommentController extends Controller
{
	public function actionIndex($id=0)
	{
		echo $id;//$this->render('index');
	}
	
	public function actionPush($text="")
	{
		$this->model=new Comment;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($this->model);

		if(isset($_POST['Comment']) or $text or $_POST['text'])
		{
			if(isset($_POST['Comment'])) $this->model->attributes=$_POST['Comment'];
			if($text) $this->model->comment = $text;
			if($_POST['text']) $this->model->comment = $_POST['text'];
			$this->model->update_live = true;

			if($this->model->save()){
				$send_mail = true;
				if(! Yii::app()->user->isGuest){
					$user = User::model()->findByPk(Yii::app()->user->id); 
					if($user->user_type_id > 2) $send_mail=false;//Dont notify managers themself
					
				}
				$last_one = Comment::model()->find(array('limit'=>2, 'offset'=>1, 'order'=>'t.id DESC'));//offset is 0 based
				if( SiteLibrary::utc_time() < ($last_one->time + 1500)) $send_mail = false;
				if($send_mail){
					$headers="From: Samesub Contact <".Yii::app()->params['contactEmail'].">\r\nReply-To: ".Yii::app()->params['contactEmail'];
					$mail_message .= "User: ".Yii::app()->user->name."\n";
					$mail_message .= "Comment: {$this->model->comment}\n";
					$mail_message .= "Current time: ".date("Y/m/d H:i", SiteLibrary::utc_time())." UTC (time of this message)\n\n";
					$mail_message .= "www.samesub.com";				
					@mail("contact@samesub.com","Comment ".$this->model->id,$mail_message,$headers);
				}
			}
			//we need to jsonecode something, as not doing so, can provoke an 
			//ajax response error on the client site if the ajax request is of type json
			echo json_encode(array('success'=>'yes'));
			return;
		}

		$this->render('add',array(
			'model'=>$this->model,
		));
	}

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}