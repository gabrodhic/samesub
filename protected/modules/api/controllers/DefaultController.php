<?php

class DefaultController extends Controller
{
	public function actionIndex()
	{
		$this->redirect('developers/');//This should do nothing but show developers documentation
		//$this->render('index');
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
	    if($error=Yii::app()->errorHandler->error)
	    {
			//$error['message'] = '你访问的页面不存在';
			//TODO: Use the ApiController Component for all actions handling instead of the ApiModule
            $arr = array(
                'response_code'=>$error['code'],
				'response_message'=>$error['message'],				
                'errorCode'=>$error['errorCode'],
                'result'=>'false',
            );
            Apimodule::d($arr);
	    	if(Yii::app()->request->isAjaxRequest)
	    		echo $error['message'];
	    	else
	        	$this->render('error', $error);
	    }
	}

}
