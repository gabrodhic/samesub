<?php

class TestController extends Controller
{
/**
	* @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	* using two-column layout. See 'protected/views/layouts/column2.php'.
	*/
	public $layout='//layouts/column1';
	
	public function actionIndex()
	{
	
	
	//SiteLibrary::generateCaptcha();
	
	
$a ='aaa';	

$b = new CCaptchaAction($this, 'index');
//$b->generateValidationHash();

$b->run();
die();
$codigo =  $b->getVerifyCode();
echo $codigo;
//$b->renderImage($codigo);

//die();

var_dump($b->validate($codigo.'a',true));
die();
function emu_getallheaders() { 
        foreach ($_SERVER as $name => $value) 
       { 
           if (substr($name, 0, 5) == 'HTTP_') 
           { 
               $name = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5))))); 
               $headers[$name] = $value; 
           } else if ($name == "CONTENT_TYPE") { 
               $headers["Content-Type"] = $value; 
           } else if ($name == "CONTENT_LENGTH") { 
               $headers["Content-Length"] = $value; 
           } 
       } 
       return $headers; 
    } 
	$_SERVER['HTTP_USER_AGENT']='mynewbrowser';
print_r(emu_getallheaders());
die();

	
	
		$ana = SiteLibrary::get_image_resized("18.jpg","D:/xampp/htdocs/samesub/img/1", 33,79,false);
var_dump( $ana);	
	echo "lllllllllllllllll";
		//print_r(getimagesize("D:/xampp/htdocs/samesub/img/1/26.png"));
		die();
		/*
		Yii::import('ext.EUploadedImage');
		$gg = new EUploadedImage("jota.jpg",addslashes("D:/xampp/htdocs/samesub/img/1/26.png"),"image/png",111,0);//_error = 0 for UPLOAD_ERR_OK pass ok
		$gg->keepratio = false;
		$gg->maxWidth = 2000;
		$gg->maxHeight = 2000;
		echo $gg->saveAs(addslashes("D:/xampp/htdocs/samesub/img/1/gab.png"),true);
		*/
		//$img_path = Yii::app()->params['img_path'];
		//if (! $this->image->saveAs(Yii::app()->params['webdir'].DIRECTORY_SEPARATOR.$img_path.DIRECTORY_SEPARATOR.$img_name)){
		die();

	
	
		$utc_time  = SiteLibrary::utc_time();
		//Round minute to a Yii::app()->params['subject_interval'] multiple (usually 5 minutes)
		$minute = date("i",$utc_time);
		$round_minute = floor(30 /5) * 5;
		echo date("H",$utc_time).":".$round_minute.":00";
		$round_utc_time = strtotime(date("H",$utc_time).":".$round_minute.":00",$utc_time);
		
		//Subject::model()->move_position_forward(47);
		//Subject::model()->set_position(47,1324390200);
		 //Yii::app()->setLanguage('en');
		 $alias = "xxxxxxx";
		 echo Yii::t('site', 'MySub');/*
		 echo Yii::t('app', 'yourcrazy', array('{alias}'=>$alias));
		 echo Yii::t('app', 'what is now doing', array('{alias}'=>$alias));
		 echo Yii::t('app', 'yourcrazy', array('{alias}'=>$alias));
		 //Yii::app()->setLanguage('afa');
		 echo Yii::t('core', 'your username is wornd', array('{alias}'=>$alias));
		 */
	}
	
		public function actionGetall()
	{
		header('Content-type: text/xml');
		$student = array (
		  'bla' => 'blub',
		  'foo' => 'bar',
		  'another_array' => array (
			'stack' => 'overflow',
		  )
		);


		function array2xml($array, $xml = false){
			if($xml === false){
				$xml = new SimpleXMLElement('<root/>');
			}
			foreach($array as $key => $value){
				if(is_array($value)){
					array2xml($value, $xml->addChild($key));
				}else{
					$xml->addChild($key, $value);
				}
			}
			return $xml->asXML();
		}

		print array2xml($student);
	}
	
	
}