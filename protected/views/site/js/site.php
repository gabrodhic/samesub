epoch_time = 0;
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
		"<?php echo Yii::app()->request->baseUrl;?>/subject/fetch",
		"time="+d.getTime(),
		function(){},
		function(json){
			
			
			if(epoch_time < json.current_time){
				epoch_time = json.current_time;//Update the epoch_time whenever the client time has get delayed
				//most probably that the clock also is slow, so lets update it also

			}
			
			$('#header_top_frame').contents().find('body').html('<?php echo Yii::t('site','LIVE NOW: {1}',array('{1}'=>'<a target="_top" style="color: #046381;" href="'.Yii::app()->createUrl('site/index').'">\' + json.title_1 + \'</a>'));?>');
			if(callback === undefined) {
				setTimeout(function (){$("#header_top_frame").contents().find('body, body a').css("color", "white");},500);
				setTimeout(function (){$("#header_top_frame").contents().find('body, body a').css("color", "");},1000);
				setTimeout(function (){$("#header_top_frame").contents().find('body, body a').css("color", "white");},1500);
				setTimeout(function (){$("#header_top_frame").contents().find('body, body a').css("color", "");},2000);
			}else{
				$('#header_top_frame').contents().find('head').append('<style> a, a:link, a:visited { text-decoration: none; color: #046381;font-weight: bold;  font-size: 13px;}a:hover { text-decoration: underline; }</style>');
				$('#header_top_frame').contents().find('body').attr('style','margin:3px 1px 1px 1px; border:0px;padding:0px;text-align:right; font: bold 13px Trebuchet MS, Arial, Helvetica, sans-serif; color: #686868;');
			}
			
			next_fetch = (json.display_time_2 - epoch_time);
			if(next_fetch < 0) next_fetch = 10;//If by any reason cron was not executed before time, then do next fetch in 10 seconds
			next_fetch = next_fetch + '000';
			var aa = setTimeout("get_Contents()", next_fetch);//(add the javascript milliseconds) Everythig loaded ok, lets make a new request to watch for new changes
			
		},
		function(){

			$("#header_top_frame").contents().find('body').html("<?php echo Yii::t('site','LIVE: There was an error getting data from the server to your device. Please check your internet connection. Retrying in 15 seconds.');?>");
			
			var bb = setTimeout(function(){$("#header_top_frame").contents().find('body').html(".")},10000);
			var bb = setTimeout("get_Contents()",15000);//There was an error loading content, lets make a new request to try to get content again
		}
	);
}
$(document).ready(function() {
epoch_timer();
get_Contents("firsttime");
});
