<h1>Quick Guide</h1>
<?php echo "Last modified: " . date ("F d Y H:i:s.", filemtime(__FILE__));?><br><br>
<p>

</p>
<p>
This Quick Guide only covers the three most basic operations in the Samesub platform: get information about the current subject in LIVE, send a comment to the current subject in LIVE, 
and add a subject.
</p>
<p>
To get more details about these and other api resources please click on the <?php echo CHtml::link('API documentation','api'); ?> link.
</p>
<p>Following the steps described here you will be able to interface to our 
platform quickly without having to enter into the details of every API 
resource. Dominating these three basic operations of the API will help you conquer 
all of the others, as these are the core and most important API operations.</p>
<p>Let's start with the first one:</p>

<br><br>
<h3>Get Live Information</h3>
&nbsp;<p>We will use the live/getall API resource to get information in live.<br>
</p>
<ol style="margin-top: 20px;">
	<li>Make a request to the following URL: http://api.samesub.com/v1/live/getall</li>
	<li>The response will return all the current data related to live (homepage) 
	in a JSON encoded format. <br>
	NOTE: you can get the response xml encoded by adding the <b>response_format=xml</b> 
	parameter to the request URL. (ie: http://api.samesub.com/v1/live/getall?response_format=xml)</li>
	<li>Decode the returned JSON/XML response string and look for 
	the following items as appropriate:<br>
	<b><br>
	subject_id</b>: The id of the subject.<b><br>
	<br>
	title</b>: The title of the subject.<br>
	<b><br>
	urn</b>: Its the URN (Uniform Resource Name) of the subject. It can be 
	used generate the <a href="http://en.wikipedia.org/wiki/Permalink">permalink</a> 
	(Permanent Link) of&nbsp; the subject. ie http://www.samesub.com/sub/<b>urn-returned-value-goes-right-here</b><br>
	<b><br>
	content_type</b>: The type of content. i.e.: image, text, video.<br>
	<b><br>
	content_html</b>: This is the actual content in html format. This is what 
	you are going to present as the subject in your application.<br>
	<b><br>
	content_data</b>: This is the same content as in <b>content_html</b> but 
	its raw in this case and is an array of element. You use <b>content_type </b>to know how to handle 
	that data. For example, if its an image&nbsp; then you would search for <b>url</b>(the 
	url of the image) sub element of <b>content_data</b> array. If its text you 
	would search for <b>text</b> sub element. If its video you would search 
	for the <b>embed_code</b> sub element, and so on.<br>
	<b><br>
	comment_id</b>: This is the id of last comment received in the server for 
	the subject in live. It should be the same as the last item in the <b>comments</b> 
	array. You make all 
	subsequent requests after the first one, with a <b>comment_id</b> param with the value you 
	received in the last response so that the server knows if you are updated or 
	not and don't have to send you all the comments you already have. i.e. :&nbsp; http://api.samesub.com/v1/live/getall?subject_id=356&amp;comment_id=568<br>
	<b><br>
	new_comment</b>: Indicates how many new comments are from the 
	<b>comment_id</b> you sent in the request parameter.<br>
	<b><br>
	new_sub</b>: Indicates(0 or 1) if there is a new subject different than 
	the one you already have. You tell the server which subject you have by 
	sending the subject_id parameter with the last value you received. i.e.: 
	http://api.samesub.com/v1/live/getall?subject_id=463<br>
	<b><br>
	comments</b>: These are the comments as an array that the subject has received from 
	other users. Each comment has the following properties: username, comment_text, comment_id, comment_time, comment_country<b>.<br>
	</b>NOTE: If you don't receive a item its because you don't need it, you have 
	the last information of that item, so the server saves bandwidth and doesn't 
	send you that item.<b><br>
	<br>
	user_comment</b>: This is the comment that the user who uploaded the content 
	has placed in the subject. You should present this information as part of 
	the content.<b><br>
	<br>
	user_id</b>: The id of the user who uploaded the content.<b><br>
	<br>
	username</b>: The username of the user who uploaded the content.<b><br>
	<br>
	current_time</b>: Its the current
	<a href="http://en.wikipedia.org/wiki/Coordinated_Universal_Time">UTC</a>.<br>
	<b><br>
	time_remaining</b>: Is the time remaining in seconds for the subject to 
	change to a new one. If you are running your application and you don't want 
	to get the comments as they come in, then you would make a new request when 
	time remaining gets to cero (by having a countdown counter in your app). 
	Other way (if you will be showing the comments in your application) you 
	should make a request every five seconds, to see if there are new comments 
	and show them.<br>
	<b><br>
	priority</b>: The priority that has the subject. i.e.: low, medium, high, 
	urgent.<br>
	<b><br>
	permalink</b>: The permanent link to access the subject when it goes away 
	from live.<br>
&nbsp;</li>
	<li>All subsequent request to the live/getall api resource should include 
	the
	<b>comment_id</b> and
	<b>subject_id</b> parameters with the last values you received respectively in 
	your last response. i.e: http://api.samesub.com/v1/live/getall?subject_id=356&amp;comment_id=568</li>
	<li>You know when a subject has changed if: the
	<b>subject_id</b> value in the response is different than the one you sent 
	parameter or if <b>new_sub</b> is not 0.</li>
	<li>You know if there is a new comment if:
	<b>new_comment</b> in the response is greater than 0.</li>
	<li>When the subject has changed(<b>new_sub</b>) or there is a new comment(<b>new_comment</b>) 
	you should update your application screen with the new values received.</li>
</ol>
<p>&nbsp;</p>
<p>&nbsp;</p>

<p>
<h3>Send comment to Live</h3>
<br>
<ol>
	<li>Make a request (either post or get) to
	http://api.samesub.com/v1/live/sendcomment.</li>
	<li>In the request simply add a parameter named <b>text</b> with the value 
	you want to send as comment.</li>
	<li>That's it. </li>
</ol>
<p>Example:<br>
http://api.samesub.com/v1/live/sendcomment?text=Hello+world&anonymously=1</p>
<h3>&nbsp;</h3>
<p>&nbsp;</p>
<h3>Add a subject</h3>
<br>
<ol>
	<li>Make a request (either post or get) to
	http://api.samesub.com/v1/subject/add with the values for the parameters of a 
	subject (i.e.: title, user_comment, content_type, etc). To see the list of 
	available parameters read the resource <a href="api#subject/add">subject/add</a> 
	documentation.</li>
	<li>You have to obtain a captcha image 
	http://api.samesub.com/v1/subject/captcha and show it to the user and add 
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
				<br>http://api.samesub.com/v1/subject/add
				<br><b>POST/GET Data</b>
				<br>title=Hello+World&content_type=text&text=my+first+api+test&verifyCode=fachione&amp;anonymously=1<br><b>POST/GET Required Headers</b>
				<br>Cookie:SSID=98d725fe19fb56b6e1777316931b76df