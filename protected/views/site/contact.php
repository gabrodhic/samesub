<?php
$this->pageTitle=Yii::app()->name . ' - '.Yii::t('site','Contact Us');
$this->breadcrumbs=array(
	Yii::t('site','Contact Us'),
);
?>

<h1><?php echo Yii::t('site','Contact Us');?></h1>

<?php if(Yii::app()->user->hasFlash('contact')): ?>

<div class="flash-success">
	<?php echo Yii::app()->user->getFlash('contact'); ?>
</div>

<?php else: ?>

<p>
<?php echo Yii::t('site','Put your questions, feedback, or any inquiries here. Make sure to write your email if you want a reply. Thanks.')?>
</p>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm'); ?>

	<p class="note"><?php echo Yii::t('site','Fields with {1} are required.',array('{1}'=>'<span class="required">*</span>'));?></p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'text'); ?>
		<?php echo $form->textArea($model,'text',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Submit'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->

<?php endif; ?>