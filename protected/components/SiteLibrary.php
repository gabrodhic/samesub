<?php
/**
 * SiteLibrary is a set of common functions useful for needed functionalities accross the site
 * 
 */
class SiteLibrary extends CComponent
{
	
	/**
	 * Generates the unix timestamp for the actual UTC/GMT time
	 * @return integer the unix timestamp
	 */
	public function utc_time()
	{
		//$utc_time = mktime(gmdate("H"), gmdate("i"), gmdate("s"), gmdate("m"), gmdate("d"), gmdate("Y"));
		$utc_time = time() - (int)substr(date('O'),0,3)*60*60; //this is faster
		return $utc_time;
	}
	
	public function parse_url_query($query){
		$var  = explode('&', $query); 
		$arr  = array(); 

		foreach($var as $val) 
		{ 
			$x          = explode('=', $val); 
			$arr[$x[0]] = $x[1]; 
		} 
		unset($val, $x, $var); 
		return $arr; 
	}
	
	function valid_url($url)
	{
		return preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url);
	}
	
}