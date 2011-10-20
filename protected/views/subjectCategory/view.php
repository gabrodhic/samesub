<?php
$this->breadcrumbs=array(
	'Subject Categories'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List SubjectCategory', 'url'=>array('index')),
	array('label'=>'Create SubjectCategory', 'url'=>array('create')),
	array('label'=>'Update SubjectCategory', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete SubjectCategory', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage SubjectCategory', 'url'=>array('admin')),
);
?>

<h1>View SubjectCategory #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
	),
)); ?>
