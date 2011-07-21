<?php
$this->breadcrumbs=array(
	'Users'=>array('index'),
	'Welcome',
);

?>
<h1>Hi <b><?php echo Yii::app()->user->getName(); ?></b>, welcome back!</h1>
<?php if(Yii::app()->user->hasFlash('registration_success')){ ?>
<br>
<div class=flash-success>
	<?php echo Yii::app()->user->getFlash('registration_success') ?>
</div>
<?php } ?>
<p>

</p>
<p><br>
Our mission is that there be a unique point of union on the internet where all 
users connected to it can interact with one 'Same Subject' synchronously, 
creating a space where any person in the world can sync with the rest of the 
world in any given moment, making an impact in the way we stay in touch with the 
world, a way in which everybody adapts to one thing in common, a subject in 
common: Samesub.
<br><br>
Know that you can always help us to achieve this goal in any of one of following 
ways:<br>
With your visits.<br>
Sharing to your friends.<br>
With your submission of content.<br>
With your code contribution.
<br><br>
If you want to become a moderator, authorizer, or help the samesub team in any other
way, please write to us with the email you registered from.</p>
<p>
Thanks<br>
Sincerely<br>
Samesub Team<br>
www.samesub.com</p>
