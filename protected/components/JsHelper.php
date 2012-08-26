<?php
/**
 * JsHelper is a Javascript helper class for the site.
 * It contatins methods that provide code for various Javascript functionalities required sitewide.
 */
class JsHelper extends CHtml
{
	
	/**
	 * Generates the proper js to add comments voting functionality for the current page
	 * @param object $subject the an instance of the Subject class
	 * @param string $mode in wich to return information(html or array of data)
	 * @return mixed string the js code
	 */
	public function comments_voting()
	{
		$js = "$(document).ready(function() {
		//Use die() to remove any previous attached click event, and use live() to preserve the event if the DOM changes
        $('.comment_like_button, .comment_dislike_button').die('click').live('click',function() {
		
			var link = '".Yii::app()->createUrl('/api/v1/comment/vote?comment_id=')."'+$(this).attr('alt')+'&vote=';			
			if($(this).attr('class') == 'comment_like_button'){
				link = link+'like';
			}else{
				link = link+'dislike';
			}			
            $.ajax({
                    url:link,
                    cache:false,
					dataType:'json',
                    success: function(response) {						
						if(response.error == 0){								
							$('#c'+response.comment_vote['comment_id']).find('.total_likes').html(response.comment_vote['likes']);
							if (response.comment_vote['likes'] > 0) $('#c'+response.comment_vote['comment_id']).find('.total_likes').removeClass('total_likes0');
							$('#c'+response.comment_vote['comment_id']).find('.total_dislikes').html(response.comment_vote['dislikes']);
							if (response.comment_vote['dislikes'] > 0) $('#c'+response.comment_vote['comment_id']).find('.total_dislikes').removeClass('total_dislikes0');
						}else{
							alert(response.error_message);
						}
                    },
					error: function(){
						alert('Sorry, there was an error.');
					}
            });
            return false;
            });
        });
		";
		return $js;
	}
	
	
}