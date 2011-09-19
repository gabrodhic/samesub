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
	public function actionView($id='')
	{
		if(! $id) $this->redirect(array('index'));
		if(! is_int($id)){
			$this->model = Subject::model()->find('urn=:urn', array(':urn'=>$id));
			if($this->model) $id = $this->model->id;
			
		}
		$this->model=$this->loadModel($id);
		if(! $this->model->authorized){//only managers can "view" unauthorized subjects
			if(! Yii::app()->user->checkAccess('subject_manage'))
			{
				throw new CHttpException(403,'Sory, this subject is not authorized and you are not a manager.');
				return;
			}
		}
		$this->render('view',array(
			'model'=>$this->model,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionAdd()
	{
		$this->model=new Subject;
		$this->model->scenario='add';

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($this->model);
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
			$this->model->attributes=$_POST['Subject'];
			// Assign the user_id 1 if is a guest
			$this->model->user_id=(Yii::app()->user->id) ? Yii::app()->user->id : 1;
			$this->model->time_submitted = SiteLibrary::utc_time();
			$this->model->user_ip = $_SERVER['REMOTE_ADDR'];
			$this->model->user_country_id = $country_id;
			
			if($this->model->save()){
				$wait = Subject::getPrognostic($this->model->id);
				Yii::app()->user->setFlash('subject_added','Subject succesfully submitted!. Your subject has just been sended to a moderator for its approval. If your subject gets approved, it will go to the homepage(livestram) on an estimated time.');
				
				Yii::app()->user->setFlash('subject_added_info','Here is your prognostic: your subject is on position <b>'.$wait['position'].'</b> of the queue and has a wating time of <b>'.$wait['time'].'</b> minutes approximately.');
				$this->refresh();
			}
		}else{
			$this->model->country_id = $country_id;
		}
		
		$this->render('create',array(
			'model'=>$this->model,
		));
	}

	/**
	 * Updates only the data submitted by the user.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$this->model=$this->loadModel($id);
		$this->model->scenario='update';

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($this->model);

		if(isset($_POST['Subject']))
		{
			//Create an array(named Subject) with the params stored in the databse for this element
			//We can't use $_POST['Subject'], because it would just load the submited user data params and not other data already in database.
			$params=array('Subject'=>$this->model->attributes);
			if(Yii::app()->user->checkAccess('subject_update',$params))
			{
				$this->model->attributes=$_POST['Subject'];
				if($this->model->save())
					$this->redirect(array('view','id'=>$this->model->id));
			}else
			{
				throw new CHttpException(403,'You are not allowed to update this subject.');
			}
		}
		

		$this->render('update',array(
			'model'=>$this->model,
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
			$this->model=$this->loadModel($id);
			$this->model->scenario='moderate';

			// Uncomment the following line if AJAX validation is needed
			// $this->performAjaxValidation($this->model);


			if(isset($_POST['Subject']))
			{
					$this->model->attributes=$_POST['Subject'];
					Yii::import('ext.EGeoIP');
					$geoIp = new EGeoIP();
					$geoIp->locate($_SERVER['REMOTE_ADDR']);
					//http://www.iso.org/iso/english_country_names_and_code_elements
					$country=Country::model()->find('code=:code', array(':code'=>$geoIp->countryCode)); 
					$this->model->moderator_country_id = $country->id;
					if($this->model->save())
						$this->redirect(array('manage'));
			}

			$this->render('moderate',array(
				'model'=>$this->model,
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
			$this->model=$this->loadModel($id);
			$this->model->scenario='authorize';

			// Uncomment the following line if AJAX validation is needed
			// $this->performAjaxValidation($this->model);

			if(isset($_POST['Subject']))
			{
					$this->model->attributes=$_POST['Subject'];
					Yii::import('ext.EGeoIP');
					$geoIp = new EGeoIP();
					$geoIp->locate($_SERVER['REMOTE_ADDR']);
					//http://www.iso.org/iso/english_country_names_and_code_elements
					$country=Country::model()->find('code=:code', array(':code'=>$geoIp->countryCode)); 
					$this->model->authorizer_country_id = $country->id;
					if($this->model->save())
						$this->redirect(array('manage'));	
			}

			$this->render('authorize',array(
				'model'=>$this->model,
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
		$data = Subject::getLiveData($subject_id_2,$comment_number,false);
		if($data['new_sub'] == 0) $this->no_log = true;//This is just to control the logging functionality(client dont receive this info)
		
		echo json_encode($data);
		
	}
	
	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		
		$this->model=new Subject('history');
		$this->model->unsetAttributes();  // clear any default values
		if(isset($_GET['Subject']))
				$this->model->attributes=$_GET['Subject'];
		
		$this->model->show_time = ">:0";
		$live_subject = Yii::app()->db->createCommand()
			->select('*')
			->from('live_subject')
			->queryRow();
		$this->model->id = "<>".$live_subject['subject_id_2'];//Do not display the cached subject(the next subject that its gonna be showed)

		$this->render('index',array(
			'model'=>$this->model,
		));
		
	}

	/**
	 * Manages all models.
	 */
	public function actionManage()
	{
		if(Yii::app()->user->checkAccess('subject_manage'))
		{
			$this->model=new Subject('manage');
			$this->model->unsetAttributes();  // clear any default values
			if(isset($_GET['Subject']))
				$this->model->attributes=$_GET['Subject'];
			
			$live_subject = Yii::app()->db->createCommand()
			->select('*')
			->from('live_subject')
			->queryRow();
			if(! isset($this->model->disabled)) $this->model->disabled = 0;//Set to view only NOT disabled subjects by default(notice isset insted of a simple if)
			$this->render('manage',array(
				'model'=>$this->model,'live_subject'=>$live_subject,
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
