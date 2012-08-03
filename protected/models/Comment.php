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
		$country_id = 1;
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
		$this->user_id = Yii::app()->user->id;
		
		
	
		return true;
	}
	
	/**
	 * Saves a comments in database and do subsequent related operations.
	 */
	public function save_comment($model)
	{
		$live_subject = Yii::app()->db->createCommand()->select('subject_id, (comment_number+1)as next_sequence')->from('live_subject')->queryRow();
		$model->comment_number = $live_subject['next_sequence'];
		$model->subject_id = $live_subject['subject_id'];
		
		if($model->save()){
			if($model->update_live){
				
				Yii::app()->db->createCommand()->insert('live_comment', array(
				'comment_id'=>$model->id,
				'comment_number'=>$model->comment_number,
				'subject_id'=>$model->subject_id,
				'comment_text'=>$model->comment,
				'comment_time'=>$model->time,
				'comment_country'=>$model->country->code,
				'username'=>(Yii::app()->user->isGuest)?'guest':Yii::app()->user->name,
				));
				Yii::app()->db->createCommand()->update('live_subject', array(
				'comment_id'=>$model->id,
				'comment_number'=>$model->comment_number,
				));
			}

			$send_mail = true;
			if(! Yii::app()->user->isGuest){
				$user = User::model()->findByPk(Yii::app()->user->id); 
				if($user->user_type_id > 2) $send_mail=false;//Dont notify managers themself
				
			}
			$last_one = Comment::model()->find(array('limit'=>2, 'offset'=>1, 'order'=>'t.id DESC'));//offset is 0 based
			if( SiteLibrary::utc_time() < ($last_one->time + 1500)) $send_mail = false;
			if($send_mail){				
				$mail_message .= "User: ".Yii::app()->user->name."\n";
				$mail_message .= "Comment: {$model->comment}\n";
				$mail_message .= "Current time: ".date("Y/m/d H:i", SiteLibrary::utc_time())." UTC (time of this message)\n\n";
				$mail_message .= "www.samesub.com";				
				SiteLibrary::send_email(Yii::app()->params['contactEmail'],"Comment ".$model->id,$mail_message);
			}
			return true;
		}else{
			return false;
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
			'user'=>array(self::BELONGS_TO, 'User', 'user_id'),
			'country'=>array(self::BELONGS_TO, 'Country', 'country_id'),
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
			'user_ip' => Yii::t('site', 'User ip'),
			'country_id' => Yii::t('site', 'Country'),
			'subject_id' => Yii::t('site', 'Subject'),
			'time' => Yii::t('site', 'Time'),
			'comment' => Yii::t('site', 'Comment'),
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