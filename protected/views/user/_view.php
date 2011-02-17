<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('username')); ?>:</b>
	<?php echo CHtml::encode($data->username); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('password')); ?>:</b>
	<?php echo CHtml::encode($data->password); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('email')); ?>:</b>
	<?php echo CHtml::encode($data->email); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ip_created')); ?>:</b>
	<?php echo CHtml::encode($data->ip_created); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ip_last_access')); ?>:</b>
	<?php echo CHtml::encode($data->ip_last_access); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('user_state_id')); ?>:</b>
	<?php echo CHtml::encode($data->user_state_id); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('user_type_id')); ?>:</b>
	<?php echo CHtml::encode($data->user_type_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('time_created')); ?>:</b>
	<?php echo CHtml::encode($data->time_created); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('time_last_access')); ?>:</b>
	<?php echo CHtml::encode($data->time_last_access); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('time_modified')); ?>:</b>
	<?php echo CHtml::encode($data->time_modified); ?>
	<br />

	*/ ?>

</div>