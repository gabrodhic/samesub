<?php
$this->breadcrumbs=array(
	'Users'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'Manage Users', 'url'=>array('admin')),
);
?>

<h1>View User #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'username',
		'email',
		array(
            'label'=>'Reset Time',
            'type'=>'raw',
            'value'=>date("Y/m/d H:i:s", $model->reset_time)
        ),
		'ip_created',
		'ip_last_access',
		'status',
		'type',
		array(
			'label'=>'Created Time',
			'value'=>date("Y/m/d H:i:s", $model->time_created)
        ),
		array(
			'label'=>'last Access',
			'value'=>date("Y/m/d H:i:s", $model->time_last_access)
        ),
		array(
			'label'=>'Time modified',
			'value'=>date("Y/m/d H:i:s", $model->time_modified)
        ),
		'country',
		'country_created',
	),
)); ?>
