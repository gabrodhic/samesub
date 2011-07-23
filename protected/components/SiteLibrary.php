<?php
/**
 * SiteLibrary is a set of common functions useful for needed functionalities accross the site
 * 
 */
class SiteLibrary extends CComponent
{
	
	/**
	 * Generates the unix timestamp for the actual UTC/GMT time
	 * @return integer the unix timestamp
	 */
	public function utc_time()
	{
		//$utc_time = mktime(gmdate("H"), gmdate("i"), gmdate("s"), gmdate("m"), gmdate("d"), gmdate("Y"));
		$utc_time = time() - (int)substr(date('O'),0,3)*60*60; //this is faster
		return $utc_time;
	}
	
	public function parse_url_query($query){
		$var  = explode('&', $query); 
		$arr  = array(); 

		foreach($var as $val) 
		{ 
			$x          = explode('=', $val); 
			$arr[$x[0]] = $x[1]; 
		} 
		unset($val, $x, $var); 
		return $arr; 
	}
	
	function valid_url($url)
	{
		return preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url);
	}
	
	/**
	 * Translates a string from one language to another.
	 * @param string $text the text to translate
	 * @param string $from the language to translate from. Leave empty to try to auto-detect.
	 * @param string $to the language to translate to. Default to English(en).
	 * @return string the translated text
	 */
	public function translate($text, $from='', $to='en',$format='text/plain'){
		//We'll be using the microsoft translator
		//http://msdn.microsoft.com/en-us/library/ff512406.aspx
		$url = 'http://api.microsofttranslator.com/V2/Ajax.svc/Translate?appId='.Yii::app()->params['bing_appid'].'&contentType='.$format.'&from='.$from.'&to='.$to.'&text='.urlencode($text);		
		$data = self::fetch_url($url);
		if($data){//If the request was valid
			//Now let's examine that there was was not error translating the text
			//the only way to do that is by checking if there is the following text in the response
			//as the api returns a text containing this when there is an error
			if( strrpos($data, ".V2_Json.Translate.") ) return false;
			$data = trim($data,'"');
		}
		return $data;
	}
	
	/**
	 * 
	 * Fetch a URL by doing a request and return the contents of the response
	 * This two extensions can be used for more advanced features
	 * http://www.yiiframework.com/extension/ehttpclient/
	 * http://www.yiiframework.com/extension/curl
	 * 
	 * @param string $url the url to request 
	 */
	public function fetch_url($url){
		
		$response = null;
		
		if ( function_exists('curl_init') ) {
		
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_USERAGENT, 'Samesub');
			$response = curl_exec($ch);
			curl_close ($ch);
			
		} else if ( ini_get('allow_url_fopen') ) {
			
			$response = file_get_contents($url, 'r');
		}
		
		return $response;
	}
	/**
	 * 
	 * Remove the link from the current active menu
	 * @param object &$that the current controller object that has the menu and the actions. We use that, because is by reference, and using this would make reference to this current library
	 * @param array $arr_titles are the menu titles
	 * Would be nice if Yii could make this, but the closest thing is just to add a class to the active item
	 * but that doesn't removes the links as we want, and it applies the class to the <li> tag, not the <a, so its imposibble via css to remove the link
	 * ie: $this->widget('zii.widgets.CMenu', array('activateItems'=>true,......			
	 * http://www.yiiframework.com/doc/api/1.1/CMenu#activateItems-detail
	 */	
	public function remove_current_url_menu(&$that,$arr_titles){ 
		
		$count = 0;
		foreach($that->menu as $arr_item)
		{
			if($arr_item['label'] == $arr_titles[$that->action->Id])  unset($that->menu[$count]['url']);
			$count++;
		}
	}
}