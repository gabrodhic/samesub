<?php

class LogController extends Controller
{

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('index'),
				'users'=>array('super','admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	
	/**
	 * List log entries
	 */
	public function actionIndex()
	{
		$this->model=new Log();
		$this->model->unsetAttributes();  // clear any default values
		if(isset($_GET['Log']))	$this->model->attributes=$_GET['Log'];
		//We need to call the validate() method explicitly, since we are not 
		//performing any CUD operation that that would call implicitly
		$this->model->validate();
		
		$this->render('index',array('model'=>$this->model));
		
	}
	


	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='user-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
