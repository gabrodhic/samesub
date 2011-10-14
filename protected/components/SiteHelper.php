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
	 * @param string $mode in wich to return information(html or array of data)
	 * @return mixed string the html content OR array with content values
	 */
	public function subject_content($subject,$mode='html')
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
				$arr_content['image'] = $img_url;
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
							if($video_code)	$html = '<iframe frameborder="0" width="480" height="360" src="http://www.dailymotion.com/embed/'.$video_code.'"></iframe>'; //$video_code already contains: /videdo/
						}
					}elseif(stripos($parsed_url['host'], 'vimeo.com')){
						if($parsed_url['path'])
						//nice, we can play with params here, title, portrait, etc
						$html = '<iframe src="http://player.vimeo.com/video'.$parsed_url['path'].'?title=0&byline=0&portrait=0" width="400" height="225" frameborder="0"></iframe>';
					}
				}else{
					$html = SiteHelper::formatted($subject->content_video->embed_code);
				}
				//Get the iframe source url and set the image url for ogtags				
				//src="...."    second match (((?!").)*) searches for any string NOT containing " as that is our delimitter used in third match
				$pattern = '/(src=")(((?!").)*)(")/i'; 
				preg_match($pattern, $html, $matches);//position 0 is the full match
				
				$arr_content['url'] = $matches[2];
				if(SiteLibrary::valid_url($arr_content['url'])){//if its an url
					$parsed_url = parse_url($arr_content['url']);
					$query_arr = SiteLibrary::parse_url_query($parsed_url['query']);
					if(stripos($parsed_url['host'], 'youtube.com')){
						$pattern =  strpos($arr_content['url'], '?') ? '/(embed\/)(.+)(\?)/i' : '/(embed\/)(.+)$/i'; //the url can have extra params (a ? mark to pass extra params to the video)
						preg_match($pattern, $arr_content['url'], $matches);//position 0 is the full match
						$arr_content['code'] = $matches[2];
						$arr_content['image'] = 'http://img.youtube.com/vi/'.$arr_content['code'].'/default.jpg';
					}
						//TODO: get schema for images in other providers
					
				}
				
				
				//intercept the content html and resize any width or height depending on current theme
				if(Yii::app()->getTheme()->name=='mobile'){
					$max_width = 250;
					$max_height = 190;
				}else{
					$max_width = 550;
					$max_height = 440;
				}
				$pattern = '/(width=")(\d+)(")/i';//Replace: width="***"  ---> width="MOBILE_WIDTH"
				preg_match($pattern, $html, $matches);//width will fall in thrid position as position 0 is the full match(see func definition)				
				if((int)$matches[2] > $max_width) $html = preg_replace($pattern, '${1}'.$max_width.'$3', $html); 				
				
				$pattern = '/(height=")(\d+)(")/i';
				preg_match($pattern, $html, $matches);//width will fall in thrid position as position 0 is the full match(see func definition)
				if((int)$matches[2] > $max_height) $html = preg_replace($pattern, '${1}'.$max_height.'$3', $html); 				
				
				
				$replacement = '${1}190$3';
				
				//TODO: http://css-tricks.com/7066-fluid-width-youtube-videos/
				
				break;
		}
		return ($mode == 'array') ?  $arr_content : $html;
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
	 * @param string $path the relative path
	 */
	public function share_links($urn,$title,$path='/sub/')
	{
		$share_html = '<a id="urn_link" title="'.$title.'" href="'.Yii::app()->params['weburl'].$path.$urn.'">Share</a>:<br><a target="_blank" href="http://api.addthis.com/oexchange/0.8/forward/facebook/offer?url='. Yii::app()->params['weburl'].$path.$urn.'&title='.$title.'&pubid='.Yii::app()->params['addthis_pubid'].'" rel="nofollow"><img src="http://cache.addthiscdn.com/icons/v1/thumbs/facebook.gif"></a>';
		$share_html .= '&nbsp;<a target="_blank" href="http://api.addthis.com/oexchange/0.8/forward/twitter/offer?url='. Yii::app()->params['weburl'].$path.$urn.'&title='.$title.'&pubid='.Yii::app()->params['addthis_pubid'].'" rel="nofollow"><img src="http://cache.addthiscdn.com/icons/v1/thumbs/twitter.gif"></a>';
		$share_html .= '&nbsp;<a target="_blank" href="http://api.addthis.com/oexchange/0.8/forward/email/offer?url='. Yii::app()->params['weburl'].$path.$urn.'&title='.$title.'&pubid='.Yii::app()->params['addthis_pubid'].'" rel="nofollow"><img src="http://cache.addthiscdn.com/icons/v1/thumbs/email.gif"></a>';
		$share_html .= '&nbsp;<a target="_blank" href="http://api.addthis.com/oexchange/0.8/forward/google/offer?url='. Yii::app()->params['weburl'].$path.$urn.'&title='.$title.'&pubid='.Yii::app()->params['addthis_pubid'].'" rel="nofollow"><img src="http://cache.addthiscdn.com/icons/v1/thumbs/google.gif"></a>';
		$share_html .= '&nbsp;<a target="_blank" href="http://api.addthis.com/oexchange/0.8/offer?url='. Yii::app()->params['weburl'].$path.$urn.'&title='.$title.'&pubid='.Yii::app()->params['addthis_pubid'].'" rel="nofollow"><img src="http://cache.addthiscdn.com/icons/v1/thumbs/menu.gif"></a>';
		 
		return $share_html."<br>";
	}
	
	/**
	 * Generates tags from a string
	 * @param string $text the text to generate the tags from
	 * @param boolean $set_link wether to make links to the tag
	 * @param string $type source where we are generating tags from. eg: From a URN field or from a TAG table field.
	 * @return array with the tags
	 */
	public function make_tags($text,$set_link=true,$type='tag')
	{
		if($type == 'urn'){
			//First, lets generate a clean text(just letters, remove everything else)		
			$text = ereg_replace("[^A-Za-z ]", " ", $text);//we cen't replace with empty as it would concatenate some strings (ie: hi:im me => hiim me)so we use whitespace
			$text = preg_replace('/\s+/', ' ', $text);//now replace any sequence of whitespace greater than one, with only one
			$text = trim($text);//we need to trim also as there can be white spaces at the end (ie: how are you? => 'how are you ')
			//$text = str_replace(" ", " *", $text);
			//And split to an array by whitespaces
			$arr_text =  explode(" ", $text);
			$arr_text = array_unique($arr_text);//remove any duplicate tag
		}else{
			$arr_text = explode(" ",$text);
		}
		
		if(! $set_link)	return $arr_text;
		
		function to_link(&$value, $key, $type){
			
			if($type == 'urn')
				$value = '<a href="'.Yii::app()->getRequest()->getBaseUrl(true).'/subject/index?'.urlencode('Subject[urn]').'='.$value.'&ajax=subject-grid">&#32;&#149;&#32;'.$value.'</a>'; 
			else
				$value = '<a href="'.Yii::app()->getRequest()->getBaseUrl(true).'/subject/index?'.urlencode('Subject[title]').'='.$value.'&ajax=subject-grid">&#32;&#149;&#32;'.$value.'</a>'; 
		} 
		
		array_walk($arr_text, 'to_link', $type);
		return $arr_text;
	}
	
	/**
	 * Generates the html for the content on the client side
	 * @param string $subject_content the subject specific html
	 * @param string $comment (optional) the user comment
	 * @param string $info (optional) additional info
	 * @param string $share (optional) the sharing links
	 * @return string with the html
	 */
	public function content_html($subject_content,$comment='',$info='',$share='')
	{
		$html = $subject_content."<br>".$comment."<br><br>".$info."<br><br>".$share;
		if(Yii::app()->getTheme()->name=='mobile'){
			$html = $subject_content."<br>".
			'<div class="expandable">'.$comment.'<br>'.$info.'<br>'.$share.'</div>';
			$html = $html .'<script>$(".expandable").expander({slicePoint:100,expandText: "[more]",userCollapseText: "[less]"});</script>';
			
			//if( strlen(strip_tags($comment)) > 64 ){	
			//}
		}
		return $html;
	
	}
	/**
	 * Generates OpenGraph meta tags to help identify the content information in the page(eg: a flash video).
	 * This is very usefull specially for sharing(eg:facebook) or indexing in search engines and AddThis.
	 * http://www.addthis.com/help/widget-sharing
	 * @param string $title the title of the page
	 * @param string $description (optional) the description of the content in page
	 * @param string $image (optional) to make a preview of the page
	 * @param string $url (optional) of the page
	 * @param string $iframe_url (optional) of the page widget
	 * @return string with the meta tags
	 */
	public function get_ogtags($title,$description='',$image='',$url='',$iframe_url='')
	{
		$ogtags = '<meta property="og:title" content="'.htmlspecialchars($title).'" />';
		if($description) $ogtags .= '<meta property="og:description" content="'.htmlspecialchars(substr($description, 0,220)).'" />';
		if($image) 		 $ogtags .= '<meta property="og:image" content="'.$image.'" />';
		if($iframe_url)  $ogtags .= '<link rel="iframe_src" href="'.$iframe_url.'" />';
		$ogtags .= '<meta property="og:site_name" content="'.Yii::app()->name.'"/>';
		return $ogtags;
	}
	
}