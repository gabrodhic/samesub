<?php
$this->breadcrumbs=array(
	'Oauth Server Registries'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List OauthServerRegistry', 'url'=>array('index')),
	array('label'=>'Create OauthServerRegistry', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('oauth-server-registry-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Oauth Server Registries</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'oauth-server-registry-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'osr_id',
		'osr_usa_id_ref',
		'osr_consumer_key',
		'osr_consumer_secret',
		'osr_enabled',
		'osr_status',
		/*
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
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
