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
				if ($subject->content_image->url){ 
					$img_url = $subject->content_image->url;
				}else{
					$img_url = Yii::app()->getRequest()->getBaseUrl(true).'/'.$subject->content_image->path.'/';
					$filename = $subject->content_image->id.'.'.$subject->content_image->extension;
					$img_url .=  (Yii::app()->getTheme()->name=='mobile') ? "small_".$filename : $filename;
				}
				$html = '<img src="'.$img_url.'" class="content_image">';//Yii::app()->getRequest()->getHostInfo().
				if($subject->content_image->url){
					$parsed_url = parse_url($subject->content_image->url);
					$html .= "<br><span>Image source: ". $parsed_url['scheme'].'://'.$parsed_url['host'];
				}
				break;
			case 2:
				$html = SiteHelper::formatted($subject->content_text->text);
				break;
			case 3:
				//would be nice to use yii validator to see if its an url but: http://code.google.com/p/yii/issues/detail?id=1324
				if(SiteLibrary::valid_url($subject->content_video->embed_code)){//if its an url
					$parsed_url = parse_url($subject->content_video->embed_code);
					$query_arr = SiteLibrary::parse_url_query($parsed_url['query']);
					if(stripos($parsed_url['host'], 'youtube.com')){//and its from youtube
						//get the V value $parsed_url['query'];						
						if (array_key_exists('v', $query_arr)) {
							$html = '<iframe width="425" height="349" src="http://www.youtube.com/embed/'.$query_arr['v'].'" frameborder="0" allowfullscreen></iframe>';
						}
					}elseif(stripos($parsed_url['host'], 'dailymotion.com')){
						//the code is before the first undersore for the video source(pending verify if thats the syntax for all cases)
						if($last_code_pos = stripos($parsed_url['path'], '_')){
							$video_code = substr($parsed_url['path'],1, $last_code_pos-1);
							if($video_code)	$html = '<iframe frameborder="0" width="480" height="360" src="http://www.dailymotion.com/embed/'.$video_code.'"></iframe>';
						}
					}elseif(stripos($parsed_url['host'], 'vimeo.com')){
						if($parsed_url['path'])
						//nice, we can play with params here, title, portrait, etc
						$html = '<iframe src="http://player.vimeo.com/video'.$parsed_url['path'].'?title=0&byline=0&portrait=0" width="400" height="225" frameborder="0"></iframe>';
					}
				}else{
					$html = SiteHelper::formatted($subject->content_video->embed_code);
				}
				
				//intercept the content html and resize any width or height property for a mobile version			
				if(Yii::app()->getTheme()->name=='mobile'){
					$pattern = '/(width=")(\d+)(")/i';//Replace: width="***"  ---> width="MOBILE_WIDTH"
					$replacement = '${1}250$3';//reference numbers come from string enclosed in parenthesis(left to right) in the pattern
					$html = preg_replace($pattern, $replacement, $html);
					$pattern = '/(height=")(\d+)(")/i';
					$replacement = '${1}190$3';
					$html = preg_replace($pattern, $replacement, $html);
					//TODO: http://css-tricks.com/7066-fluid-width-youtube-videos/
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
		$share_html = '<br><a id="urn_link" title="'.$title.'" href="'.Yii::app()->params['weburl'].'/sub/'.$urn.'">Share</a>:<br><a target="_blank" href="http://api.addthis.com/oexchange/0.8/forward/facebook/offer?url='. Yii::app()->params['weburl'].'/sub/'.$urn.'&title='.$title.'&pubid='.Yii::app()->params['addthis_pubid'].'" rel="nofollow"><img src="http://cache.addthiscdn.com/icons/v1/thumbs/facebook.gif"></a>';
		$share_html .= '&nbsp;<a target="_blank" href="http://api.addthis.com/oexchange/0.8/forward/twitter/offer?url='. Yii::app()->params['weburl'].'/sub/'.$urn.'&title='.$title.'&pubid='.Yii::app()->params['addthis_pubid'].'" rel="nofollow"><img src="http://cache.addthiscdn.com/icons/v1/thumbs/twitter.gif"></a>';
		$share_html .= '&nbsp;<a target="_blank" href="http://api.addthis.com/oexchange/0.8/forward/email/offer?url='. Yii::app()->params['weburl'].'/sub/'.$urn.'&title='.$title.'&pubid='.Yii::app()->params['addthis_pubid'].'" rel="nofollow"><img src="http://cache.addthiscdn.com/icons/v1/thumbs/email.gif"></a>';
		$share_html .= '&nbsp;<a target="_blank" href="http://api.addthis.com/oexchange/0.8/forward/google/offer?url='. Yii::app()->params['weburl'].'/sub/'.$urn.'&title='.$title.'&pubid='.Yii::app()->params['addthis_pubid'].'" rel="nofollow"><img src="http://cache.addthiscdn.com/icons/v1/thumbs/google.gif"></a>';
		$share_html .= '&nbsp;<a target="_blank" href="http://api.addthis.com/oexchange/0.8/offer?url='. Yii::app()->params['weburl'].'/sub/'.$urn.'&title='.$title.'&pubid='.Yii::app()->params['addthis_pubid'].'" rel="nofollow"><img src="http://cache.addthiscdn.com/icons/v1/thumbs/menu.gif"></a>';
		 
		return $share_html;
	}
	
	/**
	 * Generates tags from a string
	 * @param string $text the text to generate the tags from
	 * @return array with the tags
	 */
	public function make_tags($text)
	{
		//First, lets generate a clean text(just letters, remove everything else)		
		$text = ereg_replace("[^A-Za-z ]", " ", $text);//we cen't replace with empty as it would concatenate some strings (ie: hi:im me => hiim me)so we use whitespace
		$text = preg_replace('/\s+/', ' ', $text);//now replace any sequence of whitespace greater than one, with only one
		$text = trim($text);//we need to trim also as there can be white spaces at the end (ie: how are you? => 'how are you ')
		//$text = str_replace(" ", " *", $text);
		//And split to an array by whitespaces
		$arr_text =  explode(" ", $text);
		$arr_text = array_unique($arr_text);//remove any duplicate tag
		function to_link(&$value, $key){
			$value = '<a href="'.Yii::app()->params['weburl'].'/subject/index?'.urlencode('Subject[urn]').'='.$value.'&ajax=subject-grid">&#32;&#149;&#32;'.$value.'</a>'; 
		} 
		
		array_walk($arr_text, 'to_link');
		return $arr_text;
	}
	
	/**
	 * Generates the html for the content on the client side
	 * @param string $subject_content the subject specific html
	 * @param string $comment (optional) the user comment
	 * @param string $share (optional) the sharing links
	 * @return string with the html
	 */
	public function content_html($subject_content,$comment='',$share='')
	{
		$html = $subject_content."<br>".$comment."<br>".$share;
		return $html;
	
	}
	
}