<?php
$this->pageTitle=Yii::app()->name . ' - About';
$this->breadcrumbs=array(
	'About',
);
?>
<h1>About</h1>
</br>
<p></p>
<p>
<?php echo nl2br(Yii::t('site','ssAboutUsDescription'));?>
</p>
