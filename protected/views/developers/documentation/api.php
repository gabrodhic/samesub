<h1>API Documentation</h1>
<?php echo "Last modified: " . date ("F d Y H:i:s.", filemtime(__FILE__));?><br><br>
<p>

</p>
<p>In this documentation you'll find the description of all the API resources 
in the Samesub platform.</p>
<p>&nbsp;</p>
<style>
table tr td{ border-bottom:1px solid #E0E0E0;}
</style>
<table border="0" width="100%" id="table1">
	<tr>
		<td>
		<h3>Common</h3>
		<p>Common has a set of resources that don't belong to one category or 
		section in specific and that can be used to help you use other 
		resources.</td>
	</tr>
	<tr>
		<td><a href="#common/getcountries">common/getcountries</a></td>
	</tr>
	<tr>
		<td><a href="#common/gettime">common/gettime</a></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>
		<h3>&nbsp;</h3>
		<h3>Live</h3>
		<p>Live gives access to all resources (subject, comment, user, etc) 
		related to the current sub in the homepage. Its kind of a shortcut when 
		you want to send or receive information related to live(homepage).</td>
	</tr>
	<tr>
		<td><a href="#live/getall">live/getall</a></td>
	</tr>
	<tr>
		<td><a href="#live/sendcomment">live/sendcomment</a></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>
		<h3>&nbsp;</h3>
		<h3>OAuth</h3>
		<p>We implement OAuth 1.0a as our authentication protocol. For API 
		resources that need authentication, you'll need to sign your API 
		requests with OAuth or with the anonymous parameter. Please read the <a href="authentication">
		Application Authentication</a> process also. </td>
	</tr>
	<tr>
		<td><a href="#oauth/access_token">oauth/access_token</a></td>
	</tr>
	<tr>
		<td><a href="#oauth/request_token">oauth/request_token</a></td>
	</tr>
	<tr>
		<td>
		<a href="#oauth/authorize">oauth/authorize</a>&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>
		<h3>&nbsp;</h3>
		<h3>Subject</h3>
		<p>Resources that allow you interact with subjects. </td>
	</tr>
	<tr>
		<td><a href="#subject/add">subject/add</a></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>
		<h3>&nbsp;</h3>
		<h3>Comment</h3>
		<p>Resources that allow you interact with comments. </td>
	</tr>
	<tr>
		<td><a href="#comment/vote">comment/vote</a></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>
		<h3>&nbsp;</h3>
		<h3>Global (parameters and return values)</h3>
		<p>These are Parameters or Return Values available in all API resources. You can 
		use these parameters or return values in any of the API resources you 
		use when appropriate.</td>
	</tr>
	<tr>
		<td><a href="#global">response_format</a></td>
	</tr>
	<tr>
		<td><a href="#global">error</a></td>
	</tr>
	<tr>
		<td><a href="#global">error_message</a></td>
	</tr>
	<tr>
		<td><a href="#global">ok_message</a></td>
	</tr>
	<tr>
		<td><a href="#global">anonymously</a></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
</table>

<br>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<table border="0" width="100%" id="table30">
	<tr>
		<td>
		<h2><a name="common/getcountries"></a></h2>
		<p>&nbsp;</p>
		<h2>common/getcountries</h2>
		<p>Get the list of countries in the <a href="http://en.wikipedia.org/wiki/ISO_3166-1">
		ISO 3166-1</a> standard.<h3>&nbsp;</h3>
		<h3>Resource URL</h3>
		<p>http://samesub.com/api/v1/common/getcountries</p>
		<h3>&nbsp;</h3>
		<h3>Resource Information</h3>
		<p>Authentication Required: <a href="authentication">No</a><br>
		Supports Anonymity: <a href="#global">Yes</a><h3>&nbsp;</h3>
		<h3>Parameters</h3>
		</td>
	</tr>
	<tr>
		<td>
		N/A</td>
	</tr>
	<tr>
		<td>
		<h3>&nbsp;</h3>
		<h3>Return Values</h3>
		<p>This resource returns an array with the list of counties. Each item has the following elements.</p>
		<p>NOTE: There is a country named WORLDWIDE (WW). As you might already 
		know its not a country, any subject that is independent of a country 
		should have this country assigned.</p></td>
	</tr>
	<tr>
		<td>
		<table border="1" width="100%" id="table32">
			<tr>
				<th>
				<h4>Name</h4>
				</th>
				<th>
				<h4>Type</h4>
				</th>
				<th>
				<h4>Description</h4>
				</th>
			</tr>
			<tr>
				<td width="20%">
	<b>code</b></td>
				<td width="10%">string</td>
				<td>The <a href="http://en.wikipedia.org/wiki/ISO_3166-1">ISO 
				3166-1</a> two letters country code. i.e.: US, GB, JP, FR, ES, 
				RU</td>
			</tr>
			<tr>
				<td>
	<b>name</b></td>
				<td>string</td>
				<td>The <a href="http://en.wikipedia.org/wiki/ISO_3166-1">ISO 
				3166-1</a> English country name. i.e.: United States, United 
				Kingdom, Japan, France, Spain, Russian Federation</td>
			</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	</table>
<p>&nbsp;</p>
<p>&nbsp;</p>
<table border="0" width="100%" id="table35">
	<tr>
		<td>
		<h2><a name="common/gettime"></a></h2>
		<h2>&nbsp;</h2>
		<h2>common/gettime</h2>
		<p>Get the the current UTC time and the time remaining in Live(homepage) 
		for the next subject to come.<h3>&nbsp;</h3>
		<h3>Resource URL</h3>
		<p>http://samesub.com/api/v1/common/gettime</p>
		<h3>Resource Information</h3>
		<p>Authentication Required: <a href="authentication">No</a><br>
		Supports Anonymity: <a href="#global">Yes</a><h3>&nbsp;</h3>
		<h3>Parameters</h3>
		</td>
	</tr>
	<tr>
		<td>
		N/A</td>
	</tr>
	<tr>
		<td>
		<h3>&nbsp;</h3>
		<h3>Return Values</h3>
		<p>&nbsp;</p></td>
	</tr>
	<tr>
		<td>
		<table border="1" width="100%" id="table36">
			<tr>
				<th>
				<h4>Name</h4>
				</th>
				<th>
				<h4>Type</h4>
				</th>
				<th>
				<h4>Description</h4>
				</th>
			</tr>
			<tr>
				<td width="20%">
	<b>current_time</b></td>
				<td width="10%">int</td>
				<td>Its the current
	<a href="http://en.wikipedia.org/wiki/Coordinated_Universal_Time">UTC</a> 
				timestamp.</td>
			</tr>
			<tr>
				<td><b>current_time_h</b></td>
				<td>int</td>
				<td>Current UTC hour in 24 hour format.</td>
			</tr>
			<tr>
				<td><b>current_time_m</b></td>
				<td>int</td>
				<td>Current UTC minute.</td>
			</tr>
			<tr>
				<td><b>current_time_s</b></td>
				<td>int</td>
				<td>Current UTC second.</td>
			</tr>
			<tr>
				<td><b>time_remaining</b></td>
				<td>int</td>
				<td>Time remaining in seconds to change to next sub in 
				Live(homepage).</td>
			</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	</table>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<table border="0" width="100%" id="table2">
	<tr>
		<td>
		<h2><a name="live/getall"></a></h2>
		<h2>&nbsp;</h2>
		<h2>live/getall</h2>
		<p>Get all the information available in Live (homepage). This includes 
		the subject content, comments, user, etc.<h3>&nbsp;</h3>
		<h3>Resource URL</h3>
		<p>http://samesub.com/api/v1/live/getall</p>
		<h3>&nbsp;</h3>
		<h3>Resource Information</h3>
		<p>Authentication Required: <a href="authentication">No</a><br>
		Supports Anonymity: <a href="#global">Yes</a><h3>&nbsp;</h3>
		<h3>Parameters</h3>
		</td>
	</tr>
	<tr>
		<td>
		<table border="1" width="100%" id="table3">
			<tr>
				<th>
				<h4>Name</h4>
				</th>
				<th>
				<h4>Type</h4>
				</th>
				<th>
				<h4>Description</h4>
				</th>
			</tr>
			<tr>
				<td style="width:20%">
	<b>comment_id<br>
				</b>(optional)</td>
				<td style="width:10%">int</td>
				<td>This is the id of last comment you have received. You make all 
	subsequent requests after the first one, with a <b>comment_id</b> parameter with the value you 
	received in the last response so that the server knows if you are updated or 
	not and don't have to send you all the comments you already have. i.e. :&nbsp; http://samesub.com/api/v1/live/getall?subject_id=356&amp;comment_id=568</td>
			</tr>
			<tr>
				<td>
	<b>subject_id<br>
				</b>(optional)</td>
				<td>int</td>
				<td>The id of the subject you last received. You can leave empty 
				or 0, meaning its your first request. You make all subsequent 
				requests after the first one, with the <b>subject_id</b> 
				parameter with the value you received in the last response so 
				that the server knows if you are updated or not and don't have 
				to send you all the information you already have.</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td>
		<h3>&nbsp;</h3>
		<h3>Return Values</h3>
		<p>*NOTE: The resource live/getall might not return all the values 
		always (when you don't need them, usually this is determined depending 
		on the parameters you send) to save network data traffic.</p></td>
	</tr>
	<tr>
		<td>
		<table border="1" width="100%" id="table4">
			<tr>
				<th>
				<h4>Name</h4>
				</th>
				<th>
				<h4>Type</h4>
				</th>
				<th>
				<h4>Description</h4>
				</th>
			</tr>
			<tr>
				<td width="20%">
	<b>content_data</b></td>
				<td width="10%">array</td>
				<td>This is the same content as in <b>content_html</b> but 
	its raw in this case and is an array of element. You use <b>content_type </b>to know how to handle 
	that data. For example, if its an image&nbsp; then you would search for <b>url</b>(the 
	url of the image) sub element of <b>content_data</b> array. If its text you 
	would search for <b>text</b> sub element. If its video you would search 
	for the <b>embed_code</b> sub element, and so on.</td>
			</tr>
			<tr>
				<td>
	<b>content_html</b></td>
				<td>string</td>
				<td>This is the actual content in html format. This is what you 
				are going to present as the subject in your application.</td>
			</tr>
			<tr>
				<td>
	<b>content_type</b></td>
				<td>string</td>
				<td>The type of content.<p>Possible values: image, text, video.</td>
			</tr>
			<tr>
				<td>
	<b>comments</b></td>
				<td>array</td>
				<td>These are the comments(as an array) that the subject has 
				received from other users. Each comment has the following 
				properties: username, comment_text, comment_id, comment_time, comment_country<b>.<br>
				<br>
				</b>NOTE: If you don't receive an item its because you don't need it, you 
				already have 
	that item. Server determines that by the <b>comment_id</b> parameter you 
				send in the request, only sending you comments with an id 
				greater than that. By deduction it is supposed that you have all 
				comments lower than <b>comment_id</b.<br>
	&nbsp;</td>
			</tr>
			<tr>
				<td>
	<b>comment_id</b></td>
				<td>int</td>
				<td>This is the id of last comment received in the server for 
				the subject in live. It should be the same as the last item in 
				the <b>comments</b> 
	array. You make all 
	subsequent requests after the first one, with a <b>comment_id</b> param with the value you 
	received in the last response so that the server knows if you are updated or 
	not and don't have to send you all the comments you already have. i.e. :&nbsp; http://samesub.com/api/v1/live/getall?subject_id=356&amp;comment_id=568</td>
			</tr>
			<tr>
				<td>
	<b>country_code</b></td>
				<td>string</td>
				<td>The <a href="http://en.wikipedia.org/wiki/ISO_3166-1">ISO 
				3166-1</a> two letters country code. i.e.: US, GB, JP, FR, ES, 
				RU</td>
			</tr>
			<tr>
				<td>
	<b>country_name</b></td>
				<td>string</td>
				<td>The <a href="http://en.wikipedia.org/wiki/ISO_3166-1">ISO 
				3166-1</a> English country name. i.e.: United States, United 
				Kingdom, Japan, France, Spain, Russian Federation</td>
			</tr>
			<tr>
				<td><b>current_time</b></td>
				<td>int</td>
				<td>Its the current
	<a href="http://en.wikipedia.org/wiki/Coordinated_Universal_Time">UTC</a> 
				timestamp.</td>
			</tr>
			<tr>
				<td><b>new_comment</b></td>
				<td>int</td>
				<td>Indicates how many new comments are from the 
	<b>comment_id</b> you sent in the request parameter.</td>
			</tr>
			<tr>
				<td>
	<b>new_sub</b></td>
				<td>int</td>
				<td>Indicates(0 or 1) if there is a new subject different than 
				the one you already have. You tell the server which subject you 
				have by sending the subject_id parameter with the last value you received. i.e.: 
	http://samesub.com/api/v1/live/getall?subject_id=463</td>
			</tr>
			<tr>
				<td>
	<b>priority</b></td>
				<td>string</td>
				<td>The priority that has the subject.<p>Possible values are:&nbsp; low, medium, high, 
				urgent.</td>
			</tr>
			<tr>
				<td><b>permalink</b></td>
				<td>string</td>
				<td>The permanent link to access the subject when it goes away 
				from live.</td>
			</tr>
			<tr>
				<td>
	<b>subject_id</b></td>
				<td>int</td>
				<td>The id of the subject..</td>
			</tr>
			<tr>
				<td><b>title</b></td>
				<td>string</td>
				<td>The title of the subject.</td>
			</tr>
			<tr>
				<td><b>time_remaining</b></td>
				<td>int</td>
				<td>Time remaining in seconds to change to next sub.</td>
			</tr>
			<tr>
				<td><b>user_comment</b></td>
				<td>string</td>
				<td>This is the comment that the user who uploaded the content 
				has placed in the subject. You should present this information 
				as part of the content.</td>
			</tr>
			<tr>
				<td><b>user_id</b></td>
				<td>int</td>
				<td>The id of the user who uploaded the content.</td>
			</tr>
			<tr>
				<td>
	<b>urn</b></td>
				<td>string</td>
				<td>Its the URN (Uniform Resource Name) of the subject. It can 
				be used generate the <a href="http://en.wikipedia.org/wiki/Permalink">permalink</a> 
	(Permanent Link) of&nbsp; the subject. ie http://www.samesub.com/sub/<b>urn-returned-value-goes-right-here</b></td>
			</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	</table>
<p>&nbsp;</p>
<p>&nbsp;</p>
<table border="0" width="100%" id="table8">
	<tr>
		<td>
		<h2><a name="live/sendcomment"></a></h2>
		<h2>&nbsp;</h2>
		<h2>live/sendcomment</h2>
		<p>Send a comment to the Live subject.<h3>&nbsp;</h3>
		<h3>Resource URL</h3>http://samesub.com/api/v1/live/sendcomment<h3>&nbsp;</h3>
		<h3>Resource Information</h3>
		<p>Authentication Required: <a href="authentication">Yes</a><br>
		Supports Anonymity: <a href="#global">Yes</a><h3>&nbsp;</h3>
		<h3>Parameters</h3>
		</td>
	</tr>
	<tr>
		<td>
		<table border="1" width="100%" id="table9">
			<tr>
				<th>
				<h4>Name</h4>
				</th>
				<th>
				<h4>Type</h4>
				</th>
				<th>
				<h4>Description</h4>
				</th>
			</tr>
			<tr>
				<td style="width:20%"><b>text</b></td>
				<td style="width:10%">string</td>
				<td>This is the comment you want to send.</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td>
		<h3>&nbsp;</h3>
		<h3>Return Values</h3>
		<pN/A</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
</table>
<p>&nbsp;</p>
<table border="0" width="100%" id="table17">
	<tr>
		<td>
		<h2><a name="oauth/access_token"></a></h2>
		<h2>&nbsp;</h2>
		<h2>oauth/access_token</h2>This API resource implements the oauth/access_token service. Note: 
		We support XAuth also, you should make your XAuth requests to this API 
		resource.<h3>&nbsp;</h3>
		<h3>Resource URL</h3>
		<p>http://samesub.com/api/oauth/access_token</p>
		<h3>&nbsp;</h3>
		<h3>Description</h3>
		</td>
	</tr>
	<tr>
		<td>After you've successfully obtained your request token by consuming 
		the oauth/request_token API resource then you make a call to this 
		resource to get your access token and secret which you'll use to sign 
		all further requests for API resources that need authentication.<p>NOTE: 
		You can also make a XAuth request to this API resource and get your access token and secret 
		directly without having to accomplish oauth/request_token step. We always want to give all Samesub users the best user experience&nbsp; 
		and the easiest and fastest access to the Samesub platform by whatever it is the application they 
		use.</p>
		<p>Please read the  <a href="authentication">Application Authentication</a> 
		process to fully understand the whole process to authenticate in the 
		Samesub platform.</p>
		<p>Note: This API resource is based on OAuth 1.0a. You can&nbsp; see the 
		specification here <a href="http://oauth.net/core/1.0a/">
		http://oauth.net/core/1.0a/</a> and here
		<a href="http://oauth.net/code/">http://oauth.net/code/</a> there is a 
		list of libraries available to download for the&nbsp; different 
		programming languages.</td>
	</tr>
</table>
<p>&nbsp;</p>
<p>&nbsp;</p>
<table border="0" width="100%" id="table18">
	<tr>
		<td>
		<h2><a name="oauth/request_token"></a></h2>
		<h2>&nbsp;</h2>
		<h2>oauth/request_token</h2>
		<p>This API resource implements the oauth/request_token service.<h3>Resource URL</h3>http://samesub.com/api/oauth/request_token<h3>&nbsp;</h3>
		<h3>Description</h3>
		</td>
	</tr>
	<tr>
		<td>The oauth/request_token is the first step if you are not going in 
		the XAuth way, it is the endpoint to get your request token.<p>Please read the <a href="authentication">Application Authentication</a> 
		process to fully understand the whole process to authenticate in the 
		Samesub platform.</p>
		<p>Note: This API resource is based on OAuth 1.0a. You can&nbsp; see the 
		specification here <a href="http://oauth.net/core/1.0a/">
		http://oauth.net/core/1.0a/</a> and here
		<a href="http://oauth.net/code/">http://oauth.net/code/</a> there is a 
		list of libraries available to download for the&nbsp; different 
		programming languages.</td>
	</tr>
</table>
<p>&nbsp;</p>
<table border="0" width="100%" id="table19">
	<tr>
		<td>
		<h2><a name="oauth/authorize"></a></h2>
		<h2>&nbsp;</h2>
		<h2>oauth/authorize</h2>This API resource implements the oauth/authorize service.<h3>Resource URL</h3>
		<p>http://samesub.com/api/oauth/authorize</p>
		<h3>&nbsp;</h3>
		<h3>Description</h3>
		</td>
	</tr>
	<tr>
		<td>This is not a step itself its just the URL used for authorization 
		after the oauth/request_token step.<p>Please read the <a href="authentication">Application Authentication</a> 
		process to fully understand the whole process to authenticate in the 
		Samesub platform.</p>
		<p>Note: This API resource is based on OAuth 1.0a. You can&nbsp; see the 
		specification here <a href="http://oauth.net/core/1.0a/">
		http://oauth.net/core/1.0a//</a> and here
		<a href="http://oauth.net/code/">http://oauth.net/code/</a> there is a 
		list of libraries available to download for the&nbsp; different 
		programming languages.</td>
	</tr>
</table>
<p>&nbsp;</p>
<p>&nbsp;</p>
<table border="0" width="100%" id="table22">
	<tr>
		<td>
		<h2><a name="subject/add"></a></h2>
		<h2>&nbsp;</h2>
		<h2>subject/add</h2>Add a subject. This resource gives the equivalent functionality of 
		the <a href="http://samesub.com/subject/add">http://samesub.com/subject/add</a>&nbsp; 
		page.<h3>&nbsp;</h3>
		<h3>Resource URL</h3>http://samesub.com/api/v1/subject/add<h3>&nbsp;</h3>
		<h3>Resource Information</h3>
		<p>Authentication Required: <a href="authentication">Yes</a><br>
		Supports Anonymity: <a href="#global">Yes</a><p>&nbsp;</td>
	</tr>
	<tr>
		<td>
		<h3>&nbsp;</h3>
		<h3>Parameters</h3>
		</td>
	</tr>
	<tr>
		<td>
		<table border="1" width="100%" id="table23">
			<tr>
				<th>
				<h4>Name</h4>
				</th>
				<th>
				<h4>Type</h4>
				</th>
				<th>
				<h4>Description</h4>
				</th>
			</tr>
			<tr>
				<td style="width:20%"><b>title</b></td>
				<td style="width:10%">string</td>
				<td>The title of the subject.</td>
			</tr>
			<tr>
				<td style="width:20%"><b>content_type</b></td>
				<td style="width:10%">string</td>
				<td>The type of content.<p>Possible values are: image, text, video.</p>
				<p>NOTE: 
				Depending on the type of content you send, you should send the 
				respective content. That means that image, text, or video, 
				parameters become automatically not optional depending on the 
				content type you selected.</td>
			</tr>
			<tr>
				<td style="width:20%"><b>verifyCode</b></td>
				<td style="width:10%">string</td>
				<td>A CAPTCHA verification code submitted by the user. <p>To obtain a CAPTCHA image you should call http://samesub.com/api/v1/subject/captcha</p><p>Note that you should check for the Set-Cookie RESPONSE HEADER named SSID ("Set-Cookie:SSID=*****"), and use that as a HEADER in the http://samesub.com/api/v1/subject/add REQUEST</p>
				<p>Example:<br> You show to the user a subject/add form with all the required fields(ie:title,content_type,etc). In that same form you show a captcha image by invoking http://samesub.com/api/v1/subject/captcha.
				<br>In the response of the image request you get the image that shows these letters "aryhoma". Behind the scene, in that same request you get a "Set-Cookie:SSID=98d725fe19fb56b6e1777316931b76df;"  RESPONSE HEADER. Then, the user completes the form and enters the verifyCode(aryhoma) as he sees on the image you are showing him/her, and he/she submits the form.<br>
				<br>In the submission of the form you have to add to the request the HEADER Cookie:SSID=98d725fe19fb56b6e1777316931b76df and besides that a parameter named "verifyCode" with the value the user entered in the form(in this case the correct value would be 'aryhoma')
				<br><br>This would be the final result of this example:
				<br><b>POST/GET</b>
				<br>http://samesub.com/api/v1/subject/add
				<br><b>POST/GET Data</b>
				<br>title=Hello+World&content_type=text&text=this+is+just+an+api+test&verifyCode=aryhoma
				<br><b>POST/GET Request Required Headers</b>
				<br>Cookie:SSID=98d725fe19fb56b6e1777316931b76df</td>
			</tr>
			<tr>
				<td>
	<b>category<br>
	</b>(optional)</td>
				<td>string</td>
				<td>The category in which to categorize the subject.<p>NOTE: You 
				can get the list of categories available by making a call to http://samesub.com/api/v1/subject/getcategories</td>
			</tr>
			<tr>
				<td>
	<b>country_code<br>
	</b>(optional)</td>
				<td>string</td>
				<td>The <a href="http://en.wikipedia.org/wiki/ISO_3166-1">ISO 
				3166-1</a> two letters country code. i.e.: US, GB, JP, FR, ES, 
				RU<p>NOTE: You can use http://samesub.com/api/v1/common/getcountries 
				to get a list of all countries. Code WW means worldwide.</td>
			</tr>
			<tr>
				<td><b>image<br>
				</b>(optional)</td>
				<td>file</td>
				<td>This is the image parameter you need to send if you selected 
				&quot;image&quot; in the &quot;content_type&quot; parameter. This is the image file 
				you want to send if you want to upload it from the device making 
				the REQUEST. This paramenter and the &quot;image_url&quot; parameter are 
				interchangeable, you can use any depending on what you want, 
				upload the image or use a URL of an online image.<p>NOTE: 
				Remember that you have to set Content-Type to 
				&quot;multipart/form-data&quot; in the POST request to be able to send the 
				image file.</td>
			</tr>
			<tr>
				<td><b>image_url<br>
				</b>(optional)</td>
				<td>string</td>
				<td>This is the image parameter you need to send if you selected 
				&quot;image&quot; in the &quot;content_type&quot; parameter. This is the image url. 
				You should use this option if you want to use an image already 
				uploaded in some URL on the internet.</td>
			</tr>
			<tr>
				<td>
	<b>priority<br>
				</b>(optional)</td>
				<td>string</td>
				<td>The priority for the subject. 
				<p>Possible values are:&nbsp; low, medium, high, 
				urgent.</td>
			</tr>
			<tr>
				<td><b>tag<br>
				</b>(optional)</td>
				<td>string</td>
				<td>The list of tag words for the subject.<p>NOTE: You can get a 
				tag suggestions service on the subject/gettags resource. You 
				should send a parameter named &quot;tag&quot; with the value typed by the 
				user. i.e.:A REQUEST to http://samesub.com/api/v1/subject/gettags?tag=ca 
				can have a RESPONSE like this list: &quot;car&quot;,&quot;castle&quot;,&quot;california&quot;,&quot;camera&quot;</td>
			</tr>
			<tr>
				<td><b>time<br>
				</b>(optional)</td>
				<td>int</td>
				<td>This is the timestamp in UTC indicating the desired time 
				that the user want to watch the subject in Live(homepage). </td>
			</tr>
			<tr>
				<td><b>text<br>
				</b>(optional)</td>
				<td>string</td>
				<td>This is the text parameter you need to send if you selected 
				&quot;text&quot; in the &quot;content_type&quot; parameter.</td>
			</tr>
			<tr>
				<td><b>user_comment<br>
				</b>(optional)</td>
				<td>string</td>
				<td>This is the comment that the user who uploads the content 
				places in the subject.</td>
			</tr>
			<tr>
				<td><b>video<br>
				</b>(optional)</td>
				<td>string</td>
				<td>This is the video parameter you need to send if you selected 
				&quot;video&quot; in the &quot;content_type&quot; parameter. This can be either the 
				embed code for the video or simply the URL of the video.<p>NOTE: 
				Currently we only support URL format for youtube, vimeo and 
				dailymotion. All other video source should use the embed code 
				format.</td>
			</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
		<h3>&nbsp;</h3>
		<h3>Return Values</h3>
		<p>N/A</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
</table>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<table border="0" width="100%" id="table8">
	<tr>
		<td>
		<h2><a name="comment/vote"></a></h2>
		<h2>&nbsp;</h2>
		<h2>comment/vote</h2>
		<p>Vote for a specific comment, like or dislike.<h3>&nbsp;</h3>
		<h3>Resource URL</h3>http://samesub.com/api/v1/comment/vote<h3>&nbsp;</h3>
		<h3>Resource Information</h3>
		<p>Authentication Required: <a href="authentication">Yes</a><br>
		Supports Anonymity: <a href="#global">No</a><h3>&nbsp;</h3>
		<h3>Parameters</h3>
		</td>
	</tr>
	<tr>
		<td>
		<table border="1" width="100%" id="table9">
			<tr>
				<th>
				<h4>Name</h4>
				</th>
				<th>
				<h4>Type</h4>
				</th>
				<th>
				<h4>Description</h4>
				</th>
			</tr>
			<tr>
				<td style="width:20%"><b>comment_id</b></td>
				<td style="width:10%">int</td>
				<td>The id of the comment.</td>
			</tr>
			<tr>
				<td style="width:20%"><b>vote</b></td>
				<td style="width:10%">string</td>
				<td>The vote value. Either like or dislike.</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td>
		<h3>&nbsp;</h3>
		<h3>Return Values</h3>
		<pN/A</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
</table>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p><table border="0" width="100%" id="table24">
	<tr>
		<td>
		<h2><a name="global"></a></h2>
		<h2>&nbsp;</h2>
		<h2>Global (parameters and return values)</h2>Parameters or Return Values available in all API resources. You can 
		use these parameters or return values in any of the API resources you 
		use.<p>&nbsp;</td>
	</tr>
	<tr>
		<td>
		<h3>&nbsp;</h3>
		<h3>Parameters</h3>
		</td>
	</tr>
	<tr>
		<td>
		<table border="1" width="100%" id="table25">
			<tr>
				<th>
				<h4>Name</h4>
				</th>
				<th>
				<h4>Type</h4>
				</th>
				<th>
				<h4>Description</h4>
				</th>
			</tr>
			<tr>
				<td style="width:20%"><b>response_format<br>
				</b>(optional)</td>
				<td style="width:10%">string</td>
				<td>How do you want the response encoded. <b>json </b>(default) or 
				<b>xml</b>.</td>
			</tr>
			<tr>
				<td><b>anonymously<br>
				</b>(optional)</td>
				<td>int</td>
				<td>With the anonymously parameter, you can execute an action as a anonymous(guest) user, and thus you don't have to authenticate. 
				This parameter when set to 1 allows you to do just that. 
				Possible values 0 or 1. Default value 0.<p>NOTE: If you are logged in and you send a request and you 
				are sending this parameter = 1, it will take the the logged in 
				user id, and not the anonymous one for any transaction in the 
				system.</td>
			</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td>
		<h3>&nbsp;</h3>
		<h3>Return Values</h3>
		</td>
	</tr>
	<tr>
		<td>
		<table border="1" width="100%" id="table26">
			<tr>
				<th>
				<h4>Name</h4>
				</th>
				<th>
				<h4>Type</h4>
				</th>
				<th>
				<h4>Description</h4>
				</th>
			</tr>
			<tr>
				<td style="width:20%"><b>error
				</b></td>
				<td style="width:10%">int</td>
				<td>This is the error number when an error occurs. Defaults to 0, meaning there is no error. You should always check if this value is different than 0, for each response you receive.</td>
			</tr>
			<tr>
				<td><b>error_message<br>
				</b>(optional)</td>
				<td>string</td>
				<td>Error message when a error occurs. Defaults to 'empty' meaning there is no error message.</td>
			</tr>
			<tr>
				<td><b>ok_message<br>
				</b>(optional)</td>
				<td>string</td>
				<td>Message returned when there are no retuned values expected. Defaults to 'empty' meaning there is no ok message.</td>
			</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
</table>
