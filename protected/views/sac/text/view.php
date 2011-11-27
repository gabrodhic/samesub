<?php
$this->breadcrumbs=array(
	'Texts'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Text', 'url'=>array('index')),
	array('label'=>'Create Text', 'url'=>array('create')),
	array('label'=>'Update Text', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Text', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Text', 'url'=>array('admin')),
);
?>

<h1>View Text #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'category',
		'message',
	),
)); ?>
