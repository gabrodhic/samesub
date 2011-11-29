<br>
<h1>System Administration Console - SAC</h1>
<br>
<p>
Links to the various system administration options.
</p>
	<br>
	<div class="row">
		<?php echo CHtml::link('Texts Manager',Yii::app()->createUrl('sac/text')); ?> 
	</div>
	<br>
	<div class="row">
		<?php echo CHtml::link('Notification Messages',Yii::app()->createUrl('sac/notification')); ?> 
	</div>
	<br>
	<div class="row">
		<?php echo CHtml::link('System Log',Yii::app()->createUrl('sac/log')); ?> 
	</div>
	<br>
	<div class="row">
		<?php echo CHtml::link('User Administration',Yii::app()->createUrl('user/admin')); ?> 
	</div>
	<br>
	<div class="row">
		<?php echo CHtml::link('Manage Subjects',Yii::app()->createUrl('subject/manage')); ?> 
	</div>
	<br>
	<div class="row">
		<?php echo CHtml::link('Mail Messages',Yii::app()->createUrl('sac/mail')); ?> 
	</div>
	<br>
	<div class="row">
		<?php echo CHtml::link('Subject Categories',Yii::app()->createUrl('subjectCategory')); ?> 
	</div>
	<br>
	<div class="row">
		<?php echo CHtml::link('WebSite Stats Analyzer','http://samesub.com/cgi-bin/awstats/awstats.pl?config=samesub.com'); ?> 
	</div>
	