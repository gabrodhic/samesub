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
		$bots = "ABCdatos BotLink/1.0.2 (test links)
Due to a deficiency in Java it's not currently possible to set the User-Agent.
'Ahoy! The Homepage Finder'
AlkalineBOT
AnthillV1.1
appie/1.1
Arachnophilia
no
Araneo/0.7 (araneo@esperantisto.net; http://esperantisto.net)
AraybOt/1.0 (+http://www.araykoo.com/araybot.html)
ArchitextSpider
arks/1.0
ASpider/0.09
ATN_Worldwide
Atomz/1.0
AURESYS/1.0
BackRub/*.*
BaySpider
bbot/0.100
Big Brother
Bjaaland/0.5
BlackWidow
Die Blinde Kuh
None
borg-bot/0.9
BoxSeaBot/0.5 (http://boxsea.com/crawler)
Mozilla/3.01 (compatible;)
BSpider/1.0 libwww-perl/0.40
CACTVS Chemistry Spider
Calif/0.6 (kosarev@tnps.net; http://www.tnps.dp.ua)
Digimarc CGIReader/1.0
Checkbot/x.xx LWP/5.x
Mozilla/4.0 (compatible; ChristCrawler.com, ChristCrawler@ChristCENTRAL.com)
cIeNcIaFiCcIoN.nEt Spider (http://www.cienciaficcion.net)
CMC/0.01
LWP
combine/0.0
Confuzzledbot/X.X (+http://www.confuzzled.lu/bot/)
CoolBot
root/0.1
cosmos/0.3
Internet Cruiser Robot/2.1
Cusco/3.2
CyberSpyder/2.1
CydralSpider/X.X (Cydral Web Image Search;
DesertRealm.com; 0.2; [J];
Deweb/1.01
dienstspider/1.0  
Digger/1.0 JDK/1.3.0
DIIbot
grabber
DNAbot/1.0
DragonBot/1.0 libwww/5.0
DWCP/2.0
LWP::
EbiNess/0.01a
EIT-Link-Verifier-Robot/0.2
elfinbot
Emacs-w3/v[0-9\.]+
EMC Spider
esculapio/1.1
esther
Evliya Celebi v0.151 - http://ilker.ulak.net.tr
explorersearch
FastCrawler 3.0.X (crawler@1klik.dk) - http://www.1klik.dk
Mozilla/4.0 (compatible: FDSE robot)
FelixIDE/1.0
Hazel's Ferret Web hopper, 
ESIRover v1.0
fido/0.9 Harvest/1.4.pl2
Hämähäkki/0.2
KIT-Fireball/2.0 libwww/5.0a
Fish-Search-Robot
Mozilla/2.0 (compatible fouineur v2.0; fouineur.9bit.qc.ca)
Robot du CRIM 1.0a
Freecrawl
FunnelWeb-1.0
gammaSpider xxxxxxx ()/
gazz/1.0
gcreep/1.0
???
GetURL.rexx v1.05
Golem/1.1
Googlebot/2.X (+http://www.googlebot.com/bot.html)
griffon/1.0
Gromit/1.0
Gulliver/1.1
Gulper Web Bot 0.2.4 (www.ecsl.cs.sunysb.edu/~maxim/cgi-bin/Link/GulperBot)
yes
havIndex/X.xx[bxx]
AITCSRobot/1.1
Hometown Spider Pro
wired-digital-newsbot/1.5
htdig/3.1.0b2
HTMLgobble v2.2
no
iajaBot/0.1
IBM_Planetwide, 
gestaltIconoclast/1.0 libwww-FM/2.17
INGRID/0.1
Mozilla 3.01 PBWF (Win95)
IncyWincy/1.0b1
Informant
InfoSeek Robot 1.0
Infoseek Sidewinder
InfoSpiders/0.1
inspectorwww/1.0 http://www.greenpac.com/inspectorwww.html
'IAGENT/1.0'
I Robot 0.4 (irobot@chaos.dk)
Iron33/0.0
IsraeliSearch/1.0
JavaBee
JBot
JCrawler/0.2
Mozilla/2.0 (compatible; Ask Jeeves/Teoma)
JoBo
Jobot/0.1alpha libwww-perl/4.0
JoeBot
JubiiRobot
jumpstation
image.kapsi.net/1.0
Katipo/1.0
KDD-Explorer/0.1
KO_Yappo_Robot/1.0.4(http://yappo.com/info/robot.html)
LabelGrab/1.1
larbin (+mail)
legs
Linkidator/0.93
LinkScan Server/5.5 | LinkScan Workstation/5.5
LinkWalker
Lockon
logo.gif crawler
Lycos
Magpie/1.0
marvin/infoseek (marvin-team@webseek.de)
M/3.8
MediaFox/x.y
MerzScope
NEC-MeshExplorer
MindCrawler
UdmSearch
moget/1.0
MOMspider/1.00 libwww-perl/0.40
Monster
Motor/0.2
MSNBOT/0.1 (http://search.msn.com/msnbot.htm)
Muninn/0.1 libwww-perl-5.76
MuscatFerret
MwdSearch/0.1
User-Agent: Mozilla/4.0 (compatible; sharp-info-agent v1.0; )
NDSpider/1.5
NetCarta CyberPilot Pro
NetMechanic
NetScoop/1.0 libwww/5.0a
newscan-online/1.1
NHSEWalker/3.0
Nomad
NorthStar
ObjectsSearch/0.01
Occam/1.0
HKU WWW Robot,
OntoSpider/1.0 libwww-perl/5.65
Openfind data gatherer, Openbot/3.0+(robot-response@openfind.com.tw;+http://www.openfind.com.tw/robot.html)
Orbsearch/1.0
PackRat/1.0
PageBoy/1.0
ParaSite/0.21 (http://www.ianett.com/parasite/)
Patric/0.01a
web robot PEGASUS
Peregrinator-Mathematics/0.7
PerlCrawler/1.0 Xavatoria/2.0
Duppies
phpdig
PiltdownMan/1.0 profitnet@myezmail.com
Mozilla/4.0 (compatible: Pimptrain's robot)
Pioneer
PortalJuice.com/4.0
PGP-KA/1.2
PlumtreeWebAccessor/0.9
Poppi/1.0
PortalBSpider/1.0 (spider@portalb.com)
psbot/0.X (+http://www.picsearch.com/bot.html)
straight FLASH!! GetterroboPlus 1.5
Raven-v2
Resume Robot
RHCS/1.0a
RixBot (http://www.oops-as.no/rix/)
Road Runner: ImageScape Robot (lim@cs.leidenuniv.nl)
Robbie/0.1
ComputingSite Robi/1.0 (robi@computingsite.com)
RoboCrawl (http://www.canadiancontent.net)
Robofox v2.0
Robozilla/1.0
Roverbot
RuLeS/1.0 libwww/4.0
SafetyNet Robot 0.1
Scooter/2.0 G.R.A.B. V1.1.0
Mozilla/4.0 (Sleek Spider/1.2)
searchprocess/0.9
Senrigan
SG-Scout
Shagseeker at http://www.shagseek.com /1.0
Shai'Hulud
libwww-perl-5.41
SimBot/1.0
Site Valet
SiteTech-Rover
aWapClient
SLCrawler
Slurp/2.0
ESISmartSpider/2.0
Snooper/b97_01
Solbot/1.0 LWP/5.07
Speedy Spider ( http://www.entireweb.com/speedy.html )
mouse.house/7.1
SpiderBot/1.0
spiderline/3.1.3
SpiderMan 1.0
Mozilla/4.0 (compatible; SpiderView 1.0;unix)
ssearcher100
suke
suntek
http://www.sygol.com
Black Widow
Tarantula/1.0
tarspider
dlw3robot
TechBOT
Templeton
TitIn/0.2
TITAN/0.1
TLSpider/1.1
UCSD-Crawler
UdmSearch/2.1.1
uptimebot
urlck/1.2.3
URL Spider Pro
Valkyrie/1.0 libwww-perl/0.40
Verticrawlbot
Victoria/1.0
vision-search/3.0
void-bot/0.1 (bot@void.be; http://www.void.be/)
Voyager/0.0
VWbot_K/4.2
w3index
W3M2
CrawlPaper
WWWWanderer v3.0
w@pSpider
WebBandit/1.0
WebCatcher/1.0
WebCopy
WebFetcher
weblayers
WebLinker
WebMoose
WebQuest/1.0
Digimarc WebReader/1.2
WebReaper [webreaper@otway.com]
webs@recruit.co.jp
webvac/1.0
webwalk
WebWalker/1.10
WebWatch
Wget
whatUseek_winona
wlm-1.1
w3mir
WOLP/1.0 mda/1.0
WWWC
XGET
Nederland.zoek
";
	 if(stripos($bots, $bot_text)){
		return true;
	}else{
		if($bot_text == " ") return true;
		if(stripos($bot_text,"spider")) return true;
		if(stripos($bot_text,"crawl")) return true;
		if(stripos($bot_text,"bot")) return true;
	}
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
			'id' => 'ID',
			'time' => 'Time',
			'session_id' => 'Session',
			'user_id' => 'User',
			'controller' => 'Controller',
			'action' => 'Action',
			'uri' => 'Uri',
			'model' => 'Model',
			'model_id' => 'Model',
			'theme' => 'Theme',
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
		LEFT JOIN country as c ON c.id = ld.client_country_id OR c.id = ld.request_country_id
		';
		$criteria->select = 't.id, t.time, t.session_id, u.username as username, t.user_id, t.controller, t.action, t.theme,
		ld.device, c.name as country, t.uri, ld.language, ld.charset, ld.referer, ld.agent';
		

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array('defaultOrder'=>'t.id DESC'),
		));
	}
}