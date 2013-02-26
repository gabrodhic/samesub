<?php
$this->breadcrumbs=array(
	Yii::t('subject','Subjects')=>array('index'),
	Yii::t('subject','Update'),
);
?>
<h1><?php echo Yii::t('subject','UpdateSubjectTitle');?></h1>
<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>