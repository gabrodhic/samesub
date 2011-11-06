<?php
$this->pageTitle=Yii::app()->name . ' - Login';
$this->breadcrumbs=array(
	'Login',
);
?>
<div style="float:left;">
<h1>Login</h1>



	<div class="form">
	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'login-form',
		'enableAjaxValidation'=>true,
	)); ?>


		<div class="row">
			<?php echo $form->labelEx($model,'email'); ?>
			<?php echo $form->textField($model,'email'); ?>
			<?php echo $form->error($model,'email'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($model,'password'); ?>
			<?php echo $form->passwordField($model,'password'); ?>
			<?php echo $form->error($model,'password'); ?>
			<p class="hint">
				Forgot your password? <a href="<?php echo Yii::app()->createUrl('user/resetpassword');?>">Click here</a>.
			</p>
		</div>

		<div class="row rememberMe">
			<?php echo $form->checkBox($model,'rememberMe',array('checked'=>'checked')); ?>
			<?php echo $form->label($model,'rememberMe'); ?>
			<?php echo $form->error($model,'rememberMe'); ?>
		</div>

		<div class="row buttons">
			<?php echo CHtml::submitButton('Login'); ?>
		</div>

	<?php $this->endWidget(); ?>
	</div><!-- form -->
</div>


<div style="margin-left: 100px;float:left; height:250px; width:10px; border-left:1px #CCCCCC solid; padding:30px;">
</div>
<div style="padding-left:3px; float:left;">
<?php 
	$model2=new User('register');
	$this->renderPartial('../user/register',array(
	'model'=>$model2,
)); ?>
</div>
<div class="clear_both"></div>