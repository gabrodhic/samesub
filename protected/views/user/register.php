<?php $this->layout='//layouts/main';?>
<h1>Register user account</h1>

<?php if(Yii::app()->user->hasFlash('registration_success')): ?>
<br>
<div class="flash-success">
	<?php echo Yii::app()->user->getFlash('registration_success'); ?>
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
		<?php echo $form->labelEx($model,'username'); ?>
		<?php echo $form->textField($model,'username',array('size'=>25,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'username'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>25,'maxlength'=>50)); ?>
		<span id="email_verify" class="field_verify"></span>
		<?php echo $form->error($model,'email'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'password'); ?>
		<?php echo $form->passwordField($model,'password',array('size'=>25,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'password'); ?>
	</div>



	<div class="row buttons">
		<?php echo CHtml::submitButton('Register'); ?>
	</div>

<?php $this->endWidget(); ?>
<script>
$("#User_email").keyup(function (){ $("#email_verify").text($("#User_email").val());});

</script>
</div><!-- form -->
<?php endif; ?>