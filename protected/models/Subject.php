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
			array('image', 'safe', 'on'=>'add,update'),//So that it can be massively assigned, either way its gonna be validated by validateContentType
			array('image_url', 'url', 'on'=>'add,update'),
			array('text', 'safe', 'on'=>'add,update'),//So that it can be massively assigned, either way its gonna be validated by validateContentType
			array('video', 'safe', 'on'=>'add,update'),//So that it can be massively assigned, either way its gonna be validated by validateContentType
			array('content_type_id', 'validateContentType', 'on'=>'add'),

			array('disabled', 'numerical', 'integerOnly'=>true, 'on'=>'moderate,authorize'),
			
			array('approved', 'numerical', 'integerOnly'=>true, 'on'=>'moderate'),
			array('moderator_comment', 'length', 'max'=>240, 'on'=>'moderate'),
			array('approved', 'validateApprobation', 'on'=>'moderate'),
			
			array('authorized', 'numerical', 'integerOnly'=>true, 'on'=>'authorize'),
			array('authorized', 'validateAuthorization', 'on'=>'authorize'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_id, user_ip, user_comment, title, urn, content_type_id, approved, authorized, disabled, content_id, country_id, moderator_id, moderator_ip, moderator_comment, time_submitted, time_moderated, priority_id, show_time', 'safe', 'on'=>'manage'),
			array('title, urn, content_type_id, country_id, time_submitted, priority_id, show_time', 'safe', 'on'=>'history'),
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
			if( Yii::app()->db->createCommand("SELECT * FROM live_subject WHERE subject_id_1 = {$this->id} OR subject_id_2 = {$this->id}")->queryRow())
			{
				$this->addError('title','Right now this subject is either in the comming up queue or in the live-now stream. You can not modify it.');
				return false;
			}
			//Generate the urn for this subject
			if(! $this->urn = $this->generateUrn($this->title)){
				$this->addError('title','Please change something in the title.'); return false;
			}
			//TODO:Update the table belonging to each content type(image,text,video,etc)
			//also validatecontenttype
		}
		else{
			//Generate the urn for this subject
			if(! $this->urn = $this->generateUrn($this->title)){
				$this->addError('title','Please change something in the title.'); return false;
			}
			
			//Insert the content type on its proper table
			switch ($this->content_type_id) {
				case 1:
					//Image
					if(strlen($this->image_url) > 2) {
						if(! Yii::app()->db->createCommand()
						->insert('content_image', array('url'=>$this->image_url)))
						{
							$this->addError('image','We could not save the image url in the database.'); 
							return false;				
						}
						break;
					}
					//If there was an image in the post submitted, then save it in the disk and on its proper content table
					$img_extension = ($this->image->getExtensionName()) ? $this->image->getExtensionName() : '';
					$img_type = CFileHelper::getMimeType($this->image->getName());
					$img_size = $this->image->getSize();
					//The path should be changed as time passes so that directory isn't very full(ie:img/1, img/2...) 
					$img_path = Yii::app()->params['img_path'];
					
					//If can't save the image in the db or in the disk, then invalidate
					if(! Yii::app()->db->createCommand()
					->insert('content_image', array('path'=>$img_path,'extension'=>$img_extension,'type'=>$img_type,'size'=>$img_size)))
					{
						$this->addError('image','We could not save the image in the database.'); 
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
						'maxWidth' => 480,	//Because we dont know if its viewing in landscape or portrait
						'maxHeight' => 480, //we set bowth to a max of 800 instead of 480(no, lets make both 480)
						//'dir' => Yii::app()->params['dirroot'] . '/'.$img_path,
						'prefix' => 'small_',
					);
					 
					if (! $this->image->saveAs(Yii::app()->params['webdir'].DIRECTORY_SEPARATOR.$img_path.DIRECTORY_SEPARATOR.$img_name)){
						$this->addError('image','We could not save the image in the disk.'); 
						return false;
					}
					

					break;
				case 2:
					//Text
					if(! Yii::app()->db->createCommand()->insert('content_text', array('text'=>$this->text)))
					{
						$this->addError('text','We could not save the text.'); 
						return false;
					}
					break;
				case 3:
					//Video
					if(! Yii::app()->db->createCommand()->insert('content_video', array('embed_code'=>$this->video)))
					{
						$this->addError('video','We could not save the video.'); 
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
		
		
		
	
	}
	
	/**
	 * Generates a URN(Uniform Resource Name) for the given title to uniquely identify a subject(eg: in seo friendly urls)
	 * @return mixed boolean false if can't generate a urn, otherwise a string with the urn
	 */
	public function generateUrn($title)
    {
	 
		//First, lets generate a clean title(just numbers and letters, remove everything else)		
		$clean_title = ereg_replace("[^A-Za-z0-9 ]", "", $title);
		//And replace whitespaces with underscores(this gives us the possible urn)
		$possible_urn = str_replace(" ", "_", $clean_title);
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
		if( $this->authorized ) $this->addError('approved','Sorry, but the subject has already been authorized.');
	}
	
	/**
	 * Validate that any authorization provided must be after the subject is moderated with an Approved positive value
	 * 
	 */
	public function validateAuthorization($attribute,$params)
    {	//$this object loaded values from db and request
		if(! $this->approved ) $this->addError('authorized','Sorry, but the subject needs to be approved first in order to be authorized.');
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
						$this->addError('content_type_id','Please upload OR insert an image url.');
						break; 
					}
				}else{
					if($this->image->getHasError()){ $this->addError('image','Please select an image.');break; }
					if($this->image->getSize() > (1024 * 1024 * Yii::app()->params['max_image_size'])){  $this->addError('image','Please select an image smaller than 7MB.');break;}//MB
					$types = array("image/jpg", "image/png", "image/gif", "image/jpeg");
					if (! in_array(CFileHelper::getMimeType($this->image->getName()), $types)) $this->addError('image','File type '.CFileHelper::getMimeType($this->image->getName()).' not supported .Please select a valid image type.');
				}
				break;
			case 2:
				//Text
				//At least 1 char lengh
				if(strlen($this->text) < 2 ) $this->addError('text',$this->text.'Please insert text.');
				break;
			case 3:
				//Video
				//Needs more validation here
				if(strlen($this->video) < 2) $this->addError('video','Please insert the video embed code.');
				break;
			default:
				//The content type its not listed
				$this->addError('content_type_id','You are providing an unsopported content type.');
		}
		
    }
	
	/**
	 * Gets the latest live data about the current and next subject to be live
	 * 
	 * 
	 */
	public function getLiveData($subject_id_2=0, $comment_number,$sleep=false)
    {
		$arr_data = array();
		$arr_comments = array();
		$arr_comments_2 = array();
		$arr_data['new_comment']=0;
		$arr_data['new_sub']=0;
		//TODO: Store the whole subject record and its content as an array on the live_subject table
		$live_subject = Yii::app()->db->createCommand()
		->select('*')
		->from('live_subject')
		->queryRow();//returns an array, not an object

		
		$live_comments = Yii::app()->db->createCommand()
		->select('*')
		->from('live_comment')
		->where('comment_sequence > :comment_number', array(':comment_number'=>$comment_number))
		->order('comment_number ASC')
		->queryAll();
		
		foreach ($live_comments as $live_comment){
			$arr_data['new_comment']++;
			$arr_comments[] = array('display_time'=>($live_comment['comment_time']+Yii::app()->params['request_interval']),'comment_text'=> CHtml::encode($live_comment['comment_text']), 'comment_sequence'=>$live_comment['comment_sequence'],'comment_number'=>$live_comment['comment_number'],'comment_time'=>$live_comment['comment_time'],'comment_country'=>$live_comment['comment_country']);
		}
		$arr_data['comments']= $arr_comments;
		
		//If the subject cached on client's device its the same that the live_subject table indicates to be cached...
		if($subject_id_2 == $live_subject['subject_id_2']){
			
			//Then verify if there is a change in comments number, its enough to respond with the comment, dont have to wait for a change in subject
			if($comment_number <> $live_subject['last_comment_number']){
				$arr_data['comment_update'] = 'yes';
				$arr_data['id_1'] = 'somevalue';
				$arr_data['id_2'] = 'somevalue';
				$arr_data['ttt'] = $comment_number."...". $live_subject['last_comment_number'];
				
				//die();
			}
			
			if($sleep) {sleep(1);}
			//return false;
		}else{
			if($subject_id_2 != $live_subject['subject_id_1']){
				$subject_data = Subject::model()->findByPk($live_subject['subject_id_1']);
				$arr_data['id_1'] = $subject_data->id;
				$arr_data['urn_1'] = $subject_data->urn;
				$arr_data['title_1'] = $subject_data->title;
				$arr_data['content_type_id_1'] = $subject_data->content_type_id;
				$arr_data['time_submitted_1'] = $subject_data->time_submitted;
				$country = Country::model()->findByPk($subject_data->country_id);
				$arr_data['country_code_1'] = ($country->code) ? $country->code : 'WW';
				$arr_data['country_name_1'] = ($country->name) ? $country->name : 'WORLD';

				$arr_data['content_html_1'] = SiteHelper::subject_content($subject_data);
				$arr_data['content_data_1'] = (array) Subject::subject_content($subject_data)->getAttributes();
				$arr_data['user_comment_1'] = SiteHelper::formatted($subject_data->user_comment);
				$arr_data['new_sub']++;
			}
			
			$arr_data['comment_update'] = 'no';
			$arr_data['comment_sequence'] = $live_subject['comment_sequence'];
			$arr_data['new_sub']++;
			
			
			
			$subject_data = Subject::model()->findByPk($live_subject['subject_id_2']);
			
			//Add it to the cached data Array also, client needs it
			$arr_data['id_2']= $subject_data->id;
			$arr_data['urn_2']= $subject_data->urn;
			$arr_data['title_2']= $subject_data->title;			
			$arr_data['content_type_id_2']= $subject_data->content_type_id;
			$arr_data['time_submitted_2'] = $subject_data->time_submitted;
			$country = Country::model()->findByPk($subject_data->country_id);
			$arr_data['country_code_2'] = ($country->code) ? $country->code : 'WW';
			$arr_data['country_name_2'] = ($country->name) ? $country->name : 'WORLD';
			
			$arr_data['content_html_2'] = SiteHelper::subject_content($subject_data);
			$arr_data['content_data_2'] = (array) Subject::subject_content($subject_data)->getAttributes();
			$arr_data['user_comment_2'] = SiteHelper::formatted($subject_data->user_comment);
			
			$arr_data['display_time_2'] = ($subject_data->show_time + (Yii::app()->params['subject_interval']*60));
			
			
			//TEMPORAL TODO:lets add the old comments(if any) for the cached subeject
			$comments_2 = Yii::app()->db->createCommand()->select('code,time,comment,sequence')->from('comment t1')->where('subject_id ='.$live_subject['subject_id_2'])
			->leftJoin('country t2', 'country_id=t2.id')->order('time ASC')->queryAll();
			$i = 0;
			foreach($comments_2 as $comment_2){
				$i++;//we need to use a counter for the sequence as one same sequence mith be repeated if the sub was repeated(TEMPORAL)
				$country_code = ($comment_2['code']) ? $comment_2['code'] : "WW";
				
				$arr_comments_2[] = array('display_time'=>($comment_2['time']+Yii::app()->params['request_interval']),
				'comment_text'=> CHtml::encode($comment_2['comment']), 'comment_sequence'=>$i,
				'comment_time'=>$comment_2['time'],'comment_country'=>$country_code);
			}
			
			/*
			$comments_2 = Yii::app()->db->createCommand()
			->select('*')
			->from('comment')
			->where('subject_id = :subject_id', array(':subject_id'=>$live_subject['subject_id_2']))
			->order('sequence ASC')
			->queryAll();
			Yii::app()->db->createCommand()->select('code,time,comment,sequence')->from('comment t1')->where('subject_id ='.$live_subject['subject_id_2'])
			->leftJoin('country t2', 'country_id=t2.id')->order('time ASC')->queryAll();
			
			foreach ($comments_2 as $comment_2){
				//$arr_data['new_comment']++;
				$arr_comments_2[] = array('display_time'=>($comment_2['time']+Yii::app()->params['request_interval']),'comment_text'=> CHtml::encode($comment_2['comment']), 'comment_sequence'=>$live_comment['sequence'],'comment_time'=>$live_comment['time'],'comment_country'=>$live_comment['comment_country']);
			}
			*/
			$arr_data['comments_2']= $arr_comments_2;
			
			
		}
		$utc_time = SiteLibrary::utc_time();
		$arr_data['current_time'] = $utc_time;
		$arr_data['current_time_h'] = date("H",$utc_time);
		$arr_data['current_time_m'] = date("i",$utc_time);
		$arr_data['current_time_s'] = date("s",$utc_time);
		return json_encode($arr_data);
	
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
			'id' => 'ID',
			'user_id' => 'User',
			'user_ip' => 'User Ip',
			'user_country_id' => 'User Country',
			'user_comment' => 'User Comment',
			'title' => 'Title',
			'urn' => 'Urn',
			'content_type_id' => 'Content Type',
			'approved' => 'Approved',
			'authorized' => 'Authorized',
			'content_id' => 'Content',
			'country_id' => 'Country of the Subject',
			'moderator_id' => 'Moderator',
			'moderator_ip' => 'Moderator Ip',
			'moderator_comment' => 'Moderator Comment',
			'time_submitted' => 'Time Submitted',
			'time_moderated' => 'Time Moderated',
			'priority_id' => 'Priority',
			'show_time' => 'Show Time',
			'video'=>'Video embed code',
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
		$criteria->compare('user_ip',$this->user_ip,true);
		$criteria->compare('user_comment',$this->user_comment,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('urn',$this->urn,true);
		$criteria->compare('content_type_id',$this->content_type_id, true);
		$criteria->compare('priority_id',$this->priority_id, true);
		$criteria->compare('approved',$this->approved);
		$criteria->compare('authorized',$this->authorized);
		$criteria->compare('disabled',$this->disabled);
		$criteria->compare('content_id',$this->content_id);
		$criteria->compare('country_id',$this->country_id, true);
		$criteria->compare('moderator_id',$this->moderator_id);
		$criteria->compare('moderator_ip',$this->moderator_ip,true);
		$criteria->compare('moderator_comment',$this->moderator_comment,true);
		$criteria->compare('time_submitted',$this->time_submitted);
		$criteria->compare('time_moderated',$this->time_moderated);
		//Disabled, Not needed anynmore, as we better use filter in view files
		//$criteria->compare('priority_type.name',$this->priority_id, true);//Notice the relational name and not the table name, also notice that this has the user input value
		$criteria->compare('show_time',$this->show_time);
		
		//$criteria->with=array('country','priority_type','content_type');//Disabled, Not needed anynmore, as we better use filter in view files

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
			'sort'=>array('defaultOrder'=>$default_sort),
		));
	}
}