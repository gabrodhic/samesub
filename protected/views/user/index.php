<h1><?php echo Yii::t('user','Hi {username}, welcome back!', array('{username}'=>'<b>'.Yii::app()->user->getName().'</b>'));?></h1>
<?php if(Yii::app()->user->hasFlash('registration_success')){ ?>
<br>
<div class=flash-success>
	<?php echo Yii::app()->user->getFlash('registration_success') ?>
</div>
<?php } ?>
<p>

</p>
<p><br>

<?php echo nl2br( Yii::t('user','Our mission is that there be a unique point of union on the internet where all 
users connected to it can interact with one Same Subject synchronously, 
creating a space where any person in the world can sync with the rest of the 
world in any given moment, making an impact in the way we stay in touch with the 
world, a way in which everybody adapts to one thing in common, a subject in 
common: Samesub.

Know that you can always help us to achieve this goal in any of one of following 
ways:
With your visits.
Sharing to your friends.
With your submission of content.
With your code contribution.

If you want to become a moderator, authorizer, or help the samesub team in any other
way, please write to us with the email you registered from.

Thanks
Sincerely
Samesub Team
www.samesub.com
'));?>