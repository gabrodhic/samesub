<?php $this->layout='//layouts/main';?>
<h1><?php echo Yii::t('site','Register');?></h1>

<?php if(Yii::app()->user->hasFlash('registration_success')): ?>
<br>
<div class="flash-success">
	<?php echo Yii::app()->user->getFlash('registration_success'); ?>
</div>


<?php else: ?>
<br><br>
<div class="form" style="float:left">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-form',
	'action'=>CHtml::normalizeUrl(array('user/register?sh='.$sh.'&t='.$t)),//Set action url explicitly so that if we are partial rendering fron login page action is alwasy the same
	'enableAjaxValidation'=>false,
)); ?>



	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'username'); ?>
		<?php echo $form->textField($model,'username',array('size'=>25,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'username'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'email'); ?>
		<label id="email_verify" class="field_verify"></label>
		<?php echo $form->textField($model,'email',array('size'=>25,'maxlength'=>50)); ?>		
		<?php echo $form->error($model,'email'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'password'); ?>
		<?php echo $form->passwordField($model,'password',array('size'=>25,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'password'); ?>
	</div>
	<?php if(extension_loaded('gd')): ?>
	<div class="row">
		<?php echo $form->labelEx($model,'verifyCode'); ?>
		<div>
		<?php $this->widget('CCaptcha',array('clickableImage'=>true,'showRefreshButton'=>false,)); ?>
		<br>
		<?php echo $form->textField($model,'verifyCode'); ?>
		</div>
		<div class="hint"><?php echo Yii::t('site','Letters NOT case-sensitive.');?></div>
	</div>
	<?php endif; ?>



	<div class="row buttons">
		<?php echo CHtml::submitButton(Yii::t('site','Register')); ?>
	</div>

<?php $this->endWidget(); ?>
<script>
$("#User_email").keyup(function (){ $("#email_verify").text($("#User_email").val());});

</script>
</div><!-- form -->
<div style="width:300px; margin:0px 30px 0px 210px; padding:20px; float:left; background-color: #F4F4FF;">
<h2><?php echo Yii::t('user','Benefits of registering');?></h2>
<?php echo nl2br(Yii::t('user','Know about the status of each subject you upload.

You can get notified via email when your subject is going to get to the LIVE stream.

Have your own site with your subs. A url like: samesub.com/mysub/username.

Every subject you upload will have your username signature.

Other samesub users can contact you and vice versa.'));?>
</div>
<?php endif; ?>