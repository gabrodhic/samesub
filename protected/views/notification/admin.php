<?php
$this->breadcrumbs=array(
	'Notifications'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Notification', 'url'=>array('index')),
	array('label'=>'Create Notification', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('notification-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Notifications</h1>

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
	'id'=>'notification-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'notification_type_id',
		array(
			'name'=>'enabled',
			'type'=>'html',
			'value'=>'SiteHelper::yesno($data->enabled)',
			'filter'=>array('0'=>'No','1'=>'Yes'),
			'sortable'=>true,
		),
		array(
			'name'=>'fixed',
			'type'=>'html',
			'value'=>'SiteHelper::yesno($data->fixed)',
			'filter'=>array('0'=>'No','1'=>'Yes'),
			'sortable'=>true,
		),
		'message',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
