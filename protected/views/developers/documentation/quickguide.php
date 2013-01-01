<h1>Quick Start Guide</h1>
<?php echo "Last modified: " . date ("F d Y H:i:s.", filemtime(__FILE__));?><br>
<br>
<ol>
	<li><a href="#Introduction">Introduction</a></li>
	<li><a href="#Live_Info">Get Live Info - The 'Hello World' of Samesub</a></li>
	<li><a href="#Refreshing_data">Refreshing the Data</a></li>
	<li><a href="#Send_comment">Send a Comment to Live</a></li>
	<li><a href="#Add_subject">Add a Subject</a></li>
</ol>
<h2 id="Introduction">&nbsp;</h2>
	<h2>Introduction</h2>
	<p>
<br>
This Quick Start Guide covers the three most basic operations in the Samesub platform: get information about the current subject in LIVE(homepage), send a comment to the current subject in LIVE, 
and add a subject.
</p>
<p>
To get more details about these and other Api resources please click on the <?php echo CHtml::link('API documentation','api'); ?> link.
</p>
<p>By following the steps described here you will be able to interface to our 
platform quickly without having to enter into the details of every API 
resource. Dominating these three basic operations of the API will help you conquer 
all of the others, as these are the core and most important API operations.</p>
<p>Let's get started.</p>

<br><br>
<h2 id="Live_Info">Get Live Info - The 'Hello World' of Samesub</h2>
&nbsp;<p>Let's go right away with an example using Html and Javascript. Just 
copy the following code, save it in a .html file and then open it from a Web 
Browser. The example prints the current title and the time remaining.</p>
<?php
$source = "<html>
	<head>
		<script type=\"text/javascript\" src=\"http://code.jquery.com/jquery-latest.js\"></script>
		<script type=\"text/javascript\">
		function getLiveInfo(){
			$.getJSON('http://samesub.com/api/v1/live/getall?callback=?',
				{},
				function(data) {
					//If everything is ok(response_code == 200), let's print some LIVE information
					if(data.response_code == 200){
						$('#title').html(data.title);
						$('#time_remaining').html(data.time_remaining + ' seconds.');
					}else{
						//Otherwise alert data.response_message				
						alert(data.response_message);
					}
				}
			);
		}
		
		//When the page has loaded, we call our function
		$(document).ready(function() {
			getLiveInfo();
		});
		</script>
	</head>
	<body>
		<h1>My First Samesub API example</h1>
		<p>Current Title: <span id=\"title\"></span></p>
		<p>Time remaining: <span id=\"time_remaining\"></span></p>
	</body>
</html>";
$language = 'Javascript';
 
$geshi = new GeSHi($source, $language);
$geshi->enable_classes();
$geshi->set_overall_class('myjs_lines');
$geshi->set_header_type(GESHI_HEADER_PRE_TABLE);
$geshi->set_overall_style("padding:0 0 5px 0;border:1px solid #999999");
$geshi->set_tab_width(2);
$geshi->set_header_content("(<LANGUAGE>) Example - Getting current subject info in homepage(LIVE) using Jquery");
$geshi->set_header_content_style("background-color: #DFDFFF;font-size:14px;font-weight:bold;");

$geshi->enable_line_numbers(GESHI_NORMAL_LINE_NUMBERS);
$geshi->set_line_style('background: #fcfcfc;', true);

Yii::app()->clientScript->registerCss('highlightcode',$geshi->get_stylesheet());
echo $geshi->parse_code();
?>

<p>&nbsp;</p>
<p>Let's analyze the code step by step:</p>
<ol>
	<li>We include the <a href="http://jquery.com/">Jquery Javascript Framework</a> 
	to make our work more easy.</li>
<li>We use the 
<a href="api#live/getall">live/getall</a> API resource to get the information in live(homepage).</li>
	<li>For this example we have added a callback parameter to the url (notice
	<b>callback=?</b>) to prevent 'Same Origin' security policy error, since 
	this example runs in a Web Browser. Read
	<a href="http://en.wikipedia.org/wiki/JSONP">JSONp</a> for more. <b>NOTE</b>: 
	If your app does NOT run inside a Web Browser, you should NOT add this 
	parameter.</li>
	<li>After we make the ajax request to the 
<a href="api#live/getall">live/getall</a> resource, the response will return the 
	current data in a JSON format.
	<br /><br />
	<div class="flash-notice"><b>TIP</b>: You can get the response xml encoded by adding the 
		<b>response_format=xml</b> 
	parameter to the request URL. (ie: http://samesub.com/api/v1/live/getall?response_format=xml)
	</div>
	</li>
	<li>We examine <b><a href="api#global">response_code</a></b> returned value before we 
	do anything.</li>
	<li>We print the data in question.</li>
</ol>
<p>As you can see, this app is extremely simple, that's why we call it the 
&quot;Hello World&quot; of Samesub. Now let's modify the app and add some features to make 
it something more useful.</p>
<h3>&nbsp;</h3>
<h2 id="Refreshing_data">Refreshing the data</h2>
<p><br>
As you may know, subjects in the Live stream change every x seconds. We know how 
much time is left for the current subject by reading the <b>time_remaining</b> 
element in the response from the 
<a href="api#live/getall">live/getall</a> resource. When that time is over, the 
<a href="api#live/getall">live/getall</a> updates it's data automatically, and reflects 
data for the new subject. </p>
<p>Now we need that our app automatically refreshes when the <b>time_remaining</b> 
is over, and then print the new data from the new subject. So, let's make some 
changes to the function in our original example.</p>
<?php
$source = "
function getLiveInfo(){
	$.getJSON('http://samesub.com/api/v1/live/getall?callback=?',
		{},
		function(data) {
			//If everything is ok(response_code == 200), let's print some LIVE information
			if(data.response_code == 200){
				$('#title').html(data.title);
				$('#time_remaining').html(data.time_remaining + ' seconds.');
				var counter = data.time_remaining;
				var myInterval = setInterval(
					function(){
						if(counter < 1){ clearInterval(myInterval); getLiveInfo(); }
						counter = counter - 1;
						$('#time_remaining').html(counter + ' seconds.');
					}
				,1000);
			}else{
				//Otherwise alert data.response_message				
				alert(data.response_message);
			}
		}
	);
}
";
$language = 'Javascript';
 
$geshi = new GeSHi($source, $language);
$geshi->enable_classes();
$geshi->set_overall_class('myjs_no_lines');

$geshi->set_header_type(GESHI_HEADER_DIV);
$geshi->set_overall_style("padding:1px 15px 1px 15px;border:1px solid #999999;background: #fcfcfc;font: normal normal 1em/1.2em monospace;");
$geshi->set_tab_width(2);

//$geshi->enable_line_numbers(GESHI_NORMAL_LINE_NUMBERS);
//$geshi->set_line_style('background: #fcfcfc;', true);

Yii::app()->clientScript->registerCss('highlightcode2',$geshi->get_stylesheet());
echo $geshi->parse_code();
?>
<p>&nbsp;</p>
<ol>
	<li>We have added a <b>counter</b> variable initialized with the <b>time_remaining</b> 
	value.</li>
	<li>We have added an interval function that is called every 1 second.</li>
	<li>The interval function subtracts 1 to <b>counter</b> variable every time 
	it is called.</li>
	<li>When <b>counter</b> is less than 1 we clear the interval and call the <b>
	getLiveInfo</b> function again.</li>
	<li>The <b>getLiveInfo</b> gets the new data from the 
<a href="api#live/getall">live/getall</a> resource and the the whole process 
	repeats in a loop.</li>
</ol>
<p>&nbsp;</p>
<h3>Refreshing comment data</h3>
<p><br>
While the <b>time_remaining</b> 
	variable give us information about when the next subject is going to come, 
there is no way to know when the next comment is going to come as it can come in at any time. If you are displaying comments 
besides of the subject in your app, you should make a request 
	at least every 10 seconds on average (you don't know if in the next few 
	seconds there will be a new comment). But if you are just showing the 
	subject, then you should make a new request depending only on the <b>time_remaining</b> value 
as we've seen earlier. </p>
<p>So, let's modify our app function so that it also display comments:</p>
<p>First, we add a div element to our html body to hold the comments data. <br>
&nbsp;</p>

<?php
$source = "...
	<body>
		<h1>My First Samesub API example</h1>
		<p>Current Title: <span id=\"title\"></span></p>
		<p>Time remaining: <span id=\"time_remaining\"></span></p>
		<p>Comments:</p>
		<div id=\"comments_box\"></div>
	</body>
</html>";
$language = 'Javascript';
 
$geshi = new GeSHi($source, $language);
$geshi->enable_classes();
$geshi->set_overall_class('myjs_no_lines');

$geshi->set_header_type(GESHI_HEADER_DIV);
$geshi->set_overall_style("padding:1px 15px 1px 15px;border:1px solid #999999;background: #fcfcfc;font: normal normal 1em/1.2em monospace;");
$geshi->set_tab_width(2);

//$geshi->enable_line_numbers(GESHI_NORMAL_LINE_NUMBERS);
//$geshi->set_line_style('background: #fcfcfc;', true);

Yii::app()->clientScript->registerCss('highlightcode2',$geshi->get_stylesheet());
echo $geshi->parse_code();
?>

<p>&nbsp;</p>
<p>Now we modify our function:<br>
&nbsp;</p>
<?php

$source = "
var counter = 0;
var reset_comment = false;
var comment_id = 0;
var subject_id = 0;

function getLiveInfo(){
	$.getJSON('http://samesub.com/api/v1/live/getall?callback=?',
		{subject_id:subject_id, coment_id:comment_id},
		function(data) {
			//If everything is ok(response_code == 200), let's print some LIVE information
			if(data.response_code == 200){
				counter = data.time_remaining;
				if(data.new_sub != 0 || data.new_comment != 0 ){
					if(data.new_sub != 0) {
						//Every time there is a nuew sub, comments must be cleared
						reset_comment=true;
						comment_id = 0;	
						
						//Display subject
						subject_id = data.subject_id;
						$('#title').html(data.title);
						$('#time_remaining').html(data.time_remaining + ' seconds.');
					}
					if(data.new_comment != 0){
						comment_id = data.comment_id;
						show_comments(data.comments);
					}
				}
			}else{
				//Otherwise alert data.response_message				
				alert(data.response_message);
			}
		}
	);
	var aa = setTimeout('getLiveInfo()',8000);
}

function show_comments(comments){
	if(reset_comment == true) {
		$('#comments_box').html('');//Clear all previous comments
	}
	for(var i in comments) {
		$('#comments_box').prepend(comments[i]['comment_text']+ '<br />');
	}
}

var myInterval = setInterval(
	function(){
		counter = counter - 1;
		$('#time_remaining').html(counter + ' seconds.');
	}
,1000);
";
$language = 'Javascript';
 
$geshi = new GeSHi($source, $language);
$geshi->enable_classes();
$geshi->set_overall_class('myjs_no_lines');

$geshi->set_header_type(GESHI_HEADER_DIV);
$geshi->set_overall_style("padding:1px 15px 1px 15px;border:1px solid #999999;background: #fcfcfc;font: normal normal 1em/1.2em monospace;");
$geshi->set_tab_width(2);

//$geshi->enable_line_numbers(GESHI_NORMAL_LINE_NUMBERS);
//$geshi->set_line_style('background: #fcfcfc;', true);

Yii::app()->clientScript->registerCss('highlightcode2',$geshi->get_stylesheet());
echo $geshi->parse_code();
?>
<p>&nbsp;</p>
<p>As you can see there are several changes: </p>
<ol>
	<li>We have declared few global variables.</li>
	<li>We have splitted our function in three main parts: the <b>getLiveInfo</b> 
	function itself, the <b>show_comments</b> function, and the interval 
	function.</li>
	<li>Notice that now we are sending two data parameters to the <b>getJSON</b> 
	jquery function; the <b>subject_id</b> and <b>comment_id</b> parameters.</li>
	<li>Notice that the values we are sending in the two parameters are the same 
	values we are receiving from the server.</li>
	<li>The only way to know if there are newer comments than the last we 
	received is by telling the server which was the last <b>comment_id</b> we 
	received. The same goes for the <b>subject_id</b>.</li>
	<li>We know when a subject has changed if the <b>new_sub</b> value is 
	greater than 0 or the
	<b>subject_id</b> value in the response is different than the one you sent. 
	The same goes for
	<b>new_comment</b> and <b>comment_id</b>.</li>
	<li>When the subject has changed (<b>new_sub</b>) or there is a new comment (<b>new_comment</b>) 
	you should update your application screen with the new values respectively.</li>
</ol>
<p></p>
<p>The whole thing is to be in this loop waiting either for subs or comments, or both, and update your application screen as new information comes in. That's it.</p>
<p>&nbsp;</p>

<p>
<h2 id="Send_comment">Send comment to Live</h2>
<br>
To send a comment we use the live/sendcomment api resource.
<br>
<br>
<ol>
	<li>Make a request (either post or get) to
	http://samesub.com/api/v1/live/sendcomment.</li>
	<li>In the request simply add a parameter named d <b>text</b> with the value 
	you want to send as comment.</li>
	<li>That's it. </li>
</ol>
<p>Example:<br>
http://samesub.com/api/v1/live/sendcomment?text=Hello+world&anonymously=1</p>
<p>NOTE: See that we added a parameter called <b>anonymously</b> with value <b>1</b> to the request. That is because this api resource requires <a href="authentication">authentication</a> as you can see in the documentation <a href="api#live/sendcomment">here</a>, and we are authenticating as anonymous(guest) user in this case.
<p>&nbsp;</p>
<h2 id="Add_subject">Add a subject</h2>
<br>
To add a subject we use the subject/add api resource.
<br>
<br>
<ol>
	<li>Make a request (either post or get) to
	http://samesub.com/api/v1/subject/add with the values for the parameters of a 
	subject (i.e.: title, user_comment, content_type, etc). To see the list of 
	available parameters read the resource <a href="api#subject/add">subject/add</a> 
	documentation.</li>
	<li>You have to obtain a captcha image 
	http://samesub.com/api/v1/subject/captcha and show it to the user and add 
	the value entered by the user as a parameter called &quot;verifyCode&quot; to your request. Please read the <a href="api#subject/add">verifyCode</a> 
	parameter in the subject/add resource.</li>
	<li>Remember that you need to be <a href="authentication">authenticated</a> 
	for that subject to be associated with one specific user. Otherwise you can 
	add the subject as guest user with the globally available
	<a href="ap#global">anonymously</a> parameter.</li>
	<li>That's it. </li>
</ol>
<p>Example: The following example would do a post request to add a new subject 
titled &quot;Hello World&quot; with a text value of&nbsp; &quot;my first api test&quot;,&nbsp; as an 
&quot;anonymous&quot; user and with a captcha image with letters &quot;fachione&quot;, 
and a cookie value received for the captcha image equal to &quot;SSID=98d725fe19fb56b6e1777316931b76df&quot;<p>				<br><b>POST/GET</b>
				<br>http://samesub.com/api/v1/subject/add
				<br><b>POST/GET Data</b>
				<br>title=Hello+World&content_type=text&text=my+first+api+test&verifyCode=fachione&amp;anonymously=1<br><b>POST/GET Required Headers</b>
				<br>Cookie:SSID=98d725fe19fb56b6e1777316931b76df