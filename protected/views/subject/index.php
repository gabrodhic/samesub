<?php

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

<h1>Subjects History</h1>

<p>
The following list shows all the subjects that has been placed in the live stream(homepage). Note that the time is in UTC.
</p>


<?php 
	$dataProvider=$model->search('show_time DESC');
	$dataProvider->pagination->pageSize=50;
	$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'subject-grid',
	'dataProvider'=>$dataProvider,
	'filter'=>$model,
	'rowCssClass'=>'something',//we just want to not implement the default css
	'columns'=>array(
		/*array(
            'name'=>'time_submitted',
            'value'=>'date("Y/m/d H:i", $data->time_submitted)',
			'headerHtmlOptions'=>array('width'=>'100px'),
			'sortable'=>true,
        ),*/
		array(
            'name'=>'show_time',
            'value'=>'date("Y/m/d H:i", $data->show_time)." UTC"',
			'headerHtmlOptions'=>array('width'=>'120px'),
			'filter'=>'',
			'sortable'=>true,
        ),
		array(
            'name'=>'priority_id',
            'value'=>'$data->priority_type->name',
			'headerHtmlOptions'=>array('width'=>'50px'),
			'filter'=>CHtml::listData(Priority::model()->findAll(),'id','name'),
			'sortable'=>true,
        ),
		array(
            'name'=>'content_type_id',
            'value'=>'$data->content_type->fullname',
			'headerHtmlOptions'=>array('width'=>'50px'),
			'filter'=>CHtml::listData(ContentType::model()->findAll(),'id','name'),
			'sortable'=>true,
        ),
		array(
            'name'=>'country_id',
            'value'=>'$data->country->name',
			'headerHtmlOptions'=>array('width'=>'100px'),
			'filter'=>CHtml::listData(Country::model()->findAll(),'id','name'),
			'sortable'=>true,
        ),
		array(
            'name'=>'title',
			'type'=>'html',
			'value'=>'CHtml::link($data->title,"view/".$data->id)',
        ),

	),
	'enablePagination'=>true,
)); ?>
