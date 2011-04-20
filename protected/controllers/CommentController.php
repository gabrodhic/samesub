<?php

class CommentController extends Controller
{
	public function actionIndex($id=0)
	{
		echo $id;//$this->render('index');
	}
	
	public function actionPush($text="")
	{
		$model=new Comment;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Comment']) or $text or $_POST['text'])
		{
			if(isset($_POST['Comment'])) $model->attributes=$_POST['Comment'];
			if($text) $model->comment = $text;
			if($_POST['text']) $model->comment = $_POST['text'];
			$model->update_live = true;

			$model->save();
			//we need to jsonecode something, as not doing so, can provoke an 
			//ajax response error on the client site if the ajax request is of type json
			echo json_encode(array('success'=>'yes'));
			die();
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