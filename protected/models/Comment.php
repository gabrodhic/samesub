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
			array('comment', 'length', 'min'=>2, 'max'=>250),
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
		
		$this->time = time();
		$this->user_ip = $_SERVER['REMOTE_ADDR'];
	
		$live_subject = Yii::app()->db->createCommand()->select('subject_id_1, (comment_sequence+1)as next_sequence')->from('live_subject')->queryRow();
		//print_r($live_subject);return;
		Yii::app()->db->createCommand()->insert('live_comment', array(
		'comment_sequence'=>$live_subject['next_sequence'],
		'comment_text'=>$this->comment,
		));
		Yii::app()->db->createCommand()->update('live_subject', array(
		'last_comment_number'=>Yii::app()->db->getLastInsertID(),
		'comment_sequence'=>$live_subject['next_sequence'],
		));
		
		$this->sequence = $live_subject['next_sequence'];
		$this->subject_id = $live_subject['subject_id_1'];
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
		$criteria->compare('subject_id',$this->subject_id);
		$criteria->compare('time',$this->time);
		$criteria->compare('comment',$this->comment,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}