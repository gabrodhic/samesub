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
		<?php echo $form->label($model,'user_id'); ?>
		<?php echo $form->textField($model,'user_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'user_ip'); ?>
		<?php echo $form->textField($model,'user_ip',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'user_comment'); ?>
		<?php echo $form->textArea($model,'user_comment',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>250)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'urn'); ?>
		<?php echo $form->textField($model,'urn',array('size'=>60,'maxlength'=>250)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'content_type_id'); ?>
		<?php echo $form->textField($model,'content_type_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'approved'); ?>
		<?php echo $form->textField($model,'approved'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'content_id'); ?>
		<?php echo $form->textField($model,'content_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'country_id'); ?>
		<?php echo $form->textField($model,'country_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'moderator_id'); ?>
		<?php echo $form->textField($model,'moderator_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'moderator_ip'); ?>
		<?php echo $form->textField($model,'moderator_ip',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'moderator_comment'); ?>
		<?php echo $form->textField($model,'moderator_comment',array('size'=>60,'maxlength'=>250)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'time_submitted'); ?>
		<?php echo $form->textField($model,'time_submitted'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'time_moderated'); ?>
		<?php echo $form->textField($model,'time_moderated'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'priority_id'); ?>
		<?php echo $form->textField($model,'priority_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'show_time'); ?>
		<?php echo $form->textField($model,'show_time'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->