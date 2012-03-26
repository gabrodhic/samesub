<h1>Application Authentication</h1>
<?php echo "Last modified: " . date ("F d Y H:i:s.", filemtime(__FILE__));?><br><br>
<p>

</p>
<p>This page describes the process to get your application authenticated on the Samesub 
platform by using the OAuth protocol.</p>
<br>
You will need to authenticate your application(and a user) whenever you want to perform an action as some user in specific.
<br><br>
NOTE: You can always authenticate and perform an action as an anonymous(guest) user if you want. In that case, there is no need to go trough the whole process of authentication, you can simply use the <?php echo CHtml::link('anonymously','api#global'); ?> parameter in all of your app requests that require authentication. The system will allow you to perform the action but the request will be seen as from anonymous user(obviously).
<br>
<br>
<br>
<h3>If you are new to OAuth&nbsp; please read these steps first</h3>
<p>&nbsp;</p>
<p>The first step to OAuth is to read what is all about. This article
<a href="http://marktrapp.com/blog/2009/09/17/oauth-dummies">OAuth for Dummies</a> 
is very simple and clear explaining what is all about.<br>
The second step is to test what you've already learned. With this
<a href="http://term.ie/oauth/example/client.php">OAuth Test Client</a> and
<a href="http://term.ie/oauth/example/index.php">OAuth Test Server</a> you can 
start testing.<br>
The third step then is to get a library for the language or framework you are 
going to use. Here is a list of available libraries
<a href="http://oauth.net/code/">http://oauth.net/code/</a><br>
After you've done this three steps then you are ready to go.</p>
<p>&nbsp;</p>
<h3>Implementing OAuth for Samesub platform</h3>
<p>&nbsp;</p>
<p>To get your consumer_key and consumer_secret complete the <?php echo CHtml::link('Create Application','../myapps'); ?>
 form (you need to have a Samesub <?php echo CHtml::link('user account',array('user/register')); ?> before 
creating and application). After that, you are ready to start using all the API 
Resources that need authentication.</p>
<p>All API requests to oauth/* resources must use HTTPS instead of HTTP.</p>
<p>Know that xAuth is enabled in the oauth/access_token endpoint, so you can 
take advantage of it and use it if you want and give a better user experience to 
the users. If your library does not have it, you simply have to add the 
following 3 parameters to your oauth/access_token request<br>
x_auth_username = the user's username<br>
x_auth_password = the user's password<br>
x_auth_mode = &quot;client_auth&quot;</p>
<p>Using xAuth eliminates 2 steps (request_token and authorize), and you can go 
directly to the oauth/access_token step of the OAuth authentication protocol and 
give better native client login interface to the users.</p>
<p>The authentication service is based on OAuth 1.0a. You can&nbsp; see the 
specification here <a href="http://oauth.net/core/1.0a/">
http://oauth.net/core/1.0a/</a> and here <a href="http://oauth.net/code/">
http://oauth.net/code/</a> there is a list of libraries available to download 
for the&nbsp; different programming languages</p>
<p>&nbsp;</p>

