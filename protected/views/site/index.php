<?php
$this->pageTitle=$information->live;
?>
	<div id="header_bottom">
		<div id="header_error">ERROR DIV</div>
		<div id="header_title"><h1></h1></div>
		<div id="header_board">
			<div id="board_left">
				<div id="status_board">
					<div id="header_info"></div>
					<div id="comment_status"></div>
					<div id="comment_timer"></div>
				</div>
				<div id="comments_form" style="clear:left">
					<form id="CommentAddForm" method="post" action="<?php echo Yii::app()->getRequest()->getBaseUrl(true);?>/comment/add">		
						<div style="float:left"><textarea id="comment_textarea" name="Comment[comment]" rows="2"></textarea></div>
						<div id="send_comment" class="button">Send</div>
					</form>
				</div>
			</div>
			<div id="board_right">
				<div id="previous_subs">
					<div id="previous_left">Previous&nbsp;&nbsp;&nbsp;1-)<br>Subjects:&nbsp;&nbsp;2-)</div>
					<div id="previous_right"></div>
				</div>
				
			</div>
		</div>
	</div>
	<div id="left_container">
		<div id="content_div_1"></div>
		<div id="content_div_2" style="display:none; visibility:hidden;"></div>
	</div>
	<div id="right_container">
		<div id="comments_container">
			<div id="comments_title">Latest user comments:</div>
			<div id="comments_board"></div>
		</div>
	</div>