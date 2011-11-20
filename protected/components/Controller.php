<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/column1';
	/**
	 * @var string OpenGraph meta tags to help identify the content information in the page(eg: a flash video).
	 * This is very usefull specially for sharing(eg:facebook) or indexing in search engines and AddThis.
	 * http://www.addthis.com/help/widget-sharing#tagging
	 */
	public $ogtags='';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();
	/**
	 * @var object the model used by the controller action.
	 * A Controller can instantiate multiple models. This property gives a way to access 
	 * the current model used in one controller action(provided model var was assigned to the controller
	 * object var in the current action code). eg in a controller action: 
	 * $model = new User; //We can NOT access the model thru the controller
	 * $this->model = new User; //notice "$this". //We can access the model thru the controller
	 */
	public $model;
	/**
	 * This variable can be set to indicate when not to log the current request
	 */
	public $no_log = false;
	/**
	 * Set the view theme depending if the user wants a mobile or standard version
	 *
	 */
	private function set_theme(){
		if (stristr($_SERVER['HTTP_HOST'],'m.') or stristr($_SERVER['HTTP_HOST'],'mobile.')){
			Yii::app()->theme='mobile';
		}
	}	
	/*
	* Override afterAction method to implement some codes(eg: logging)
	*/
	function afterAction(){
		$this->log_request();
		if( Yii::app()->session->get('site_loaded') != "yes") Yii::app()->session->add('site_loaded', 'yes');
		
		return true;
	}
	/**
	 * Log each appropiate request on the application
	 *
	 */
	private function log_request(){
	
		$command = Yii::app()->db->createCommand();
		
		//Do NOT log some specific actions in some specific conditions
		//ie:We should not be logging each user subject fetch unless there is a new subject(that would be a line in the log every 10 sec)
		if($this->action->Id == 'js') $this->no_log = true;
		if(Log::is_bot($_SERVER['HTTP_USER_AGENT'])) $this->no_log = true; ///Dont log if its a bot request
		if($this->no_log == false){
			try {
				$command->insert('log',array('time'=>SiteLibrary::utc_time(),'session_id'=>Yii::app()->getSession()->getSessionID(), 
				'user_id'=>(int)Yii::app()->user->id,'controller'=>$this->id,'action'=>$this->action->Id,'uri'=>Yii::App()->request->getRequestUri(),
				'model'=>get_class($this->model),'model_id'=>(isset($this->model->id))?(int)$this->model->id:0,
				'theme'=>(Yii::app()->getTheme()) ? strtolower(substr(Yii::app()->getTheme()->getName(),0,2)):'re'));
				//Get real clients ip if from a proxy
				$client_ip = "";
				$client_host = (!empty($_SERVER["HTTP_X_FORWARDED_HOST"])) ? $_SERVER["HTTP_X_FORWARDED_HOST"] : "";
				if (!empty($_SERVER["HTTP_CLIENT_IP"]))
				{
					$client_ip = $_SERVER["HTTP_CLIENT_IP"];//first verify ip from share internet
				}elseif(!empty($_SERVER["HTTP_X_FORWARDED_FOR"]))
				{
					$client_ip = $_SERVER["HTTP_X_FORWARDED_FOR"];//if not then for a proxy user
				}
				else{
				}
				
				//Only the first request should go here
				//if(Yii::App()->request->getCookies()->getCount() == 0)//If cookies are not set this might be the first request
				if(Yii::app()->session->get('site_loaded') != "yes"){
					$command->insert('log_detail',array('log_id'=>Yii::app()->db->getLastInsertID(),
					'session'=>Yii::app()->getSession()->getSessionID(),'client_ip'=>$client_ip,
					'client_host'=>$client_host,'request_ip'=>$_SERVER['REMOTE_ADDR'],'request_host'=>$_SERVER['REMOTE_HOST'],
					'agent'=>$_SERVER['HTTP_USER_AGENT'],'referer'=>$_SERVER['HTTP_REFERER'],'charset'=>$_SERVER['HTTP_ACCEPT_CHARSET'],
					'language'=>$_SERVER['HTTP_ACCEPT_LANGUAGE'],'device'=>substr(SiteLibrary::get_device(),0,2)));
				}
			}
			catch(CException $e){
				Yii::log($e, 'warning', 'system.web.Controller');
			}
			//'params'=>serialize($this->actionParams)
		}
		

		
	}
	// Override init to set theme property and language
	function init() {
		$this->set_theme();
		Yii::app()->setLanguage('en');//set it fixed to english by now
	}
}