<?php
$this->breadcrumbs=array(
	'Users'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'Manage Users', 'url'=>array('admin')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('user-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Users</h1>

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
	'id'=>'user-grid',
	'dataProvider'=>$dataProvider,
	'filter'=>$model,
	'columns'=>array(
		'id',
		array(
            'name'=>'username',
			'headerHtmlOptions'=>array('width'=>'150px'),
		),
		array(
            'name'=>'country_id',
			'headerHtmlOptions'=>array('width'=>'250px'),
			'value'=>'$data->country',
			'filter'=>CHtml::listData(Country::model()->findAll(),'id','name'),
			'sortable'=>true,
        ),
		array(
            'name'=>'status',			
			'filter'=>CHtml::listData(Yii::app()->db->createCommand()
			->select('*')
			->from('user_status')
			->queryAll(),'id','name'),
			'sortable'=>true,
        ),
		array(
            'name'=>'type',			
			'filter'=>CHtml::listData(Yii::app()->db->createCommand()
			->select('*')
			->from('user_type')
			->queryAll(),'id','name'),
			'sortable'=>true,
        ),
		array(
            'name'=>'time_last_access',
            'value'=>'date("Y/m/d H:i:s", $data->time_last_access)',
			'headerHtmlOptions'=>array('width'=>'180px'),
			'filter'=>'',
			'sortable'=>true,
        ),
		array(
			'header'=>'Subs',
			'type'=>'html',
			'value'=>'CHtml::link( "$data->subs", Yii::app()->createUrl("mysub/".$data->username))',
			'headerHtmlOptions'=>array('width'=>'50px'),
			'sortable'=>true,
		),
		array(
			'header'=>'Details',
			'type'=>'html',
			'value'=>'CHtml::link( "Details", Yii::app()->createUrl("user/view/".$data->id))',
			'headerHtmlOptions'=>array('width'=>'50px'),
		),
	),
)); ?>
