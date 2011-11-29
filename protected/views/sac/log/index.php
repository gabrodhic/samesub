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
<style>
.show_hide_content{
display:none}
</style>
<h1>System Log</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

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
			'headerHtmlOptions'=>array('width'=>'30px'),
			'sortable'=>true,
        ),
		array(
            'name'=>'session_id',
			//'value'=>
			'headerHtmlOptions'=>array('width'=>'30px'),
        ),
		array(
            'name'=>'time',
            'value'=>'date("Y/m/d H:i:s", $data->time)',
			'headerHtmlOptions'=>array('width'=>'420px'),
			//'filter'=>'',
			'sortable'=>true,
        ),
		array(
            'name'=>'username',
			//http://localhost/samesub/mysub/super
			'type'=>'html',
			'value'=>'CHtml::link( $data->username, Yii::app()->createUrl("mysub/".$data->username))',
			'headerHtmlOptions'=>array('width'=>'80px'),
			'sortable'=>true,
        ),
		array(
            'name'=>'controller',
			'headerHtmlOptions'=>array('width'=>'100px'),
        ),
		array(
            'name'=>'action',
			'type'=>'html',
			'value'=>'CHtml::link($data->action,Yii::app()->getRequest()->getBaseUrl(true)."".$data->uri)',
			'headerHtmlOptions'=>array('width'=>'100px'),
        ),
		array(
            'name'=>'theme',
			'filter'=>array('re'=>'Regular','mo'=>'Mobile'),
			'headerHtmlOptions'=>array('width'=>'10px'),
        ),
		array(
            'name'=>'device',
			'filter'=>array('mo'=>'Mobile','de'=>'Desktop'),
			'headerHtmlOptions'=>array('width'=>'10px'),
        ),
		array(
            'name'=>'country',
			'headerHtmlOptions'=>array('width'=>'400px'),
			'filter'=>CHtml::listData(Country::model()->findAll(),'id','name'),
			'sortable'=>true,
        ),
		array(
			'header'=>'Details',
			'filter'=>'<a href="#" class="show_button" onClick="$(\'.show_hide_content\').show()">Show</a>/<a href="#" class="hide_button" onClick="$(\'.show_hide_content\').hide()">Hide</a>',
			'type'=>'html',
			'value'=>'CHtml::decode("<span class=\"show_button\">Show</span>/<span class=\"hide_button\">Hide</span><div class=\"show_hide_content\" style=\"display:none;\">$data->charset <br> $data->language <br> $data->referer <br> $data->agent <br> $data->request_ip <rb> $data->client_ip</div>")',
			'headerHtmlOptions'=>array('width'=>'400px'),
			//'filter'=>CHtml::listData(Country::model()->findAll(),'id','name'),
			//'sortable'=>true,
        ),

	),
	'enablePagination'=>true,
)); ?>
<script>

$('.hide_button').click(function () {$('.show_hide_content').hide();});
$('.show_button').click(function () {$('.show_hide_content').show();});
</script>