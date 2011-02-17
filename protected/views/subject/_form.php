<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'subject-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'user_id'); ?>
		<?php echo $form->textField($model,'user_id'); ?>
		<?php echo $form->error($model,'user_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'user_ip'); ?>
		<?php echo $form->textField($model,'user_ip',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'user_ip'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'user_comment'); ?>
		<?php echo $form->textArea($model,'user_comment',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'user_comment'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>250)); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'urn'); ?>
		<?php echo $form->textField($model,'urn',array('size'=>60,'maxlength'=>250)); ?>
		<?php echo $form->error($model,'urn'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'content_type_id'); ?>
		<?php echo $form->textField($model,'content_type_id'); ?>
		<?php echo $form->error($model,'content_type_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'content_state_id'); ?>
		<?php echo $form->textField($model,'content_state_id'); ?>
		<?php echo $form->error($model,'content_state_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'content_id'); ?>
		<?php echo $form->textField($model,'content_id'); ?>
		<?php echo $form->error($model,'content_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'country_id'); ?>
		<?php echo $form->textField($model,'country_id'); ?>
		<?php echo $form->error($model,'country_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'moderator_id'); ?>
		<?php echo $form->textField($model,'moderator_id'); ?>
		<?php echo $form->error($model,'moderator_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'moderator_ip'); ?>
		<?php echo $form->textField($model,'moderator_ip',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'moderator_ip'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'moderator_comment'); ?>
		<?php echo $form->textField($model,'moderator_comment',array('size'=>60,'maxlength'=>250)); ?>
		<?php echo $form->error($model,'moderator_comment'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'time_submitted'); ?>
		<?php echo $form->textField($model,'time_submitted'); ?>
		<?php echo $form->error($model,'time_submitted'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'time_moderated'); ?>
		<?php echo $form->textField($model,'time_moderated'); ?>
		<?php echo $form->error($model,'time_moderated'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'priority_id'); ?>
		<?php echo $form->textField($model,'priority_id'); ?>
		<?php echo $form->error($model,'priority_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'show_time'); ?>
		<?php echo $form->textField($model,'show_time'); ?>
		<?php echo $form->error($model,'show_time'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->