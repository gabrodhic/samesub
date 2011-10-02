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

<p>Legend: <span class="row_red">RED</span> => Live NOW,  <span class="row_yellow">YELLOW</span> => Comming up</p>
<?php 
	$dataProvider=$model->search();
	$dataProvider->pagination->pageSize=50;
	$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'subject-grid',
	'dataProvider'=>$dataProvider,
	'filter'=>$model,
	'rowCssClass'=>'something',//we just want to not implement the default css
	'columns'=>array(
		array(
            'name'=>'id',
			'id'=>$live_subject["subject_id_1"].'_'.$live_subject["subject_id_2"],//id its just a temporal space for the $live_subject variable since we cant access it on the cssexpression
            'value'=>'$data->id',
			'headerHtmlOptions'=>array('width'=>'25px'),
			'cssClassExpression'=>'($data->id == substr($this->id, 0, strpos($this->id, "_"))) ? row_red : (($data->id == substr(strrchr($this->id, "_"), 1 )) ? row_yellow : something)',
			'sortable'=>true,
        ),
		array(
            'name'=>'user_id',
            'value'=>'$data->user_id',
			'headerHtmlOptions'=>array('width'=>'25px'),
			'sortable'=>true,
        ),
		array(
            'name'=>'country_id',
            'value'=>'$data->country->name',
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
            'name'=>'title',
			'headerHtmlOptions'=>array('width'=>'410px'),
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
            'name'=>'time_submitted',
            'value'=>'date("Y/m/d H:i", $data->time_submitted)',
			'headerHtmlOptions'=>array('width'=>'100px'),
			'sortable'=>true,
        ),
		array(
			'name'=>'approved',
			'type'=>'html',
			'value'=>'CHtml::link(SiteHelper::yesno($data->approved),"moderate/".$data->id)',
			'headerHtmlOptions'=>array('width'=>'20px'),
			'filter'=>array('0'=>'No','1'=>'Yes'),
			'sortable'=>true,
		),
		array(
			'name'=>'authorized',
			'type'=>'html',
			'value'=>'CHtml::link(SiteHelper::yesno($data->authorized),"authorize/".$data->id)',
			'headerHtmlOptions'=>array('width'=>'20px'),
			'filter'=>array('0'=>'No','1'=>'Yes'),
			'sortable'=>true,
		),
		array(
			'name'=>'disabled',
			'value'=>'SiteHelper::yesno($data->disabled)',
			'headerHtmlOptions'=>array('width'=>'20px'),
			'filter'=>array('0'=>'No','1'=>'Yes'),
			'sortable'=>true,
		),
	),
	'enablePagination'=>true,
)); ?>
