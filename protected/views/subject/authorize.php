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

Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/jquery.taghandler.js');
Yii::app()->clientScript->registerScriptFile('http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js');
Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/css/jquery.taghandler.css');
Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/css/jquery-ui-1.8.2.custom.css');
$code = "
$('#Subject_tag_ul').tagHandler({
";
$code.= ($model->tag)? "assignedTags: ".json_encode(explode(",",CHtml::encode($model->tag))).",": "";
$code.="
    availableTags: ".json_encode(Subject::getTags()).",
    autocomplete: true,
    maxTags: 15,
	minChars: 1
}
);

$('#Subject_category_ul').tagHandler({
";
$code.= ($model->category)? "assignedTags: ".json_encode(explode(",",CHtml::encode($model->category))).",": "";
$code.="
    availableTags: ".json_encode(Subject::getTags('category')).",
    autocomplete: true,
    maxTags: 5

}
);

$('#subject-form').submit(function () {
    var tagNames = new Array();
    $('#Subject_tag_ul li.tagItem').each(function () {
        tagNames.push($(this).html());
    });
    $('#Subject_tag').val(tagNames);
	tagNames = new Array();
    $('#Subject_category_ul li.tagItem').each(function () {
        tagNames.push($(this).html());
    });
    $('#Subject_category').val(tagNames);
});
";

Yii::app()->clientScript->registerScript('tagscodeid',$code);
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
		<?php echo $form->labelEx($model,'tag'); ?>
		<ul id="Subject_tag_ul"></ul>
		<?php echo $form->hiddenField($model,'tag',array('size'=>40,'maxlength'=>250)); ?>
		<?php echo $form->error($model,'tag'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'category'); ?>
		<ul id="Subject_category_ul"></ul>
		<?php echo $form->hiddenField($model,'category',array('size'=>25,'maxlength'=>250)); ?>
		<?php echo $form->error($model,'category'); ?>
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