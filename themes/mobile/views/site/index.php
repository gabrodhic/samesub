<?php
$this->pageTitle=$information->live;
?>
	<div id="header_bottom">
		<div id="header_error">ERROR DIV</div>
		<div id="header_title"><h1></h1></div>
		<div id="contents_container">
			<div id="content_div_1" class="user_input"></div>
			<div id="content_div_2" class="user_input" style="display:none; visibility:hidden;"></div>
		</div>
		<div id="header_board">
			
				<div id="status_board">
					<div id="header_info"></div>
					
					<div id="comment_timer"></div>
				</div>
				<br class="clear_both">
				<div id="comments_form">
					<form id="CommentAddForm" method="post" action="" onsubmit="javascript:send_comment();">		
						<input type="text" id="comment_textarea" name="Comment[comment]"></input>
						<span id="send_comment"><a href="javascript:send_comment();">Send</a></span>
						<div id="comment_status"></div>
					</form>
				</div>
			
		</div>
	</div>

	<div id="comments_container">		
		<div id="comments_board" class="user_input"></div>
	</div>
