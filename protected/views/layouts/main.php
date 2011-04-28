<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
	
	<script>
		<?php $time = SiteLibrary::utc_time(); ?>
		var baseUrl = "<?php echo Yii::app()->getRequest()->getBaseUrl(true);?>";
		var utc_time = <?php echo $time;?>;
		var utc_hour = <?php echo date("h",$time); ?>;
		var utc_min = <?php echo date("i",$time); ?>;
		var utc_sec = <?php echo date("s",$time); ?>;
	</script>
	<?php if(Yii::app()->session->get('site_loaded')){
	?>
		<!-- blueprint CSS framework -->
		<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
		<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
		<!--[if lt IE 8]>
		<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
		<![endif]-->

		<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
		<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />
		<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/core.css" />
		<script>
		var preload_time_passed = 100;//no need to wait
		</script>
	<?php
		if (strtolower($this->id) == 'site' and strtolower($this->action->Id) == 'index'){
			Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl."/js/core.js", CClientScript::POS_END);
		}else{
			Yii::app()->clientScript->registerCoreScript('jquery');
		}
	}else{
	?>
		<script type="text/javascript">
		var preload_time_passed = 0;
		window.setTimeout(function () { preload_time_passed = 5;},5000);
		
		var element1 = document.createElement("link");
		element1.type="text/css";
		element1.rel = "stylesheet";
		element1.href = baseUrl+"/css/core.css";
		document.getElementsByTagName("head")[0].appendChild(element1);

		var element2 = document.createElement("script");
		element2.src = baseUrl+"/js/core.js";
		element2.type="text/javascript";
		document.getElementsByTagName("head")[0].appendChild(element2);

		</script>
		<style>
		#page{display:none;}
		</style>
	<?php
		
	}
	?>

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>
<noscript>Your browser does NOT support javascript or has it disabled. Please click <?php echo CHtml::link('here',Yii::app()->getRequest()->getBaseUrl(true).'/index/noscript'); ?> if you want to use this site without javascript or enable the javascript feature in your browser and reload the page.</noscript>

<?php if(! Yii::app()->session->get('site_loaded')){
?>
<div id="preload" style="position:fixed; width: 680px; left: 50%; margin:20px 0px 0px -340px;font-family: Trebuchet MS, Arial, Helvetica, sans-serif;">
	<div style="font-size: 12px;"><b>NOW: </b><?php echo Notification::getNotification()->live;?></div>
	<div style="background-color: #336699; font-size: 170px; color: white; font-weight:bold; margin: 20px 0px 20px 0px;">samesub</div>
	<hr style="border: 1px solid grey;" />
	<div style="font-size: 20px; color:#303030;"><?php echo Notification::getNotification()->note;?></div>
	<hr style="border: 1px solid grey;" />
	<div style="margin:50px 0px 0px 0px; font-size: 16px;">Page is loading, get ready ...</div>
</div>
<?php 
}
?>
<div id="page">
	<div id="header" class="bounded">
		<div id="header_top"></div>
		<div id="header_middle">
			<div id="logo">HERE GOES THE LOGO</div>
			<div id="main_menu">
				<div id="menu_left">
					<div class="navigation">
							<?php $this->widget('zii.widgets.CMenu',array(
							'items'=>array(
								array('label'=>'Live', 'url'=>array('site/index')),
								array('label'=>'Add Subject', 'url'=>array('subject/add')),
								array('label'=>'History', 'url'=>array('subject/index')),
								array('label'=>'Manage', 'url'=>array('subject/manage')),
							),
						)); ?>					
					</div>
				</div>
				<div id="menu_right">
					<span><a href="<? echo Yii::app()->getRequest()->getBaseUrl(true);?>/site/feedback">Contact us</a></span>
					
					<?php if(strtolower(Yii::app()->controller->action->id) == 'index' and strtolower(Yii::app()->controller->id) == 'site')
					echo '<span><b> | UTC NOW: </b></span><span id="utc_clock"></span>'; ?>
				</div>
			</div>
		</div>
		<div class="clear_both"></div>
	</div>
	<div id="main_body" class="bounded">
	
	<?php echo $content; ?>
	
	</div>
	<br class="clear_both">
	<hr class="page_hrline">
	<div id="footer" class="bounded">
		
			<span style="margin-right:20px">&copy; <?php echo date('Y'); ?> Samesub</span>
			<span><a href="<? echo Yii::app()->getRequest()->getBaseUrl(true);?>/site/feedback">Contact us</a></span>
			<span><b> | </b><a href="<? echo Yii::app()->getRequest()->getBaseUrl(true);?>/site/about">About</a></span>
			<span><b> | </b><a href="<? echo Yii::app()->getRequest()->getBaseUrl(true);?>/site/faq">FAQ</a></span>
			<span><b> | </b><a href="<? echo Yii::app()->getRequest()->getBaseUrl(true);?>/site/terms">Terms of Use</a></span>
			<span><b> | </b><a href="<? echo Yii::app()->getRequest()->getBaseUrl(true);?>/site/privacy">Privacy Statement</a></span>
		<br/>
	</div>

</div>

</body>
</html>
<?php
if(! Yii::app()->session->get('site_loaded')) Yii::app()->session->add('site_loaded', 'yes');
?>