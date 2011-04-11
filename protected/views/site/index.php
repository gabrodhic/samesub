<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN">
<html>
<head>
<meta http-equiv="Content-Language" content="en-us">
<title>Samesub</title>
<script type="text/javascript">
var baseUrl = "<?php echo $base_url;?>";

var element1 = document.createElement("link");
element1.type="text/css";
element1.rel = "stylesheet";
element1.href = baseUrl+"/css/core.css";
document.getElementsByTagName("head")[0].appendChild(element1);

var element2 = document.createElement("script");
element2.src = baseUrl+"/js/core.js";
element2.type="text/javascript";
document.getElementsByTagName("head")[0].appendChild(element2);

</script>
<style>
.modal_note{
	width: 800px;
	height: 200px;
	position: fixed;
	top: 50%;
	left: 50%;
	margin: -100px 0 0 -400px;
	/*background-color: grey;*/
	color: black;
	/*font-weight: bold;*/
	text-align: center; 
	font-size: 14px;
	font-family: Trebuchet MS, Arial, Helvetica, sans-serif;
	padding: 3px;
	z-index: 3000;
	/*top: expression((document.documentElement.scrollTop || document.body.scrollTop) + Math.round(17 * (document.documentElement.offsetHeight || document.body.clientHeight) / 100) + 'px');*/

}

</style>
</head>
<body>
<noscript>Your browser does NOT support javascript or has it disabled. Please click <?php echo CHtml::link('here',Yii::app()->getRequest()->getBaseUrl(true).'/index/noscript'); ?> if you want to use this site without javascript or enable the javascript feature in your browser and reload the page.</noscript>
<div id="notification" class="modal_note">
	<div style="font-size: 24px;">Welcome to samesub!</div>	
	<div style="margin-top:5px;"><?php echo $note;?></div>
	<div style="margin-top:100px">page is loading...</div>
</div>

<div id="page" style="display:none; position:absolute; top: 10px; right: 30px; left:30px">
<div id="header">
	<div id="header_top"></div>
	<div id="header_middle">
		<div id="logo" style="float:left;">HERE GOES THE LOGO INSIDE THIS DIV POSITION</div>
		<div id="main_menu">
			<div style="float:left;">
				<ul class="navigation" style="list-style: disc; margin-left: 30px; margin-bottom: 1em;">
					<li class="selected"><a href="<? echo $base_url;?>/">Live</a></li>
					<li><a href="<? echo $base_url;?>/subject/add">Add Subject</a></li>
					<li><a href="<? echo $base_url;?>/subject/manage">Manage</a></li>
				</ul>
			</div>
			<div style="float:right; padding:8px;" >
				<a href="<? echo $base_url;?>/feedback">Contact us</a>
			</div>
		</div>
	</div>
	<br class="clear_both">
	<div id="header_bottom">
		<div id="header_error">ERROR DIV</div>
		<div id="header_title"></div>
		<div id="header_info"></div>
	</div>
</div>
<div id="left_container">
	<div id="content_div"></div>
</div>
<div id="right_container">
	<div id="comments_container">
		<div>
			<h4>Send Comment</h4>
			<form id="CommentAddForm" method="post" action="/samesub/comment/add">		
			<div class="textwrapper"><textarea id="comment_textarea" name="Comment[comment]" rows="2"></textarea></div>
			</form>	
		</div>
		<div id="comments_board"></div>
	</div>
</div>
<br class="clear_both">
<div id="footer">CONTENT FOR FOOTER</div>

</div>

</body>
</html>

     