<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	private $_id;
	
	/**
	 * Authenticates a user.
	 * We use username and email interchangeably so that we dont have to modify CUserIdentity Class to implemente email authentication
	 * http://www.larryullman.com/2010/01/07/custom-authentication-using-the-yii-framework/
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()
	{
		//First try to find by email, if not found by username
		if(! $user=User::model()->find('email=?',array(strtolower($this->username))))
			$user=User::model()->find('username=?',array(strtolower($this->username)));
		if($user===null)
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		else if(!$user->validatePassword($this->password))
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
		else
		{
			$this->_id=$user->id;
			$this->username=$user->username;
			$this->errorCode=self::ERROR_NONE;
		}
		return $this->errorCode==self::ERROR_NONE;
	}

	/**
	 * @return integer the ID of the user record
	 */
	public function getId()
	{
		return $this->_id;
	}	
}