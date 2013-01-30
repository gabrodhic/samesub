<?php
$this->breadcrumbs=array(
	'Subjects'=>array('index'),
	$model->title=>array('view','id'=>$model->id),
	Yii::t('subject','Moderate'),
);


Yii::app()->clientScript->registerCoreScript('jquery.ui');
Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/tag-it.min.js');
Yii::app()->clientScript->registerCssFile(Yii::app()->clientScript->getCoreScriptUrl().'/jui/css/base/jquery-ui.css');
Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/css/jquery.tagit.css');
$code = "
$(document).ready(function() {
$('#Subject_tag').tagit({
    autocomplete: {delay: 0, minLength: 2,source: '".Yii::app()->request->baseUrl.'/api/v1/subject/gettags'."'},
	allowSpaces: true,
});
});
";
Yii::app()->clientScript->registerScript('tagscodeid',$code);
?>

<h1>Moderate Subject <?php echo $model->id; ?></h1>

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
		<?php echo $form->labelEx($model,'urn'); ?>
		<?php echo CHtml::encode($model->urn); ?>
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
		<?php echo $form->labelEx($model,'tag'); ?>		
		<?php 
		foreach ($model->tags as $tag) $model->tag .= $tag->name.',';
		echo $form->textField($model,'tag',array('size'=>40,'maxlength'=>250)); ?>
		<?php echo $form->error($model,'tag'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'category'); ?>
		<?php echo $form->DropDownList($model, 'category', CHtml::listData(Yii::app()->db->createCommand()
		->select('name')
		->from('subject_category')
		->order('name ASC')
		->queryAll(),'name','name'), array('prompt'=>'Select Category')); ?> 
		<?php echo $form->error($model,'category'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'moderator_comment'); ?>
		<?php echo $form->textArea($model,'moderator_comment',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'moderator_comment'); ?>
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
		<?php echo $form->labelEx($model,'deleted'); ?>
		<?php echo $form->DropDownList($model, 'deleted',array('0'=>'No', '1'=>'Yes')); ?>
		<?php echo $form->error($model,'deleted'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'approved'); ?>
		<?php echo $form->DropDownList($model, 'approved',array('0'=>'No', '1'=>'Yes')); ?>
		<?php echo $form->error($model,'approved'); ?>
	</div>
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->