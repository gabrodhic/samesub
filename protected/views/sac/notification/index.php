<?php
$this->breadcrumbs=array(
	'Notifications',
);

$this->menu=array(
	array('label'=>'Create Notification', 'url'=>array('create')),
	array('label'=>'Manage Notification', 'url'=>array('admin')),
);
?>

<h1>Notifications</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
