<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'subject-form',
	'enableAjaxValidation'=>false,'htmlOptions'=>array('enctype' => 'multipart/form-data'),
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>250)); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'content_type_id'); ?>
		<?php echo $form->DropDownList($model, 'content_type_id', CHtml::listData(ContentType::model()->findAll(),'id','name')); ?> 
		<?php echo $form->error($model,'content_type_id'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'image'); ?>		
		<?php echo $form->FileField($model, 'image');?>
		<?php echo $form->error($model,'image'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'text'); ?>
		<?php echo $form->textArea($model,'text',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'text'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'video'); ?>
		<?php echo $form->textArea($model,'video',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'video'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'user_comment'); ?>
		<?php echo $form->textArea($model,'user_comment',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'user_comment'); ?>
	</div>


	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->