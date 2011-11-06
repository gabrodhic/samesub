<?php
$this->breadcrumbs=array(
	'Subjects'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Subject', 'url'=>array('index')),
	array('label'=>'Manage Subject', 'url'=>array('manage')),
);


Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/tag.js');
Yii::app()->clientScript->registerCss('tagssugest',
"
SPAN.tagMatches {
	margin-left: 10px;
}  
SPAN.tagMatches SPAN {
	padding: 2px;
	margin-right: 4px;
	background-color: #0000AB;
	color: #fff;
	cursor: pointer;
}
");
$code = "
$('#Subject_tag').tagSuggest({
        url: '".Yii::app()->request->baseUrl.'/subject/gettags'."',
        delay: 300
});
";
Yii::app()->clientScript->registerScript('tagscodeid',$code);
?>

<h1>Add Subject</h1>

<?php if(Yii::app()->user->hasFlash('subject_added')): ?>
<br>
<div class="flash-success">
	<?php echo Yii::app()->user->getFlash('subject_added'); ?>
</div>
<?php if(Yii::app()->user->isGuest){ ?>
	<br>
	<div>
		<b>NOTE:</b> You have uploaded this subject as anonymous(guest) user. If you <a href="<?php echo $this->createUrl('user/register?sh='.$model->hash.'&t='.$model->time_submitted);?>">click here</a> and register, the system will asign this subject to the new account you register. (Don't worry, we don't ask much about you when registering. Just your email, username, and password. We hate to ask much to the user.)
	</div>
	<div class="clear_both"></div>
	<br><br>
<?php } ?>

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
		<?php echo $form->DropDownList($model, 'content_type_id', CHtml::listData(ContentType::model()->findAll(),'id','name'),array('prompt'=>'Select Type')); ?> 
		<?php echo $form->error($model,'content_type_id'); ?>
	</div>
	
	<div class="row">
		<label>Please upload an image from your computer OR paste a url on the field bellow.</label>
		<?php echo $form->labelEx($model,'image'); ?>		
		<?php echo $form->FileField($model, 'image');?>
		<?php echo $form->error($model,'image'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'image_url'); ?>		
		<?php echo $form->textField($model,'image_url',array('size'=>80,'maxlength'=>250));?>
		<?php echo $form->error($model,'image_url'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'text'); ?>
		<?php echo $form->textArea($model,'text',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'text'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'video'); ?>
		<?php echo $form->textArea($model,'video',array('rows'=>2, 'cols'=>50)); ?>
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
		<?php echo $form->labelEx($model,'tag'); ?>
		<?php echo $form->textField($model,'tag',array('size'=>40,'maxlength'=>250)); ?>
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
		<?php echo $form->labelEx($model,'priority_id'); ?>
		<?php echo $form->DropDownList($model, 'priority_id',array('1'=>'Low', '2'=>'Medium','3'=>'High')); ?>
		<?php echo $form->error($model,'priority_id'); ?>
	</div>
	<?php if(extension_loaded('gd')): ?>
	<div class="row">
		<?php echo $form->labelEx($model,'verifyCode'); ?>
		<div>
		<?php $this->widget('CCaptcha',array('clickableImage'=>true,)); ?>
		<?php echo $form->textField($model,'verifyCode'); ?>
		</div>
		<div class="hint">Please enter the letters as they are shown in the image above.
		<br/>Letters are not case-sensitive.</div>
	</div>
	<?php endif; ?>
	
	<div class="row">
	By submitting this content you agree with the <a href="<?php echo $this->createUrl('site/page/view/terms');?>">Terms of Use</a>.
	</div>
	
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Send' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>
<script>

function show_content_input(){

	switch($("#Subject_content_type_id").val())
	{
	case '':
	  $("#Subject_image,#Subject_image_url").parent().hide();
	  $("#Subject_text").parent().hide();
	  $("#Subject_video").parent().hide();
	  $("#Subject_user_comment").parent().hide();
	  break;
	case '1':
	  $("#Subject_image,#Subject_image_url").parent().show();
	  $("#Subject_text").parent().hide();
	  $("#Subject_video").parent().hide();
	  $("#Subject_user_comment").parent().show();
	  break;
	case '2':
	  $("#Subject_image,#Subject_image_url").parent().hide();
	  $("#Subject_text").parent().show();
	  $("#Subject_video").parent().hide();
	  $("#Subject_user_comment").parent().show();
	  break;
	case '3':
	  $("#Subject_image,#Subject_image_url").parent().hide();
	  $("#Subject_text").parent().hide();
	  $("#Subject_video").parent().show();
	  $("#Subject_user_comment").parent().show();
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