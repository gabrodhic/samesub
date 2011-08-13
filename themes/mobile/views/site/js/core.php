<?php echo $this->renderPartial('js/core_lib'); ?>



function display_elements(obj_json){


	if(cache_div_id == 0){
		reset_page=true;
		reset_comment = true;
		current_id = obj_json.id_1;//id
		current_title = obj_json.title_1;//title
		share_html = '<?php echo SiteHelper::share_links("'+obj_json.urn_1+'","'+obj_json.title_1+'"); ?>';
		$('#content_div_1').html('<?php echo SiteHelper::content_html("'+obj_json.content_html_1+'","'+obj_json.user_comment_1+'","'+share_html+'"); ?> ');
		time_submitted = fromUnixTime(obj_json.time_submitted_1);
		current_info = '<b>'+obj_json.country_name_1+'</b> '+ time_submitted.getHours()+':'+time_submitted.getMinutes()+' UTC | ';
	}else{
		if(cached == true){
			if(epoch_time >= cache_display_time){
				pending_sub=false;
				reset_page = true;
				reset_comment = true;
				cached = false;//We resete cached after we display for the new element
				
				if(obj_json.comments_2.length > 0){
					pending_comment = true;
					last_displayed_comment=-1;
					
				}
				load_comments(obj_json.comments_2);

				var new_title = ($("#header_title h1").html().length >45) ? $("#header_title h1").html().substring(0,45) + '...' : $("#header_title h1").html();
				
				
				$("#comment_timer").attr("style", "");
				$("#comment_timer").html('');
						
				current_id = cache_div_id;
				current_title = cache_div_title;
				if($("#content_div_1").css("display") == 'none'){
					$("#content_div_1").css("display", "inline");
					$("#content_div_1").css("visibility", "visible");
					$("#content_div_2").css("display", "none");
					$("#content_div_2").css("visibility", "hidden");
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
					
					if(countdown > 60){
						$("#comment_timer").css("color", "#686868");
						hms = secondsToTime(countdown);
						$("#comment_timer").html('<span>Time remaining: </span><span>'+ hms.m + 'min ' +  hms.s + 's</span>');
					}else{
						$("#comment_timer").html('<span>Time remaining: </span><span>'+countdown.toString()+ ' seconds</span>');
						

						if(countdown > 12){
						
							$("#comment_timer  span:nth-child(2)").css("color", "black");//only the second span
							setTimeout(function (){$("#comment_timer  span:nth-child(2)").css("color", "white");},500);

						}else if(countdown > 10){						
							$("#comment_timer").css("color", "black");
							
						}else if(countdown > 2){
							$("#comment_timer").attr("style", "color:red");
							$("#comment_textarea").attr('disabled',true);
							
						}else{
							$("#comment_timer").attr("style", "");
							$("#comment_timer").html('Changing to next subject');
						}
						
						
					}
				}
			}
		}
	}
	
	if(cached==false && pending_sub ==true){
		
		cached = true;
		cache_div_id = obj_json.id_2;
		share_html = '<?php echo SiteHelper::share_links("'+obj_json.urn_2+'","'+obj_json.title_2+'"); ?>';
		cache_div_title = obj_json.title_2;
		if($("#content_div_1").css("display") == 'none'){
			$("#content_div_1").html(  '<?php echo SiteHelper::content_html("'+obj_json.content_html_2+'","'+obj_json.user_comment_2+'","'+share_html+'"); ?> ');
		}else{
			$("#content_div_2").html(  '<?php echo SiteHelper::content_html("'+obj_json.content_html_2+'","'+obj_json.user_comment_2+'","'+share_html+'"); ?> ');
		}
		
		time_submitted = fromUnixTime(obj_json.time_submitted_2);
		cache_div_info = '<b>'+obj_json.country_name_2+'</b> '+ time_submitted.getHours()+':'+time_submitted.getMinutes()+' UTC | ';
		cache_display_time = obj_json.display_time_2;
	
	}
	
	if(reset_page == true){
	
		$("#header_title h1").html(current_title);
		$("#header_info").html(current_info);

		$("#comment_textarea").attr('disabled',false);
				
	}

	
	
	reset_page=false;
	//Dont do the timeout if there are no more elements in the obj_json variable to be shown
	if(pending_sub == true) setTimeout(function (){display_elements(obj_json);},1000);
}



var interval_check;
get_Contents(function(){
	//Add this callback only the first time after we fetch content
	interval_check = setInterval("check_preload()",1000);	
});
