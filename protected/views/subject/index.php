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
            'name'=>'user_id',
			'type'=>'html',
            'value'=>'CHtml::link(User::model()->findByPk($data->user_id)->username,Yii::app()->getRequest()->getBaseUrl(true)."/mysub/".User::model()->findByPk($data->user_id)->username)',
			'filter'=>'',
			'headerHtmlOptions'=>array('width'=>'90px'),
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
            'name'=>'category',
			'filter'=>CHtml::listData(Yii::app()->db->createCommand()
			->select('name')
			->from('subject_category')
			->queryAll(),'name','name'),//categories a treated just like tags( we dont use ids to store them in db) so we use name as id
			'sortable'=>true,
        ),
		array(
			'header'=>'Subject',
            'name'=>'title',
			'type'=>'html',
			'value'=>'CHtml::link($data->title,Yii::app()->getRequest()->getBaseUrl(true)."/sub/".$data->urn)',
        ),

	),
	'enablePagination'=>true,
)); ?>
