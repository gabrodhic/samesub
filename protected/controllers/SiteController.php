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
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				if($model->email) 
				{	
					$headers="From: {$model->email}\r\nReply-To: {$model->email}";
					mail(Yii::app()->params['contactEmail'],"SSCONTACT ".SiteLibrary::utc_time(),$model->text,$headers);
				}else{
					mail(Yii::app()->params['contactEmail'],"SSCONTACT ".SiteLibrary::utc_time(),$model->text);
				}
				Yii::app()->user->setFlash('contact','Thank you for contacting us. If you provided an email we will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('login',array('model'=>$model));
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