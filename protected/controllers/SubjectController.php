<?php

class SubjectController extends Controller
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
		);
	}
	
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
				'actions'=>array('index','view','add','fetch','gettags','captcha','countdown'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'update' actions
				'actions'=>array('update','moderate','authorize','manage','timeboard'),
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
	 * Searches for a tag suggestion
	 * @param string $term the text to search for
	 */
	public function actionGettags($term='',$limit='')
	{
		$tags = ($limit) ? Subject::getTags($term,(int)$limit) : Subject::getTags($term);
		if ($tags){
			echo json_encode($tags);
		}
	}
	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id='')
	{
		if(! $id) $this->redirect(array('index'));
		if(! is_int($id)){
			$this->model = Subject::model()->find('urn=:urn AND deleted=0', array(':urn'=>$id));			
		}else{
			$this->model=$this->loadModel($id);
		}
		if(! $this->model) throw new CHttpException(404,Yii::t('site','The requested page does not exist.'));
		if(!(Yii::app()->session['subject_view'])) Yii::app()->session['subject_view'] = array('1'=>1); //just in case start it with something
		if(! in_array($this->model->id, Yii::app()->session['subject_view'])){
			//buggy we need to reasign a new array as we can not modify an array on the fly in a session var
			$arr_sv = Yii::app()->session['subject_view'];
			$arr_sv[] = $this->model->id;
			Yii::app()->session['subject_view'] = $arr_sv;
			
			$this->model->views += 1;
			$this->model->save();
		}
		
		$this->render('view',array(
			'model'=>$this->model,
		));
	}

	/**
	 * Displays the countdown for a subject to go to LIVE.
	 * @param mixed $id or urn the ID of the model to be displayed
	 */
	public function actionCountdown($id='')
	{
		if(! $id) $this->redirect(array('index'));
		if(! is_int($id)){
			$this->model = Subject::model()->find('urn=:urn', array(':urn'=>$id));						
		}else{
			$this->model=$this->loadModel($id);
		}
		if(! $this->model) throw new CHttpException(404,Yii::t('site','The requested page does not exist.'));
		
		
		$this->render('countdown',array(
			'model'=>$this->model,
		));
	}
	
	
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionAdd()
	{
		$this->layout = '//layouts/column1';
		$this->model=new Subject;
		$this->model->scenario='add';

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($this->model);
		$country_id = 1;
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

			$this->model->user_country_id = $country_id;
			
			if($this->model->save()){
				$wait = Subject::getPrognostic($this->model->id);
				Yii::app()->user->setFlash('subject_added',Yii::t('subject','Subject succesfully submitted!. Your subject has just been sended to a moderator for its approval. If your subject gets approved, it will go to the homepage(livestram) on an estimated time.'));
				Yii::app()->user->setFlash('subject_added_info',Yii::t('subject','Here is your prognostic: your subject is on position <b>{position}</b> of the queue and has a wating time of <b>{time}</b> minutes approximately.',array('{position}'=>$wait['position'],'{time}'=>$wait['time'])));
				
				//Notify manager users
				//$users = User::model()->findAll('user_type_id > 2 AND user_status_id = 1');
				//foreach($users as $user){
					$send_mail = true;
					if(! Yii::app()->user->isGuest){
						$user = User::model()->findByPk(Yii::app()->user->id); 
						if($user->user_type_id > 2) $send_mail=false;//Dont notify managers themself
					}
					if($send_mail){						
						//$mail_message = "Hi {$user->username}, \n\n";
						$mail_message = Yii::t('subject',"This is a automatic message to notify you that a subject has been added by a user an that it is
pending for approval by a samesub moderator.
Details
Subject Title: {title}
Uploaded time: {uploaded_time} UTC
Current time: {current_time} UTC (time of this message)
You can go right now and approve this subject so that the final user can
see his/her subject in the LIVE stream(homepage) as soon as posible.

{link}
NOTE: This message is supposed be received only by moderator users. If you
are not a moderator or authorizer please notify us replaying to this mail.",array('{title}'=>$this->model->title,'{uploaded_time}'=>date("Y/m/d H:i", $this->model->time_submitted), '{current_time}'=>date("Y/m/d H:i", SiteLibrary::utc_time()),'{url}'=>Yii::app()->getRequest()->getBaseUrl(true)."/subject/manage"));
$mail_message .= "\n\n";		
$mail_message .= Yii::t('site',"Thanks
Sincerely
Samesub Team
www.samesub.com");							
						SiteLibrary::send_email(Yii::app()->params['contactEmail'],Yii::t('subject',"New subject added {id}",array('{id}'=>$this->model->id)),$mail_message);
					}
				//}
					
				
			}
		}else{
			$this->model->country_id = $country_id;
			$this->model->user_position_anydatetime = 1;
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
				throw new CHttpException(403,Yii::t('subject','You are not allowed to update this subject.'));
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
			throw new CHttpException(403,Yii::t('subject','You are not allowed to moderate this subject.'));
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
					if($this->model->save()){
						
						$user = User::model()->findByPk($this->model->user_id); 
						if($user->notify_subject_authorized){							
							$mail_message = Yii::t('subject',"Hi {username} 
This is a automatic message to notify that your subject has been authorized.
That means it is going to get LIVE(homepage) very soon, so be alert.
Details
Subject Title: {title}
Uploaded time: {uploaded_time} UTC
Current time: {current_time} UTC (time of this message)
NOTE: This message is supposed be received only by the uploader user. If you
are not the uploader of this subject please notify us by replaying to this mail."
,array('{username}'=>$user->username,'{title}'=>$this->model->title,'{uploaded_time}'=>date("Y/m/d H:i", $this->model->time_submitted), '{current_time}'=>date("Y/m/d H:i", SiteLibrary::utc_time())));

$mail_message .= "\n\n";		
$mail_message .= Yii::t('site',"Thanks
Sincerely
Samesub Team
www.samesub.com");			
							SiteLibrary::send_email($user->email,Yii::t('subject',"Subject Authorized"),$mail_message);
						}
						
						
						$this->redirect(array('manage'));
					}
			}

			$this->render('authorize',array(
				'model'=>$this->model,
			));
		}else
		{
			throw new CHttpException(403,Yii::t('subject','You are not allowed to authorize this subject.'));
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
				throw new CHttpException(400,Yii::t('site','Invalid request. Please do not repeat this request again.'));
		}else
		{
			throw new CHttpException(403,Yii::t('subject','You are not allowed to delete this subject.'));
		}
	}
	
	/**
	 * Fetch data about a subject.
	 * This action does NOT return a html web page. 
	 * This action returns data about a subject in an encoded format for data interchange(JSON,XML,etc.)
	 * It can be used for API implementation.
	 */
	public function actionFetch($subject_id=0,$comment_id=0)
	{
		//in case params are sent via POST
		if(isset($_POST['subject_id'])) $subject_id = $_POST['subject_id'];
		if(isset($_POST['comment_id'])) $comment_id = $_POST['comment_id'];
		//PHP casts any string to 0. so its ok. We need to cast in case we receive a string.
		$subject_id =  (int)$subject_id;
		$comment_id = (int)$comment_id;
		$data = NULL;
		$data = Subject::getLiveData($subject_id,$comment_id,false);
		if($data['new_sub'] == 0) $this->no_log = true;//This is just to control the logging functionality(client dont receive this info)
		
		
		if($data['new_sub'] <> 0) {
			if(isset($_GET['subject_id'])){//Only if its not comming from site.php js(that script does not sends that param)
				if($data['subject_id'])	$this->model=$this->loadModel($data['subject_id']);

				//Track each unique view of the subject in homepage as subjects passes by
				if(!(Yii::app()->session['subject_view_live'])) Yii::app()->session['subject_view_live'] = array('1'=>1); //just in case start it with something
				if(! in_array($this->model->id, Yii::app()->session['subject_view_live'])){
					//buggy we need to reasign a new array as we can not modify an array on the fly in a session var
					$arr_sv = Yii::app()->session['subject_view_live'];
					$arr_sv[] = $this->model->id;
					Yii::app()->session['subject_view_live'] = $arr_sv;
					
					$this->model->live_views += 1;
					$this->model->save();
				}
			}
			
		}
		
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
		$this->model->deleted = 0;
		

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
			throw new CHttpException(403,Yii::t('subject','You are not allowed to manage subjects.'));
		}
	}
	
	/**
	 * Time Board.
	 */
	public function actionTimeboard($id=null,$day=null,$hour=null,$minute=null)
	{
		if(Yii::app()->user->checkAccess('subject_manage'))
		{
			$utc_time  = SiteLibrary::utc_time();
			//If there are any position changes update the timeboard first
			if($id and $day and isset($hour) and isset($minute)){ //hour and minute can be 0 thats why we use isset instead of simple if
								
				
				//if day is less than today then set month as next future month, 
				if($day < (int)date("j",$utc_time)){
					$month = (date("m",$utc_time) == '12') ? 1 : (((int)date("m",$utc_time)) + 1);
					$year = ((int)date("Y",$utc_time)) + 1;
				}else{
					$month = date("m",$utc_time);
					$year = date("Y",$utc_time);
				}
				
				$position = strtotime($year."-".$month."-".$day." ".$hour.":".$minute.":00",$utc_time);
				//$position = strtotime("2012-06-10 14:28");
				//echo $position. $year."-".$month."-".$day." ".$hour.":".$minute.":00";
				//die($position);
				Subject::set_position($id,$position);
			}
			

			
			$this->model=new Subject('manage');
			$this->model->unsetAttributes();  // clear any default values
			$this->model->authorized = 1;
			$this->model->approved = 1;
			$this->model->disabled = 0;
			$this->model->deleted = 0;
			$this->model->position = ">=".SiteLibrary::utc_time_interval();
			if(isset($_GET['Subject']))
				$this->model->attributes=$_GET['Subject'];
			
			$live_subject = Yii::app()->db->createCommand()
			->select('*')
			->from('live_subject')
			->queryRow();
			//if(! isset($this->model->disabled)) $this->model->disabled = 0;//Set to view only NOT disabled subjects by default(notice isset insted of a simple if)
			
			$this->render('timeboard',array(
				'model'=>$this->model,'live_subject'=>$live_subject,
			));
		}else
		{
			throw new CHttpException(403,Yii::t('subject','You are not allowed to manage subjects.'));
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
			throw new CHttpException(404,Yii::t('site','The requested page does not exist.'));
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
