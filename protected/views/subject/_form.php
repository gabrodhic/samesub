<?php
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

<?php if(Yii::app()->user->hasFlash('subject_added')): ?>
<br>
<div class="flash-success">
	<?php echo Yii::app()->user->getFlash('subject_added'); ?>
</div>
<?php if(Yii::app()->user->isGuest){ ?>
	<br><br>
	<div style="font-size: 16px;">
		<?php echo Yii::t('subject','{1}NOTE:{2} You have uploaded this subject as anonymous(guest) user. If you {link_begin1}Click Here{link_end} and register, the system will asign this subject to the new account you register. You may {link_begin2}Click Here{link_end} also to login if you are already registered.',array('{1}'=>'<b>','{2}'=>'</b>','{link_begin1}'=>'<a href="'.$this->createUrl('user/register?sh='.$model->hash.'&t='.$model->time_submitted).'">','{link_begin2}'=>'<a href="'.$this->createUrl('site/login?sh='.$model->hash.'&t='.$model->time_submitted).'">','{link_end}'=>'</a>'));?>		
	</div>
	<div class="clear_both"></div>
	
<?php } ?>

<br /><br />
<div style="font-size: 16px;">
	<?php echo Yii::t('subject','You can check the countdown of your subject in this {link_begin}link{link_end}.',array('{link_begin}'=>'<a href="'.$this->createUrl('countdown/'.$model->urn).'">','{link_end}'=>'</a>'));?>		
</div>

<?php else: ?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'subject-form',
	'enableAjaxValidation'=>false,'htmlOptions'=>array('enctype' => 'multipart/form-data'),
)); ?>

	<p class="note"><?php echo Yii::t('site','Fields with {1} are required.',array('{1}'=>'<span class="required">*</span>'));?></p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>250)); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'content_type_id'); ?>
		<?php echo $form->DropDownList($model, 'content_type_id', CHtml::listData(ContentType::model()->findAll(),'id','name'),array('prompt'=>'Select Type', 'disabled'=> (Yii::app()->controller->action->id == 'update') )); ?> 
		<?php echo $form->error($model,'content_type_id'); ?>
	</div>
	
	<div class="row">
		<label><?php echo Yii::t('subject','Image source');?></label>
		<?php echo $form->DropDownList($model, 'image_source',  array('0'=>Yii::t('subject','My Computer'), '1'=>Yii::t('subject','Link or URL'))); ?>		
	</div>
	<div class="row">		
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
		<?php echo $form->labelEx($model,'Content'); ?>
		<?php echo SiteHelper::subject_content($model); ?>
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
	<div class="row">
		<?php echo $form->labelEx($model,'datetime'); ?>
		<?php echo $form->error($model,'user_position'); ?>
		<?php echo $form->DropDownList($model, 'user_position_ymd',SiteLibrary::get_time_intervals('ymd')); ?>
		<?php echo $form->DropDownList($model, 'user_position_hour',SiteLibrary::get_time_intervals('hour')); ?>
		:<?php echo $form->DropDownList($model, 'user_position_minute',SiteLibrary::get_time_intervals('minute')); ?>
		<span><?php echo $form->CheckBox($model, 'user_position_anydatetime'); ?><?php echo Yii::t('subject','Any date and time.');?></span>	
		
	</div>
	<?php if(extension_loaded('gd') and $this->action->id == 'add'): ?>
	<div class="row">
		<?php echo $form->labelEx($model,'verifyCode'); ?>
		<div>
		<?php $this->widget('CCaptcha',array('clickableImage'=>true,)); ?>
		<?php echo $form->textField($model,'verifyCode'); ?>
		</div>
		<div class="hint"><?php echo Yii::t('subject','Please enter the letters as they are shown in the image above.{1}Letters are not case-sensitive.',array('{1}'=>'<br/>'));?></div>
	</div>
	<?php endif; ?>
	
	<div class="row">
	<?php echo Yii::t('subject','By submitting this content you agree with the {link_begin}Terms of Use{link_end}.',array('{link_begin}'=>'<a href="'.$this->createUrl('site/page/view/terms').'">','{link_end}'=>'</a>'));?>
	</div>
	
	<div class="row buttons">
		<?php echo CHtml::submitButton(Yii::t('site','Submit')); ?>
	</div>

<?php $this->endWidget(); ?>
<script>

function show_content_input(){

	switch($("#Subject_content_type_id").val())
	{
	case '':
	  $("#Subject_image,#Subject_image_url,#Subject_image_source").parent().hide();
	  $("#Subject_text").parent().hide();
	  $("#Subject_video").parent().hide();
	  $("#Subject_user_comment").parent().hide();
	  break;
	case '1':
	  $("#Subject_image_source").parent().show();
	  if( $("#Subject_image_source").val() == '0' ){
		$("#Subject_image").parent().show();
		$("#Subject_image_url").parent().hide();
	  }else{
		$("#Subject_image_url").parent().show();
		$("#Subject_image").parent().hide();
	  }

	  $("#Subject_text").parent().hide();
	  $("#Subject_video").parent().hide();
	  $("#Subject_user_comment").parent().show();
	  break;
	case '2':
	  $("#Subject_image,#Subject_image_url,#Subject_image_source").parent().hide();
	  $("#Subject_text").parent().show();
	  $("#Subject_video").parent().hide();
	  $("#Subject_user_comment").parent().show();
	  break;
	case '3':
	  $("#Subject_image,#Subject_image_url,#Subject_image_source").parent().hide();
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
$("#Subject_image_source").change(function (){ show_content_input();});

function any_datetime(){
	if($("#Subject_user_position_anydatetime").is(':checked')){
		$("#Subject_user_position_ymd").attr("disabled",true);
		$("#Subject_user_position_hour").attr("disabled",true);
		$("#Subject_user_position_minute").attr("disabled",true);
	}else{
		$("#Subject_user_position_ymd").attr("disabled",false);
		$("#Subject_user_position_hour").attr("disabled",false);
		$("#Subject_user_position_minute").attr("disabled",false);
	}
}
any_datetime();
$("#Subject_user_position_anydatetime").change( function () { any_datetime() });

</script>
</div><!-- form -->
<?php endif; ?>
<br><br>