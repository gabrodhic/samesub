<?php
/**
 * ECountryCodes.php
 * @requires egeonameservice
 *
 * A simple component to generate dropDownLists with country info.
 * Uses data from http://www.geonames.org
 * @see http://www.geonames.org/export/web-services.html#countryInfo
 *
 * Support for countryname, countrycode/isoalpha3, currency, capital ... in different languages
 * @see property supportedAttributes
 *
 *
 * Usage:
 * Install extension egeonameservice.
 *
 * $codes = new ECountryCodes;
 * $codes->language = 'de';
 * $codes->topKeys = array('AT','DE');
 * $codes->value = array('countryCode','currencyCode','countryName','capital');
 * echo $codes->dropDownList('codes1','DE');
 *
 * $codes->resetKeys();
 * $codes->key = 'currencyCode';
 * $codes->value = array('currencyCode');
 * echo $codes->dropDownList('codes2');
 *
 *
 *
 * @author Joe Blocher <yii@myticket.at>
 * @copyright 2011 myticket it-solutions gmbh
 * @license New BSD License
 * @category UI
 * @version 2.0
 */
class ECountryCodes extends CComponent
{

	/**
	 * The filepath of the info file
	 *
	 * @var string $filePath
	 */
	public $filePath; //default is set to applications runtime path

	/**
	 * Set to true to update the info file
	 * @var boolean $update
	 */
	public $update = false; //set to true to get the latest data

	/**
	 *
	 * The language for geonames countryinfo request
	 * @var string language
	 */
	public $language;

	/**
	 * The separator for the values when displaying multiple properties
	 * @var string $valueSeparator
	 */
	public $valueSeparator = ' | ';


	/**
	 * The separator for the keys when displaying multiple properties
	 * @var string $keySeparator
	 */
	public $keySeparator = '_';

	/**
	 * Set the specified keys at the top of the dropDownList
	 * @var array $topKeys
	 */
	public $topKeys = array();

	/**
	 * Exclude the specified keys in the dropDownList
	 * @var array $excludeKeys
	 */
	public $excludeKeys = array();

	/**
	 * Add only the specified keys
	 * @var array $selectKeys
	 */
	public $selectKeys = array();

	/**
	 *
	 * Username to access the API
	 * @see http://www.geonames.org/export/web-services.html
	 * @var string $_geoUsername
	 */
	private $_geoUsername;

	/**
	 * The object EGeoNameService
	 * @var geo
	 */
	private $_geo;

	/**
	 * The key attribute(s) of the dropDownList
	 * Default: countryCode
	 * @var string $_key
	 */
	private $_key;

	/**
	 * The value attribute(s) of the dropDownList
	 * Default: countryName
	 * @var mixed $_value string or array
	 */
	private $_value;


	private $_supportedAttributes = array(
	    'countryName',
	    'bBoxWest',
	    'currencyCode',
	    'fipsCode',
	    'countryCode',
	    'isoNumeric',
	    'capital',
	    'areaInSqKm',
	    'languages',
	    'bBoxEast',
	    'isoAlpha3',
	    'continent',
	    'bBoxNorth',
	    'geonameId',
	    'bBoxSouth',
	    'population',
	);

	/**
	 * Create the component
	 *
	 * @param array $config
	 */
	public function __construct($config = array())
	{
		$this->getGeo(); //create EGeoNameService

		if (!empty($config))
			foreach($config as $key=>$value)
				$this->$key=$value;

		if (empty($this->filePath))
			$this->filePath = Yii::app()->getRuntimePath();

		if (!isset($this->_geoUsername))
		  $this->geoUsername = 'demo';
	}

	/**
	 * Reset the keys properties
	 *
	 * @return
	 */
	public function resetKeys()
	{
	  $this->topKeys = array();
	  $this->excludeKeys = array();
	  $this->selectKeys = array();
	  $this->_key = null;
	  $this->_value = null;
	}

	/**
	 *
	 * Property set username
	 * @param string $username sets the username to use with the webservice
	 */
	public function setGeoUsername($username)
	{
		$this->_geoUsername = $username;
		if (!empty($this->geo))
			$this->geo->username = $username;
	}

	/**
	 *
	 * Property get geoUsername
	 */
	public function getGeoUsername()
	{
		return $this->_geoUsername;
	}

	/**
	 * Check if extension egeonames is installed
	 *
	 * @return boolean
	 */
	public function getGeo()
	{
	  if (!isset($this->_geo))
      {
		Yii::import('ext.egeonames.*');
      	$this->_geo = new EGeoNameService();
      };

	  return $this->_geo;
	}

	/**
	 * Set the key property
	 *
	 * @param string $key
	 */
	public function setKey($key)
	{
		if (is_string($key))
			$key = array($key);

		//check if all value properties are supported
		if (count(array_diff($key,$this->_supportedAttributes)))
	     throw new CException('Unsupported key properties');

	   $this->_key = $key;
	}

	/**
	 * Get the key property
	 */
	public function getKey()
	{
		return $this->_key;
	}

	/**
	 * Set the value property
	 *
	 * @param string $key
	 */
	public function setValue($value)
	{
		if (is_string($value))
			$value = array($value);

		//check if all value properties are supported
		if (count(array_diff($value,$this->_supportedAttributes)))
			throw new CException('Unsupported value properties');

		$this->_value = $value;
	}

	/**
	 * Get the value property
	 */
	public function getValue()
	{
		return $this->_value;
	}

	/**
	 * Absolute path of the country info file
	 *
	 * @return string
	 */
	protected function getCountryInfoFile()
	{
		$language = strtolower($this->language);
		return $this->filePath . DIRECTORY_SEPARATOR . "countryinfo_$language.dat";
	}

	/**
	 * Download the country info file
	 *
	 * @return string
	 */
	public function downloadCountryInfoData()
	{
		if (!isset($this->geo))
			return false;

		$config = array();
		$language = $this->language;

		if (!empty($language))
			$config['lang']=$language;

		$result=$this->geo->countryInfo($config);
		$dataArray = $result['geonames'];

		$file = $this->getCountryInfoFile();
		file_put_contents($file, serialize($dataArray));
		if (!is_file($file))
			throw new CException('Error on saving country code data.');

		return $dataArray;
	}

	/**
	 * Load (or download) the country info data
	 *
	 * @return array
	 */
	public function getInfoData()
	{
		$result = array();
		$file = $this->getCountryInfoFile();

		if (!is_file($file) || $this->update)
			$data = $this->downloadCountryInfoData();
		else
			$data = unserialize(file_get_contents($file));

		return $data;
	}


	/**
	 * Get the codes as array
	 *
	 * @return array
	 */
	public function getCodesArray()
	{
		if (empty($this->_key))
		  $this->key = 'countryCode';

		if (empty($this->_value))
			$this->value = 'countryName';

		$result = array();

		$data = $this->getInfoData();

		if (!empty($data))
			foreach ($data as $item)
			{
				$keys = array();
				foreach($this->_key as $key)
					$keys[] = $item[$key];

				$itemKey = implode($this->keySeparator,$keys);

				if ( empty($itemKey) ||
					in_array($itemKey,$this->excludeKeys) ||
					(!empty($this->selectKeys) && !in_array($itemKey,$this->selectKeys)))
					continue;

				$values = array();
				foreach($this->_value as $value)
				{
					if ($value=='population')
						$values[] = Yii::app()->numberFormatter->formatDecimal($item[$value]);
					else
						$values[] = $item[$value];
				}

				$itemValue = implode($this->valueSeparator,$values);

				$result[$itemKey] =$itemValue;
			}

		return $result;
	}

	/**
	 * Move the topKeys to the top of the array
	 *
	 * @param array $data
	 */
	protected function setTopKeys(&$data)
	{
		if (!empty($this->topKeys))
		{
			$sorted = array();
			foreach ($this->topKeys as $key)
			{
				if (array_key_exists($key,$data))
				{
					$sorted[$key] = $data[$key];
					unset($data[$key]);
				}
			}

			$data = array_merge($sorted,$data);
		}
	}

	/**
	 * Generates a drop down list
	 *
	 * @param string $name the input name
	 * @param string $select the selected value
	 * @param array $htmlOptions see CHtml::dropDownList
	 * @return string
	 */
	public function dropDownList($name,$select='',$htmlOptions=array())
	{
		$data = $this->getCodesArray();
		$this->setTopKeys($data);
		return CHtml::dropDownList($name,$select,$data,$htmlOptions);
	}

	/**
	 * Generates a active drop down list
	 *
	 * @param string $model the data model
	 * @param string $attribute the attribute
	 * @param array $htmlOptions see CHtml::activeDropDownList
	 * @return string
	 */
	public function activeDropDownList($model,$attribute,$htmlOptions=array())
	{
		$data = $this->getCodesArray();
		$this->setTopKeys($data);
		return CHtml::activeDropDownList($model,$attribute,$data,$htmlOptions);
	}

}