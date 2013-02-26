<?php
$this->breadcrumbs=array(
	Yii::t('subject','Subjects')=>array('index'),
	Yii::t('subject','Add'),
);
?>
<h1><?php echo Yii::t('subject','Add Subject');?></h1>
<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>