<?php

class TextController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

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
				'actions'=>array('index','view','create','update','admin','delete'),
				'users'=>array('admin','super'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate($id,$category,$language='')
	{
		$this->model=new Text;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($this->model);
		
		$this->model=Text::model()->find(
			array('join' => 'LEFT JOIN Message as m ON m.id = t.id'
			, 'select' => 't.id, m.id as messageid, t.category, t.message, m.language, m.translation as translation'
			,'condition'=>('t.id = '.$id))
		);
		$this->model->category=$category;
		$this->model->language=$language;
		if(! $this->model->language) $this->model->language = 'en';
		
		//$this->model->id = $id;
		//$this->model = $this->model->search();
		
		

		if(isset($_POST['Text']))
		{
			if(! $this->model->messageid){
				$model=new Message;
			}else{
				$model=Message::model()->find(
					array('condition'=>('t.id = '.$this->model->messageid." AND language = '".$this->model->language."'"))
				);
			}
			$model->attributes=$_POST['Text'];
			
			if($model->save())
				$this->redirect(array('admin'));
		}

		$this->render('create',array(
			'model'=>$this->model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$this->model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($this->model);

		if(isset($_POST['Text']))
		{
			$this->model->attributes=$_POST['Text'];
			if($this->model->save())
				$this->redirect(array('admin'));
		}

		$this->render('update',array(
			'model'=>$this->model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$this->loadModel($id)->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$this->redirect(array('admin'));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$this->model=new Text('search');
		$this->model->unsetAttributes();  // clear any default values
		if(isset($_GET['Text']))
			$this->model->attributes=$_GET['Text'];

		$this->render('admin',array(
			'model'=>$this->model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Text::model()->findByPk((int)$id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='Text-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
