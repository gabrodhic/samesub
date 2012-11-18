<?php
/**
 * ApiController is a customized controller class for the API.
 * All controller classes for the API should extend from this base class.
 */
class ApiController extends CController
{
	public $arr_response = array();
	
	
	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'Global',
			'accessControl', // perform access control for CRUD operations
		);
	}
	
		/**
	 * Override the missingAction method with our own.
	 */
	public function missingAction(){
		global $arr_response;
		$arr_response['response_code'] = 404;
		$arr_response['response_message'] = Yii::t('api','No such method in this API section.');
		if($_REQUEST['response_format'] == 'xml'){
			header('Content-type: text/xml');
			$response = SiteLibrary::array2xml($arr_response);
		}else{
			header('Content-type: text/plain');
			$response = json_encode($arr_response);
		}
		echo $response;
	}
	
	/*
	 * Filter to be applied to all actions
	 */
	public function FilterGlobal($filterChain){
		global $arr_response;
		
		//Pre-filter
		//Initialize API response default values. This values can be overriten in any of the different API Controller actions.
		$arr_response['response_code'] = 200;
		$arr_response['response_message'] = 'OK';		
		$filterChain->run();
		//Post-filter
		if($arr_response){
			if($_REQUEST['response_format'] == 'xml'){
				header('Content-type: text/xml');
				$response = SiteLibrary::array2xml($arr_response);
			}else{
				//Do a pretty print if available in the running php/json version compilation
				if($_REQUEST['response_print'] == 'pretty'){
					if (defined(JSON_PRETTY_PRINT))
						$response = json_encode($arr_response,JSON_PRETTY_PRINT);
					else
						$response = json_encode($arr_response);
				}else{
					header('Content-type: text/plain');
					$response = json_encode($arr_response);
				}
			}
			echo $response;
		}
		return true;
	}
}