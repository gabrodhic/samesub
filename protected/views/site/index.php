<?php
$this->pageTitle=Yii::app()->name.": ".$information->live;
?>
	<div id="header_bottom">
		<div id="header_error">ERROR DIV</div>
		<div id="header_title"></div>
		<div style="background-color:white">
		<form id="CommentAddForm" method="post" action="<?php echo Yii::app()->getRequest()->getBaseUrl(true);?>/comment/add">		
				<div class="textwrapper"><textarea id="comment_textarea" name="Comment[comment]" rows="2"></textarea></div>
		</form>
		</div>
	</div>
	<div id="left_container">
		<div id="header_info"></div>
		<div id="content_div"></div>
	</div>
	<div id="right_container">
		<div id="comments_container">
			<div id="comments_board"></div>
		</div>
	</div>