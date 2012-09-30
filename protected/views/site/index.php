<?php
$this->pageTitle=$information->live;
?>
	<div id="header_bottom">
		<div id="header_error">ERROR DIV</div>
		<div id="time_remaining_box">
			<div><?php echo Yii::t('site','Time remaining:');?></div>
			<div id="comment_timer">.. ..</div>
		</div>
		<div id="header_title">
			<div><h1></h1></div>
		</div>
		<div class="clear_both"></div>
		<div id="header_board">
			<div id="board_left">
				<div id="share_links"></div>
				<div id="header_info"></div>
				<div id="submit_info"></div>
				<div id="perma_link">PERMALINK</div>
			</div>
			<div id="board_right">
				<div id="comments_title"><? echo Yii::t('site','Latest user comments:');?></div>
				<div id="comment_status"></div>
				<a id="send_comment"><? echo Yii::t('site','Send');?></a>
				<div class="clear_both"></div>
				<div id="comments_form" style="clear:left">
					<form id="CommentAddForm" method="post" action="<?php echo Yii::app()->getRequest()->getBaseUrl(true);?>/comment/add">		
						<div style="float:left"><textarea id="comment_textarea" name="Comment[comment]" rows="2"></textarea></div>
						
					</form>
				</div>
			</div>
		</div>
	</div>
	<div id="left_container">		
		<div id="content_div_1" class="user_input"></div>
		<div id="content_div_2" class="user_input" style="display:none; visibility:hidden;"></div>
	</div>
	<div id="right_container">
		<div id="comments_container">			
			<div id="comments_board" class="user_input"></div>
		</div>
	</div>