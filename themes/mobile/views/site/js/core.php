<?php echo $this->renderPartial('js/core_lib'); ?>

//http://plugins.learningjquery.com/expander/index.html
(function($) {

  $.fn.expander = function(options) {

    var opts = $.extend({}, $.fn.expander.defaults, options);
    var delayedCollapse;
    return this.each(function() {
      var $this = $(this);
      var o = $.meta ? $.extend({}, opts, $this.data()) : opts;
     	var cleanedTag, startTags, endTags;	
     	var allText = $this.html();
     	var startText = allText.slice(0, o.slicePoint).replace(/\w+$/,'');
     	startTags = startText.match(/<\w[^>]*>/g);
   	  if (startTags) {startText = allText.slice(0,o.slicePoint + startTags.join('').length).replace(/\w+$/,'');}
   	  
     	if (startText.lastIndexOf('<') > startText.lastIndexOf('>') ) {
     	  startText = startText.slice(0,startText.lastIndexOf('<'));
     	}
     	var endText = allText.slice(startText.length);    	  
     	// create necessary expand/collapse elements if they don't already exist
   	  if (!$('span.details', this).length) {
        // end script if text length isn't long enough.
       	if ( endText.replace(/\s+$/,'').split(' ').length < o.widow ) { return; }
       	// otherwise, continue...    
       	if (endText.indexOf('</') > -1) {
         	endTags = endText.match(/<(\/)?[^>]*>/g);
          for (var i=0; i < endTags.length; i++) {

            if (endTags[i].indexOf('</') > -1) {
              var startTag, startTagExists = false;
              for (var j=0; j < i; j++) {
                startTag = endTags[j].slice(0, endTags[j].indexOf(' ')).replace(/(\w)$/,'$1>');
                if (startTag == rSlash(endTags[i])) {
                  startTagExists = true;
                }
              }              
              if (!startTagExists) {
                startText = startText + endTags[i];
                var matched = false;
                for (var s=startTags.length - 1; s >= 0; s--) {
                  if (startTags[s].slice(0, startTags[s].indexOf(' ')).replace(/(\w)$/,'$1>') == rSlash(endTags[i]) 
                  && matched == false) {
                    cleanedTag = cleanedTag ? startTags[s] + cleanedTag : startTags[s];
                    matched = true;
                  }
                };
              }
            }
          }

          endText = cleanedTag && cleanedTag + endText || endText;
        }
     	  $this.html([
     		startText,
     		'<span class="read-more">',
     		o.expandPrefix,
       		'<a href="#">',
       		  o.expandText,
       		'</a>',
        '</span>',
     		'<span class="details">',
     		  endText,
     		'</span>'
     		].join('')
     	  );
      }
      var $thisDetails = $('span.details', this),
        $readMore = $('span.read-more', this);
   	  $thisDetails.hide();
 	    $readMore.find('a').click(function() {
 	      $readMore.hide();

 	      if (o.expandEffect === 'show' && !o.expandSpeed) {
          o.beforeExpand($this);
 	        $thisDetails.show();
          o.afterExpand($this);
          delayCollapse(o, $thisDetails);
 	      } else {
          o.beforeExpand($this);
 	        $thisDetails[o.expandEffect](o.expandSpeed, function() {
            $thisDetails.css({zoom: ''});
            o.afterExpand($this);
            delayCollapse(o, $thisDetails);
 	        });
 	      }
        return false;
 	    });
      if (o.userCollapse) {
        $this
        .find('span.details').append('<span class="re-collapse">' + o.userCollapsePrefix + '<a href="#">' + o.userCollapseText + '</a></span>');
        $this.find('span.re-collapse a').click(function() {

          clearTimeout(delayedCollapse);
          var $detailsCollapsed = $(this).parents('span.details');
          reCollapse($detailsCollapsed);
          o.onCollapse($this, true);
          return false;
        });
      }
    });
    function reCollapse(el) {
       el.hide()
        .prev('span.read-more').show();
    }
    function delayCollapse(option, $collapseEl) {
      if (option.collapseTimer) {
        delayedCollapse = setTimeout(function() {  
          reCollapse($collapseEl);
          option.onCollapse($collapseEl.parent(), false);
          },
          option.collapseTimer
        );
      }
    }
    function rSlash(rString) {
      return rString.replace(/\//,'');
    }    
  };
    // plugin defaults
  $.fn.expander.defaults = {
    slicePoint:       100,  // the number of characters at which the contents will be sliced into two parts. 
                            // Note: any tag names in the HTML that appear inside the sliced element before 
                            // the slicePoint will be counted along with the text characters.
    widow:            4,  // a threshold of sorts for whether to initially hide/collapse part of the element's contents. 
                          // If after slicing the contents in two there are fewer words in the second part than 
                          // the value set by widow, we won't bother hiding/collapsing anything.
    expandText:       'read more', // text displayed in a link instead of the hidden part of the element. 
                                      // clicking this will expand/show the hidden/collapsed text
    expandPrefix:     '&hellip; ',
    collapseTimer:    0, // number of milliseconds after text has been expanded at which to collapse the text again
    expandEffect:     'fadeIn',
    expandSpeed:      '',   // speed in milliseconds of the animation effect for expanding the text
    userCollapse:     true, // allow the user to re-collapse the expanded text.
    userCollapseText: '[collapse expanded text]',  // text to use for the link to re-collapse the text
    userCollapsePrefix: ' ',
    beforeExpand: function($thisEl) {},
    afterExpand: function($thisEl) {},
    onCollapse: function($thisEl, byUser) {}
  };
})(jQuery);


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
		time_submitted = fromUnixTime(obj_json.time_submitted_2);
		submit_info = 'by <a href="<?php echo Yii::app()->getRequest()->getBaseUrl(true);?>/mysub/'+obj_json.username_2+'">' + obj_json.username_2 + '</a> at ' 
		+time_submitted.getHours()+':'+time_submitted.getMinutes()+' UTC';
		share_html = '<?php echo SiteHelper::share_links("'+obj_json.urn_2+'","'+obj_json.title_2+'"); ?>';
		cache_div_title = obj_json.title_2;
		if($("#content_div_1").css("display") == 'none'){
			$("#content_div_1").html(  '<?php echo SiteHelper::content_html("'+obj_json.content_html_2+'","'+obj_json.user_comment_2+'","'+submit_info+'","'+share_html+'"); ?> ');
		}else{
			$("#content_div_2").html(  '<?php echo SiteHelper::content_html("'+obj_json.content_html_2+'","'+obj_json.user_comment_2+'","'+submit_info+'","'+share_html+'"); ?> ');
		}
				
		cache_div_info = '<b>'+obj_json.country_name_2+'</b> UTC | ';
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
