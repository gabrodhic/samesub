<?php
$this->breadcrumbs=array(
	'Subjects'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Subject', 'url'=>array('index')),
	array('label'=>'Manage Subject', 'url'=>array('manage')),
);
?>

<h1>Add Subject</h1>

<?php if(Yii::app()->user->hasFlash('subject_added')): ?>

<div class="flash-success">
	<?php echo Yii::app()->user->getFlash('subject_added'); ?>
</div>

<?php else: ?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'subject-form',
	'enableAjaxValidation'=>false,'htmlOptions'=>array('enctype' => 'multipart/form-data'),
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>250)); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'content_type_id'); ?>
		<?php echo $form->DropDownList($model, 'content_type_id', CHtml::listData(ContentType::model()->findAll(),'id','name')); ?> 
		<?php echo $form->error($model,'content_type_id'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'image'); ?>		
		<?php echo $form->FileField($model, 'image');?>
		<?php echo $form->error($model,'image'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'text'); ?>
		<?php echo $form->textArea($model,'text',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'text'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'video'); ?>
		<?php echo $form->textArea($model,'video',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'video'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'user_comment'); ?>
		<?php echo $form->textArea($model,'user_comment',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'user_comment'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'country_id'); ?>
		<?php echo $form->DropDownList($model, 'country_id', CHtml::listData(Country::model()->findAll(),'id','name'), array('prompt'=>'Select Country')); ?> 
		<?php echo $form->error($model,'country_id'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'priority_id'); ?>
		<?php echo $form->DropDownList($model, 'priority_id',array('1'=>'Low', '2'=>'Medium','3'=>'High')); ?>
		<?php echo $form->error($model,'priority_id'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>
<script>

function show_content_input(){

	switch($("#Subject_content_type_id").val())
	{
	case '1':
	  $("#Subject_image").parent().show();
	  $("#Subject_text").parent().hide();
	  $("#Subject_video").parent().hide();
	  break;
	case '2':
	  $("#Subject_image").parent().hide();
	  $("#Subject_text").parent().show();
	  $("#Subject_video").parent().hide();
	  break;
	case '3':
	  $("#Subject_image").parent().hide();
	  $("#Subject_text").parent().hide();
	  $("#Subject_video").parent().show();
	  break;
	default:
	  //nothing
	}
}
show_content_input();
$("#Subject_content_type_id").change(function (){ show_content_input();});

</script>
</div><!-- form -->
<?php endif; ?>