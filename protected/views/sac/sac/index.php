<h1>System Administration Console - SAC</h1>

<p>
Links to the various system administration options.
</p>
	<div class="row">
		<?php echo CHtml::link('Notification Messages',Yii::app()->createUrl('sac/notification')); ?> 
	</div>
	<div class="row">
		<?php echo CHtml::link('System Log',Yii::app()->createUrl('sac/log')); ?> 
	</div>
	<div class="row">
		<?php echo CHtml::link('User Administration',Yii::app()->createUrl('user/admin')); ?> 
	</div>
	<div class="row">
		<?php echo CHtml::link('Manage Subjects',Yii::app()->createUrl('subject/manage')); ?> 
	</div>
		<div class="row">
		<?php echo CHtml::link('Mail Messages',Yii::app()->createUrl('sac/mail')); ?> 
	</div>