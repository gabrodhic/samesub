<?php

/**
 * This is the model class for table "notification".
 *
 * The followings are the available columns in table 'notification':
 * @property integer $id
 * @property integer $enabled
 * @property integer $fixed
 * @property string $message
 */
class Notification extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Notification the static model class
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
		return 'notification';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('fixed, message, notification_type_id', 'required'),
			array('enabled, fixed,notification_type_id', 'numerical', 'integerOnly'=>true),
			array('message', 'length', 'max'=>250),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, enabled, fixed, message', 'safe', 'on'=>'search'),
		);
	}
	/**
	 * @param integer $type the type of notification requested, usually related to the space whe its gonna 
	 * be placed. eg: 1 = welcome (first visit), 2 = headline (in the  top of the pages)
	 * @return string A random or a fixed notification (depends on what is set on the database)
	 */
	public function getNotification($type=1)
	{
		//First we must return a notification that is set to fixed
		$information = new stdClass;
		if($note = Notification::model()->find('fixed=:fixed AND notification_type_id=:notification_type_id',
			array(':fixed'=>1,'notification_type_id'=>$type))) 
			$information->note = $note->message;		 
		if($type == 1){//we may also send other type of info depending of the type of notification requested
			//If there is no fixed notification then return the live subject if it has a high priority
			$live_subject = Yii::app()->db->createCommand()->select('*')->from('live_subject')->queryRow();
			$subject_data =  Yii::app()->db->createCommand()->select('*')->from('subject')
				->where('id=:id', 
				array(':id'=>$live_subject['subject_id']))
				->queryRow();
			if($subject_data) $information->live = $subject_data['title'];
		}
		return $information;
		//If nothing above is found then return a random notification
		//$notifications = Yii::app()->db->createCommand()->select('id')->from(Notification::tableName())->queryAll();
		//$rand_num = mt_rand(0,(count($notifications)-1));
		//return Notification::model()->findByPk($notifications[$rand_num]['id'])->message;
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
	 * Do some things before save
	 * 
	 */
	public function beforeSave()
    {
		
		if($this->fixed == 1){
			Notification::model()->updateAll(array('fixed'=>0),'notification_type_id=:notification_type_id',array(':notification_type_id'=>$this->notification_type_id));
		}
		return true;
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('site', 'ID'),
			'enabled' => Yii::t('site', 'Enabled'),
			'fixed' => Yii::t('site', 'Fixed'),
			'notification_type_id' => Yii::t('site', 'Notification Type'),
			'message' => Yii::t('site', 'Message'),
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
		$criteria->compare('enabled',$this->enabled);
		$criteria->compare('fixed',$this->fixed);
		$criteria->compare('message',$this->message,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}