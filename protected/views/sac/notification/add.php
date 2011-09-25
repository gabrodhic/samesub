<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'notification-add-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'fixed'); ?>
		<?php echo $form->textField($model,'fixed'); ?>
		<?php echo $form->error($model,'fixed'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'message'); ?>
		<?php echo $form->textField($model,'message'); ?>
		<?php echo $form->error($model,'message'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'enabled'); ?>
		<?php echo $form->textField($model,'enabled'); ?>
		<?php echo $form->error($model,'enabled'); ?>
	</div>


	<div class="row buttons">
		<?php echo CHtml::submitButton('Submit'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->