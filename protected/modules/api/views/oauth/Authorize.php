<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/core.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />
</head>
<body>
	<div style="margin: 0 auto; width: 200px; ">
		<div id="logo"><a href="<?php echo Yii::app()->createUrl('site/index');?>"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/logo.jpg"></a></div><br>
		<?php if(!empty($errmsg)):?>
			<?php echo $errmsg;?>
		<?php endif;?>
	
		<h1><? echo Yii::t('site','Login');?></h1>
		<br><p>Login to use your Samesub account with <b><?php echo $rs['application_title'];?></b></p>
	  

	<div class="form">
	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'login-form',
		'enableAjaxValidation'=>true,
	)); ?>

		<div class="row">
			<?php echo $form->labelEx($model,'username'); ?>
			<?php echo $form->textField($model,'username'); ?>
			<?php echo $form->error($model,'username'); ?>
		</div>
		<div class="row">
			<?php echo $form->labelEx($model,'password'); ?>
			<?php echo $form->passwordField($model,'password'); ?>
			<?php echo $form->error($model,'password'); ?>
		</div>
		<div class="row rememberMe">
			<?php echo $form->checkBox($model,'rememberMe'); ?>
			<?php echo $form->label($model,'rememberMe'); ?>
			<?php echo $form->error($model,'rememberMe'); ?>
		</div>

		<div class="row submit">
			<?php echo CHtml::submitButton('Login'); ?>
		</div>

	<?php $this->endWidget(); ?>
		</div>


	</div>

</body>
</html>
