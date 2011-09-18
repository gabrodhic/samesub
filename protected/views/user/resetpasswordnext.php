<?php $this->layout='//layouts/main';?>
<h1>Change user password</h1>
<?php if(Yii::app()->user->hasFlash('changepass_success')): ?>
<br>
<div class=flash-success>
	<?php echo Yii::app()->user->getFlash('changepass_success') ?>
</div>
<?php else: ?>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'newpassword'); ?>
		<?php echo $form->passwordField($model,'newpassword',array('size'=>25,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'newpassword'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'newpassword2'); ?>
		<?php echo $form->passwordField($model,'newpassword2',array('size'=>25,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'newpassword2'); ?>
	</div>



	<div class="row buttons">
		<?php echo CHtml::submitButton('Change Password'); ?>
	</div>

<?php $this->endWidget(); ?>
</div><!-- form -->
<?php endif; ?>