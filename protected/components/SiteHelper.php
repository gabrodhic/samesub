<?php
/**
 * SiteHelper is a helper class for the site.
 * It contatins methods that provide easier and reusable code to print custom elements in views.
 */
class SiteHelper extends CHtml
{
	
	/**
	 * Generates the proper html depending on the content_type_id
	 * @param object $subject the an instance of the Subject class
	 * @return string the html content
	 */
	public function subject_content($subject)
	{
		switch ($subject->content_type_id) {
			case 1:
				$img_url = ($subject->content_image->url) ? $subject->content_image->url : Yii::app()->params['weburl'].'/'.$subject->content_image->path.'/'.$subject->content_image->id.'.'.$subject->content_image->extension;
				$html = '<img src="'.$img_url.'" class="content_image">';//Yii::app()->getRequest()->getHostInfo().
				if($subject->content_image->url){
					$parsed_url = parse_url($subject->content_image->url);
					$html .= "<br><span>Source: ". $parsed_url['scheme'].'://'.$parsed_url['host'];
				}
				break;
			case 2:
				$html = SiteHelper::formatted($subject->content_text->text);
				break;
			case 3:
				//would be nice to use yii validator to see if its an url but: http://code.google.com/p/yii/issues/detail?id=1324
				if(SiteLibrary::valid_url($subject->content_video->embed_code)){//if its an url
					$parsed_url = parse_url($subject->content_video->embed_code);
					if(stripos($parsed_url['host'], 'youtube.com')){//and its from youtube
						//get the V value $parsed_url['query'];
						$query_arr = SiteLibrary::parse_url_query($parsed_url['query']);
						if (array_key_exists('v', $query_arr)) {
							$html = '<iframe width="425" height="349" src="http://www.youtube.com/embed/'.$query_arr['v'].'" frameborder="0" allowfullscreen></iframe>';
						}
					}
				}else{
					$html = SiteHelper::formatted($subject->content_video->embed_code);
				}
				break;
		}
		return $html;
	}
	
	/**
	 * Formats a string with all its html properties
	 * @param strin $value the value
	 * @return string the correspondent html string
	 */
	public function formatted($value)
	{
		return nl2br($value);
	}
	
	/**
	 * Generates a yes or no depending on the boolean value
	 * @param boolean $value the value
	 * @return string the correspondent text
	 */
	public function yesno($value)
	{
		return ($value) ? 'Yes' : 'No';
	}
	
	/**
	 * Generates a on or off depending on the boolean value
	 * @param boolean $value the value
	 * @return string the correspondent text
	 */
	public function onoff($value)
	{
		return ($value) ? 'On' : 'Off';
	}
	
	/**
	 * Generates a set of buttons to share content to external sites
	 * @param string $urn the urn to generate a url to send to external sites
	 * @param string $title the title to send to external sites
	 */
	public function share_links($urn,$title)
	{
		$share_html = '<br><a title="'.$title.'" href="'.Yii::app()->params['weburl'].'/sub/'.$urn.'">Share</a>:<br><a target="_blank" href="http://api.addthis.com/oexchange/0.8/forward/facebook/offer?url='. Yii::app()->params['weburl'].'/sub/'.$urn.'&title='.$title.'&pubid='.Yii::app()->params['addthis_pubid'].'" rel="nofollow"><img src="http://cache.addthiscdn.com/icons/v1/thumbs/facebook.gif"></a>';
		$share_html .= '&nbsp;<a target="_blank" href="http://api.addthis.com/oexchange/0.8/forward/twitter/offer?url='. Yii::app()->params['weburl'].'/sub/'.$urn.'&title='.$title.'&pubid='.Yii::app()->params['addthis_pubid'].'" rel="nofollow"><img src="http://cache.addthiscdn.com/icons/v1/thumbs/twitter.gif"></a>';
		$share_html .= '&nbsp;<a target="_blank" href="http://api.addthis.com/oexchange/0.8/forward/email/offer?url='. Yii::app()->params['weburl'].'/sub/'.$urn.'&title='.$title.'&pubid='.Yii::app()->params['addthis_pubid'].'" rel="nofollow"><img src="http://cache.addthiscdn.com/icons/v1/thumbs/email.gif"></a>';
		$share_html .= '&nbsp;<a target="_blank" href="http://api.addthis.com/oexchange/0.8/forward/google/offer?url='. Yii::app()->params['weburl'].'/sub/'.$urn.'&title='.$title.'&pubid='.Yii::app()->params['addthis_pubid'].'" rel="nofollow"><img src="http://cache.addthiscdn.com/icons/v1/thumbs/google.gif"></a>';
		$share_html .= '&nbsp;<a target="_blank" href="http://api.addthis.com/oexchange/0.8/offer?url='. Yii::app()->params['weburl'].'/sub/'.$urn.'&title='.$title.'&pubid='.Yii::app()->params['addthis_pubid'].'" rel="nofollow"><img src="http://cache.addthiscdn.com/icons/v1/thumbs/menu.gif"></a>';
		 
		return $share_html;
	}
	
}