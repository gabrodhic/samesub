<?php

/**
 * This is the model class for table "log".
 *
 * The followings are the available columns in table 'log':
 * @property integer $id
 * @property integer $time
 * @property string $session_id
 * @property integer $user_id
 * @property string $controller
 * @property string $action
 * @property string $uri
 * @property string $model
 * @property integer $model_id
 * @property string $theme
 */
class Log extends CActiveRecord
{
	public $username;
	public $device;
	public $country;
	public $charset;
	public $language;
	public $referer;
	public $agent;
	/**
	 * Returns the static model of the specified AR class.
	 * @return Log the static model class
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
		return 'log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('time, controller', 'required'),
			array('user_id, model_id', 'numerical', 'integerOnly'=>true),
			array('session_id', 'length', 'max'=>40),
			array('controller, action, username, device', 'length', 'max'=>30),
			array('uri', 'length', 'max'=>255),
			array('model', 'length', 'max'=>20),
			array('theme', 'length', 'max'=>2),
			array('time', 'length', 'max'=>30),
			array('country', 'length', 'max'=>30),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, time, session_id, user_id, controller, action, uri, model, model_id, theme', 'safe', 'on'=>'search'),
		);
	}

	public function afterValidate()
    {
		//If the time param was submitted in a non unix timestamp format, then format it that way
		//$this->time = strtotime($this->time);
		//Placing the code as above causes that on the response back to the client view 
		//the input gets the converted value, so we'll just convert it on the DB query
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
			'time' => 'Time',
			'session_id' => 'Session',
			'user_id' => 'User',
			'controller' => 'Controller',
			'action' => 'Action',
			'uri' => 'Uri',
			'model' => 'Model',
			'model_id' => 'Model',
			'theme' => 'Theme',
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
		$pattern = "/(<|>|<=|>=|<>|=)(.+)/i";
		$time_cond = preg_replace($pattern, "\$1", $this->time);//preg_replace() returns the same string if nothing is found
		if($time_cond == $this->time) $time_cond = "";
		$time_val = preg_replace($pattern, "\$2", $this->time);

		$criteria->compare('time',$time_cond.strtotime($time_val));
		$criteria->compare('session_id',$this->session_id,true);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('controller',$this->controller,true);
		$criteria->compare('action',$this->action,true);
		$criteria->compare('uri',$this->uri,true);
		$criteria->compare('model',$this->model,true);
		$criteria->compare('model_id',$this->model_id);
		$criteria->compare('theme',$this->theme,true);
		
		$criteria->compare('username',$this->username);
		$criteria->compare('device',$this->device);
		$criteria->compare('c.id',$this->country);
		
		$criteria->join = 'LEFT JOIN user as u ON u.id = t.user_id 
		LEFT JOIN log_detail as ld ON ld.session = t.session_id
		LEFT JOIN country as c ON c.id = ld.client_country_id OR c.id = ld.request_country_id
		';
		$criteria->select = 't.id, t.time, t.session_id, u.username as username, t.user_id, t.controller, t.action, t.theme,
		ld.device, c.name as country, t.uri, ld.language, ld.charset, ld.referer, ld.agent';
		

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'sort'=>array('defaultOrder'=>'t.id DESC'),
		));
	}
}