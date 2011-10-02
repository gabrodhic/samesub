<?php

/**
 * This is the model class for table "comment".
 *
 * The followings are the available columns in table 'comment':
 * @property string $id
 * @property integer $user_id
 * @property integer $subject_id
 * @property integer $time
 * @property string $comment
 */
class Comment extends CActiveRecord
{
	public $update_live = false;
	/**
	 * Returns the static model of the specified AR class.
	 * @return Comment the static model class
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
		return 'comment';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('comment', 'required'),
			array('comment', 'length', 'min'=>2, 'max'=>65500),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_id, subject_id, time, comment', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * 
	 */
	public function beforeSave()
	{
		// Assign the user_id 1 if is a guest
///TODO:add userid. Issue, cant make use of user component while other request is open(subject/fetch)		$this->user_id=(Yii::app()->user->getId()) ? Yii::app()->user->getId() : 1;
		$this->user_id = 0;
		
		//$this->time = SiteLibrary::utc_time();
		$this->user_ip = $_SERVER['REMOTE_ADDR'];
		$country_id = 0;
		$country_code = 'WW';
		if($_SERVER['REMOTE_ADDR'] != '127.0.0.1'){
			Yii::import('ext.EGeoIP');
			$geoIp = new EGeoIP();
			$geoIp->locate($_SERVER['REMOTE_ADDR']);
			//http://www.iso.org/iso/english_country_names_and_code_elements
			$country=Country::model()->find('code=:code', array(':code'=>$geoIp->countryCode));
			if($country) {$country_id = $country->id; $country_code = $country->code;}
		}
		$this->country_id = $country_id;
		
		$this->time = SiteLibrary::utc_time();
		
		if($this->update_live){
			$live_subject = Yii::app()->db->createCommand()->select('subject_id_1, (comment_sequence+1)as next_sequence')->from('live_subject')->queryRow();
			//print_r($live_subject);return;
			Yii::app()->db->createCommand()->insert('live_comment', array(
			'comment_sequence'=>$live_subject['next_sequence'],
			'subject_id'=>$live_subject['subject_id_1'],
			'comment_text'=>$this->comment,
			'comment_time'=>$this->time,
			'comment_country'=>$country_code,
			));
			Yii::app()->db->createCommand()->update('live_subject', array(
			'last_comment_number'=>Yii::app()->db->getLastInsertID(),
			'comment_sequence'=>$live_subject['next_sequence'],
			));
		
			$this->sequence = $live_subject['next_sequence'];
			$this->subject_id = $live_subject['subject_id_1'];
		}
	
		return true;
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
			'user_ip' => 'User ip',
			'country_id' => 'Country',
			'subject_id' => 'Subject',
			'time' => 'Time',
			'comment' => 'Comment',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('user_ip',$this->user_ip);
		$criteria->compare('country_id',$this->country_id);
		$criteria->compare('subject_id',$this->subject_id);
		$criteria->compare('time',$this->time);
		$criteria->compare('comment',$this->comment,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}