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

function display_elements(obj_json){


	if(cache_div_id == 0){
		time_submitted = fromUnixTime(obj_json.time_submitted_1);
		reset_page=true;
		reset_comment = true;
		current_id = obj_json.id_1;//id
		current_title = obj_json.title_1;//title
		submit_info = 'by <a href="<?php echo Yii::app()->getRequest()->getBaseUrl(true);?>/mysub/'+obj_json.username_1+'">' + obj_json.username_1 + '</a> at ' 
		+time_submitted.getHours()+':'+time_submitted.getMinutes()+' UTC';
		share_html = '<?php echo SiteHelper::share_links("'+obj_json.urn_1+'","'+obj_json.title_1+'"); ?>';
		$('#content_div_1').html('<?php echo SiteHelper::content_html("'+obj_json.content_html_1+'","'+obj_json.user_comment_1+'","'+submit_info+'","'+share_html+'"); ?> ');
		current_info = '<b>'+obj_json.country_name_1+'</b> | ';
		//$("#comments_board").html("Waiting for comments");
	}else{
		if(cached == true){
			if(epoch_time >= cache_display_time){
				pending_sub=false;
				reset_page = true;
				reset_comment = true;
				cached = false;//We resete cached after we display for the new element
				//$("#comments_board").html("Waiting for comments");
				if(obj_json.comments_2.length > 0){
					//obj_json.comments = [];
					//obj_json.comments = obj_json.comments_2;
					pending_comment = true;
					last_displayed_comment=-1;
					//alert('entro if');
					
				}
				load_comments(obj_json.comments_2);

				var new_title = ($("#header_title h1").html().length >45) ? $("#header_title h1").html().substring(0,45) + '...' : $("#header_title h1").html();
				$("#previous_right").html('<a title="'+$("#header_title h1").html().replace(/"/g,'')+'" href="'+$('#urn_link').attr('href')+'">'+new_title+'</a>');
				
				$("#comments_title").attr("style", "");
				$("#comments_title").html('Latest user comments:');
				$("#comment_timer").attr("style", "");
				$("#comment_timer").html('');
						
				current_id = cache_div_id;
				current_title = cache_div_title;
				if($("#content_div_1").css("display") == 'none'){
					$("#content_div_1").css("display", "inline");
					$("#content_div_1").css("visibility", "visible");
					$("#content_div_2").css("display", "none");
					$("#content_div_2").css("visibility", "hidden");
					//$("#content_div").html($('#cache_html').html());
				}else{
					$("#content_div_1").css("display", "none");
					$("#content_div_1").css("visibility", "hidden");
					$("#content_div_2").css("display", "inline");
					$("#content_div_2").css("visibility", "visible");
				}
				current_info = cache_div_info;
				
			}else{
				countdown = (cache_display_time-epoch_time);
				if(countdown <= 300 && countdown >=0){//when to start the countdown
					if(countdown == 120){
						var backup_title = $("#header_title h1").html();
						$("#header_title h1").attr("style", "color:#1C75CE");
						$("#header_title h1").html('Subject changes in 2 minutes, comments close in 1 minute 50 seconds');
						$('#header_title h1').typewriter( 2000, function(){
							setTimeout(function(){$("#header_title h1").html(backup_title); $("#header_title h1").attr("style", "");},4000);
						}, new Date());						
					}
					
					if(countdown > 60){
						$("#comment_timer").css("color", "#686868");
						hms = secondsToTime(countdown);
						$("#comment_timer").html('<span>Time remaining: </span><span>'+ hms.m + 'min ' +  hms.s + 's</span>');
					}else{
						$("#comment_timer").html('<span>Time remaining: </span><span>'+countdown.toString()+ ' seconds</span>');
						
						if(countdown > 55){
							$("#comments_title").fadeTo("fast", 1.0);//notice, this is just to reset it to original opacity
							$("#comments_title").css("color", "black");
							$("#comments_title").html('Comments closing...');
							$("#comments_title").fadeTo("medium", 0.0);
						}else if(countdown > 12){
							$("#comments_title").attr("style", "");
							$("#comments_title").html('People, make your conclusions:');
							
							$("#comment_timer  span:nth-child(2)").css("color", "black");//only the second span
							setTimeout(function (){$("#comment_timer  span:nth-child(2)").css("color", "white");},500);
							//$("#comment_timer").fadeTo("fast", 1.0);//notice, this is just to reset it to original opacity
							//$("#comment_timer").css("color", "black");
							//$("#comment_timer").html(countdown.toString() + 'seconds);
							//$("#comment_timer").fadeTo("medium", 0.0);
						}else if(countdown > 10){
							$("#comments_title").attr("style", "color:red");//notice, we are overriding the style property fully cleans all other previous styles by jquery(opacity, filter, etc)							
							$("#comments_title").html('Comments CLOSED');
							
							$("#comment_timer").css("color", "black");
							
						}else if(countdown > 2){
							$("#comment_timer").attr("style", "color:red");
							//$("#comment_timer").html(countdown.toString() + 'seconds');
							$("#comment_textarea").attr('disabled',true);
							
						}else{
							$("#comment_timer").attr("style", "");
							$("#comment_timer").html('Changing to next subject');
						}
						
						//var tt = (cache_display_time-epoch_time);
						
					}
				}
			}
		}
	}
	
	if(cached==false && pending_sub ==true){
		
		cached = true;
		cache_div_id = obj_json.id_2;
		time_submitted = fromUnixTime(obj_json.time_submitted_2);
		submit_info = 'by <a href="<?php echo Yii::app()->getRequest()->getBaseUrl(true);?>/mysub/'+obj_json.username_2+'">' + obj_json.username_2 + '</a> at ' 
		+time_submitted.getHours()+':'+time_submitted.getMinutes()+' UTC';
		share_html = '<?php echo SiteHelper::share_links("'+obj_json.urn_2+'","'+obj_json.title_2+'"); ?>';
		cache_div_title = obj_json.title_2;
		if($("#content_div_1").css("display") == 'none'){
			$("#content_div_1").html(  '<?php echo SiteHelper::content_html("'+obj_json.content_html_2+'","'+obj_json.user_comment_2+'","'+submit_info+'","'+share_html+'"); ?> ');
			//$('#cache_html').html(  obj_json.user_comment_2 + '<br>' + obj_json.content_html_2 + share_html);
		}else{
			$("#content_div_2").html(  '<?php echo SiteHelper::content_html("'+obj_json.content_html_2+'","'+obj_json.user_comment_2+'","'+submit_info+'","'+share_html+'"); ?> ');
		}
		
		
		cache_div_info = '<b>'+obj_json.country_name_2+'</b> | ';
		cache_display_time = obj_json.display_time_2;
	
	}
	
	if(reset_page == true){
	
		$("#header_title h1").html(current_title);
		$("#header_info").html(current_info);

		blink_page_title(current_title);
		//$("#comment_timer").html('');
		$("#comment_textarea").attr('disabled',false);
		
		var title_1 = (obj_json.last_sub_1_title.length >45) ? obj_json.last_sub_1_title.substring(0,45) + '...' : obj_json.last_sub_1_title;
		var title_2 = (obj_json.last_sub_2_title.length >45) ? obj_json.last_sub_2_title.substring(0,45) + '...' : obj_json.last_sub_2_title;
		
		if(request_count == 0) {
			$("#previous_right").html('<a title="'+obj_json.last_sub_1_title.replace(/"/g,'')+'" href="<?php echo Yii::app()->params['weburl'];?>/sub/'+obj_json.last_sub_1_urn+'">'+ title_1 + '</a>');
			$("#previous_right").append('<br>' + '<a title="'+obj_json.last_sub_2_title.replace(/"/g,'')+'" href="<?php echo Yii::app()->params['weburl'];?>/sub/'+obj_json.last_sub_2_urn+'">'+ title_2 + '</a>');
		}else{
			$("#previous_right").append('<br>' + '<a title="'+obj_json.last_sub_1_title.replace(/"/g,'')+'" href="<?php echo Yii::app()->params['weburl'];?>/sub/'+obj_json.last_sub_1_urn+'">'+ title_1 + '</a>');
		}
		//$("#comments_board").attr("data-number", obj_json.comment_number);
		//$("#comments_board").html("Waiting for comments");
	}

	
	
	reset_page=false;
	//Dont do the timeout if there are no more elements in the obj_json variable to be shown
	//if((clock_time.getTime()/1000) <= (obj_json.current_time+5))	setTimeout(function (){display_elements(obj_json);},3000);
	if(pending_sub == true) setTimeout(function (){display_elements(obj_json);},1000);
}



var interval_check;
get_Contents(function(){
	//Add this callback only the first time after we fetch content
	interval_check = setInterval("check_preload()",1000);	
});
