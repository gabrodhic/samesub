<?php
$this->breadcrumbs=array(
	'Subjects'=>array('index'),
	$model->title=>array('view','id'=>$model->id),
	'View',
);

?>

<h1><?php echo CHtml::encode($model->title); ?></h1>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'subject-form',
	'enableAjaxValidation'=>false,'htmlOptions'=>array('enctype' => 'multipart/form-data'),
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo CHtml::encode($model->title); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'show_time'); ?>
		<?php echo CHtml::encode(date("Y/m/d H:i", $model->show_time)." UTC"); ?>
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
		<?php echo $form->labelEx($model,'country_id'); ?>
		<?php echo $model->country->name; ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'user_comment'); ?>
		<?php echo $model->user_comment; ?>
	</div>
	

<?php $this->endWidget(); ?>
<h3>Comments:</h3>

<?php foreach($model->comments as $comment): ?>
<div class="comment" id="c<?php echo $comment->id; ?>">



	<div class="time">
		<?php echo date("Y/m/d H:i",$comment->time)." UTC"; ?>
	</div>

	<div class="content">
		<?php echo nl2br(CHtml::encode($comment->comment)); ?>
	</div>

</div><!-- comment -->
<?php endforeach; ?>

</div><!-- form -->