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
		$utc_time = mktime(gmdate("H"), gmdate("i"), gmdate("s"), gmdate("m"), gmdate("d"), gmdate("Y"));
		//this also works $gmtimenow = time() - (int)substr(date('O'),0,3)*60*60; 
		return $utc_time;
	}
	
}