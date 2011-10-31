<?php $this->layout='//layouts/main';?>
<h1>Register</h1>

<?php if(Yii::app()->user->hasFlash('registration_success')): ?>
<br>
<div class="flash-success">
	<?php echo Yii::app()->user->getFlash('registration_success'); ?>
</div>


<?php else: ?>
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



	<div class="row buttons">
		<?php echo CHtml::submitButton('Register'); ?>
	</div>

<?php $this->endWidget(); ?>
<script>
$("#User_email").keyup(function (){ $("#email_verify").text($("#User_email").val());});

</script>
</div><!-- form -->
<div style="width:300px; margin:0px 30px 0px 30px; padding:20px; float:left; background-color: #F4F4FF;">
<h2>Benefits of registering</h2>
<ul>
<li>Know about the status of each subject you upload.</li>
<li>You can get notified via email when your subject is going to get to the LIVE stream.</li>
<li>Have your own site with your subs that you can share with your friends. A url like: samesub.com/mysub/username.</li>
<li>Every subject you upload will have your username signature.</li>
<li>Other samesub users can contact you and vice versa.</li>
</ul>
</div>
<?php endif; ?>