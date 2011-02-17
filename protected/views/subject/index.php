<?php
$this->breadcrumbs=array(
	'Subjects',
);

$this->menu=array(
	array('label'=>'Create Subject', 'url'=>array('create')),
	array('label'=>'Manage Subject', 'url'=>array('admin')),
);
?>

<h1>Subjects</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
