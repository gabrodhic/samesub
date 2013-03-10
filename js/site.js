//Update of js/site.js file should be made upon modification of this file.
//This file is generated from another path: /site/js/site

String.prototype.format = function() {
  var args = arguments;
  return this.replace(/{(\d+)}/g, function(match, number) { 
    return typeof args[number] != 'undefined'
      ? args[number]
      : match
    ;
  });
};

epoch_time = 0;
next_fetch = 0;
function epoch_timer(){

	epoch_time = epoch_time+1;
	
	//Now lets check if we are reaching the time to change to the next subject
	//If so, then set a timer and disable the comments textarea

	tick_timer=window.setTimeout("epoch_timer()",1000);
}

function ajax_request(url,data,bsend,suc,err,method,dt){
	if ( method === undefined ) { method = "GET"; }
	if ( dt === undefined ) { dt = "json"; }
	$.ajax({type:method,password:"123",url:url,data:data,dataType:dt,beforeSend:bsend,success:suc,error:err});
}

var interval_check;

function get_Contents(callback){
	var d = new Date();
	ajax_request(
		ssBaseUrl+"/api/v1/live/getall",
		"time="+d.getTime(),
		function(){},
		function(json){
			window.getallData = json;//This variable can be used sitewide on different pages, so that only one ajax request is used for most things.
			
			if(epoch_time < json.current_time){
				epoch_time = json.current_time;//Update the epoch_time whenever the client time has get delayed
				//most probably that the clock also is slow, so lets update it also

			}			
			$('#header_top_frame').contents().find('body').html('<a target="_top" style="color: #046381;" href="'+ ssBaseUrl + '/site/index">' + ssLang.site.liveNowTitle.format(json.title) + '</a>');
			if(callback === undefined) {
				setTimeout(function (){$("#header_top_frame").contents().find('body, body a').css("color", "white");},500);
				setTimeout(function (){$("#header_top_frame").contents().find('body, body a').css("color", "");},1000);
				setTimeout(function (){$("#header_top_frame").contents().find('body, body a').css("color", "white");},1500);
				setTimeout(function (){$("#header_top_frame").contents().find('body, body a').css("color", "");},2000);
			}else{				
				$('#header_top_frame').contents().find('head').append('<style> a, a:link, a:visited { text-decoration: none; color: #046381;font-weight: bold;  font-size: 13px;}a:hover { text-decoration: underline; }</style>');
				$('#header_top_frame').contents().find('body').attr('style','margin:3px 1px 1px 1px; border:0px;padding:0px;text-align:left; font: bold 13px Trebuchet MS, Arial, Helvetica, sans-serif; color: #686868;');
				
				if(callback == "firsttime"){
					$('#top_page').css("width","100%");
					if( typeof json.session_username !== 'undefined' ) {
						$('#top_page_menu_right1').html('<a href="'+ ssBaseUrl + '/profile/'+json.session_username+'"><img style="vertical-align:middle" src="'+ json.session_userimage + '" width="20" height="20"></a> <a href="'+ ssBaseUrl + '/mysub/'+json.session_username+'">'+json.session_username+'</a> | <a href="'+ ssBaseUrl + '/subject/add">'+ssLang.site.uploadSubject+'</a> | ');
						$('#top_page_menu_right2 ul li ul').append('<li><a href="'+ ssBaseUrl + '/site/logout">'+ssLang.site.logout+'</a></li>');
					}
				}
			}
			
			next_fetch = json.time_remaining + 5;//let's give an extra 5 seconds in case of robot delay to prevent 2 requests
			if(next_fetch < 0) next_fetch = 10;//If by any reason cron was not executed before time, then do next fetch in 10 seconds
			next_fetch = next_fetch + '000';
			var aa = setTimeout("get_Contents()", next_fetch);//(add the javascript milliseconds) Everythig loaded ok, lets make a new request to watch for new changes
			
			//Update all contents in different pages on the site that need this notification change
			//ie:
			if(typeof window.refresh_timeboard == 'function') {
				refresh_timeboard();
			}
		},
		function(){

			$("#header_top_frame").contents().find('body').html(ssLang.site.errorGettingData);
			
			var bb = setTimeout(function(){$("#header_top_frame").contents().find('body').html(".")},10000);
			var bb = setTimeout("get_Contents()",15000);//There was an error loading content, lets make a new request to try to get content again
		}
	);
}
$(document).ready(function() {
epoch_timer();
//Setup searchbox
$("#search_icon").click(function () {		
	$("#search_box").css({display:'inline'});
	$("#search_box").animate({width: '150px'});
	$("#search_box").focus();
});
$("#search_box").blur(function () {
	$(this).css({width: '1px'});
	$("#search_box").css({display:'none'});
});

get_Contents("firsttime");
});