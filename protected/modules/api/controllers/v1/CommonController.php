<?php

class CommonController extends ApiController
{
	
	//Notice that default filters are defined in ApiController class

	
	
	/**
	 * Get the list of countries
	 */
	public function actionGetcountries()
	{
		global $arr_response;
				
		$countries = Country::model()->findAll();
		
		foreach($countries as $country){
			$i++;
			$arr_countries[$i]['code'] = $country['code'];
			$arr_countries[$i]['name'] = $country['name'];
		}
		
		
		$arr_response = $arr_countries;
		
	}
	
	/**
	 * Get the current time and time remaining
	 */
	public function actionGettime()
	{
		global $arr_response;
		
		$live_subject = Yii::app()->db->createCommand()
		->select('*')
		->from('live_subject')
		->queryRow();//returns an array, not an object
		
		
		$utc_time = SiteLibrary::utc_time();
		$arr_data['current_time'] = $utc_time;
		$arr_data['current_time_h'] = date("H",$utc_time);
		$arr_data['current_time_m'] = date("i",$utc_time);
		$arr_data['current_time_s'] = date("s",$utc_time);
		$arr_data['time_remaining'] = (($live_subject['scheduled_time'] + (Yii::app()->params['subject_interval']*60)) - $utc_time) + 2;//lets give some seconds rage in case cron gets delayed
		
		$arr_response = $arr_data;
	
	}


}
