<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
	<?php 
	if( strtolower($this->id) == 'site' and strtolower($this->action->Id) == 'index'){
	?>
	<meta name="description" content="Samesub is a space where only one subject is transmitted at a time in a synchronous manner, thus, everyone connected to the site interact with that same subject">
	<?php } ?>
	<meta name="keywords" content="<?php echo  str_replace(" ", ",", str_replace(",", "", $this->pageTitle));?>">

	<?php if(strtolower($this->id) != 'site' or strtolower($this->action->Id) != 'index'){	?>
		<!-- blueprint CSS framework -->
		<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
		<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
		<!--[if lt IE 8]>
		<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
		<![endif]-->

		<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
		<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />
		<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/core.css" />
	<?php
		Yii::app()->clientScript->registerCoreScript('jquery');		
	}else{
	?>
		<script type="text/javascript">
		
		var preload_time_passed = 0;
		window.setTimeout(function () { preload_time_passed = 5;},5000);
		
		var element1 = document.createElement("link");
		element1.type="text/css";
		element1.rel = "stylesheet";
		element1.href = "<?php echo Yii::app()->getRequest()->getBaseUrl(true);?>/css/core.css";
		document.getElementsByTagName("head")[0].appendChild(element1);

		var element2 = document.createElement("script");
		element2.src = "<?php echo Yii::app()->getRequest()->getBaseUrl(true);?>/site/js/core";
		element2.type="text/javascript";
		document.getElementsByTagName("head")[0].appendChild(element2);

		</script>
		<style>
		
		</style>
	<?php
		
	}
	?>
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>
<noscript>Your browser does NOT support javascript or has it disabled. Please click <?php echo CHtml::link('here',Yii::app()->getRequest()->getBaseUrl(true).'/index/noscript'); ?> if you want to use this site without javascript or enable the javascript feature in your browser and reload the page.</noscript>

<?php 
if( Yii::app()->session->get('site_loaded') != "yes" and (strtolower($this->id) == 'site' and strtolower($this->action->Id) == 'index')){
?>
<div id="preload" style="position:fixed; width: 680px; left: 50%; margin:20px 0px 0px -340px;font-family: Trebuchet MS, Arial, Helvetica, sans-serif;">
	<div style="font-size: 12px;"><b>NOW: </b><?php echo '<a href="'.Yii::app()->createUrl('subject/index').'">'.$this->pageTitle.'</a>';?></div>
	<div style="background-color: #336699; font-size: 170px; color: white; font-weight:bold; margin: 20px 0px 20px 0px;">samesub</div>
	<hr style="border: 1px solid grey;" />
	<div style="font-size: 20px; color:#303030;"><?php echo Notification::getNotification()->note;?></div>
	<hr style="border: 1px solid grey;" />
	<div style="margin:50px 0px 0px 0px; font-size: 16px;">Page is loading, get ready ...</div>
</div>
<?php 
}else{
?>
<script>
preload_time_passed = 5;
</script>
<?php
}
?>
<div id="page" class="container" <?php echo (Yii::app()->session->get('site_loaded') != "yes" and (strtolower($this->id) == 'site' and strtolower($this->action->Id) == 'index')) ? 'style="display:none;"' : '';?>>
	<div id="header" class="bounded">
		<div id="header_top"><?php if(strtolower($this->id) != 'site' or strtolower($this->action->Id) != 'index')
		echo '<a href="'.Yii::app()->params['weburl'].'">LIVE: '. Notification::getNotification()->live. '</a>'; ?></div>
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
					<span><a href="<? echo Yii::app()->getRequest()->getBaseUrl(true);?>/site/contact">Feedback</a></span>
					<span> | <?php echo (Yii::app()->user->isGuest) ? '<a href="'. Yii::app()->createUrl('site/login').'">Login</a>' 
					:  '<a href="'. Yii::app()->createUrl('mysub/'.Yii::app()->user->name).'">Mysub'
					.'</a>| <span><a href="'. Yii::app()->createUrl('user').'">Settings</a></span>'
					.'| <span><a href="'. Yii::app()->createUrl('site/logout').'">Logout</a></span>';?></span>
					<?php if(strtolower(Yii::app()->controller->action->id) == 'index' and strtolower(Yii::app()->controller->id) == 'site')
					echo '<span><b> | UTC NOW: </b></span><span id="utc_clock"></span>'; ?>
				</div>
			</div>
		</div>
		<div class="clear_both"></div>
		<?php $this->widget('zii.widgets.CBreadcrumbs', array(
              'links'=>$this->breadcrumbs,
		)); ?><!-- breadcrumbs -->
	</div>
	<div id="main_body" class="bounded">
	
	<?php echo $content; ?>
	
	</div>
	<br class="clear_both">
	<hr class="page_hrline">
	<div id="footer" class="bounded">
		
			<span style="margin-right:20px">&copy; <?php echo date('Y'); ?> Samesub</span>
			<span><a href="<? echo Yii::app()->getRequest()->getBaseUrl(true);?>/site/contact">Contact us</a></span>
			<span><b> | </b><a href="<? echo Yii::app()->getRequest()->getBaseUrl(true);?>/site/page/view/about">About</a></span>
			<span><b> | </b><a href="<? echo Yii::app()->getRequest()->getBaseUrl(true);?>/site/page/view/faq">FAQ</a></span>
			<span><b> | </b><a href="<? echo Yii::app()->getRequest()->getBaseUrl(true);?>/site/page/view/terms">Terms of Use</a></span>
			<span><b> | </b><a href="<? echo Yii::app()->getRequest()->getBaseUrl(true);?>/site/page/view/privacy">Privacy Statement</a></span>
		<br/>
	</div>

</div>

</body>
</html>