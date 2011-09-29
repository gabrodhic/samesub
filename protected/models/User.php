<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $email
 * @property string $ip_created
 * @property string $ip_last_access
 * @property integer $user_status_id
 * @property integer $user_type_id
 * @property integer $time_created
 * @property integer $time_last_access
 * @property integer $time_modified
 */
class User extends CActiveRecord
{
	public $oldpassword;
	public $newpassword;
	public $newpassword2;
	public $country;
	public $status;
	public $type;
	public $country_created;
	public $subs;
	/**
	 * Returns the static model of the specified AR class.
	 * @return User the static model class
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
		return 'user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('username, password, email', 'required', 'on'=>'register'),
			array('oldpassword, newpassword, newpassword2', 'required', 'on'=>'changepassword'),
			array('newpassword', 'compare', 'compareAttribute'=>'newpassword2', 'on'=>'changepassword'),
			array('username, email', 'validateOne', 'on'=>'resetpassword'),
			array('newpassword, newpassword2', 'required', 'on'=>'resetpasswordnext'),
			array('newpassword', 'compare', 'compareAttribute'=>'newpassword2', 'on'=>'resetpasswordnext'),
			array('username, email', 'unique', 'on'=>'register'),
			array('email', 'email'),
			array('country_id, notify_subject', 'numerical'),
			array('username, password, email', 'length', 'max'=>50, 'min'=>3),			
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, username, password, email, ip_created, ip_last_access, user_status_id, user_type_id, time_created, time_last_access, time_modified, country, type, status, subs', 'safe', 'on'=>'search'),
		);
	}
	/**
	 * Validate either one field is valid
	 */
	public function validateOne($attribute,$params)
    {
		if (($this->username==null)and($this->email==null)){
			$this->addError('username,email','Please submit at least one data');
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
			'username' => 'Username',
			'password' => 'Password',
			'oldpassword' => 'Old Password',
			'newpassword' => 'New Password',
			'newpassword2' => 'Retype New Password',
			'email' => 'Email',
			'ip_created' => 'Ip Created',
			'ip_last_access' => 'Ip Last Access',
			'user_status_id' => 'User State',
			'user_type_id' => 'User Type',
			'time_created' => 'Time Created',
			'time_last_access' => 'Time Last Access',
			'time_modified' => 'Time Modified',
			'country_id' => 'Country',
			'notify_subject'=>'Notify me when going LIVE',
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
		$criteria->compare('username',$this->username,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('ip_created',$this->ip_created,true);
		$criteria->compare('ip_last_access',$this->ip_last_access,true);
		$criteria->compare('user_status_id',$this->status);
		$criteria->compare('user_type_id',$this->type);
		$criteria->compare('time_created',$this->time_created);
		$criteria->compare('time_last_access',$this->time_last_access);
		$criteria->compare('time_modified',$this->time_modified);
		
		$criteria->compare('t.country_id',$this->country_id);
		
		$criteria->join = 'LEFT JOIN country as c ON c.id = t.country_id
		LEFT JOIN user_status as us ON us.id = t.user_status_id
		LEFT JOIN user_type as ut ON ut.id = t.user_type_id
		LEFT JOIN subject as su ON su.user_id = t.id';
		//$criteria->distinct = true;
		$criteria->group = 't.id';
		$criteria->select = 'COUNT(su.id) as subs, t.id, t.username, c.name as country, t.country_id as country_id, t.time_last_access, us.name as status, ut.name as type';

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
	/**
	 * Do some things prior to save
	 * 
	 */
	public function beforeSave()
    {
		//If its a new record
		if($this->getIsNewRecord()){
			$this->salt = $this->generateSalt();
			$this->password = $this->hashPassword($this->password, $this->salt);
		}
		if($this->scenario != 'login') $this->time_modified = SiteLibrary::utc_time();//login also saves data
		return true;
	}

	/**
	 * Checks if the given password is correct.
	 * @param string the password to be validated
	 * @return boolean whether the password is valid
	 */
	public function validatePassword($password)
	{
		return $this->hashPassword($password,$this->salt)===$this->password;
	}

	/**
	 * Generates the password hash.
	 * @param string password
	 * @param string salt
	 * @return string hash
	 */
	public function hashPassword($password,$salt)
	{
		return md5($salt.$password);
	}

	/**
	 * Generates a salt that can be used to generate a password hash.
	 * @return string the salt
	 */
	public function generateSalt()
	{
		return uniqid('',true);
	}
}