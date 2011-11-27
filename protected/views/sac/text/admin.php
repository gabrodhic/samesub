<?php
$this->breadcrumbs=array(
	'Texts'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Text', 'url'=>array('index')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('text-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Texts</h1>

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

<?php 
	$dataProvider=$model->search();
	$dataProvider->pagination->pageSize=50;
	$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'text-grid',
	'dataProvider'=>$dataProvider,
	'filter'=>$model,
	'columns'=>array(
		array(
            'name'=>'id',
			'headerHtmlOptions'=>array('width'=>'30px'),
		),
		array(
            'name'=>'category',
            'value'=>'$data->category',
			'headerHtmlOptions'=>array('width'=>'50px'),
			'filter'=>CHtml::listData(Text::model()->findAll(array('distinct'=>true, 'order'=>'t.category DESC')),'category','category'),
			'sortable'=>true,
        ),
		'message',
		array(
            'name'=>'language',
			'filter'=>CHtml::listData(Message::model()->findAll(array('distinct'=>true, 'order'=>'t.language DESC')),'language','language'),
		),
		'translation',
		array(
			'header'=>'Actions',
			'type'=>'html',
			'value'=>'CHtml::link( "Update", Yii::app()->createUrl("sac/text/create?id=".$data->id."&category=".$data->category."&language=".$data->language))',
			'headerHtmlOptions'=>array('width'=>'50px'),
		),
	),
)); ?>
