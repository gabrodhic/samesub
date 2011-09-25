<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'notification-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'enabled'); ?>
		<?php echo $form->DropDownList($model, 'enabled',array('0'=>'No', '1'=>'Yes')); ?>
		<?php echo $form->error($model,'enabled'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'fixed'); ?>
		<?php echo $form->DropDownList($model, 'fixed',array('0'=>'No', '1'=>'Yes')); ?>
		<?php echo $form->error($model,'fixed'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'notification_type_id'); ?>
		<?php echo $form->DropDownList($model, 'notification_type_id', CHtml::listData(Yii::app()->db->createCommand()
    ->select('*')
    ->from('notification_type')
    ->queryAll(),'id','name')); ?> 
		<?php echo $form->error($model,'notification_type_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'message'); ?>
		<?php echo $form->textField($model,'message',array('size'=>60,'maxlength'=>250)); ?>
		<?php echo $form->error($model,'message'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->