<h1><?php echo $username;?> subs</h1>
<?php
$dataProvider=$model->search();
$this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
	