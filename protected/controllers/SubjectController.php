<?php

class SubjectController extends Controller
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
			array('allow',  // allow all users to perform 'index' 'add' 'view' actions
				'actions'=>array('index','view','add','fetch'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'update' actions
				'actions'=>array('update','moderate','authorize','manage'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('delete'),
				'users'=>array('super'),
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
		if(! is_int($id)){
			$model = Subject::model()->find('urn=:urn', array(':urn'=>$id));
			if($model) $id = $model->id;
			
		}
		$model=$this->loadModel($id);
		if(! $model->authorized){//only managers can "view" unauthorized subjects
			if(! Yii::app()->user->checkAccess('subject_manage'))
			{
				throw new CHttpException(403,'Sory, this subject is not authorized and you are not a manager.');
				return;
			}
		}
		$this->render('view',array(
			'model'=>$model,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionAdd()
	{
		$model=new Subject;
		$model->scenario='add';

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		$country_id = 0;
		if($_SERVER['REMOTE_ADDR'] != '127.0.0.1'){
			Yii::import('ext.EGeoIP');
			$geoIp = new EGeoIP();
			$geoIp->locate($_SERVER['REMOTE_ADDR']);
			//http://www.iso.org/iso/english_country_names_and_code_elements
			$country=Country::model()->find('code=:code', array(':code'=>$geoIp->countryCode));
			if($country) $country_id = $country->id;
		}
		
		if(isset($_POST['Subject']))
		{
			$model->attributes=$_POST['Subject'];
			// Assign the user_id 1 if is a guest
			$model->user_id=(Yii::app()->user->id) ? Yii::app()->user->id : 1;
			$model->time_submitted = SiteLibrary::utc_time();
			$model->user_ip = $_SERVER['REMOTE_ADDR'];
			$model->user_country_id = $country_id;
			
			if($model->save()){
				$wait = Subject::getPrognostic($model->id);
				Yii::app()->user->setFlash('subject_added','Subject succesfully submitted!. Your subject has just been sended to a moderator for its approval. If your subject gets approved, it will go to the homepage(livestram) on an estimated time.');
				
				Yii::app()->user->setFlash('subject_added_info','Here is your prognostic: your subject is on position <b>'.$wait['position'].'</b> of the queue and has a wating time of <b>'.$wait['time'].'</b> minutes approximately.');
				$this->refresh();
			}
		}else{
			$model->country_id = $country_id;
		}
		
		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates only the data submitted by the user.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);
		$model->scenario='update';

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Subject']))
		{
			//Create an array(named Subject) with the params stored in the databse for this element
			//We can't use $_POST['Subject'], because it would just load the submited user data params and not other data already in database.
			$params=array('Subject'=>$model->attributes);
			if(Yii::app()->user->checkAccess('subject_update',$params))
			{
				$model->attributes=$_POST['Subject'];
				if($model->save())
					$this->redirect(array('view','id'=>$model->id));
			}else
			{
				throw new CHttpException(403,'You are not allowed to update this subject.');
			}
		}
		

		$this->render('update',array(
			'model'=>$model,
		));
	}
	
	/**
	 * Updates particular fields of a subject submitted by a user.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionModerate($id)
	{
		if(Yii::app()->user->checkAccess('subject_moderate'))
		{
			$model=$this->loadModel($id);
			$model->scenario='moderate';

			// Uncomment the following line if AJAX validation is needed
			// $this->performAjaxValidation($model);


			if(isset($_POST['Subject']))
			{
					$model->attributes=$_POST['Subject'];
					Yii::import('ext.EGeoIP');
					$geoIp = new EGeoIP();
					$geoIp->locate($_SERVER['REMOTE_ADDR']);
					//http://www.iso.org/iso/english_country_names_and_code_elements
					$country=Country::model()->find('code=:code', array(':code'=>$geoIp->countryCode)); 
					$model->moderator_country_id = $country->id;
					if($model->save())
						$this->redirect(array('manage'));
			}

			$this->render('moderate',array(
				'model'=>$model,
			));
		}else
		{
			throw new CHttpException(403,'You are not allowed to moderate this subject.');
		}
	}
	
	/**
	 * Updates particular fields of a subject submitted by a user.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionAuthorize($id)
	{
		if(Yii::app()->user->checkAccess('subject_authorize'))
		{
			$model=$this->loadModel($id);
			$model->scenario='authorize';

			// Uncomment the following line if AJAX validation is needed
			// $this->performAjaxValidation($model);

			if(isset($_POST['Subject']))
			{
					$model->attributes=$_POST['Subject'];
					Yii::import('ext.EGeoIP');
					$geoIp = new EGeoIP();
					$geoIp->locate($_SERVER['REMOTE_ADDR']);
					//http://www.iso.org/iso/english_country_names_and_code_elements
					$country=Country::model()->find('code=:code', array(':code'=>$geoIp->countryCode)); 
					$model->authorizer_country_id = $country->id;
					if($model->save())
						$this->redirect(array('manage'));	
			}

			$this->render('authorize',array(
				'model'=>$model,
			));
		}else
		{
			throw new CHttpException(403,'You are not allowed to authorize this subject.');
		}
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		if(Yii::app()->user->checkAccess('subject_delete'))
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
		}else
		{
			throw new CHttpException(403,'You are not allowed to delete this subject.');
		}
	}
	
	/**
	 * Fetch data about a subject.
	 * This action does NOT return a html web page. 
	 * This action returns data about a subject in an encoded format for data interchange(JSON,XML,etc.)
	 * It can be used for API implementation.
	 */
	public function actionFetch($subject_id_2=0,$comment_number=0)
	{
		//in case params are sent via POST
		if(isset($_POST['subject_id_2'])) $subject_id_2 = $_POST['subject_id_2'];
		if(isset($_POST['comment_number'])) $comment_number = $_POST['comment_number'];
		//PHP casts any string to 0. so its ok. We need to cast in case we receive a string.
		$subject_id_2 =  (int)$subject_id_2;
		$comment_number = (int)$comment_number;
		$data = NULL;
		//In this for is where all the comet(long polling) magic occurs
		//TODO:conflict with session component(maybe cus of cookies). All other simultanous request done to a 
		//page will not be compleated until this finish. So, smaller wait time and more request its recomended here
		for ($i = 1; $i <= 3; $i++) {
			if ( $data = Subject::getLiveData($subject_id_2,$comment_number,true) )
			{
				echo $data;
				die();
			}
		}

		//Time has gone, user its updated, respone 0
		echo json_encode(array('id_1'=>'0'));
		die();

		
		//$dataProvider=new CActiveDataProvider('Subject');
		//$this->render('index',array(
		//	'dataProvider'=>$dataProvider,
		//));
		
	}
	
	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		
		$model=new Subject('history');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Subject']))
				$model->attributes=$_GET['Subject'];
		
		$model->show_time = ">:0";

		$this->render('index',array(
			'model'=>$model,
		));
		
	}

	/**
	 * Manages all models.
	 */
	public function actionManage()
	{
		if(Yii::app()->user->checkAccess('subject_manage'))
		{
			$model=new Subject('manage');
			$model->unsetAttributes();  // clear any default values
			if(isset($_GET['Subject']))
				$model->attributes=$_GET['Subject'];
			
			$live_subject = Yii::app()->db->createCommand()
			->select('*')
			->from('live_subject')
			->queryRow();
			$this->render('manage',array(
				'model'=>$model,'live_subject'=>$live_subject,
			));
		}else
		{
			throw new CHttpException(403,'You are not allowed to manage subjects.');
		}
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Subject::model()->findByPk((int)$id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='subject-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
