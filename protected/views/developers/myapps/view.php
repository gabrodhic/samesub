<?php
$this->breadcrumbs=array(
	'Oauth Server Registries'=>array('index'),
	$model->osr_id,
);

$this->menu=array(
	array('label'=>'List OauthServerRegistry', 'url'=>array('index')),
	array('label'=>'Create OauthServerRegistry', 'url'=>array('create')),
	array('label'=>'Update OauthServerRegistry', 'url'=>array('update', 'id'=>$model->osr_id)),
	array('label'=>'Delete OauthServerRegistry', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->osr_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage OauthServerRegistry', 'url'=>array('admin')),
);
?>

<h1>View OauthServerRegistry #<?php echo $model->osr_id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'osr_id',
		'osr_usa_id_ref',
		'osr_consumer_key',
		'osr_consumer_secret',
		'osr_enabled',
		'osr_status',
		'osr_requester_name',
		'osr_requester_email',
		'osr_callback_uri',
		'osr_application_uri',
		'osr_application_title',
		'osr_application_descr',
		'osr_application_notes',
		'osr_application_type',
		'osr_application_commercial',
		'osr_issue_date',
		'osr_timestamp',
	),
)); ?>
