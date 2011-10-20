<?php
$this->breadcrumbs=array(
	'Subject Categories'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List SubjectCategory', 'url'=>array('index')),
	array('label'=>'Create SubjectCategory', 'url'=>array('create')),
	array('label'=>'View SubjectCategory', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage SubjectCategory', 'url'=>array('admin')),
);
?>

<h1>Update SubjectCategory <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>