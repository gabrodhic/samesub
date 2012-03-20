<?php

/**
 * This is the model class for table "oauth_server_registry".
 *
 * The followings are the available columns in table 'oauth_server_registry':
 * @property integer $osr_id
 * @property integer $osr_usa_id_ref
 * @property string $osr_consumer_key
 * @property string $osr_consumer_secret
 * @property integer $osr_enabled
 * @property string $osr_status
 * @property string $osr_requester_name
 * @property string $osr_requester_email
 * @property string $osr_callback_uri
 * @property string $osr_application_uri
 * @property string $osr_application_title
 * @property string $osr_application_descr
 * @property string $osr_application_notes
 * @property string $osr_application_type
 * @property integer $osr_application_commercial
 * @property string $osr_issue_date
 * @property string $osr_timestamp
 */
class OauthServerRegistry extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return OauthServerRegistry the static model class
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
		return 'oauth_server_registry';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('osr_application_title, osr_application_descr', 'required'),
			array('osr_application_notes, osr_application_uri, osr_callback_uri', 'type', 'type'=>'string',),
			array('osr_enabled', 'numerical', 'integerOnly'=>true),		
			array('osr_callback_uri, osr_application_uri', 'length', 'max'=>255),
			array('osr_application_title', 'length', 'max'=>80),

			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('osr_id, osr_usa_id_ref, osr_consumer_key, osr_consumer_secret, osr_enabled, osr_status, osr_requester_name, osr_requester_email, osr_callback_uri, osr_application_uri, osr_application_title, osr_application_descr, osr_application_notes, osr_application_type, osr_application_commercial, osr_issue_date, osr_timestamp', 'safe', 'on'=>'search'),
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
			'osr_id' => 'Osr',
			'osr_usa_id_ref' => 'Osr Usa Id Ref',
			'osr_consumer_key' => 'Consumer Key',
			'osr_consumer_secret' => 'Consumer Secret',
			'osr_enabled' => 'Enabled',
			'osr_status' => 'Status',
			'osr_requester_name' => 'Osr Requester Name',
			'osr_requester_email' => 'Osr Requester Email',
			'osr_callback_uri' => 'Callback Uri',
			'osr_application_uri' => 'Application Uri',
			'osr_application_title' => 'Application Title',
			'osr_application_descr' => 'Application Descr',
			'osr_application_notes' => 'Application Notes',
			'osr_application_type' => 'Osr Application Type',
			'osr_application_commercial' => 'Osr Application Commercial',
			'osr_issue_date' => 'Osr Issue Date',
			'osr_timestamp' => 'Osr Timestamp',
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

		$criteria->compare('osr_id',$this->osr_id);
		$criteria->compare('osr_usa_id_ref',$this->osr_usa_id_ref);
		$criteria->compare('osr_consumer_key',$this->osr_consumer_key,true);
		$criteria->compare('osr_consumer_secret',$this->osr_consumer_secret,true);
		$criteria->compare('osr_enabled',$this->osr_enabled);
		$criteria->compare('osr_status',$this->osr_status,true);
		$criteria->compare('osr_requester_name',$this->osr_requester_name,true);
		$criteria->compare('osr_requester_email',$this->osr_requester_email,true);
		$criteria->compare('osr_callback_uri',$this->osr_callback_uri,true);
		$criteria->compare('osr_application_uri',$this->osr_application_uri,true);
		$criteria->compare('osr_application_title',$this->osr_application_title,true);
		$criteria->compare('osr_application_descr',$this->osr_application_descr,true);
		$criteria->compare('osr_application_notes',$this->osr_application_notes,true);
		$criteria->compare('osr_application_type',$this->osr_application_type,true);
		$criteria->compare('osr_application_commercial',$this->osr_application_commercial);
		$criteria->compare('osr_issue_date',$this->osr_issue_date,true);
		$criteria->compare('osr_timestamp',$this->osr_timestamp,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}