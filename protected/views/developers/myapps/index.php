<h1>My Apps</h1>


<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'oauth-server-registry-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'osr_application_title',
		'osr_application_descr',
		array(
			'name'=>'osr_enabled',
			'headerHtmlOptions'=>array('width'=>'20px'),
			'value'=>'SiteHelper::yesno($data->osr_enabled)',
			'filter'=>array('0'=>'No','1'=>'Yes'),
			'sortable'=>true,
		),
		array(
			'name'=>'osr_status',
			'headerHtmlOptions'=>array('width'=>'80px'),
			'sortable'=>true,
		),
		/*
		'osr_requester_name',
		'osr_requester_email',
		'osr_callback_uri',
		'osr_application_uri',
		'osr_application_title',
		'osr_application_descr',
		'osr_application_notes',
		'osr_application_type',
		'osr_application_commercial',
		'osr_issue_date',
		'osr_timestamp',
		*/
		array(
			'class'=>'CButtonColumn',
			 'template'=>'{delete}{update}',
		),
	),
)); ?>
