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
				'actions'=>array('register','resetpassword','resetpasswordnext'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'index' and 'update' actions
				'actions'=>array('index','update','changepassword'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin','view'
				'actions'=>array('admin','view'),
				'users'=>array('admin','super'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	
	public function filterMenu($filterChain){
		//.$this->action->Id
		$arr_titles = array();
		$arr_titles['index'] = 'Welcome';
		$arr_titles['mysub'] = 'MySub';
		$arr_titles['update'] = 'Update Profile';
		$arr_titles['changepassword'] = 'Change Password';
		$this->breadcrumbs=array(
		'User'=>array('index'),
		$arr_titles[$this->action->Id] ,
		);

		$this->menu=array(
		array('label'=>$arr_titles['index'], 'url'=>array('user/index')),//Note:we have to indicate the controller also, for the CMenu widget activateItems propoerty work properly
		array('label'=>$arr_titles['mysub'], 'url'=>array('mysub/')),
		array('label'=>$arr_titles['update'], 'url'=>array('user/update')),		
		array('label'=>$arr_titles['changepassword'], 'url'=>array('user/changepassword')),
		//array('label'=>$arr_titles['changepassword'], 'url'=>array('user/changepassword', 'id'=>Yii::app()->user->id)),
		//array('label'=>'Manage User', 'url'=>array('admin')),		
		);
		SiteLibrary::remove_current_url_menu($this,$arr_titles);

		$filterChain->run();
	
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->model=$this->loadModel($id);
		$this->model->country = Country::model()->findByPk($this->model->country_id)->name;
		$status = Yii::app()->db->createCommand()->select('*')->from('user_status')->queryRow();
		$this->model->status = $status['name'];
		$type = Yii::app()->db->createCommand()->select('*')->from('user_type')->queryRow();
		$this->model->type = $type['name'];
		$this->model->country_created = Country::model()->findByPk($this->model->country_id_created)->name;
		$this->render('view',array(
			'model'=>$this->model
		));
	}

	/**
	 * Change the current user password.
	 * Musk ask the old password again.
	 */
	public function actionChangepassword()
	{
		$this->model=$this->loadModel(Yii::app()->user->id);
		$this->model->scenario='changepassword';
		if(isset($_POST['User']))
		{
			
			$this->model->attributes=$_POST['User'];
			if(! $this->model->validatePassword($this->model->oldpassword)) $this->model->addError('oldpassword','The old password is incorrect.');
			$this->model->salt = $this->model->generateSalt();//lets give it a new salt also, just in case
			$this->model->password = $this->model->hashPassword($this->model->newpassword, $this->model->salt);
			if($this->model->save()){
				Yii::app()->user->setFlash('changepass_success','Your password has been changed successfully.');
			}else{
				$this->model->password=$_POST['User']['password'];
			}
		}

		$this->render('changepassword',array(
			'model'=>$this->model,
		));
	}
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionRegister()
	{
		$this->model=new User('register');

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($this->model);

		if(isset($_POST['User']))
		{
			$country_id = 1;
			if($_SERVER['REMOTE_ADDR'] != '127.0.0.1'){
				Yii::import('ext.EGeoIP');
				$geoIp = new EGeoIP();
				$geoIp->locate($_SERVER['REMOTE_ADDR']);
				//http://www.iso.org/iso/english_country_names_and_code_elements
				$country=Country::model()->find('code=:code', array(':code'=>$geoIp->countryCode));
				if($country) $country_id = $country->id;
			}
			$this->model->time_created = SiteLibrary::utc_time();
			$this->model->ip_created = $_SERVER['REMOTE_ADDR'];
			$this->model->country_id = $country_id;
			$this->model->country_id_created = $country_id;
			
			$this->model->attributes=$_POST['User'];
			
			
			if($this->model->save()){
				$headers="From: Samesub Contact <".Yii::app()->params['contactEmail'].">\r\nReply-To: ".Yii::app()->params['contactEmail'];
				$mail_message = "Hi {$this->model->email}, welcome to samesub!\n\n";
				$mail_message .= "Name:  " . $this->model->username."\n";
				$mail_message .= "Email: " . $this->model->email."\n\n";
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
	
				if(@mail($this->model->email,"Registration successful",$mail_message,$headers)){
					$mail_sent = "An email has been sent to you.";
				}else{
					$mail_sent = "Email could not be sent.";
				}
				$model2=new LoginForm;
				$model2->email=$this->model->email;
				$model2->password=$_POST['User']['password'];
				// validate user input and redirect to the previous page if valid
				if($model2->validate() && $model2->login()){
					Yii::app()->user->setFlash('registration_success','Registration has been completed successfully. '.$mail_sent);
					$this->redirect(array('index'));
				}
				
			}else{
				//Set back the password to its original as the view will receive it hashed
				$this->model->password=$_POST['User']['password'];
			}
				
		}

		$this->render('register',array(
			'model'=>$this->model,
		));
	}
	/**
	 * Forgot password option.
	 */
	public function actionResetPassword()
	{
		$this->model=new User('resetpassword');

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($this->model);

		$this->model->unsetAttributes();  // clear any default values
		if(isset($_POST['User']))
		{
			$this->model->attributes=$_POST['User'];
			if($this->model->validate()){
				
				if(! $model2 = User::model()->find('username=:username OR email=:email',array(':username'=>$this->model->username,':email'=>$this->model->email))){
					$this->model->addError('username, email','The username or email was not found in our database.');
				}else{
					$this->model = $model2;//to be able to log model id

					$this->model->reset_hash = md5(uniqid(rand(), true));
					$this->model->reset_time = SiteLibrary::utc_time();
					
					//Set up the mail message
					$headers="From: Samesub Contact <".Yii::app()->params['contactEmail'].">\r\nReply-To: ".Yii::app()->params['contactEmail'];
					$mail_message = "Hi {$this->model->username}!\n\n";
					$mail_message .= "We recently received a request to reset your password.\n";
					$mail_message .= "If you did not request this, please ignore this message and the steps described in it.\n\n";
					$mail_message .= "Username:  " . $this->model->username."\n";
					$mail_message .= "Email: " . $this->model->email."\n\n";					
					$mail_message .= "To reset your password please click or visit the link bellow and complete the steps \n";
					$mail_message .= "described there.\n\n";
					$mail_message .= Yii::app()->getRequest()->getBaseUrl(true)."/user/resetpasswordnext?reset_hash=".$this->model->reset_hash;
					$mail_message .= "\n\nThanks\n\n";
					$mail_message .= "Sincerely\n";
					$mail_message .= "Samesub Team\n";
					$mail_message .= "www.samesub.com";				
		

					if($this->model->save()){
					//User::model()->updateByPk($model2->id, array('reset_hash'=>md5(rand(100,9000)),'reset_time'=>SiteLibrary::utc_time()));
						if(@mail($this->model->email,"Password Reset",$mail_message,$headers)){
							Yii::app()->user->setFlash('resetpassword_success','An email has been sent to your address '. '***'.substr($model2->email, 3).' with the link to reset your password. Please verify your email spam folder if you do not see the message.');
						}else{
							Yii::app()->user->setFlash('resetpassword_success','Ooops!. We could not sent an email to your address. We need to send an email to your address to reset your password automatically. Please contact us to request a password reset manually.');
						}
					}
				}
			}
		}
		
		$this->render('resetpassword',array(
			'model'=>$this->model,
		));
				
	}
	
	/**
	 * Next step after password reset has been requested. The user types in the new password.
	 */
	public function actionResetPasswordNext($reset_hash)
	{
		if(! $this->model = User::model()->find('reset_hash=:reset_hash AND reset_time>:reset_time',
			array(':reset_hash'=>$reset_hash,':reset_time'=>(SiteLibrary::utc_time() - 604800)  ))){ //expires in 1 week
			throw new CHttpException(404,'Sorry but the reset code in the link is incorrect or has expired, or you have already reset your password. Please repeat the process or contact us.');
		}
		
		$this->model->scenario='resetpasswordnext';
		if(isset($_POST['User']))
		{
			$this->model->attributes=$_POST['User'];

			$this->model->salt = $this->model->generateSalt();//lets give it a new salt also, just in case
			$this->model->password = $this->model->hashPassword($this->model->newpassword, $this->model->salt);
			$this->model->reset_hash = rand(1000,9000)."_". SiteLibrary::utc_time()."_" .$this->model->reset_hash;
			if($this->model->save()){
				Yii::app()->user->setFlash('layout_flash_success','Your password has been changed successfully. You may now login with your new password.');
				$this->redirect(array('site/login'));
			}else{
				$this->model->password=$_POST['User']['password'];
			}
		}

		$this->render('resetpasswordnext',array(
			'model'=>$this->model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate()
	{
		$this->model=$this->loadModel(Yii::app()->user->id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($this->model);

		if(isset($_POST['User']))
		{
			$this->model->attributes=$_POST['User'];
			if($this->model->Year){
				if(! $this->model->Month) $this->model->Month = 01;
				if(! $this->model->Day) $this->model->Day = 01;
				$this->model->birthdate = strtotime($this->model->Year."/".$this->model->Month."/".$this->model->Day);
			}
			
			if($this->model->validate()){
				//Delete image reference if marked
				if($this->model->deleteimage) $this->model->image_name = '';
				//Save the image if any
				$image=CUploadedFile::getInstance($this->model,'image');
				if (get_class($image) == 'CUploadedFile'){
					if($image->getSize() > (1024 * 1024 * Yii::app()->params['max_image_size'])){  $this->model->addError('image','Please select an image smaller than 7MB.');
					$error = true;}//MB
					$types = array("image/jpg", "image/png", "image/gif", "image/jpeg");
					if (! in_array(CFileHelper::getMimeType($image->getName()), $types)){ $this->model->addError('image','File type '.CFileHelper::getMimeType($image->getName()).' not supported .Please select a valid image type.'); $error = true;} 
				}
				if(! $error){
					Yii::import('ext.EUploadedImage');
					if($image){
						$img_extension = ($image->getExtensionName()) ? $image->getExtensionName() : '';
						$img_name = $this->model->id.'.'.$img_extension;
						$this->model->image_name = $img_name;
						$this->model->image = EUploadedImage::getInstance($this->model,'image');
						$this->model->image->maxWidth = 980;
						$this->model->image->maxHeight = 750;
						 
						$this->model->image->thumb = array(
							'maxWidth' => 45,	
							'maxHeight' => 45,
							'keepratio'=>false,
							//'dir' => Yii::app()->params['dirroot'] . '/'.$img_path,
							'prefix' => 'small_',
						);
						 
						if (! $this->model->image->saveAs(Yii::app()->params['webdir'].DIRECTORY_SEPARATOR.Yii::app()->params['user_img_path'].DIRECTORY_SEPARATOR.$img_name)){
							$this->model->addError('image','We could not save the image in the disk.'); 
							return false;
						}
					}
				}
				
			}
			if($this->model->save())
			Yii::app()->user->setFlash('profile_success','Profile Settings updated successfully');
				//$this->redirect(array('index'));
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
		$this->model=new User('search');
		$this->model->unsetAttributes();  // clear any default values
		if(isset($_GET['User']))
			$this->model->attributes=$_GET['User'];

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
