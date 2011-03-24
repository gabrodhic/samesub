<?php

class CommentController extends Controller
{
	public function actionIndex($id=0)
	{
		echo $id;//$this->render('index');
	}
	
	public function actionAdd()
	{
		$model=new Comment;
		

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Comment']))
		{
			$model->attributes=$_POST['Comment'];
			// Assign the user_id 1 if is a guest
			$model->user_id=(Yii::app()->user->id) ? Yii::app()->user->id : 1;
			$model->time = time();
			$model->user_ip = $_SERVER['REMOTE_ADDR'];
			
			$live_subject = Yii::app()->db->createCommand()->select('subject_id1, (comment_sequence+1)as next_sequence')->from('live_subject')->queryRow();
			//print_r($live_subject);return;
			Yii::app()->db->createCommand()->insert('live_comment', array(
			'comment_sequence'=>$live_subject['next_sequence'],
			'comment_text'=>$model->comment,
			));
			Yii::app()->db->createCommand()->update('live_subject', array(
			'last_comment_number'=>Yii::app()->db->getLastInsertID(),
			'comment_sequence'=>$live_subject['next_sequence'],
			));
			
			$model->sequence = $live_subject['next_sequence'];
			$model->subject_id = $live_subject['subject_id1'];
			
			if($model->save())
				$this->redirect(array('index','id'=>$model->id));
		}

		$this->render('add',array(
			'model'=>$model,
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