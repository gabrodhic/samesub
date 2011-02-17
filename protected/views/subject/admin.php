<?php
$this->breadcrumbs=array(
	'Subjects'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Subject', 'url'=>array('index')),
	array('label'=>'Create Subject', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('subject-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Subjects</h1>

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
	'id'=>'subject-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'user_id',
		'user_ip',
		'user_comment',
		'title',
		'urn',
		/*
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
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
