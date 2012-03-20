<?php echo $this->renderPartial('js/core_lib'); ?>

//Evaluate ifis necesary to add the jquery blink plugin: https://github.com/fcarriedo/jquery-blink
//$('selector').blink({maxBlinks: 60, blinkPeriod: 1000, speed: 'slow', onMaxBlinks: function(){}, onBlink: function(){}});

/*
* USAGE:
* $('.element').typewriter( speed, callback );
* www.labs.skengdon.com/typewriter/
*/
;(function($){
	$.fn.typewriter = function( speed, callback, time_before ) {
		if ( typeof callback !== 'function' ) callback = function(){};
		var write = function( e, text, time ) {
			//We add support for inactive tabs in Chrome. It is an issue of design of chrome itself. here:
			//http://stackoverflow.com/questions/5927284/how-can-i-make-setinterval-also-work-when-a-tab-is-inactive-in-chrome?s=2b3e7c34-9910-4053-9e82-97dec0b55b17#new-answer
			//http://codereview.chromium.org/6577021/diff/1/webkit/glue/webkit_constants.h
			now = new Date();
			var elapsedTime = (now.getTime() - time_before.getTime());
			if(elapsedTime > (speed + 1000)){ //let's give a 1 second margin, IE: gets delayed some times on small milliseconds operations
				e.callback();
			}else{
				var next = $(e).text().length + 1;
				if ( next <= text.length ) {
					$(e).text( text.substr( 0, next ) );
					setTimeout( function( ) {
						write( e, text, time );
					}, time);
				} else {
					e.callback();
				}
			}
		};
		return this.each(function() {
			this.callback = callback;
			var text = $(this).text();
			var time = speed/text.length;
			
			$(this).text('');
			
			write( this, text, time )
		});
	}
}(jQuery));

// We start our code bellow this line.
function ObserveEsc(objEvent) 
    {
        try {
            var sKey;
        
            if(window.event) {
                sKey = window.event.keyCode;
            }
            else if(objEvent) {
                sKey = objEvent.which;
            }   
            
            var objEvent = objEvent || window.event;    
                       
            if(sKey == 27) {      
                //alert("You clicked Alt + F4");
                return false;    
            }  
        }
        catch(ex) {
            alert(ex.toString());
        }
	return true;
}
document.onkeydown = ObserveEsc;

function trim(stringToTrim) {return stringToTrim.replace(/^\s+|\s+$/g,"");}


/**
 * Blink page status to get user attention and notify that the subject has changed or a similar event has occurred
 *
 * @return void
 */
function blink_page_title(text){

	
	setTimeout ( function(){document.title = '______';}, 100);
	setTimeout ( function(){document.title = text;}, 700);
	setTimeout ( function(){document.title = '______';}, 1200);
	setTimeout ( function(){document.title = text;}, 1700);
	setTimeout ( function(){document.title = '______'; }, 2200);
	setTimeout ( function(){document.title = text; }, 2700);
		
	
}

var tick;
var clock_time = null;

<?php $time = SiteLibrary::utc_time(); ?>

var utc_time = <?php echo $time;?>;
var utc_hour = <?php echo date("H",$time); ?>;
var utc_min = <?php echo date("i",$time); ?>;
var utc_sec = <?php echo date("s",$time); ?>;


/**
 * This function provides a utc clock.
 * NOTE: UTC time is calculated server-side
 * @return void
 */

function clock() {
  
	if( clock_time != null ){
		clock_time.setSeconds(clock_time.getSeconds() + 1);
	}else{
		clock_time=new Date(utc_time * 1000);
		clock_time.setHours(utc_hour,utc_min,utc_sec,0);
	}
	
	var h,m,s;
	var time="        ";
	h=clock_time.getHours();
	m=clock_time.getMinutes();
	s=clock_time.getSeconds();
	if(s<=9) s="0"+s;
	if(m<=9) m="0"+m;
	if(h<=9) h="0"+h;
	time+=h+":"+m+":"+s;
	$('#utc_clock').html(time);
	tick=window.setTimeout("clock()",1000); 
}
clock();



/**
 * Shows the countdown timer for the current sub and places all warnings about the time remaining for the sub
 *
 * @return void
 */
function countdown(){

	
	if(time_remaining >=0){//when to start the time_remaining
		if(time_remaining == 120){
			var backup_title = $("#header_title h1").html();
			$("#header_title h1").attr("style", "color:#1C75CE");
			$("#header_title h1").html('<?php echo Yii::t('site','Subject changes in 2 minutes, comments close in 1 minute 50 seconds');?>');
			$('#header_title h1').typewriter( 2000, function(){
				setTimeout(function(){$("#header_title h1").html(backup_title); $("#header_title h1").attr("style", "");},4000);
			}, new Date());						
		}
		
		if(time_remaining > 60){
			$("#comment_timer").css("color", "#686868");
			hms = secondsToTime(time_remaining);
			$("#comment_timer").html('<span><?php echo Yii::t('site','Time remaining:');?> </span><span>'+ hms.m + 'min ' +  hms.s + 's</span>');
		}else{

			$("#comment_timer").html('<span><?php echo Yii::t('site','Time remaining:');?> </span><span>'+time_remaining.toString()+ ' seconds</span>');
			
			if(time_remaining < 30){
				if(time_remaining > 25){
					$("#comments_title").fadeTo("fast", 1.0);//notice, this is just to reset it to original opacity
					$("#comments_title").css("color", "black");
					$("#comments_title").html('<?php echo Yii::t('site','Comments closing...');?>');
					$("#comments_title").fadeTo("medium", 0.0);
				}else if(time_remaining > 15){
					$("#comments_title").attr("style", "");
					//$("#comments_title").html('People, make your conclusions:');
					
					$("#comment_timer  span:nth-child(2)").css("color", "black");//only the second span
					setTimeout(function (){$("#comment_timer  span:nth-child(2)").css("color", "white");},500);
					//$("#comment_timer").fadeTo("fast", 1.0);//notice, this is just to reset it to original opacity
					//$("#comment_timer").css("color", "black");
					//$("#comment_timer").html(time_remaining.toString() + 'seconds);
					//$("#comment_timer").fadeTo("medium", 0.0);
				}else if(time_remaining > 10){

					$("#comment_timer  span:nth-child(2)").css("color", "red");//only the second span
					setTimeout(function (){$("#comment_timer  span:nth-child(2)").css("color", "white");},500);
					//$("#comment_timer").css("color", "black");
					
				}else if(time_remaining > 6){
					$("#comments_title").attr("style", "color:red");//notice, we are overriding the style property fully cleans all other previous styles by jquery(opacity, filter, etc)							
					$("#comments_title").html('<?php echo Yii::t('site','Comments CLOSED');?>');
					
					$("#comment_textarea").attr('disabled',true);								
					//$("#comment_timer").attr("style", "");
					$("#comment_timer  span:nth-child(2)").css("color", "red");//only the second span
					//$("#comment_timer").html('Changing to next subject');
				}else{
					if(time_remaining == 6){
						$("#header_title h1").attr("style", "color:#1C75CE");
						$("#header_title h1").html('<?php echo Yii::t('site','Changing to next subject, get ready!');?>');
						$('#header_title h1').typewriter( 1000, function(){
							setTimeout(function(){$("#header_title h1").attr("style", "");},3000);
						}, new Date());
					}else if(time_remaining == 1 || time_remaining < 2){
						$('#frame_box').show();
						//$('#frame_box').contents().find('body').attr('style','background-color:white;');
					}
				}
			}
			

		}
		
		time_remaining = time_remaining - 1;
	}else{
		$('#frame_box').show();
	}
	
	window.setTimeout("countdown()",1000);
}





/**
 * Puts all subject's information elements on its proper place in the page
 * @param object obj_json the json object containing all the information about the current sub
 * @return void
 */
function display_elements(obj_json){



	time_submitted = fromUnixTime(obj_json.time_submitted);
	current_title = obj_json.title;//title
	submit_info = '<?php echo Yii::t('site','by {username} at {time} UTC',array('{username}'=>'<a href="'.Yii::app()->getRequest()->getBaseUrl(true).'/mysub/\'+obj_json.username+\'">\' + obj_json.username + \'</a>','{time}'=>"'+time_submitted.getHours()+':'+time_submitted.getMinutes()+'"));?>';
	share_html = '<?php echo SiteHelper::share_links("'+obj_json.urn+'","'+obj_json.title+'"); ?>';
	$('#content_div_1').empty();//some times iframes or object elements stay inside, so clean it just in those rare cases
	$('#content_div_1').html('<?php echo SiteHelper::content_html("'+obj_json.content_html+'","'+obj_json.user_comment+'","'+submit_info+'","'+share_html+'"); ?> ');
	current_info = '<b>'+obj_json.country_name+'</b> | ';
	//$("#comments_board").html("Waiting for comments");
	
	$("#comments_title").attr("style", "");
	$("#comments_title").html('Latest user comments:');
	$("#comment_timer").attr("style", "");
	$("#comment_timer").html('');
	$('#frame_box').hide();
			



	$("#header_title h1").html(current_title);
	$("#header_info").html(current_info);

	blink_page_title(current_title);
	$("#comment_textarea").attr('disabled',false);
	
	var title = (obj_json.last_sub_title.length >45) ? obj_json.last_sub_title.substring(0,45) + '...' : obj_json.last_sub_title;
	var title_2 = (obj_json.last_sub_2_title.length >45) ? obj_json.last_sub_2_title.substring(0,45) + '...' : obj_json.last_sub_2_title;
	
	
	$("#previous_right").html('<a title="'+obj_json.last_sub_title.replace(/"/g,'')+'" href="<?php echo Yii::app()->params['weburl'];?>/sub/'+obj_json.last_sub_urn+'">'+ title + '</a>');
	$("#previous_right").append('<br>' + '<a title="'+obj_json.last_sub_2_title.replace(/"/g,'')+'" href="<?php echo Yii::app()->params['weburl'];?>/sub/'+obj_json.last_sub_2_urn+'">'+ title_2 + '</a>');
		
}



var interval_check;
$(document).ready(function() {
	get_Contents(function(){
		//Add this callback only the first time after we fetch content
		interval_check = setInterval("check_preload()",1000);	
	});
		//We need to iframe the layer as it is windowed element and we are working with different user content
		//adata a as swf flash that dont have wmode=opaque
		$('<iframe src="about:blank" width="980" height="900" id="frame_box" frameBorder="0" scrolling="no" style="display:none; background-color:white; z-index:9000; position:absolute;"></iframe>').prependTo('#main_body');
		 setTimeout(function (){
		 $('#frame_box').contents().find('body').html('<p align="center">&nbsp;</p><p align="center">&nbsp;</p><p align="center">&nbsp;</p><p align="center">&nbsp;</p><p align="center"><b><font size="7" face="Trebuchet MS" color="#3F3F3F"><?php echo Yii::t('site','Next sub is:');?></font></b></p><p align="center">&nbsp;</p><p align="center">&nbsp;</p><p align="center">&nbsp;</p><p align="center">&nbsp;</p><p align="center" style="font: normal 14px Trebuchet MS, Arial, Helvetica, sans-serif; color:#3F3F3F;"><b><?php echo CHtml::link(Yii::t('subject','You can add your own sub here'),Yii::app()->getRequest()->getBaseUrl(true)."/subject/add", array('target'=>'_top')); ?></b></p>');
		 },3000); 
		 
});
