<h1>Update Profile</h1>
<?php if(Yii::app()->user->hasFlash('profile_success')): ?>
<br>
<div class=flash-success>
	<?php echo Yii::app()->user->getFlash('profile_success') ?>
</div>
<?php else: ?>
<div class="form" style="width:600px;">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-form',
	'enableAjaxValidation'=>false,'htmlOptions'=>array('enctype' => 'multipart/form-data'),
)); ?>
	<div class="row">
		<?php echo $form->labelEx($model,'image'); ?>
		<img src="<?php echo $model->getUserImage('small_');?>">
		<?php if($model->image_name) echo $form->checkBox($model,'deleteimage')." Delete Image"; ?>
	</div>
	<div class="row">
		<label>Upload an image from your computer.</label>
		<?php echo $form->labelEx($model,'image'); ?>		
		<?php echo $form->FileField($model, 'image');?>
		<?php echo $form->error($model,'image'); ?>
	</div>

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
		<?php echo $form->labelEx($model,'notify_subject_live'); ?>
		<?php echo $form->DropDownList($model, 'notify_subject_live',array('0'=>'No', '1'=>'Yes')); ?>
		<?php echo $form->error($model,'notify_subject_live'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'notify_subject_authorized'); ?>
		<?php echo $form->DropDownList($model, 'notify_subject_authorized',array('0'=>'No', '1'=>'Yes')); ?>
		<?php echo $form->error($model,'notify_subject_authorized'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'sex'); ?>
		<?php echo $form->DropDownList($model, 'sex',array('1'=>'Male', '2'=>'Female'), array('prompt'=>'Please Select')); ?>
		<?php echo $form->error($model,'sex'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'country_id'); ?>
		<?php echo $form->DropDownList($model, 'country_id', CHtml::listData(Country::model()->findAll(),'id','name'), array('prompt'=>'Select Country')); ?> 
		<?php echo $form->error($model,'country_id'); ?>
	</div>
	<div class="row" style="float:left;">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo CHtml::encode($model->email); ?>
	</div>
	<div style="float:right;"><br><?php echo $form->DropDownList($model, 'share_email',array('1'=>'Public','3'=>'Only Me')); ?></div>
	<div class="clear_both"></div>
	<div class="row" style="float:left;">
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
	<div style="float:right;"><br><?php echo $form->DropDownList($model, 'share_birthdate',array('1'=>'Public','3'=>'Only Me')); ?></div>
	<div class="clear_both"></div>
	<div class="row"style="float:left;">
		<?php echo $form->labelEx($model,'region'); ?>
		<?php echo $form->textField($model,'region',array('size'=>25,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'region'); ?>
	</div>
	<div style="float:right;"><br><?php echo $form->DropDownList($model, 'share_region',array('1'=>'Public','3'=>'Only Me')); ?></div>
	<div class="clear_both"></div>
	<div class="row"style="float:left;">
		<?php echo $form->labelEx($model,'city'); ?>
		<?php echo $form->textField($model,'city',array('size'=>25,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'city'); ?>
	</div>
	<div style="float:right;"><br><?php echo $form->DropDownList($model, 'share_city',array('1'=>'Public','3'=>'Only Me')); ?></div>
	<div class="clear_both"></div>
	<div class="row" style="float:left;">
		<?php echo $form->labelEx($model,'interests'); ?>
		<?php echo $form->textArea($model,'interests',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'interests'); ?>
	</div>
	<div style="float:right;"><br><?php echo $form->DropDownList($model, 'share_interests',array('1'=>'Public','3'=>'Only Me')); ?></div>
	<div class="clear_both"></div>
	<div class="row" style="float:left;">
		<?php echo $form->labelEx($model,'activities'); ?>
		<?php echo $form->textArea($model,'activities',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'activities'); ?>
	</div>
	<div style="float:right;"><br><?php echo $form->DropDownList($model, 'share_activities',array('1'=>'Public','3'=>'Only Me')); ?></div>
	<div class="clear_both"></div>
	<div class="row" style="float:left;">
		<?php echo $form->labelEx($model,'about'); ?>
		<?php echo $form->textArea($model,'about',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'about'); ?>
	</div>
	<div style="float:right;"><br><?php echo $form->DropDownList($model, 'share_about',array('1'=>'Public','3'=>'Only Me')); ?></div>
	<div class="clear_both"></div>
	
	<div class="row buttons">
		<?php echo CHtml::submitButton('Update Profile'); ?>
	</div>

<?php $this->endWidget(); ?>
</div><!-- form -->
<?php endif; ?>