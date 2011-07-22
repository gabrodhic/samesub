<?php

class UserController extends Controller
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
			'Menu',
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
			array('allow',  // allow all users to perform 'register' actions
				'actions'=>array('register'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'index' and 'update' actions
				'actions'=>array('index','update','changepassword'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	
	public function filterMenu($filterChain){
		//.$this->action->Id
		$this->menu=array(
		array('label'=>'Welcome', 'url'=>array('index')),
		array('label'=>'My Profile', 'url'=>array('update', 'id'=>Yii::app()->user->id)),
		array('label'=>'Change Password', 'url'=>array('changepassword', 'id'=>Yii::app()->user->id)),
		//array('label'=>'Manage User', 'url'=>array('admin')),
		);
		$filterChain->run();
	
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
	 * Change the current user password.
	 * Musk ask the old password again.
	 */
	public function actionChangepassword($id)
	{
		$model=$this->loadModel($id);
		$model->scenario='changepassword';
		if(isset($_POST['User']))
		{
			
			$model->attributes=$_POST['User'];
			if(! $model->validatePassword($model->oldpassword)) $model->addError('oldpassword','The old password is incorrect.');
			$model->salt = $model->generateSalt();//lets give it a new salt also, just in case
			$model->password = $model->hashPassword($model->newpassword, $model->salt);
			if($model->save()){
				Yii::app()->user->setFlash('changepass_success','Your password has been changed successfully.');
			}else{
				$model->password=$_POST['User']['password'];
			}
		}

		$this->render('changepassword',array(
			'model'=>$model,
		));
	}
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionRegister()
	{
		$model=new User;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['User']))
		{
			$country_id = 0;
			if($_SERVER['REMOTE_ADDR'] != '127.0.0.1'){
				Yii::import('ext.EGeoIP');
				$geoIp = new EGeoIP();
				$geoIp->locate($_SERVER['REMOTE_ADDR']);
				//http://www.iso.org/iso/english_country_names_and_code_elements
				$country=Country::model()->find('code=:code', array(':code'=>$geoIp->countryCode));
				if($country) $country_id = $country->id;
			}
			$model->time_created = SiteLibrary::utc_time();
			$model->ip_created = $_SERVER['REMOTE_ADDR'];
			$model->country_id = $country_id;
			$model->country_id_created = $country_id;
			
			$model->attributes=$_POST['User'];
			
			
			if($model->save()){
				$headers="From: Samesub Contact <".Yii::app()->params['contactEmail'].">\r\nReply-To: ".Yii::app()->params['contactEmail'];
				$mail_message = "Hi {$model->email}, welcome to samesub!\n\n";
				$mail_message .= "Name:  " . $model->username."\n";
				$mail_message .= "Email: " . $model->email."\n\n";
				$mail_message .= "Thanks for registering.\n\n";
				$mail_message .= "Remember that our mission:\n\n";
				$mail_message .= "Is that there be a unique point of union on the internet where all users connected\n";
				$mail_message .= "to it can interact with one 'Same Subject' synchronously, giving an impact in the way we stay in touch\n";
				$mail_message .= "with the world, a way in which everybody adapts to one thing in common, a subject in common:\n";
				$mail_message .= "Samesub\n\n";
				$mail_message .= "Know that you can always help us to achive this goal in any of one of these ways:\n";
				$mail_message .= "With your visits.\nSharing to your friends.\nWith your submission of content.\nWith your code contribution.\n\n";
				$mail_message .= "If you want to become a moderator, authorizer, or help the samesub team in any way, please write to us\n";
				$mail_message .= "with the email you registered from.\n\n";
				$mail_message .= "Thanks\n\n";
				$mail_message .= "Sincerely\n";
				$mail_message .= "Samesub Team\n";
				$mail_message .= "www.samesub.com";				
	
				if(@mail($model->email,"Registration successful",$mail_message,$headers)){
					$mail_sent = "An email has been sent to you.";
				}else{
					$mail_sent = "Email could not be sent.";
				}
				$model2=new LoginForm;
				$model2->email=$model->email;
				$model2->password=$_POST['User']['password'];
				// validate user input and redirect to the previous page if valid
				if($model2->validate() && $model2->login()){
					Yii::app()->user->setFlash('registration_success','Registration has been completed successfully. '.$mail_sent);
					$this->redirect(array('index'));
				}
				
			}else{
				//Set back the password to its original as the view will receive it hashed
				$model->password=$_POST['User']['password'];
			}
				
		}

		$this->render('register',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['User']))
		{
			$model->attributes=$_POST['User'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
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
	 * User profile.
	 */
	public function actionIndex()
	{
		$this->render('index');
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new User('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['User']))
			$model->attributes=$_GET['User'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=User::model()->findByPk((int)$id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='user-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
