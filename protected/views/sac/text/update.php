<?php
$this->breadcrumbs=array(
	'Texts'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Text', 'url'=>array('index')),
	array('label'=>'Create Text', 'url'=>array('create')),
	array('label'=>'View Text', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Text', 'url'=>array('admin')),
);
?>

<h1>Update Text <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>