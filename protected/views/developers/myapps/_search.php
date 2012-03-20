<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'osr_id'); ?>
		<?php echo $form->textField($model,'osr_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'osr_usa_id_ref'); ?>
		<?php echo $form->textField($model,'osr_usa_id_ref'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'osr_consumer_key'); ?>
		<?php echo $form->textField($model,'osr_consumer_key',array('size'=>60,'maxlength'=>64)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'osr_consumer_secret'); ?>
		<?php echo $form->textField($model,'osr_consumer_secret',array('size'=>60,'maxlength'=>64)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'osr_enabled'); ?>
		<?php echo $form->textField($model,'osr_enabled'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'osr_status'); ?>
		<?php echo $form->textField($model,'osr_status',array('size'=>16,'maxlength'=>16)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'osr_requester_name'); ?>
		<?php echo $form->textField($model,'osr_requester_name',array('size'=>60,'maxlength'=>64)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'osr_requester_email'); ?>
		<?php echo $form->textField($model,'osr_requester_email',array('size'=>60,'maxlength'=>64)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'osr_callback_uri'); ?>
		<?php echo $form->textField($model,'osr_callback_uri',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'osr_application_uri'); ?>
		<?php echo $form->textField($model,'osr_application_uri',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'osr_application_title'); ?>
		<?php echo $form->textField($model,'osr_application_title',array('size'=>60,'maxlength'=>80)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'osr_application_descr'); ?>
		<?php echo $form->textArea($model,'osr_application_descr',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'osr_application_notes'); ?>
		<?php echo $form->textArea($model,'osr_application_notes',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'osr_application_type'); ?>
		<?php echo $form->textField($model,'osr_application_type',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'osr_application_commercial'); ?>
		<?php echo $form->textField($model,'osr_application_commercial'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'osr_issue_date'); ?>
		<?php echo $form->textField($model,'osr_issue_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'osr_timestamp'); ?>
		<?php echo $form->textField($model,'osr_timestamp'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->