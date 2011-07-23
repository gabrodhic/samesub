<h1>Profile Settings</h1>
<?php if(Yii::app()->user->hasFlash('profile_success')): ?>
<br>
<div class=flash-success>
	<?php echo Yii::app()->user->getFlash('profile_success') ?>
</div>
<?php else: ?>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-form',
	'enableAjaxValidation'=>false,
)); ?>
	<div class="row">
		<?php echo $form->labelEx($model,'country_id'); ?>
		<?php echo $form->DropDownList($model, 'country_id', CHtml::listData(Country::model()->findAll(),'id','name'), array('prompt'=>'Select Country')); ?> 
		<?php echo $form->error($model,'country_id'); ?>
	</div>
	
	<div class="row buttons">
		<?php echo CHtml::submitButton('Update Settings'); ?>
	</div>

<?php $this->endWidget(); ?>
</div><!-- form -->
<?php endif; ?>