<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'username'); ?>
		<?php echo $form->textField($model,'username',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'username'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'password'); ?>
		<?php echo $form->passwordField($model,'password',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'password'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ip_created'); ?>
		<?php echo $form->textField($model,'ip_created',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'ip_created'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ip_last_access'); ?>
		<?php echo $form->textField($model,'ip_last_access',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'ip_last_access'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'user_status_id'); ?>
		<?php echo $form->textField($model,'user_status_id'); ?>
		<?php echo $form->error($model,'user_status_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'user_type_id'); ?>
		<?php echo $form->textField($model,'user_type_id'); ?>
		<?php echo $form->error($model,'user_type_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'time_created'); ?>
		<?php echo $form->textField($model,'time_created'); ?>
		<?php echo $form->error($model,'time_created'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'time_last_access'); ?>
		<?php echo $form->textField($model,'time_last_access'); ?>
		<?php echo $form->error($model,'time_last_access'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'time_modified'); ?>
		<?php echo $form->textField($model,'time_modified'); ?>
		<?php echo $form->error($model,'time_modified'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->