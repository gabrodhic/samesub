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
		<?php echo $form->labelEx($model,'firstname'); ?>
		<?php echo $form->textField($model,'firstname',array('size'=>25,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'firstname'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'lastname'); ?>
		<?php echo $form->textField($model,'lastname',array('size'=>25,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'lastname'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'sex'); ?>
		<?php echo $form->DropDownList($model, 'sex',array(''=>'Please Select','1'=>'Male', '0'=>'Female')); ?>
		<?php echo $form->error($model,'sex'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'birthdate'); ?>
		<?php
			$this->widget('application.extensions.EHtmlDateSelect',
							array(
								  'time'=>$model->birthdate, // $model->dateField
								  'field_array'=>'User',//Set to model name using this widget
								  'prefix'=>'',
								  'field_order'=>'DMY',
								  'start_year'=>1900,
								  'end_year'=>+1,
								  'reverse_years'=>true,
								  'day_empty'=>(! $model->birthdate)?'Please Select':NULL,
								  'month_empty'=>(! $model->birthdate)?'Please Select':NULL,
								  'year_empty'=>(! $model->birthdate)?'Please Select':NULL,
								 )
			);
		?>
		<?php echo $form->error($model,'birthdate'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'country_id'); ?>
		<?php echo $form->DropDownList($model, 'country_id', CHtml::listData(Country::model()->findAll(),'id','name'), array('prompt'=>'Select Country')); ?> 
		<?php echo $form->error($model,'country_id'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'region'); ?>
		<?php echo $form->textField($model,'region',array('size'=>25,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'region'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'city'); ?>
		<?php echo $form->textField($model,'city',array('size'=>25,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'city'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'notify_subject'); ?>
		<?php echo $form->DropDownList($model, 'notify_subject',array('0'=>'No', '1'=>'Yes')); ?>
		<?php echo $form->error($model,'notify_subject'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'interests'); ?>
		<?php echo $form->textArea($model,'interests',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'interests'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'activities'); ?>
		<?php echo $form->textArea($model,'activities',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'activities'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'about'); ?>
		<?php echo $form->textArea($model,'about',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'about'); ?>
	</div>
	
	<div class="row buttons">
		<?php echo CHtml::submitButton('Update Settings'); ?>
	</div>

<?php $this->endWidget(); ?>
</div><!-- form -->
<?php endif; ?>