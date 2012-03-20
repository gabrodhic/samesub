<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'oauth-server-registry-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>
	
	<div class="row">
		<?php echo $form->labelEx($model,'osr_application_title'); ?>
		<?php echo $form->textField($model,'osr_application_title',array('size'=>60,'maxlength'=>80)); ?>
		<?php echo $form->error($model,'osr_application_title'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'osr_application_descr'); ?>
		<?php echo $form->textArea($model,'osr_application_descr',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'osr_application_descr'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'osr_consumer_key'); ?>
		<?php echo CHtml::encode($model->osr_consumer_key); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'osr_consumer_secret'); ?>
		<?php echo CHtml::encode($model->osr_consumer_secret); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'osr_enabled'); ?>
		<?php echo $form->CheckBox($model,'osr_enabled'); ?>
		<?php echo $form->error($model,'osr_enabled'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'osr_callback_uri'); ?>
		<?php echo $form->textField($model,'osr_callback_uri',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'osr_callback_uri'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'osr_application_uri'); ?>
		<?php echo $form->textField($model,'osr_application_uri',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'osr_application_uri'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'osr_application_notes'); ?>
		<?php echo $form->textArea($model,'osr_application_notes',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'osr_application_notes'); ?>
	</div>


	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->