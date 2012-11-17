<?php

/**
 * This is the model class for table "subject".
 *
 * The followings are the available columns in table 'subject':
 * @property integer $id
 * @property integer $user_id
 * @property string $user_ip
 * @property string $user_comment
 * @property string $title
 * @property string $urn
 * @property integer $content_type_id
 * @property integer $approved
 * @property integer $content_id
 * @property integer $country_id
 * @property integer $moderator_id
 * @property string $moderator_ip
 * @property string $moderator_comment
 * @property integer $time_submitted
 * @property integer $time_moderated
 * @property integer $priority_id
 * @property integer $show_time
 */
class Subject extends CActiveRecord
{
	public $image;
	public $image_url;
	public $text;
	public $video;
	public $urn;
	public $content;
	public $username;
	public $verifyCode;
	public $image_source;
	public $user_position_ymd;
	public $user_position_hour;
	public $user_position_minute;
	public $user_position_anydatetime;
	/**
	 * Returns the static model of the specified AR class.
	 * @return Subject the static model class
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
		return 'subject';
	}
	
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('priority_id', 'numerical', 'integerOnly'=>true, 'on'=>'add,update,moderate,authorize'),
			array('priority_id', 'numerical', 'min'=>1, 'max'=>3),
			
			
			array('title, content_type_id', 'required', 'on'=>'add,update'),
			array('content_type_id,country_id', 'numerical', 'integerOnly'=>true, 'on'=>'add,update'),			
			array('title', 'length', 'max'=>240, 'on'=>'add,update'),
			array('user_comment', 'type', 'type'=>'string', 'on'=>'add,update'),			
			array('image,image_source', 'safe', 'on'=>'add,update'),//So that it can be massively assigned, either way its gonna be validated by validateContentType
			array('image_url', 'url', 'on'=>'add,update'),
			array('text', 'safe', 'on'=>'add,update'),//So that it can be massively assigned, either way its gonna be validated by validateContentType
			array('video', 'safe', 'on'=>'add,update'),//So that it can be massively assigned, either way its gonna be validated by validateContentType
			array('content_type_id', 'validateContentType', 'on'=>'add'),
			array('user_position_ymd,user_position_hour,user_position_minute', 'safe', 'on'=>'add,update'),//So that it can be massively assigned, either way its gonna be validated by validateDateTime
			array('user_position_anydatetime', 'validateDateTime', 'on'=>'add,update'),

			array('disabled,deleted', 'numerical', 'integerOnly'=>true, 'on'=>'moderate,authorize'),
			array('tag, category', 'length', 'max'=>240),
			
			array('approved', 'numerical', 'integerOnly'=>true, 'on'=>'moderate'),
			array('moderator_comment', 'length', 'max'=>240, 'on'=>'moderate'),
			array('approved', 'validateApprobation', 'on'=>'moderate'),
			
			array('authorized', 'numerical', 'integerOnly'=>true, 'on'=>'authorize'),
			array('authorized', 'validateAuthorization', 'on'=>'authorize'),
			// verifyCode needs to be entered correctly
			array('verifyCode', 'captcha', 'allowEmpty'=>!CCaptcha::checkRequirements(), 'on'=>'add'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_id, user_ip, user_comment, title, urn, content_type_id, approved, authorized, disabled, deleted, content_id, country_id, moderator_id, moderator_ip, moderator_comment, time_submitted, time_moderated, priority_id, show_time,position', 'safe', 'on'=>'manage'),
			array('username, title, deleted, urn, tag, content_type_id, country_id, time_submitted, priority_id, show_time', 'safe', 'on'=>'history'),
		);
	}
	
	
	
	/**
	 * Check some things prior to validation
	 * 
	 */
	public function beforeValidate()
    {
		//TODO: Validate that the ip the request is coming from(independently that it comes from a proxy,nat,etc)
		//hasn't submitted any other record in a considered time frame.		
		//To validate this, we can use a combination of keys stored in the table (ip,time,request headers,title)
		//and then obtain a "trust metric" result obtained by adding and subtracting the values
		//assigned to those keys for the actual request in question.
		//If the submitted request gets enough points according to the "trust metric" then
		//it can go on, otherwise it can't and should be stopped. 
 
		return true;
	}
	/**
	 * Check some things prior to save
	 * 
	 */
	public function beforeSave()
    {
		//If its being modified
		if(! $this->getIsNewRecord()){
			//Invalidate any change if the subject has been showed while it was being modified
			if( Yii::app()->db->createCommand("SELECT * FROM live_subject WHERE subject_id = {$this->id}")->queryRow())
			{
				if(Yii::app()->controller->action->id != 'fetch' and Yii::app()->controller->action->id != 'view'){
					$this->addError('title',Yii::t('subject', 'Right now this subject is either in the comming up queue or in the live-now stream. You can not modify it.'));
					return false;
				}
			}

			//TODO:Update the table belonging to each content type(image,text,video,etc)
			//also validatecontenttype
		}
		else{
		
			// Assign the user_id 1 if is a guest
			$this->user_id=(Yii::app()->user->id) ? Yii::app()->user->id : 1;
			$this->deleted = (Yii::app()->user->isGuest) ? 1 : 0;//Hide the subject by default if its a guest
			$this->time_submitted = SiteLibrary::utc_time();
			$this->user_ip = $_SERVER['REMOTE_ADDR'];
			
			//Assign subject hash(for guest users that want to register and own an added subject)
			$this->hash = md5(uniqid(""));
			//Generate the urn for this subject
			if(! $this->urn = $this->generateUrn($this->title)){
				$this->addError('title',Yii::t('subject', 'Please change something in the title.')); return false;
			}
			
			//Insert the content type on its proper table
			switch ($this->content_type_id) {
				case 1:
					//Image
					if(strlen($this->image_url) > 2) {
						if(! Yii::app()->db->createCommand()
						->insert('content_image', array('url'=>$this->image_url)))
						{
							$this->addError('image',Yii::t('subject', 'We could not save the image url in the database.')); 
							return false;				
						}
						break;
					}
					//If there was an image in the post submitted, then save it in the disk and on its proper content table
					$img_extension = ($this->image->getExtensionName()) ? $this->image->getExtensionName() : '';
					$img_type = CFileHelper::getMimeType($this->image->getTempName());
					$img_size = $this->image->getSize();
					//The path should be changed as time passes so that directory isn't very full(ie:img/1, img/2...) 
					$img_path = Yii::app()->params['img_path'];
					
					//If can't save the image in the db or in the disk, then invalidate
					if(! Yii::app()->db->createCommand()
					->insert('content_image', array('path'=>$img_path,'extension'=>$img_extension,'type'=>$img_type,'size'=>$img_size)))
					{
						$this->addError('image',Yii::t('subject', 'We could not save the image in the database.')); 
						return false;				
					}
					$img_name = Yii::app()->db->getLastInsertID().'.'.$img_extension;
					
					//if(! $this->image->saveAs(Yii::app()->basePath . '/../'.$img_path.'/' . 'large_'.$img_name))
					//{
					//	$this->addError('image','We could not save the image in the disk.'); 
					//	return false;				
					//}
					//WARNING: make sure you have at least ini_set("memory_limit","64M");
					Yii::import('ext.EUploadedImage');
					$this->image = EUploadedImage::getInstance($this,'image');
					$this->image->maxWidth = 980;
					$this->image->maxHeight = 750;
					 
					$this->image->thumb = array(
						'maxWidth' => 300,	//Because we dont know if its viewing in landscape or portrait
						'maxHeight' => 300, //we set bowth to a max of 800 instead of 480(no, lets make both 480)
						//'dir' => Yii::app()->params['dirroot'] . '/'.$img_path,
						'prefix' => 'small_',
					);
					 
					if (! $this->image->saveAs(Yii::app()->params['webdir'].DIRECTORY_SEPARATOR.$img_path.DIRECTORY_SEPARATOR.$img_name)){
						$this->addError('image',Yii::t('subject', 'We could not save the image in the disk.')); 
						return false;
					}
					

					break;
				case 2:
					//Text
					if(! Yii::app()->db->createCommand()->insert('content_text', array('text'=>$this->text)))
					{
						$this->addError('text',Yii::t('subject', 'We could not save the text.')); 
						return false;
					}
					break;
				case 3:
					//Video
					if(! Yii::app()->db->createCommand()->insert('content_video', array('embed_code'=>$this->video)))
					{
						$this->addError('video',Yii::t('subject', 'We could not save the video.')); 
						return false;
					}
					break;
			}
			//Get the insert id as our content id for the subject
			$this->content_id = Yii::app()->db->getLastInsertID();
		}
		
		
		return true;
	}
	/**
	 * Do some things after save
	 * 
	 */
	public function afterSave()
	{
		//Update the tag table if there are new tags
		if($this->tag){
			$tags = Subject::getTags();
			$new_tags = explode(" ",strtolower($this->tag));
			foreach($new_tags as $new_tag) {
				$new_tag = substr($new_tag,0,48);//a word can't be larger than 50 chars,maybe we have no white spaces also
				if (! in_array($new_tag, $tags)) 
					Yii::app()->db->createCommand()->insert('subject_tag',array('name'=>$new_tag));
			}
		}
		//Set position for the time board if not set and if it is authorized only
		if( $this->authorized and (!$this->position) and ($this->user_position or $this->manager_position) ) {
				$position = ($this->manager_position) ? $this->manager_position : $this->user_position;
				$type = ($this->manager_position) ? 'manager' : 'user';
				Subject::set_position($this->id,$position,$type); 
		}
		if( $this->authorized and (!$this->position) and (! $this->user_position and ! $this->manager_position) ) {
			Subject::model()->reschedule_positions();
		}
		//Unset position if it has been unauthorized, disabled, or deleted
		if( ( !$this->authorized or  $this->disabled or  $this->deleted ) and $this->position ) {
			Subject::model()->updateByPk($this->id, array('position'=>'0'));
			Subject::model()->reschedule_positions();
		}

	
	}
	
	/**
	 * Generates a URN(Uniform Resource Name) for the given title to uniquely identify a subject(eg: in seo friendly urls)
	 * @return mixed boolean false if can't generate a urn, otherwise a string with the urn
	 */
	public function generateUrn($title)
    {
		
		//Translate the text to english as all urns should be english
		if ($translated = SiteLibrary::translate($title)) $title = $translated;
			
		//then lets generate a clean title(just numbers and letters, remove everything else)		
		$clean_title = ereg_replace("[^A-Za-z0-9 ]", "", $title);
		$clean_title = strtolower($clean_title);
		$clean_title = preg_replace('/\s+/', ' ', $clean_title);//replace any sequence of whitespace greater than one, with only one
		$clean_title = trim($clean_title);
		//And replace whitespaces with dashes(this gives us the possible urn)
		$possible_urn = str_replace(" ", "-", $clean_title);
		//Verify that the obtained possible_urn its really unique on the database table 
		//if not, generate one adding a sequence number to the possible_urn variable
		//Make 50 attempts to find a unique urn
		for ($i = 1; $i <= 50; $i++) {
			$possible_urn_i = ($i==1) ? $possible_urn : $possible_urn.'_'.$i;
			if (! Subject::model()->find('urn=:urn', array(':urn'=>$possible_urn_i)) ) {
				$urn = $possible_urn_i;//we found it
				return $urn;
			}
		}
		return false;

	}

	/**
	 * Validate that any approbation provided must be before the subject is authorized
	 * 
	 */
	public function validateApprobation($attribute,$params)
    {	//$this object loaded values from db and request
		if( $this->authorized ) $this->addError('approved',Yii::t('subject', 'Sorry, but the subject has already been authorized.'));
	}
	
	/**
	 * Validate that any authorization provided must be after the subject is moderated with an Approved positive value
	 * 
	 */
	public function validateAuthorization($attribute,$params)
    {	//$this object loaded values from db and request
		if(! $this->approved ) $this->addError('authorized',Yii::t('subject', 'Sorry, but the subject needs to be approved first in order to be authorized.'));
	}
	
	
	/**
	 * Validate the date and time set by the user
	 * 
	 */
	public function validateDateTime($attribute,$params)
    {
		if(! $this->user_position_anydatetime) {
			$utc_time  = SiteLibrary::utc_time();
			
			if($this->user_position_ymd and $this->user_position_hour){//This is not a required field for the user
				$this->user_position = strtotime($this->user_position_ymd ." ".$this->user_position_hour.":".(floor(((int)$this->user_position_minute)/Yii::app()->params['subject_interval']) * Yii::app()->params['subject_interval']).":00",$utc_time);

				if($this->user_position){
					if( $this->user_position < SiteLibrary::utc_time_interval()  )
						$this->addError('user_position',Yii::t('subject', 'Time must be greater than current time.'));
				}else{
					$this->addError('user_position',Yii::t('subject', 'Invalid date and times.'));
				}
			}
		}
	}
	/**
	 * Validate the content depending on the type
	 * 
	 */
	public function validateContentType($attribute,$params)
    {
        
		switch ($this->content_type_id) {
			case 1:
				//Image
				$this->image=CUploadedFile::getInstance($this,'image');
				if(get_class($this->image) <> 'CUploadedFile'){
					if(strlen($this->image_url) < 2) {
						$this->addError('content_type_id',Yii::t('subject', 'Please upload OR insert an image url.'));
						break; 
					}
				}else{
					if($this->image->getHasError()){ $this->addError('image',Yii::t('subject', 'Please select an image.'));break; }
					if($this->image->getSize() > (1024 * 1024 * Yii::app()->params['max_image_size'])){  $this->addError('image',Yii::t('subject', 'Please select an image smaller than 7MB.'));break;}//MB
					$types = array("image/jpg", "image/png", "image/gif", "image/jpeg");
					if (! in_array(CFileHelper::getMimeType($this->image->getTempName()), $types)) $this->addError('image', Yii::t('subject', 'File type {filetype} not supported .Please select a valid image type.', array('{filetype}'=>CFileHelper::getMimeType($this->image->getTempName()))));
				}
				break;
			case 2:
				//Text
				//At least 1 char lengh
				if(strlen($this->text) < 2 ) $this->addError('text',Yii::t('subject', 'Please insert text.'));
				break;
			case 3:
				//Video
				//Needs more validation here
				if(strlen($this->video) < 2) $this->addError('video',Yii::t('subject', 'Please insert the video embed code.'));
				break;
			default:
				//The content type its not listed
				$this->addError('content_type_id',Yii::t('subject', 'You are providing an unsopported content type.'));
		}
		
    }
	
	/**
	 * Gets the latest live data about the current and next subject to be live
	 * 
	 * 
	 */
	public function getLiveData($subject_id=0, $comment_id, $width=0, $height=0, $keepratio=true)
    {
		$arr_data = array();
		$arr_comments = array();
		$arr_data['new_comment']=0;
		$arr_data['new_sub']=0;
		//TODO: Store the whole subject record and its content as an array on the live_subject table
		$live_subject = Yii::app()->db->createCommand()
		->select('*')
		->from('live_subject')
		->queryRow();//returns an array, not an object
		
		$arr_data['subject_id'] = $live_subject['subject_id'];
		$arr_data['comment_id'] = $live_subject['comment_id'];
		
		//If the subject cached on client's device its the same that the live_subject table indicates to be cached...
		if($subject_id <> $live_subject['subject_id']){

			$subject_data = Subject::model()->findByPk($live_subject['subject_id']);
			$arr_data['title'] = $subject_data->title;
			$arr_data['content_type_id'] = $subject_data->content_type_id;
			$arr_data['content_type'] = strtolower($subject_data->content_type->name);
			$arr_data['priority'] = strtolower($subject_data->priority_type->name);
			$country = Country::model()->findByPk($subject_data->country_id);
			$arr_data['country_code'] = ($country->code) ? $country->code : 'WW';
			$arr_data['country_name'] = ($country->name) ? $country->name : 'WORLD';
			$user = User::model()->findByPk($subject_data->user_id);
			$arr_data['username'] = $user->username;

			$arr_data['content_html'] = SiteHelper::subject_content($subject_data);
			$arr_data['content_data'] = (array) Subject::subject_content($subject_data)->getAttributes();
			if($arr_data['content_type'] == 'image'){
				$img_name = $arr_data['content_data']['id'].".".$arr_data['content_data']['extension'];
				$url_base = Yii::app()->getRequest()->getBaseUrl(true).'/'.$arr_data['content_data']['path'].'/';				
				if($width or $height){
					$new_img_name = SiteLibrary::get_image_resized($img_name
					,Yii::app()->params['webdir'].DIRECTORY_SEPARATOR.$arr_data['content_data']['path'], $width,$height,$keepratio);
					if($new_img_name)
						$arr_data['content_data']['image_url'] = $url_base.$new_img_name;
					else
						$arr_data['content_data']['image_url'] =  $url_base.$img_name;
				}else{
					$arr_data['content_data']['image_url'] = $url_base.$img_name;
				}				
			}
			$arr_data['user_comment'] = SiteHelper::formatted($subject_data->user_comment);
			$arr_data['time_submitted'] = $subject_data->time_submitted;
			$arr_data['display_time'] = $subject_data->show_time;
			$arr_data['scheduled_time'] = $subject_data->position;
			if($subject_id != $live_subject['subject_id']){
				$arr_data['new_sub']++;
			}
			
			$arr_data['urn'] = $subject_data->urn;
			$arr_data['permalink'] = Yii::app()->getRequest()->getBaseUrl(true)."/sub/".$subject_data->urn;
			//Send the last two previous subjects
			$last_subs = Yii::app()->db->createCommand()
			->select('*')
			->from('subject')
			->where('show_time>:show_time AND id <>:id1',
			array(':show_time'=>0,':id1'=>$live_subject['subject_id']))
			->order('show_time DESC')->limit(5)
			->queryAll();
			
			$arr_data['last_sub_title'] = $last_subs[0]['title'];
			$arr_data['last_sub_2_title'] = $last_subs[1]['title'];
			$arr_data['last_sub_urn'] = $last_subs[0]['urn'];
			$arr_data['last_sub_2_urn'] = $last_subs[1]['urn'];

		}
		
		//Search comments
		if($subject_id != $live_subject['subject_id']) $comment_id = 0;
		$live_comments = Yii::app()->db->createCommand()
		->select('*')
		->from('live_comment')
		->where('comment_id > :comment_id AND subject_id = :subject_id', array(':comment_id'=>$comment_id, ':subject_id'=>$live_subject['subject_id']))
		->order('comment_id ASC')
		->queryAll();
		foreach ($live_comments as $live_comment){
			$arr_data['new_comment']++;
			$arr_data['comment_id'] = $live_comment['comment_id'];
			$arr_comments[] = array('comment_id'=>$live_comment['comment_id'],
			'username'=>$live_comment['username'],
			'comment_text'=> CHtml::encode($live_comment['comment_text']), 'comment_number'=>$live_comment['comment_number'],
			'comment_time'=>date("H:i:s",$live_comment['comment_time']),
			'comment_country'=>$live_comment['comment_country'],
			'likes'=>$live_comment['likes'],
			'dislikes'=>$live_comment['dislikes']
			);
		}
		$arr_data['comments']= $arr_comments;
		
		//Set times
		$utc_time = SiteLibrary::utc_time();
		$arr_data['current_time'] = $utc_time;
		$arr_data['current_time_h'] = date("H",$utc_time);
		$arr_data['current_time_m'] = date("i",$utc_time);
		$arr_data['current_time_s'] = date("s",$utc_time);
		$arr_data['time_remaining'] = (($live_subject['scheduled_time'] + (Yii::app()->params['subject_interval']*60)) - $utc_time) + 2;//lets give some seconds rage in case cron gets delayed
		
		return $arr_data;
	
	}
	/**
	 * Gets tag list of a category or tag
	 * @param string $text to search for in the table
	 * @return Array with the tags
	 */
	public function getTags($text=''){
		$tags = Yii::app()->db->createCommand()
		->select('name')
		->from('subject_tag')
		->where(array('like', 'name', '%'.$text.'%'))
		->order('name DESC')
		->queryAll();
		foreach($tags as $tag){
			if($text){
				if (stripos($tag['name'], $text) === 0) {
					$arr_tags[] = $tag['name'];
				}
			}else{
				$arr_tags[] = $tag['name'];
			}
		}
		return $arr_tags;
		
	
	}
	
	/**
	 * Get the list of subject categories
	 * @return Array with the tags
	 */
	public function getCategories(){
		$categories = Yii::app()->db->createCommand()
		->select('name')
		->from('subject_category')
		->order('name DESC')
		->queryAll();
		foreach($categories as $category) $arr_categories[] = $category['name'];
		return $arr_categories;	
	}

	/**
	 * Gets the prognostic about the subject(s) to be shown
	 * @param integer $id (optional) of the subject to get pronostic. If null, search for all subjects to be shown.
	 * @param string $format (optional) desired to return the time to wait(minutes, date)
	 * @return mixed Array with the wait time and wait position for each subject or the id received
	 */
	public function getPrognostic($id=NULL, $format="minutes")
	{
		$un_shown_subjects =  Yii::app()->db->createCommand()
		->select('*')
		->from('subject')
		->where('show_time=:show_time AND disabled=0',
		array(':show_time'=>0))
		->order('priority_id DESC , time_submitted ASC')
		->queryAll(); //print_r($un_shown_subjects);
		
		$prognostic = array();
		$wait_time = (Yii::app()->params['subject_interval'] * 2);//This is the minimun: 1 for actual subject being shown, and 2  for the cached subject
		$i = 0;
		foreach($un_shown_subjects as $un_shown_subject){
			$i++;
			$wait_time = $wait_time + Yii::app()->params['subject_interval'];//each subject displays for Yii::app()->params['subject_interval'] minutes
			$wait = ($format == "minutes") ? $wait_time : (SiteLibrary::utc_time() + ($wait_time*60));//minutes to seconds
			if($id){
				
				if($id == $un_shown_subject['id'])	return array('time'=>$wait,'position'=>$i);
			}else{
				$prognostic[$un_shown_subject['id']] = array('time'=>$wait,'position'=>$i);
			}
		}
		return $prognostic;
	}
	
	/**
	 * @return object containing the content related to a subject.
	 */
	public function subject_content($subject)
	{
		switch ($subject->content_type_id) {
			case 1:
				$content = $subject->content_image;
				break;
			case 2:
				$content = $subject->content_text;
				break;
			case 3:
				$content = $subject->content_video;
				break;
		}
		return $content;

	}
	/**
	 * Sets the position of a subject in the timeboard schedule
	 * This function automatically reschedules all subs to properly fit in the timeboard after setting the new position
	 * @param integer $id The id of the subject to set position to
	 * @param integer $position The position(in timestamp format) to set to the subject
	 * @param integer $type The position type set by who(user or manager)
	 * @return boolean true on success false on failure 
	 */
	public function set_position($id,$position,$type='manager')
	{
		if($position < SiteLibrary::utc_time()) return false;//Position to be set can't be smaller than the current available timeboard positions
		//if(Subject::model()->find('id=:id AND position=:position',array(':id'=>$id,':position'=>$position))) return false; //Nothing to do, already set
		
		//If there is a fixed sub occupying the requested position, then move it forward
		if( $occupied_pos = Subject::model()->find('(user_position > 0 OR manager_position > 0) AND position = '.$position.' AND id<>'.$id)){
			Subject::move_position_forward($occupied_pos->id,false);
		}
		if ($type == 'manager')
			Subject::model()->updateByPk($id, array('position'=>$position, 'manager_position'=>$position));
		else
			Subject::model()->updateByPk($id, array('position'=>$position, 'user_position'=>$position));
			
		Subject::reschedule_positions();
		return true;

	}	
	/**
	 * Move forward the position of a subject in the timeboard schedule
	 * This function automatically moves forward all subjects ahead of the subject in question if its needed or if they must be moved
	 * @param integer $id The id of the subject to move
	 * @param boolean $reschedule wether to reschedule positions or not(this is to add a little overload protection, ie:if calling this function subsequently we just need to reschedule once)
	 * @return boolean true on success false on failure 
	 */
	public function move_position_forward($id,$reschedule=true)
	{

	
		if(! $move_sub = Subject::model()->findByPk($id)) return false;
			
		//If next position is occupied by a fixed sub(user_position OR manager_position) then move that one, iterate again until a no fixed position is found then loop is finished.
		//Then update the position of the received sub id
		//Then do a positions reschedule

		$timed_subs = Subject::model()->findAll(array('condition'=>'position > '.$move_sub->position, 'order'=>'position ASC'));
		
		$next_pos = $move_sub->position + (Yii::app()->params['subject_interval'] * 60);
		
		foreach($timed_subs as $timed_sub){
			if( ($timed_sub->user_position or $timed_sub->manager_position) and ($timed_sub->position == $next_pos) ){
				
				Subject::model()->updateByPk($timed_sub->id, array('position'=>$next_pos + (Yii::app()->params['subject_interval'] * 60)));
				$next_pos = $next_pos + (Yii::app()->params['subject_interval'] * 60);
				continue;
			
			}else{
				Subject::model()->updateByPk($move_sub->id, array('position'=>( $move_sub->position + (Yii::app()->params['subject_interval'] * 60) )));
				break;//We found a hole ,end the loop
			}
		}
		if($reschedule) Subject::reschedule_positions();
		return true;
		/*
		//Move all subs with user/manager position UNset
		//Note: user/manager UNset are always together and always in the most beginin possible of the current clock time
		Subject::model()->updateAll(array('position'=>new CDbExpression('position + '.(Yii::app()->params['subject_interval'] * 60) )), 'position >= '.$move_sub->position.' AND user_position = 0 AND manager_position = 0');
		*/
		
	
	}
	/**
	 * Reschedule positions in the timeboard schedule
	 * This function does NOT touch fixed positions(user_position OR manager_position) as those positions are untouchable(Only another fixed position can touch them)
	 * @return boolean true on success false on failure 
	 */
	public function reschedule_positions()
	{
	
		//Put position in cero all subs that dont have a fixed position then position them again by priority and arrival order
		//and respect that this new assgnation never overrites Fixed postions(user_position OR manager_position)
		Subject::model()->updateAll(array('position'=>'0'), 'position > '.SiteLibrary::utc_time_interval().' AND user_position = 0 AND manager_position = 0');//We CAN NOT touch current sub(live), thats why position must be greater than current time
		
		$timed_subs = Subject::model()->findAll(array('condition'=>'position = 0 AND user_position = 0 AND manager_position = 0 AND approved=1 AND authorized=1 AND disabled=0 AND deleted=0', 'order'=>'show_time ASC, priority_id DESC , time_submitted ASC','limit'=>24));
		$position = SiteLibrary::utc_time_interval();// + (Yii::app()->params['subject_interval'] * 60);
		foreach($timed_subs as $timed_sub){

			do{
				
				if($occupied_pos = Subject::model()->find('position = '.$position)) {
					$position = $position + (Yii::app()->params['subject_interval'] * 60);
					continue;
				}
				break;
			} while (true);
			Subject::model()->updateByPk($timed_sub->id, array('position'=>( $position )));
		}
		
		
	
	}
	
	/**
	 * Adds one point(either like or dislike) for the current model.
	 * @param int $subject_id of the subject
	 * @param int $vote wether like or dislike
	 * @param int $user_id the user id
	 * @return Array with the subject_id, likes and dislikes count
	 */
	public function add_vote($subject_id, $vote, $user_id)
	{

		$model=Subject::model()->findByPk((int)$subject_id);
		if($model===null){			
			return false;
		}
		$likes = $model->likes;
		$dislikes = $model->dislikes;
		
		$model2=new SubjectVote;
		$model2->subject_id = $subject_id;
		$model2->user_id = $user_id;
		$model2->vote = ($vote == "like") ? 1 : 0;
		$model2->time = SiteLibrary::utc_time();
		if(! $model2->save()) return false;
		
		if ($vote == "like"){
			$model->likes = $model->likes + 1;
			$likes = $model->likes;
		}else{
			$model->dislikes = $model->dislikes + 1;
			$dislikes = $model->dislikes;
		}			
		if(! $model->save()) return false;
		
		//Update Live subjects table if needed(if record doesnt exists, it simply wont update anything)	
		//TODO
		//Yii::app()->db->createCommand()->update('live_subject', array('likes'=>$likes,'dislikes'=>$dislikes)
		//,'subject_id=:subject_id',array(':subject_id'=>$subject_id));
		
		return array('subject_id'=>$subject_id, 'likes'=>$likes, 'dislikes'=>$dislikes);
	}
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		//something like this should work, 
		//but right now doesn't: var_dump( Subject::model()->with('type_content')->findByPk(30)->type_content->name);
		return array(
			'comments' => array(self::HAS_MANY, 'Comment', 'subject_id', 'order'=>'comments.id DESC'),
			'country'=>array(self::BELONGS_TO, 'Country', 'country_id'),
			'user'=>array(self::BELONGS_TO, 'User', 'user_id'),
			'user_country'=>array(self::BELONGS_TO, 'Country', 'user_country_id'),
			'priority_type'=>array(self::BELONGS_TO, 'Priority', 'priority_id'),
			'content_type'=>array(self::BELONGS_TO, 'ContentType', 'content_type_id'),
			'content_image'=>array(self::BELONGS_TO, 'ContentImage', 'content_id'),
			'content_text'=>array(self::BELONGS_TO, 'ContentText', 'content_id'),
			'content_video'=>array(self::BELONGS_TO, 'ContentVideo', 'content_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('site', 'ID'),
			'user_id' => Yii::t('site', 'User'),
			'user_ip' => Yii::t('site', 'User Ip'),
			'user_country_id' => Yii::t('site', 'User Country'),
			'user_comment' => Yii::t('site', 'User Comment'),
			
			'title' => Yii::t('site','Title'),
			'urn' => Yii::t('site','Urn'),
			'content_type_id' => Yii::t('site','Content Type'),
			'approved' => Yii::t('site','Approved'),
			'authorized' => Yii::t('site','Authorized'),
			'content_id' => Yii::t('site','Content'),
			'country_id' => Yii::t('subject','Country of Subject'),
			'moderator_id' => Yii::t('site','Moderator'),
			'moderator_ip' => Yii::t('site','Moderator Ip'),
			'moderator_comment' => Yii::t('site','Moderator Comment'),
			'time_submitted' => Yii::t('site','Time Submitted'),
			'time_moderated' => Yii::t('site','Time Moderated'),
			'priority_id' => Yii::t('site','Priority'),
			'show_time' => Yii::t('site','Show Time'),
			'video'=>Yii::t('subject','Video link or embed code'),
			'tag'=>Yii::t('site','Tags'),
			'category'=>Yii::t('site','Category'),
			'image_url'=>Yii::t('subject','Image link or URL'),
			'datetime'=>Yii::t('subject','Date and time (UTC)'),
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search($default_sort='t.id DESC')
	{//Notice t. = to model table.  http://www.yiiframework.com/doc/guide/1.1/en/database.arr#disambiguating-column-names
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('user_id',$this->user_id);
		//$jaja = User::model()->find('username=:username',array(':username='=>'admin'));
		if($this->username) $user_id = User::model()->find('username=:username',array(':username'=>$this->username));
		if($this->username) $user_id = ($user_id) ? $user_id->id : 0;
		if($this->username) $criteria->compare('user_id', $user_id);
		$criteria->compare('user_ip',$this->user_ip,true);
		$criteria->compare('user_comment',$this->user_comment,true);
		$criteria->compare('title',$this->title,true);
		//3 Things to take note in the following line here:
		//Notice $this->title: we can just have just one field in the griddview. 
		//Notice also OR, thats to not force this condition. 
		//Notice  that 'tag' compare condition its immediately after the former 'title' compare condition. So that the OR its between them and not other fields
		$criteria->compare('tag',$this->title,true,'OR');
		$criteria->compare('urn',$this->urn,true);
		$criteria->compare('content_type_id',$this->content_type_id);
		$criteria->compare('priority_id',$this->priority_id);
		$criteria->compare('approved',$this->approved);
		$criteria->compare('authorized',$this->authorized);
		$criteria->compare('disabled',$this->disabled);
		$criteria->compare('deleted',$this->deleted);
		$criteria->compare('content_id',$this->content_id);
		$criteria->compare('country_id',$this->country_id);
		$criteria->compare('moderator_id',$this->moderator_id);
		$criteria->compare('moderator_ip',$this->moderator_ip,true);
		$criteria->compare('moderator_comment',$this->moderator_comment,true);
		$criteria->compare('time_submitted',$this->time_submitted);
		$criteria->compare('time_moderated',$this->time_moderated);
		//Disabled, Not needed anynmore, as we better use filter in view files
		//$criteria->compare('priority_type.name',$this->priority_id, true);//Notice the relational name and not the table name, also notice that this has the user input value
		$criteria->compare('show_time',$this->show_time);
		
		$criteria->compare('category',$this->category, true);
		$criteria->compare('position',$this->position);

		$criteria->with=array('user','country','priority_type','content_type');//Disabled, Not needed anynmore, as we better use filter in view files

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
			'sort'=>array('defaultOrder'=>$default_sort),
		));
	}
}