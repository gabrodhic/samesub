<?php

/**
 * This is the model class for table "subject_vote".
 *
 * The followings are the available columns in table 'subject_vote':
 * @property integer $id
 * @property integer $subject_id
 * @property integer $user_id
 * @property integer $vote
 * @property integer $time
 */
class SubjectVote extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return SubjectVote the static model class
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
		return 'subject_vote';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('subject_id, vote', 'required'),
			array('subject_id, user_id, vote, time', 'numerical', 'integerOnly'=>true),
			//Unique validation(subject_id,user_id,vote)A user can vote twice(different votes, giving an total equivalent as unchaged)
			//http://www.yiiframework.com/extension/unique-attributes-validator/
			array('subject_id', 'unique', 'caseSensitive'=>false, 'criteria'=>array('condition'=>'user_id = :user_id AND vote = :vote', 'params' => array(':user_id' => $this->user_id,':vote' => $this->vote))),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, subject_id, user_id, vote, time', 'safe', 'on'=>'search'),
		);
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
			'subject_id' => 'Subject',
			'user_id' => 'User',
			'vote' => 'Vote',
			'time' => 'Time',
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
		$criteria->compare('subject_id',$this->subject_id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('vote',$this->vote);
		$criteria->compare('time',$this->time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}