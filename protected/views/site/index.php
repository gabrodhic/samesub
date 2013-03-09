<?php
$this->pageTitle=Yii::app()->name . ' - '.Yii::t('site','ssTitleDescription');
?>
	<div id="header_error"></div>
	<div id="header_board">
		<div id="board_left">			
				<div id="header_title">
					<div><h1></h1></div>
				</div>							
				<div id="submit_info"></div>			
		</div>
		<div id="board_right">
			<div id="time_remaining_box">
				<div class="time_remaining_encloser"></div>
				<div id="comment_timer">00:00</div>
				<div class="time_remaining_encloser"></div>
			</div>		
		</div>
	</div>
	
	<div id="left_container">		
		<div id="left_content_container">
			<div id="left_content_container_outter">
				<div id="left_content_container_inner">
					<div id="left_content_container_inside">
						<div id="left_content_div" class="content"></div>
					</div>
				</div>
			</div>
		</div>
		<div id="content_footer">
			<div id="subject_voting" style="float:right;"></div>
			<div id="perma_link"></div>
			<div id="share_links"></div>
			
		</div>
		<div class="clear_both"></div>
		<div id="comment_status"></div>
		<div class="clear_both"></div>
		<div id="comments_form" style="clear:left">
			<form id="CommentAddForm" method="post" action="<?php echo Yii::app()->getRequest()->getBaseUrl(true);?>/comment/add">
				<div style="float:left">
					<textarea id="comment_textarea" name="Comment[comment]" rows="2"></textarea><div id="send_comment"><a><? echo Yii::t('site','Send');?></a></div>
				</div>
			</form>
		</div>		
		<div class="clear_both"></div>
		<div id="comments_title"><? echo Yii::t('site','Latest user comments:');?></div>		
		<div class="clear_both"></div>
		<div id="comments_container">			
			<div id="comments_board" class="user_input"></div>
		</div>
	</div>
	<div id="right_container">
		<div id="live_now_box">LIVE NOW
		</div>
		<div id="right_content_container">
			<div id="country_info"></div>		
			<div id="right_content_div" class="user_input"></div>
		</div>
	</div>