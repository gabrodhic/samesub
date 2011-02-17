<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('user_id')); ?>:</b>
	<?php echo CHtml::encode($data->user_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('user_ip')); ?>:</b>
	<?php echo CHtml::encode($data->user_ip); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('user_comment')); ?>:</b>
	<?php echo CHtml::encode($data->user_comment); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('title')); ?>:</b>
	<?php echo CHtml::encode($data->title); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('urn')); ?>:</b>
	<?php echo CHtml::encode($data->urn); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('content_type_id')); ?>:</b>
	<?php echo CHtml::encode($data->content_type_id); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('content_state_id')); ?>:</b>
	<?php echo CHtml::encode($data->content_state_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('content_id')); ?>:</b>
	<?php echo CHtml::encode($data->content_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('country_id')); ?>:</b>
	<?php echo CHtml::encode($data->country_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('moderator_id')); ?>:</b>
	<?php echo CHtml::encode($data->moderator_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('moderator_ip')); ?>:</b>
	<?php echo CHtml::encode($data->moderator_ip); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('moderator_comment')); ?>:</b>
	<?php echo CHtml::encode($data->moderator_comment); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('time_submitted')); ?>:</b>
	<?php echo CHtml::encode($data->time_submitted); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('time_moderated')); ?>:</b>
	<?php echo CHtml::encode($data->time_moderated); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('priority_id')); ?>:</b>
	<?php echo CHtml::encode($data->priority_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('show_time')); ?>:</b>
	<?php echo CHtml::encode($data->show_time); ?>
	<br />

	*/ ?>

</div>