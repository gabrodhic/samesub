<?php
$this->pageTitle=Yii::app()->name . ' - Login';
$this->breadcrumbs=array(
	'Login',
);
?>
<div style="float:left;">
<h1>Login</h1>
<br><br>


	<div class="form">
	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'login-form',
		'enableAjaxValidation'=>true,
	)); ?>


		<div class="row">
			<?php echo $form->labelEx($model,'email'); ?>
			<?php echo $form->textField($model,'email',array('style'=>'width: 170px;')); ?>
			<?php echo $form->error($model,'email'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'password'); ?>
			<?php echo $form->passwordField($model,'password',array('style'=>'width: 170px;')); ?>
			<?php echo $form->error($model,'password'); ?>

		</div>

		<div class="row rememberMe">
			<?php echo $form->checkBox($model,'rememberMe',array('checked'=>'checked')); ?>
			<?php echo $form->label($model,'rememberMe'); ?>
			<?php echo $form->error($model,'rememberMe'); ?>
		</div>

		<div class="row buttons">
			<?php echo CHtml::submitButton('Login'); ?>
		</div>
		<br><br>
		<p class="hint">
			Forgot your password? <a href="<?php echo Yii::app()->createUrl('user/resetpassword');?>">Click here</a>.
		</p>

	<?php $this->endWidget(); ?>
	</div><!-- form -->
</div>


<div style="margin-left: 65px;float:left; height:240px; width:10px; border-left:1px #CCCCCC solid; padding:30px;">
</div>
<div style="padding-left:55px; float:left;">
<a href="<?php echo Yii::app()->createUrl('user/register');?>"><h1>Register</h1></a>
<br><br><br>
<p>
	Only one step. <a href="<?php echo Yii::app()->createUrl('user/register');?>">Click here</a>.
</p>
</div>
<div class="clear_both"></div>