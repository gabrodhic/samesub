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
				preg_match_all('#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#', $subject->content_video->embed_code, $match);
				//would be nice to use yii validator to see if its an url but: http://code.google.com/p/yii/issues/detail?id=1324
				
				if(SiteLibrary::valid_url($match[0][0])){//if its an url(if we could extract an url from the content)
					$parsed_url = parse_url($match[0][0]);
					$query_arr = SiteLibrary::parse_url_query($parsed_url['query']);
					if(stristr($parsed_url['host'], 'youtube.com')){//and its from youtube
					
						//get the V value $parsed_url['query'];						
						if (array_key_exists('v', $query_arr)) {
							//set ?wmode=opaque because so that the the #header_top div does not get bellow the movie
							//http://stackoverflow.com/questions/3820325/overlay-opaque-div-over-youtube-iframe
							$time = ($query_arr['t']) ? '#at='.$query_arr['t'] : '';//#at=** is the correct syntax for embed url, ie clic on the youtube logo while wathching a video on X seconds: it will open a new window with that param
							$html = '<iframe width="640" height="390" src="http://www.youtube.com/embed/'.$query_arr['v'].'?wmode=opaque'.$time.'" frameborder="0" allowfullscreen></iframe>';
						}else{
							$time = ($query_arr['t']) ? '#at='.$query_arr['t'] : ''; //#at=** is the correct syntax for embed url, ie clic on the youtube logo while wathching a video on X seconds: it will open a new window with that param
							
							$vid_url = str_replace("/v/","",$parsed_url['path']);//remove the old embed code variable just in case it is present
							if(strpos($vid_url,"?"))
								$vid_url = substr($vid_url, 0, strpos($vid_url,"?"));//remove the old params
							elseif(strpos($vid_url,"&"))
								$vid_url = substr($vid_url, 0, strpos($vid_url,"&"));//remove the old params
							$vid_url = str_replace("/embed/","",$vid_url);//remove the old embed code variable just in case it is present
							$html = '<iframe width="640" height="390" src="http://www.youtube.com/embed/'.$vid_url.'?wmode=opaque'.$time.'" frameborder="0" allowfullscreen></iframe>';
						}
					}elseif(stristr($parsed_url['host'],'youtu.be')){//and its from youtube shortly
						$time = ($query_arr['t']) ? '#at='.$query_arr['t'] : ''; //#at=** is the correct syntax for embed url, ie clic on the youtube logo while wathching a video on X seconds: it will open a new window with that param
						
						$html = '<iframe width="640" height="390" src="http://www.youtube.com/embed/'.$parsed_url['path'].'?wmode=opaque'.$time.'" frameborder="0" allowfullscreen></iframe>';
					}elseif(stristr($parsed_url['host'],'dailymotion.com')){
						//the code is before the first undersore for the video source(pending verify if thats the syntax for all cases)
						if($last_code_pos = stripos($parsed_url['path'], '_')){
							$video_code = substr($parsed_url['path'],1, $last_code_pos-1);
							if($video_code)	$html = '<iframe frameborder="0" width="640" height="390" src="http://www.dailymotion.com/embed/'.$video_code.'"></iframe>'; //$video_code already contains: /videdo/
						}
					}elseif(stristr($parsed_url['host'],'vimeo.com')){
						if($parsed_url['path'])
						//nice, we can play with params here, title, portrait, etc
						$html = '<iframe src="http://player.vimeo.com/video'.$parsed_url['path'].'?title=0&byline=0&portrait=0" width="640" height="390" frameborder="0"></iframe>';
					}else{
						$html = SiteHelper::formatted($subject->content_video->embed_code);
					}
				}else{
					$html = SiteHelper::formatted($subject->content_video->embed_code);
				}
				//Get the iframe source url and set the image url for ogtags
				//Also set opaque property for youtube videos
				//src="...."    second match (((?!").)*) searches for any string NOT containing " as that is our delimitter used in third match
				$pattern = '/(src=")(((?!").)*)(")/i'; 
				preg_match($pattern, $html, $matches);//position 0 is the full match
				
				$arr_content['url'] = $matches[2];
				if(SiteLibrary::valid_url($arr_content['url'])){//if its an url
					$parsed_url = parse_url($arr_content['url']);
					$query_arr = SiteLibrary::parse_url_query($parsed_url['query']);
					if(stripos($parsed_url['host'], 'youtube.com')){
						$pattern2 =  strpos($arr_content['url'], '?') ? '/(embed\/)(.+)(\?)/i' : '/(embed\/)(.+)$/i'; //the url can have extra params (a ? mark to pass extra params to the video)
						preg_match($pattern2, $arr_content['url'], $matches);//position 0 is the full match
						$arr_content['code'] = $matches[2];
						$arr_content['image'] = 'http://img.youtube.com/vi/'.$arr_content['code'].'/default.jpg';
						
						//Intercept the content html iframe url of youtube videos and set opaque property if not set
						if(! strpos($arr_content['url'], 'wmode=opaque')){
							$opaque_mode = strpos($arr_content['url'], '?') ? '&wmode=opaque' : '?wmode=opaque'; 
							$html = preg_replace('/(.*)(src=")(((?!").)*)(")(.*)/i', '${1}$2'.$arr_content['url'].$opaque_mode.'$5$6', $html);
						}
					}
						//TODO: get schema for images in other providers
					
				}
				
				
				
				//intercept the content html and resize any width or height depending on current theme
				if(Yii::app()->getTheme()->name=='mobile'){
					$max_width = 250;
					$max_height = 190;
				}else{
					$max_width = 640;
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
		$share_html = '<a target="_blank" href="http://api.addthis.com/oexchange/0.8/forward/facebook/offer?url='. Yii::app()->params['weburl'].$path.$urn.'&title='.$title.'&pubid='.Yii::app()->params['addthis_pubid'].'" rel="nofollow"><img src="http://cache.addthiscdn.com/icons/v1/thumbs/facebook.gif"></a>';
		$share_html .= '&nbsp;<a target="_blank" href="http://api.addthis.com/oexchange/0.8/forward/twitter/offer?url='. Yii::app()->params['weburl'].$path.$urn.'&title='.$title.'&pubid='.Yii::app()->params['addthis_pubid'].'" rel="nofollow"><img src="http://cache.addthiscdn.com/icons/v1/thumbs/twitter.gif"></a>';
		$share_html .= '&nbsp;<a target="_blank" href="http://api.addthis.com/oexchange/0.8/forward/email/offer?url='. Yii::app()->params['weburl'].$path.$urn.'&title='.$title.'&pubid='.Yii::app()->params['addthis_pubid'].'" rel="nofollow"><img src="http://cache.addthiscdn.com/icons/v1/thumbs/email.gif"></a>';
		$share_html .= '&nbsp;<a target="_blank" href="http://api.addthis.com/oexchange/0.8/forward/google/offer?url='. Yii::app()->params['weburl'].$path.$urn.'&title='.$title.'&pubid='.Yii::app()->params['addthis_pubid'].'" rel="nofollow"><img src="http://cache.addthiscdn.com/icons/v1/thumbs/google.gif"></a>';
		$share_html .= '&nbsp;<a target="_blank" href="http://api.addthis.com/oexchange/0.8/offer?url='. Yii::app()->params['weburl'].$path.$urn.'&title='.$title.'&pubid='.Yii::app()->params['addthis_pubid'].'" rel="nofollow"><img src="http://cache.addthiscdn.com/icons/v1/thumbs/menu.gif"></a>';
		 
		return $share_html."<br>";
	}
	
	/**
	 * Generates tags from a string
	 * @param mixed $text the text OR array to generate the tags from
	 * @param boolean $set_link wether to make links to the tag
	 * @param string $type source where we are generating tags from. eg: From a URN field or from a COMMA separated table field or SPACE separated.
	 * @return array with the tags
	 */
	public function make_tags($text,$set_link=true,$type='comma')
	{
		if($type == 'urn'){
			//First, lets generate a clean text(just letters, remove everything else)		
			$text = ereg_replace("[^A-Za-z ]", " ", $text);//we cen't replace with empty as it would concatenate some strings (ie: hi:im me => hiim me)so we use whitespace
			$text = preg_replace('/\s+/', ' ', $text);//now replace any sequence of whitespace greater than one, with only one
			$text = trim($text);//we need to trim also as there can be white spaces at the end (ie: how are you? => 'how are you ')
			//$text = str_replace(" ", " *", $text);
			//And split to an array by whitespaces
			$arr_text =   array_filter(explode(" ", $text));//Notice that array_filter removes any empty values from array if no callback its specified
			$arr_text = array_unique($arr_text);//remove any duplicate tag
		}elseif($type == 'comma'){
			$arr_text =  array_filter (explode(",",trim($text)));//Notice that array_filter removes any empty values from array if no callback its specified
		}else{
			$arr_text =  array_filter(explode(" ",trim($text)));//Notice that array_filter removes any empty values from array if no callback its specified
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
	
	/**
	 * Get the user picture
	 * @param mixed $username_id the user id or username
	 * @param string $size the size
	 * @param string $link wether to set a link to the image. ie: user profile or mysub page or image
	 * @return string the img tag code, otherwise false if user_id is not found
	 */
	public function get_user_picture($username_id, $size='small_',$link='')
	{
		if(is_int($username_id))
			$user=User::model()->findByPk((int)$username_id);
		else
			$user=User::model()->find('username=:username',array(':username'=>$username_id));
			
		if($user===null) return false;
		$dimension ='';
		
		if($size == 'small_') $dimension = 'width="45" height="45"';
		if($size == 'icon_') {$dimension = 'width="25" height="25"';$size = 'small_';}
		if($size == 'medium_') {$dimension = 'width="130" height="120"'; $size = '';}
		if($size == 'large_') $size = '';
		$link1 = '';
		$link2 = '';
		if($link === 'mysub') $link1 = '<a href="'.Yii::app()->createUrl('mysub/'.$user->username).'">';
		if($link === 'profile') $link1 = '<a href="'.Yii::app()->createUrl('profile/'.$user->username).'">';
		if($link === 'image') $link1 = '<a href="'.$user->getUserImage('').'" class="nyroModal">';
		if($link1) $link2 = '</a>';
		return $link1.'<img src='.$user->getUserImage($size).' '.$dimension .'>'.$link2;
		
	}
	
	
	/**
	 * Get the comment vote elements
	 * @param int $id the id of the comment
	 * @param int $likes the likes of the comment
	 * @param int $dislikes the dislikes of the comment
	 * @return string the html content
	 */
	public function comment_vote($id, $likes, $dislikes)
	{		
		
		$html = '<span class="comment_vote">'		
		.' <a class="comment_like_button" alt="'.$id.'"></a>'		
		.' <em class="comment_votes_count total_likes total_likes'.$likes;
		$html .=  '">'.$likes.$likes_int.'</em>'
		.' <a class="comment_dislike_button"  alt="'.$id.'"></a>'
		.' <em class="comment_votes_count total_dislikes total_dislikes'.$dislikes;
		$html .=  '">'.$dislikes.$dislikes_int.'</em>'
		.'</span>';
		return $html;
	}
	
	
	/**
	 * Get the subject vote elements
	 * @param int $id the id of the subject
	 * @param int $likes the likes of the subject
	 * @param int $dislikes the dislikes of the subject
	 * @param boolean $raw wether to return just the raw data about the vote,percent,etc, or the full html
	 * @return mixed the html content or raw data to generate the html
	 */
	public function subject_vote($id, $likes, $dislikes, $raw=false)
	{	
		// Testing numbers. Replace with your own.
		$value = $likes;
		$max = ($likes + $dislikes);
		$scale = 2.0;

		// Get Percentage out of 100
		if ( !empty($max) ) { $percent = ($value * 100) / $max; } 
		else { $percent = 0; }

		// Limit to 100 percent (if more than the max is allowed)
		if ( $percent > 100 ) { $percent = 100; }
		$round1 = round(100 * $scale);
		$round2 = round($percent * $scale);
		$round1_color = ($dislikes > 0) ? 'D10000' : '';
		
		if($raw == true) return array('subject_id'=>$id,'percent'=>round($percent),'likes'=>$likes,'dislikes'=>$dislikes,'width_dislikes'=>$round1,'width_likes'=>$round2, 'color_dislikes'=>$round1_color);
		

		
		$html .= '<div style="float:left;width:'.$round1.'px;">';		
				$html .= '<div style="float:right;">';//height and padding for the Vertical alignment
		$html .= '<span class="">'		
		.' <a class="sub_like_button" alt="'.$id.'"></a>'		
		.' <em class="sub_vote_label" style="margin-right:10px;">'.Yii::t('site','Like').'</em>'
		.' <a class="sub_dislike_button"  alt="'.$id.'"></a>'
		.' <em class="sub_vote_label">'.Yii::t('site','Dislike').'</em>'.'</span>';		
		$html .=  '</div>';
		$html .=  '<div style="clear:both"></div>';
		$html .= '<div class="sub_vote_percentbar_dislikes" style="width:'.$round1.'px;';
		$html .= ($round1_color) ? 'background:#'.$round1_color.';' : '';
		$html .= '">';
		$html .= '<div class="sub_vote_percentbar_likes" style="width:'.$round2.'px;"></div>';
		$html .= '</div>';
		$html .= '<div id="sub_likes_dislikes" style="text-align:right;">'.Yii::t('subject', '{likes} likes, {dislikes} dislikes', array('{likes}'=>$likes, '{dislikes}'=>$dislikes)).'</div>';
		$html .= '</div>';
		$html .= '<div id="sub_likes_percent" style="float:left; text-align:center; margin:0 15px 0 15px; background:grey; color:white;padding:2px;font-weight:bold;">'.round($percent).'%</div>';

		return $html;
	}
	
}