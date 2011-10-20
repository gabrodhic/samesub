<?php
$this->breadcrumbs=array(
	'Subject Categories'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List SubjectCategory', 'url'=>array('index')),
	array('label'=>'Manage SubjectCategory', 'url'=>array('admin')),
);
?>

<h1>Create SubjectCategory</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>