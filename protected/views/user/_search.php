<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'username'); ?>
		<?php echo $form->textField($model,'username',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ip_created'); ?>
		<?php echo $form->textField($model,'ip_created',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ip_last_access'); ?>
		<?php echo $form->textField($model,'ip_last_access',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'user_state_id'); ?>
		<?php echo $form->textField($model,'user_state_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'user_type_id'); ?>
		<?php echo $form->textField($model,'user_type_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'time_created'); ?>
		<?php echo $form->textField($model,'time_created'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'time_last_access'); ?>
		<?php echo $form->textField($model,'time_last_access'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'time_modified'); ?>
		<?php echo $form->textField($model,'time_modified'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->