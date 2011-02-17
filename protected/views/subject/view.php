<?php
$this->breadcrumbs=array(
	'Subjects'=>array('index'),
	$model->title,
);

$this->menu=array(
	array('label'=>'List Subject', 'url'=>array('index')),
	array('label'=>'Create Subject', 'url'=>array('create')),
	array('label'=>'Update Subject', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Subject', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Subject', 'url'=>array('admin')),
);
?>

<h1>View Subject #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'user_id',
		'user_ip',
		'user_comment',
		'title',
		'urn',
		'content_type_id',
		'content_state_id',
		'content_id',
		'country_id',
		'moderator_id',
		'moderator_ip',
		'moderator_comment',
		'time_submitted',
		'time_moderated',
		'priority_id',
		'show_time',
	),
)); ?>
