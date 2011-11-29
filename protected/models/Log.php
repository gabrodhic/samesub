<?php

/**
 * This is the model class for table "log".
 *
 * The followings are the available columns in table 'log':
 * @property integer $id
 * @property integer $time
 * @property string $session_id
 * @property integer $user_id
 * @property string $controller
 * @property string $action
 * @property string $uri
 * @property string $model
 * @property integer $model_id
 * @property string $theme
 */
class Log extends CActiveRecord
{
	public $username;
	public $device;
	public $country;
	public $charset;
	public $language;
	public $referer;
	public $agent;
	public $request_ip;
	public $client_ip;
	/**
	 * Returns the static model of the specified AR class.
	 * @return Log the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('time, controller', 'required'),
			array('user_id, model_id', 'numerical', 'integerOnly'=>true),
			array('session_id', 'length', 'max'=>40),
			array('controller, action, username, device', 'length', 'max'=>30),
			array('uri', 'length', 'max'=>255),
			array('model', 'length', 'max'=>20),
			array('theme', 'length', 'max'=>2),
			array('time', 'length', 'max'=>30),
			array('country', 'length', 'max'=>30),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, time, session_id, user_id, controller, action, uri, model, model_id, theme', 'safe', 'on'=>'search'),
		);
	}

	public function afterValidate()
    {
		//If the time param was submitted in a non unix timestamp format, then format it that way
		//$this->time = strtotime($this->time);
		//Placing the code as above causes that on the response back to the client view 
		//the input gets the converted value, so we'll just convert it on the DB query
		return true;
	}
	/*
	 * Determines if a string is related to a bot, sipder, crawler etc
	 * @param string the text to evaluate(this would be most of the time a user_agent string comming from a http request)
	 * @return boolean true if is related, false if not.
	 * List source taken from http://www.robotstxt.org/db/all.txt
	 * TODO: Parse the link above insert it on a DB Table periodically as it gets updated (weekly)
	 * See also http://awstats.cvs.sourceforge.net/viewvc/awstats/awstats/wwwroot/cgi-bin/lib/robots.pm?view=markup
	 * Or http://www.user-agents.org/index.shtml
	*/
	public function is_bot($bot_text){
		$bots = 
$bots = array();
$bots[] = "ABCdatos ";
$bots[] = "Due to a deficiency in Java it's not currently possible to set the User-Agent.";
$bots[] = "Ahoy";
$bots[] = "AlkalineBOT";
$bots[] = "Anthill";
$bots[] = "appie";
$bots[] = "Arachnophilia";
$bots[] = "no";
$bots[] = "Araneo";
$bots[] = "AraybOt";
$bots[] = "ArchitextSpider";
$bots[] = "arks/1.0";
$bots[] = "ASpider";
$bots[] = "ATN_Worldwide";
$bots[] = "Atomz";
$bots[] = "AURESYS";
$bots[] = "BackRub";
$bots[] = "BaySpider";
$bots[] = "bbot";
$bots[] = "Big Brother";
$bots[] = "Bjaaland/";
$bots[] = "BlackWidow";
$bots[] = "Die Blinde Kuh";
$bots[] = "None";
$bots[] = "borg-bot";
$bots[] = "BoxSeaBot";
$bots[] = "Mozilla";
$bots[] = "BSpider";
$bots[] = "CACTVS Chemistry Spider";
$bots[] = "Calif";
$bots[] = "Digimarc CGIReader";
$bots[] = "Checkbot";
$bots[] = "ChristCrawler.com";
$bots[] = "cIeNcIaFiCcIoN.nEt Spider";
$bots[] = "CMC/0.01";
$bots[] = "LWP";
$bots[] = "combine/0.0";
$bots[] = "Confuzzledbot";
$bots[] = "CoolBot";
$bots[] = "root/";
$bots[] = "cosmos/";
$bots[] = "Internet Cruiser Robot";
$bots[] = "Cusco/";
$bots[] = "CyberSpyder";
$bots[] = "CydralSpider";
$bots[] = "DesertRealm.com";
$bots[] = "Deweb/";
$bots[] = "dienstspider";
$bots[] = "Digger/";
$bots[] = "DIIbot";
$bots[] = "grabber";
$bots[] = "DNAbot";
$bots[] = "DragonBot";
$bots[] = "DWCP/";
$bots[] = "LWP";
$bots[] = "EbiNess/";
$bots[] = "EIT-Link-Verifier-Robot";
$bots[] = "elfinbot";
$bots[] = "Emacs-w3/";
$bots[] = "EMC Spider";
$bots[] = "esculapio/";
$bots[] = "esther";
$bots[] = "Evliya Celebi";
$bots[] = "explorersearch";
$bots[] = "FastCrawler";
$bots[] = "FDSE robot";
$bots[] = "FelixIDE/";
$bots[] = "Hazel's Ferret Web hopper";
$bots[] = "ESIRover";
$bots[] = "fido/";
$bots[] = "Hämähäkki/";
$bots[] = "KIT-Fireball/";
$bots[] = "Fish-Search-Robot";
$bots[] = "fouineur";
$bots[] = "CRIM";
$bots[] = "Freecrawl";
$bots[] = "FunnelWeb";
$bots[] = "gammaSpider";
$bots[] = "gazz";
$bots[] = "gcreep/";
$bots[] = "???";
$bots[] = "GetURL";
$bots[] = "Golem/";
$bots[] = "Googlebot";
$bots[] = "griffon";
$bots[] = "Gromit";
$bots[] = "Gulliver/";
$bots[] = "Gulper";
$bots[] = "havIndex";
$bots[] = "AITCSRobot";
$bots[] = "Hometown Spider Pro";
$bots[] = "wired-digital-newsbot";
$bots[] = "htdig/";
$bots[] = "HTMLgobble";
$bots[] = "iajaBot/";
$bots[] = "IBM_Planetwide";
$bots[] = "gestaltIconoclast";
$bots[] = "INGRID/";
$bots[] = "Mozilla 3.01 PBWF (Win95)";
$bots[] = "IncyWincy";
$bots[] = "Informant";
$bots[] = "InfoSeek Robot";
$bots[] = "Infoseek Sidewinder";
$bots[] = "InfoSpiders";
$bots[] = "inspectorwww";
$bots[] = "IAGENT/";
$bots[] = "I Robot";
$bots[] = "Iron33";
$bots[] = "IsraeliSearch";
$bots[] = "JavaBee";
$bots[] = "JBot";
$bots[] = "JCrawler";
$bots[] = "Ask Jeeves";
$bots[] = "JoBo";
$bots[] = "Jobot";
$bots[] = "JoeBot";
$bots[] = "JubiiRobot";
$bots[] = "jumpstation";
$bots[] = "image.kapsi.net";
$bots[] = "Katipo";
$bots[] = "KDD-Explorer";
$bots[] = "KO_Yappo_Robot";
$bots[] = "LabelGrab";
$bots[] = "larbin";
$bots[] = "legs";
$bots[] = "Linkidator";
$bots[] = "LinkScan Server";
$bots[] = "LinkWalker";
$bots[] = "Lockon";
$bots[] = "logo.gif crawler";
$bots[] = "Lycos";
$bots[] = "Magpie";
$bots[] = "marvin/infoseek";
$bots[] = "M/3.";
$bots[] = "MediaFox";
$bots[] = "MerzScope";
$bots[] = "NEC-MeshExplorer";
$bots[] = "MindCrawler";
$bots[] = "UdmSearch";
$bots[] = "moget/";
$bots[] = "MOMspider";
$bots[] = "Monster";
$bots[] = "Motor/";
$bots[] = "MSNBOT/";
$bots[] = "Muninn/";
$bots[] = "MuscatFerret";
$bots[] = "MwdSearch";
$bots[] = "sharp-info-agent";
$bots[] = "NDSpider/";
$bots[] = "NetCarta CyberPilot Pro";
$bots[] = "NetMechanic";
$bots[] = "NetScoop/";
$bots[] = "newscan-online";
$bots[] = "NHSEWalker";
$bots[] = "Nomad";
$bots[] = "NorthStar";
$bots[] = "ObjectsSearch";
$bots[] = "Occam";
$bots[] = "HKU WWW Robot";
$bots[] = "OntoSpider";
$bots[] = "Openfind";
$bots[] = "Orbsearch";
$bots[] = "PackRat";
$bots[] = "PageBoy";
$bots[] = "ParaSite";
$bots[] = "Patric";
$bots[] = "web robot PEGASUS";
$bots[] = "Peregrinator-Mathematics";
$bots[] = "PerlCrawler";
$bots[] = "Duppies";
$bots[] = "phpdig";
$bots[] = "PiltdownMan";
$bots[] = "Pimptrain";
$bots[] = "Pioneer";
$bots[] = "PortalJuice.com";
$bots[] = "PGP-KA";
$bots[] = "PlumtreeWebAccessor";
$bots[] = "Poppi";
$bots[] = "PortalBSpider";
$bots[] = "psbot";
$bots[] = "straight";
$bots[] = "Raven-";
$bots[] = "Resume Robot";
$bots[] = "RHCS/";
$bots[] = "RixBot";
$bots[] = "Road Runner";
$bots[] = "Robbie/";
$bots[] = "ComputingSite";
$bots[] = "RoboCrawl";
$bots[] = "Robofox";
$bots[] = "Robozilla/";
$bots[] = "Roverbot";
$bots[] = "RuLeS/";
$bots[] = "SafetyNet Robot";
$bots[] = "Scooter/2.0 G.R.A.B";
$bots[] = "Sleek Spider";
$bots[] = "searchprocess/";
$bots[] = "Senrigan";
$bots[] = "SG-Scout";
$bots[] = "Shagseeker";
$bots[] = "Shai'Hulud";
$bots[] = "libwww-perl";
$bots[] = "SimBot";
$bots[] = "Site Valet";
$bots[] = "SiteTech-Rover";
$bots[] = "aWapClient";
$bots[] = "SLCrawler";
$bots[] = "Slurp";
$bots[] = "ESISmartSpider";
$bots[] = "Snooper";
$bots[] = "Solbot";
$bots[] = "Speedy Spider";
$bots[] = "mouse.house";
$bots[] = "SpiderBot";
$bots[] = "spiderline";
$bots[] = "SpiderMan0";
$bots[] = "SpiderView";
$bots[] = "ssearcher100";
$bots[] = "suke";
$bots[] = "suntek";
$bots[] = "http://www.sygol.com";
$bots[] = "Black Widow";
$bots[] = "Tarantula/";
$bots[] = "tarspider";
$bots[] = "dlw3robot";
$bots[] = "TechBOT";
$bots[] = "Templeton";
$bots[] = "TitIn";
$bots[] = "TITAN";
$bots[] = "TLSpider";
$bots[] = "UCSD-Crawler";
$bots[] = "UdmSearch";
$bots[] = "uptimebot";
$bots[] = "urlck/";
$bots[] = "URL Spider Pro";
$bots[] = "Valkyrie/";
$bots[] = "Verticrawlbot";
$bots[] = "Victoria/";
$bots[] = "vision-search";
$bots[] = "void-bot";
$bots[] = "Voyager/";
$bots[] = "VWbot_K/";
$bots[] = "w3index";
$bots[] = "W3M2";
$bots[] = "CrawlPaper";
$bots[] = "WWWWanderer";
$bots[] = "w@pSpider";
$bots[] = "WebBandit";
$bots[] = "WebCatcher";
$bots[] = "WebCopy";
$bots[] = "WebFetcher";
$bots[] = "weblayers";
$bots[] = "WebLinker";
$bots[] = "WebMoose";
$bots[] = "WebQuest";
$bots[] = "Digimarc WebReader";
$bots[] = "WebReaper";
$bots[] = "webs@recruit.co.jp";
$bots[] = "webvac/";
$bots[] = "webwalk";
$bots[] = "WebWalker/";
$bots[] = "WebWatch";
$bots[] = "Wget";
$bots[] = "whatUseek_winona";
$bots[] = "wlm-";
$bots[] = "w3mir";
$bots[] = "WOLP/";
$bots[] = "WWWC";
$bots[] = "XGET";
$bots[] = "Nederland.zoek";


	foreach($bots as $bot){
		if(stripos($bot_text, $bot)) return true;
	}
	
	if($bot_text == " ") return true;
	if(stripos($bot_text,"spider")) return true;
	if(stripos($bot_text,"crawl")) return true;
	if(stripos($bot_text,"bot")) return true;

	return false;
		
	}
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('site','ID'),
			'time' => Yii::t('site','Time'),
			'session_id' => Yii::t('site','Session'),
			'user_id' => Yii::t('site','User'),
			'controller' => Yii::t('site','Controller'),
			'action' => Yii::t('site','Action'),
			'uri' => Yii::t('site','Uri'),
			'model' => Yii::t('site','Model'),
			'model_id' => Yii::t('site','Model'),
			'theme' => Yii::t('site','Theme'),
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$pattern = "/(<|>|<=|>=|<>|=)(.+)/i";
		$time_cond = preg_replace($pattern, "\$1", $this->time);//preg_replace() returns the same string if nothing is found
		if($time_cond == $this->time) $time_cond = "";
		$time_val = preg_replace($pattern, "\$2", $this->time);

		$criteria->compare('time',$time_cond.strtotime($time_val));
		$criteria->compare('session_id',$this->session_id,true);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('controller',$this->controller,true);
		$criteria->compare('action',$this->action,true);
		$criteria->compare('uri',$this->uri,true);
		$criteria->compare('model',$this->model,true);
		$criteria->compare('model_id',$this->model_id);
		$criteria->compare('theme',$this->theme,true);
		
		$criteria->compare('username',$this->username);
		$criteria->compare('device',$this->device);
		$criteria->compare('c.id',$this->country);
		
		$criteria->join = 'LEFT JOIN user as u ON u.id = t.user_id
		LEFT JOIN log_detail as ld ON ld.session = t.session_id
		LEFT JOIN country as c ON c.id = ld.client_country_id
		LEFT JOIN country as cc ON cc.id = ld.request_country_id
		';
		$criteria->select = 't.id, t.time, t.session_id, u.username as username, t.user_id, t.controller, t.action, t.theme,
		ld.device, c.name as country, t.uri, ld.language, ld.charset, ld.referer, ld.agent, ld.request_ip, ld.client_ip';
		

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array('defaultOrder'=>'t.id DESC'),
		));
	}
}