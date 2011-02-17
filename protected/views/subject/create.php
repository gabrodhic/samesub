<?php
$this->breadcrumbs=array(
	'Subjects'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Subject', 'url'=>array('index')),
	array('label'=>'Manage Subject', 'url'=>array('admin')),
);
?>

<h1>Create Subject</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>