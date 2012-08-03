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
	
	/**
	 * Generates the unix timestamp for the actual UTC/GMT time rounded down to the nearest lower subject time interval
	 * @return integer the unix timestamp
	 */
	public function utc_time_interval()
	{
		$utc_time  = SiteLibrary::utc_time();
		//Round minute to a Yii::app()->params['subject_interval'] multiple (usually 5 minutes)
		//NOTE:use floor instead of round, as we want the nearset LOWER multimple number, and not just the nearest multiple number
		$minute = date("i",$utc_time);
		$round_minute = floor($minute/Yii::app()->params['subject_interval']) * Yii::app()->params['subject_interval'];
		$round_utc_time = strtotime(date("H",$utc_time).":".$round_minute.":00",$utc_time);
		return $round_utc_time;
	
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
		
	/*
	* Get the device type used by the current user
	* This function determines the device by examining the "user agent" Request Header.
	* This is the same code used on the index entry page to redirect to the proper subdomain depending on the device detected.
	* @return string the device type name
	*/
	public function get_device(){
		//Todo:be more specific in determining the various types of device like:
		//mobile(mobile is ambiguous, we should call it cellphone), tablet, tv, desktop, laptop, etc
		//maybe implement a webservice or install a devices DB
		$useragent=$_SERVER['HTTP_USER_AGENT'];
		if(preg_match('/android|avantgo|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|e\-|e\/|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(di|rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|xda(\-|2|g)|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)))
		{
			return 'mobile';
		}else{
			return 'desktop';
		}
	}
	
	/*
	* Get the device type used by the current user
	* This function determines the device by examining the "user agent" Request Header.
	* This is the same code used on the index entry page to redirect to the proper subdomain depending on the device detected.
	* @return object the country record
	*/
	public function get_country_from_ip($ip = ''){
		if(! $ip) $ip = $_SERVER['REMOTE_ADDR'];
		Yii::import('ext.EGeoIP');
		$geoIp = new EGeoIP();
		$geoIp->locate($ip);
		//http://www.iso.org/iso/english_country_names_and_code_elements
		$country=Country::model()->find('code=:code', array(':code'=>$geoIp->countryCode));
		if($country) 
			return $country;
		else 
			return false;
		
	}
	
	/*
	* Get the time intervals for a type of time(day,hour,minuete)
	* @param string $type the type of time interval(day,hour,minute)
	* @return array the time interval items. Otherwise return false
	*/
	public function get_time_intervals($type = 'day'){
	
		if($type == 'ymd'){
		
			$utc_time  = SiteLibrary::utc_time();
			//A 30 days iteration from NOW
			for($i = 0; $i < 30; $i++){
				$next_date = strtotime("+".$i." days",$utc_time);
				$next_date_formatted = date("Y",$next_date)."/".date("m",$next_date)."/".date("d",$next_date); 
				$next_date_formatted_txt = date("Y",$next_date)." / ".date("m",$next_date)." / ".date("d",$next_date); 
				if($i == 0) $next_date_formatted_txt = Yii::t('site','Today');
				if($i == 1) $next_date_formatted_txt = Yii::t('site','Tomorrow');
				$dates[$next_date_formatted] = $next_date_formatted_txt;
			}
			return $dates;	
		}
		elseif($type == 'day'){
			return array('1'=>1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31);//set first element to one so that array does not initializes it value on 0
		}elseif($type == 'hour'){
			return array(0=>'12 AM',1=>'01 AM',2=>'02 AM',3=>'03 AM',4=>'04 AM',5=>'05 AM',6=>'06 AM',7=>'07 AM',8=>'08 AM',9=>'09 AM',10=>'10 AM',11=>'11 AM',12=>'12 PM',13=>'01 PM',14=>'02 PM',15=>'03 PM',16=>'04 PM',17=>'05 PM',18=>'06 PM',19=>'07 PM',20=>'08 PM',21=>'09 PM',22=>'10 PM',23=>'11 PM');
		}elseif($type == 'minute'){
			return array('00'=>'00','05'=>'05','10'=>10,'15'=>15,'20'=>20,'25'=>25,'30'=>30,'35'=>35,'40'=>40,'45'=>45,'50'=>50,'55'=>55);
		}else{
			return false;
		}
	
	
	}
	
	/*
	 * Converts an array to xml. Useful for API responses ie instead of JSON encoded responses(natively supported in PHP).
	 */
	function array2xml($array, $xml = false){
			if($xml === false){
				$xml = new SimpleXMLElement('<root/>');
			}
			foreach($array as $key => $value){
				if(is_array($value)){
					if(is_numeric($key)) $key = "item";//xml syntax does not allow a tag name to be a number, so rename it for the word "item"
					self::array2xml($value, $xml->addChild($key));
				}else{
					$value = str_replace("&amp;", "&", $value);//In case by some reason the string its already escaped
					$value = str_replace("&", "&amp;", $value);//XML does not allow an ampersand symbol naked, must be escaped
					if(is_numeric($key)) $key = "item";//xml syntax does not allow a tag name to be a number, so rename it for the word "item"
					$xml->addChild($key, $value);
				}
			}
			return $xml->asXML();
	}
	
	/*
	 * Converts an existing image to the desired dimension. If the image has already been created it returns that image.
	 * @param string $filename the file name
	 * @param string $path the path of the file
	 * @param int $width the width
	 * @param int $height the height
	 * @param bool $keepratio if keeping the ratio is desireable
	 * @param bool $keepmaxresolution if image should be resized without ever exceeding the maximum available original image resolution(width x height)
	 * @return mixed file name of the new image on success, false on error.
	 */
	function get_image_resized($filename, $path, $width, $height, $keepratio=true, $keepmaxresolution=true ){
		
		//First check that the file exists( created by a previous invocation), if so return it
		if(! $image_size = getimagesize($path.DIRECTORY_SEPARATOR.$filename)) return false;
		if(!$width) $width = $height;//if width isn't set, then set it as height
		if(!$height) $height = $width;//if height isn't set, then set it as width
		$new_width = $width;
		$new_height = $height;
		

		//Try to find an original file with larger size if possible and desiareable for the ocation
		if( ($width > 980 or $height > 750) and  (! strstr($filename, 'org')) ){//Don't use strpos() as first position is 0, so will evaluate as false
			if( $image_size2 = getimagesize($path.DIRECTORY_SEPARATOR.'org_'.$filename)) { //if the file exists
				$image_size = $image_size2;
				$filename = 'org_'.$filename;
			}
		}

			
		//TODO:When keep max resolution is false we need to do some work on the naming,
		//as EUploadedImage there is not available solution for grater than original size
		$keepmaxresolution=true;
		if($keepmaxresolution and $keepratio){
			if($width > $image_size[0])	$new_width = $image_size[0];
			if($height > $image_size[1]) $new_height = $image_size[1]; 
		}
		
		
		if($keepratio) {
			if ($image_size[0] > $width) {
				$new_width = $width;
				$new_height =  floor (($width * $image_size[1] / $image_size[0]));
				$image_size[1] = (($width * $image_size[1] / $image_size[0]));//notice whiout floor. Also this. EUploadedImage:resizeImage, does this too, so we need to do it also.
				
			}
			if ($image_size[1] > $height) {
				$new_width =  floor(($height * $new_width / $image_size[1]));
				$new_height = $height;
				
			}
		}

		$split_name = explode(".", $filename);
		$new_name = str_replace('org_','',$split_name[0])."_".$new_width."x".$new_height.".".$split_name[1];//in case org,Set the name back, we dont want 'org_' prefix in the new image names

		if(file_exists($path.DIRECTORY_SEPARATOR.$new_name))
			return $new_name;

		
		Yii::import('ext.EUploadedImage'); 
		$newimage = new EUploadedImage("some.img",addslashes($path.DIRECTORY_SEPARATOR.$filename),$image_size['mime'],123,0);//_size doesnt matters just a number, _error = 0 for UPLOAD_ERR_OK pass ok
		$newimage->keepratio = $keepratio;
		$newimage->maxWidth = $width;
		$newimage->maxHeight = $height;

		if($newimage->saveAs(addslashes($path.DIRECTORY_SEPARATOR.$new_name),true))
			return $new_name;
		else
			return false;
			
		
	}
	
	/*
	 * Sends an email.
	 * @param string $to email
	 * @param string $title the message title
	 * @param string $body the message body
	 * @param string $reply_to email
	 * @param string $from email
	 * @return bool true on success false on error.
	 */
	function send_email($to, $title, $body, $reply_to="", $from="" ){
		//NOTE: In case of having problem sending email, this function can be optimized by using the smtp service with username and password
		//Any email's FROM header must be from a domain that the mail server is working with		
		if($from) 	
			$headers="From: ".$from;//This might need authentication, there might be some setting on the mailserver only to accept Default site mail
		else
			$headers="From: ".Yii::app()->params['contactEmailName']." <".Yii::app()->params['contactEmail'].">";// Default site email
			
		if($reply_to) $headers .= "\r\nReply-To: ".$reply_to;
		
		return @mail($to,$title,$body,$headers);			
	}
	
}