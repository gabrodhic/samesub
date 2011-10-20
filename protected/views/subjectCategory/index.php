<?php
$this->breadcrumbs=array(
	'Subject Categories',
);

$this->menu=array(
	array('label'=>'Create SubjectCategory', 'url'=>array('create')),
	array('label'=>'Manage SubjectCategory', 'url'=>array('admin')),
);
?>

<h1>Subject Categories</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
