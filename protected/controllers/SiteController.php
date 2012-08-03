<?php

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}
	/**
	 * Renders a javascript file.
	 */
	public function actionJs($id)
	{
		header('Content-Type: application/javascript');//by default is set to text/html for a normal view file, so we set it to JS because some browsers may complain
		$this->renderPartial('js/'.$id);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		$information = Notification::getNotification();
		$base_url = Yii::app()->getRequest()->getBaseUrl(true);
		$utc_time = SiteLibrary::utc_time();
		
		//Yii::app()->session->add('site_loaded', 'yes');
		//echo "jjjj".Yii::app()->session->get('site_loaded');
		$this->render('index',array('information'=>$information,'base_url'=>$base_url,'utc_time'=>$utc_time));
		//if(Yii::app()->session->get('site_loaded')){
			//$this->render('index2',array('information'=>$information,'base_url'=>$base_url,'utc_time'=>$utc_time));
		//}else{
			
			//$this->renderPartial('index',array('information'=>$information,'base_url'=>$base_url,'utc_time'=>$utc_time));
		//}
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
	    if($error=Yii::app()->errorHandler->error)
	    {
	    	if(Yii::app()->request->isAjaxRequest)
	    		echo $error['message'];
	    	else
	        	$this->render('error', $error);
	    }
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$this->model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$this->model->attributes=$_POST['ContactForm'];
			if($this->model->validate())
			{
				if($this->model->email) 
				{						
					SiteLibrary::send_email(Yii::app()->params['contactEmail'],"SSCONTACT ".SiteLibrary::utc_time(),$this->model->text,$this->model->email);
				}else{
					SiteLibrary::send_email(Yii::app()->params['contactEmail'],"SSCONTACT ".SiteLibrary::utc_time(),$this->model->text);
				}
				Yii::app()->user->setFlash('contact',Yii::t('site','Thank you for contacting us. If you provided an email we will respond to you as soon as possible.'));
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$this->model));
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin($sh='',$t='')
	{
		$this->model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($this->model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$this->model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($this->model->validate() && $this->model->login()){
				
				//Assign the subject to the new user if he/she registered after adding a subject
				if($sh and $t){
					//Allow asignment only within 15 minutes since subject added
					if((SiteLibrary::utc_time() - $t) < 900)
					Subject::model()->updateAll(array('user_id'=>Yii::app()->user->id), 'time_submitted=:time_submitted AND hash=:hash', array(':time_submitted'=>$t, ':hash'=>$sh));
					$this->redirect(array('mysub/'.Yii::app()->user->name));
				}else{
					$this->redirect(Yii::app()->user->returnUrl);
				}
			}
		}
		// display the login form
		$this->render('login',array('model'=>$this->model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
}