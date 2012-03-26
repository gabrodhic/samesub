<?php

class MyappsController extends Controller
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
			'Menu',
		);
	}
	
	public function filterMenu($filterChain){
		$arr_titles = array();
		$arr_titles['index'] = 'My Applications';
		$arr_titles['create'] = 'Create Application';
		$arr_titles['update'] = 'Update Application';//Notice Update option is not on the menu, this is just for the breadcrumbs

		$this->breadcrumbs=array(
		'Developers'=>array('developers/'),
		'My Applications'=>array('index'),
		$arr_titles[$this->action->Id] ,
		);

		$this->menu=array(
		array('label'=>$arr_titles['index'], 'url'=>array('developers/myapps/index')),//Note:we have to indicate the controller also, for the CMenu widget activateItems propoerty work properly
		array('label'=>$arr_titles['create'], 'url'=>array('developers/myapps/create')),
		);
		SiteLibrary::remove_current_url_menu($this,$arr_titles);

		$filterChain->run();
	
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow', // allow authenticated user to perform 'create', 'update','index' and 'view' actions
				'actions'=>array('create','update','index','view'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
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
	public function actionCreate()
	{
		$this->model=new OauthServerRegistry;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($this->model);

		if(isset($_POST['OauthServerRegistry']))
		{
			$this->model->attributes=$_POST['OauthServerRegistry'];
			$this->model->osr_usa_id_ref = Yii::app()->user->id;
			$this->model->osr_issue_date = date(DATE_RFC822);
			$this->model->osr_consumer_key =  Yii::app()->session->get('osr_consumer_key');
			$this->model->osr_consumer_secret =  Yii::app()->session->get('osr_consumer_secret');
			
			if($this->model->save())
				$this->redirect(array('view','id'=>$this->model->osr_id));
		}else{
			$this->model->osr_consumer_key =  md5(uniqid());
			$this->model->osr_consumer_secret =  md5(uniqid());
			Yii::app()->session->add('osr_consumer_key', $this->model->osr_consumer_key);
			Yii::app()->session->add('osr_consumer_secret', $this->model->osr_consumer_secret);
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

		if(isset($_POST['OauthServerRegistry']))
		{
			$this->model->attributes=$_POST['OauthServerRegistry'];
			if($this->model->save())
				$this->redirect(array('index'));
		}

		$this->render('update',array(
			'model'=>$this->model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
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
		$this->model=new OauthServerRegistry('search');
		$this->model->unsetAttributes();  // clear any default values
		$this->model->osr_usa_id_ref = Yii::app()->user->id;
		if(isset($_GET['OauthServerRegistry']))
			$this->model->attributes=$_GET['OauthServerRegistry'];

		$this->render('index',array(
			'model'=>$this->model,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$this->model=new OauthServerRegistry('search');
		$this->model->unsetAttributes();  // clear any default values
		if(isset($_GET['OauthServerRegistry']))
			$this->model->attributes=$_GET['OauthServerRegistry'];

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
		$model=OauthServerRegistry::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='oauth-server-registry-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
