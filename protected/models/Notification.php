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
			array('fixed, message', 'required'),
			array('enabled, fixed', 'numerical', 'integerOnly'=>true),
			array('message', 'length', 'max'=>250),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, enabled, fixed, message', 'safe', 'on'=>'search'),
		);
	}
	/**
	 * @return string A random or a fixed notification (depends on what is set on the database)
	 */
	public function getNotification()
	{
		//First we must return a notification that is set to fixed
		$information = new stdClass;
		if($note = Notification::model()->find('fixed=:fixed', array(':fixed'=>1))) $information->note = $note->message;		 
		//If there is no fixed notification then return the live subject if it has a high priority
		$live_subject = Yii::app()->db->createCommand()->select('*')->from('live_subject')->queryRow();
		$subject_data =  Yii::app()->db->createCommand()->select('*')->from('subject')
			->where('id=:id', 
			array(':id'=>$live_subject['subject_id_1']))
			->queryRow();
		if($subject_data) $information->live = $subject_data['title'];
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
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'enabled' => 'Enabled',
			'fixed' => 'Fixed',
			'message' => 'Message',
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