<?php $this->layout='//layouts/main';?>
<h1>Reset password</h1>

<?php if(Yii::app()->user->hasFlash('resetpassword_success')): ?>
<br>
<div class="flash-success">
	<?php echo Yii::app()->user->getFlash('resetpassword_success'); ?>
</div>


<?php else: ?>
<p>Please enter your email OR username, and we will send your new password to your email.</p>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>
	
	<br>
	<div class="row">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>25,'maxlength'=>50)); ?>
		<span id="email_verify" class="field_verify"></span>
		<?php echo $form->error($model,'email'); ?>
	</div>
	<p>Or</p>
	<div class="row">
		<?php echo $form->labelEx($model,'username'); ?>
		<?php echo $form->textField($model,'username',array('size'=>25,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'username'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Send'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
<?php endif; ?>