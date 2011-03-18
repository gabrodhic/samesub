<?php
$this->breadcrumbs=array(
	'Subjects'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Subject', 'url'=>array('index')),
	array('label'=>'Create Subject', 'url'=>array('add')),
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
		'country_id',
		array(
            'name'=>'urn',
            'value'=>'substr($data->urn, 0,30)',
        ),
		array(
            'name'=>'priority_id',
            'value'=>'$data->priority_type->name',
			'sortable'=>true,
        ),
		array(
            'name'=>'content_type_id',
            'value'=>'$data->content_type->fullname',
			'sortable'=>true,
        ),
		array(
            'name'=>'time_submitted',
            'value'=>'date("Y/m/d H:i", $data->time_submitted)',
			'headerHtmlOptions'=>array('width'=>'100px'),
			'sortable'=>true,
        ),
		array(
			'name'=>'approved',
			'type'=>'html',
			'value'=>'CHtml::link(SiteHelper::yesno($data->approved),"moderate/".$data->id)',
			'sortable'=>true,
		),
		array(
			'name'=>'authorized',
			'type'=>'html',
			'value'=>'CHtml::link(SiteHelper::yesno($data->authorized),"authorize/".$data->id)',
			'sortable'=>true,
		),
	),
	'pager'=>array(
		'class'=>'CLinkPager',
        'pageSize'=>15,//TODO:Doesn't seems to work
     ),
	'enablePagination'=>true,
)); ?>
