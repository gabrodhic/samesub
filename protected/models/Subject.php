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
 * @property integer $content_state_id
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
	public $text;
	public $video;
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
			array('title, content_type_id', 'required', 'on'=>'add'),
			array('content_state_id', 'required', 'on'=>'moderate'),
			array('content_type_id', 'numerical', 'integerOnly'=>true, 'on'=>'add'),
			array('content_state_id', 'numerical', 'integerOnly'=>true, 'on'=>'moderate'),			
			array('title', 'length', 'max'=>240, 'on'=>'add'),
			array('user_comment', 'type', 'type'=>'string', 'on'=>'add'),
			array('moderator_comment', 'length', 'max'=>240, 'on'=>'moderate'),
			array('priority_id, country_id, language_id', 'numerical', 'integerOnly'=>true),
			array('content_type_id', 'validateContentType', 'on'=>'add'),
			array('image', 'safe', 'on'=>'add'),//So that it can be massively assigned, either way its gonna be validated by validateContentType
			array('text', 'safe', 'on'=>'add'),//So that it can be massively assigned, either way its gonna be validated by validateContentType
			array('video', 'safe', 'on'=>'add'),//So that it can be massively assigned, either way its gonna be validated by validateContentType
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_id, user_ip, user_comment, title, urn, content_type_id, content_state_id, content_id, country_id, moderator_id, moderator_ip, moderator_comment, time_submitted, time_moderated, priority_id, show_time', 'safe', 'on'=>'search'),
		);
	}
	
	/**
	 * Validate the content depending on the type
	 * 
	 */
	public function validateContentType($attribute,$params)
    {
        
		switch ($this->content_type_id) {
			case 1:
				$image=CUploadedFile::getInstance($this,'image');
				if(get_class($image) <> 'CUploadedFile'){ $this->addError('image','No file received'); break; }
				if($image->getHasError()){ $this->addError('image','Please select an image.');break; }
				if($image->getSize() > (1024 * 1024 * 4)){  $this->addError('image','Please select an image smaller than 4MB.');break;}//4MB
				$types = array("image/jpg", "image/png", "image/gif", "image/jpeg");
				if (! in_array(CFileHelper::getMimeType($image->getName()), $types)) $this->addError('image','File type '.CFileHelper::getMimeType($image->getName()).' not supported .Please select a valid image type.');//4MB
				break;
			case 2:
				//At least 1 char lengh
				if(strlen($this->text) < 2 ) $this->addError('text',$this->text.'Please insert text.');
				break;
			case 3:
				//Needs more validation here
				if(strlen($this->video) < 2) $this->addError('video','Please insert the video embed code.');
				break;
			default:
				//The content type its not listed
				$this->addError('content_type_id','You are providing an unsopported content type.');
		}
		
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
			'user_id' => 'User',
			'user_ip' => 'User Ip',
			'user_comment' => 'User Comment',
			'title' => 'Title',
			'urn' => 'Urn',
			'content_type_id' => 'Content Type',
			'content_state_id' => 'Content State',
			'content_id' => 'Content',
			'country_id' => 'Country',
			'moderator_id' => 'Moderator',
			'moderator_ip' => 'Moderator Ip',
			'moderator_comment' => 'Moderator Comment',
			'time_submitted' => 'Time Submitted',
			'time_moderated' => 'Time Moderated',
			'priority_id' => 'Priority',
			'show_time' => 'Show Time',
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
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('user_ip',$this->user_ip,true);
		$criteria->compare('user_comment',$this->user_comment,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('urn',$this->urn,true);
		$criteria->compare('content_type_id',$this->content_type_id);
		$criteria->compare('content_state_id',$this->content_state_id);
		$criteria->compare('content_id',$this->content_id);
		$criteria->compare('country_id',$this->country_id);
		$criteria->compare('moderator_id',$this->moderator_id);
		$criteria->compare('moderator_ip',$this->moderator_ip,true);
		$criteria->compare('moderator_comment',$this->moderator_comment,true);
		$criteria->compare('time_submitted',$this->time_submitted);
		$criteria->compare('time_moderated',$this->time_moderated);
		$criteria->compare('priority_id',$this->priority_id);
		$criteria->compare('show_time',$this->show_time);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}