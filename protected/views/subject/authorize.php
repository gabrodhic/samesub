<?php
$this->breadcrumbs=array(
	'Subjects'=>array('index'),
	$model->title=>array('view','id'=>$model->id),
	'Moderate',
);

$this->menu=array(
	array('label'=>'List Subject', 'url'=>array('index')),
	array('label'=>'Create Subject', 'url'=>array('add')),
	array('label'=>'View Subject', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Subject', 'url'=>array('manage')),
);
?>

<h1>Authorize Subject <?php echo $model->id; ?></h1>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'subject-form',
	'enableAjaxValidation'=>false,'htmlOptions'=>array('enctype' => 'multipart/form-data'),
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo CHtml::encode($model->title); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'country_id'); ?>
		<?php echo CHtml::encode($model->country->name); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'content_type_id'); ?>
		<?php echo CHtml::encode($model->content_type->fullname); ?> 
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'Content'); ?>
		<?php echo SiteHelper::subject_content($model); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'Content code'); ?>
		<?php echo CHtml::encode(SiteHelper::subject_content($model)); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'user_country_id'); ?>
		<?php echo CHtml::encode($model->user_country->name); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'user_comment'); ?>
		<?php echo SiteHelper::formatted($model->user_comment); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'User Comment code'); ?>
		<?php echo CHtml::encode(SiteHelper::formatted($model->user_comment)); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'moderator_comment'); ?>
		<?php echo CHtml::encode($model->moderator_comment); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'approved'); ?>
		<?php echo SiteHelper::yesno($model->approved); ?>		
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'priority_id'); ?>
		<?php echo $form->DropDownList($model, 'priority_id',array('1'=>'Low', '2'=>'Medium','3'=>'High')); ?>
		<?php echo $form->error($model,'priority_id'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'disabled'); ?>
		<?php echo $form->DropDownList($model, 'disabled',array('0'=>'No', '1'=>'Yes')); ?>
		<?php echo $form->error($model,'disabled'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'authorized'); ?>
		<?php echo $form->DropDownList($model, 'authorized',array('0'=>'No', '1'=>'Yes')); ?>
		<?php echo $form->error($model,'authorized'); ?>
	</div>
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->