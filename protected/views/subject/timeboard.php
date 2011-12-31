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

<h1>Time Board</h1>

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
<script>

function change_position(id){
	
}

///function bind_click_positions(){
  $(".set_position").live('click', function(){
	var id = $(this).html();
	$.fn.yiiGridView.update("subject-grid", {url:"",data:{"id":id, "day":$("#Subject_Position_day_"+id).val()
	,"hour":$("#Subject_Position_hour_"+id).val(), "minute":$("#Subject_Position_minute_"+id).val()   } });
  });
//}

//Auto update the grid timeboard every subject change interval

function refresh_timeboard()
{  
  $.fn.yiiGridView.update("subject-grid", {url:""});
  //self.setInterval("refresh_timeboard()",<?php echo (Yii::app()->params['subject_interval'] * 60);?>000);
}


</script>


<p>Legend: <span class="row_red">RED</span> => Live NOW</p>
<?php 
	$dataProvider=$model->search('t.position ASC');
	$dataProvider->pagination->pageSize=30;
	$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'subject-grid',
	'dataProvider'=>$dataProvider,
	'filter'=>$model,
	'rowCssClass'=>'something',//we just want to not implement the default css
	'columns'=>array(
		array(
            'name'=>'id',
			'id'=>$live_subject["subject_id"].'_'.$live_subject["subject_id_2"],//id its just a temporal space for the $live_subject variable since we cant access it on the cssexpression
			'type'=>'html',
            'value'=>'"<div class=\"set_position\" onClick=\"\">".$data->id."</div>"',
			'headerHtmlOptions'=>array('width'=>'25px'),
			'cssClassExpression'=>'($data->id == substr($this->id, 0, strpos($this->id, "_"))) ? row_red : something',
			'sortable'=>true,
        ),
		array(
            'name'=>'position',
			'header'=>'Position Da/Ho/Mi',
			'filter'=>'',
			'type'=>'raw',
            'value'=>'CHtml::DropDownList("Subject_Position_day_".$data->id, date("j",$data->position), SiteLibrary::get_time_intervals("day")) . CHtml::DropDownList("Subject_Position_hour_".$data->id, date("G",$data->position), SiteLibrary::get_time_intervals("hour")). CHtml::DropDownList("Subject_Position_minute_".$data->id, date("i",$data->position), SiteLibrary::get_time_intervals("minute"))',
			'headerHtmlOptions'=>array('width'=>'175px'),
			'sortable'=>true,
        ),
		array(
            'name'=>'user_position',
			'header'=>'User Position',
			'type'=>'raw',
			'value'=>'($data->user_position) ? date("d",$data->user_position) . " " . date("H",$data->user_position) ." ". date("i",$data->user_position) : "--  --  --"',
		),
		array(
            'name'=>'manager_position',
			'header'=>'Manager Position',
			'type'=>'raw',
			'value'=>'($data->manager_position) ? date("d",$data->manager_position) . " " . date("H",$data->manager_position) ." ". date("i",$data->manager_position) : "--  --  --"',
		),
		array(
            'name'=>'user_id',
   			'type'=>'html',
            'value'=>'CHtml::link(User::model()->findByPk($data->user_id)->username,Yii::app()->getRequest()->getBaseUrl(true)."/mysub/".User::model()->findByPk($data->user_id)->username)',
			'filter'=>'',
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
			'headerHtmlOptions'=>array('width'=>'200px'),
			'type'=>'html',
			'value'=>'CHtml::link($data->title,Yii::app()->getRequest()->getBaseUrl(true)."/sub/".$data->urn)',
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
	),
	'enablePagination'=>true,
)); ?>
