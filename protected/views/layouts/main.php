<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
	<?php echo $this->ogtags; ?>
	<?php 
	if( strtolower($this->id) == 'site' and strtolower($this->action->Id) == 'index'){
	?>
	<meta name="description" content="<?php echo Yii::t('site','Samesub is a space where only one subject is transmitted at a time in a synchronous manner, thus, everyone connected to the site interact with that same subject');?>">
	<?php } ?>
	<meta name="keywords" content="<?php echo  str_replace(" ", ",", str_replace(",", "", $this->pageTitle));?>">

	<?php
	$filepath = Yii::app()->params['webdir'];
	if(strtolower($this->id) != 'site' or strtolower($this->action->Id) != 'index'){	?>
		<!-- blueprint CSS framework -->
		<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen-<?php echo filemtime($filepath.'/css/screen.css'); ?>.css" media="screen, projection" />
		<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print-<?php echo filemtime($filepath.'/css/print.css'); ?>.css" media="print" />
		<!--[if lt IE 8]>
		<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
		<![endif]-->

		<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main-<?php echo filemtime($filepath.'/css/main.css'); ?>.css" />
		<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form-<?php echo filemtime($filepath.'/css/form.css'); ?>.css" />
		<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/core-<?php echo filemtime($filepath.'/css/core.css'); ?>.css" />
	<?php
		Yii::app()->clientScript->registerCoreScript('jquery');
		Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/site-'.filemtime($filepath.'/js/core.js').'.js');
	}else{
	?>
		<script type="text/javascript">
		
		var preload_time_passed = 0;
		window.setTimeout(function () { preload_time_passed = 5;},5000);
		
		var element1 = document.createElement("link");
		element1.type="text/css";
		element1.rel = "stylesheet";
		element1.href = "<?php echo Yii::app()->getRequest()->getBaseUrl(true);?>/css/core-<?php echo filemtime($filepath.'/css/core.css'); ?>.css";
		document.getElementsByTagName("head")[0].appendChild(element1);

		<?php $time = SiteLibrary::utc_time(); ?>

		var utc_time = <?php echo $time;?>;
		var utc_hour = <?php echo date("H",$time); ?>;
		var utc_min = <?php echo date("i",$time); ?>;
		var utc_sec = <?php echo date("s",$time); ?>;

		var element2 = document.createElement("script");
		element2.src = "<?php echo Yii::app()->getRequest()->getBaseUrl(true);?>/js/core-<?php echo filemtime($filepath.'/js/core.js'); ?>.js";
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
<noscript>Your browser does NOT support javascript or has it disabled. Please click <?php echo CHtml::link('here',Yii::app()->getRequest()->getBaseUrl(true).'/index'); ?> if you want to use this site without javascript or enable the javascript feature in your browser and reload the page.</noscript>

<?php 
if( Yii::app()->session->get('site_loaded') != "yes" and (strtolower($this->id) == 'site' and strtolower($this->action->Id) == 'index')){
?>
<div id="preload" style="padding-top:100px; position:fixed; width: 680px; left: 50%; margin:20px 0px 0px -340px;font-family: Trebuchet MS, Arial, Helvetica, sans-serif;">
	<div style="text-align:center; padding:50px;"><a href="<?php echo Yii::app()->createUrl('site/index');?>"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/logo.jpg"></a></div>	
	<div style="font-size: 20px; color:#303030;"><?php echo Notification::getNotification()->note;?></div>	
	<div style="margin:150px 0px 0px 0px; font-size: 12px; color:grey;"><b><?php echo Yii::t('site','NOW: {live_title}', array('{live_title}'=>'</b><a href="'.Yii::app()->createUrl('subject/index').'" style="text-decoration: none; color: grey;">'.$this->pageTitle.'</a>'));?></div>
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
		<div id="header_top">
		<?php if(strtolower(Yii::app()->controller->action->id) == 'index' and strtolower(Yii::app()->controller->id) == 'site'){
				
		} else { ?>
			<iframe src="about:blank" width="980" height="30" id="header_top_frame" frameBorder="0" scrolling="no" style="background-color:white; z-index:9000; position:absolute;"></iframe>
		<?php } ?>
		</div>
		<div id="header_middle">
			<div id="logo"><a href="<?php echo Yii::app()->createUrl('site/index');?>"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/logo.jpg"></a></div>
			<div id="main_menu">
				<div id="menu_left">
					<div class="navigation">
							<?php $this->widget('zii.widgets.CMenu',array(
							'items'=>array(
								array('label'=>Yii::t('site','Live'), 'url'=>array('site/index')),
								array('label'=>" | "),
								array('label'=>Yii::t('site','Add Subject'), 'url'=>array('subject/add')),
								array('label'=>" | "),
								array('label'=>Yii::t('site','Mysub'), 'url'=>array((Yii::app()->user->isGuest) ? '/mysub' : 'mysub/'.Yii::app()->user->name)),
								array('label'=>" | "),
								array('label'=>Yii::t('site','History'), 'url'=>array('subject/index')),

							),
						)); ?>					
					</div>
				</div>
				<div id="menu_right">					
					<span><?php echo (Yii::app()->user->isGuest) ? '<a href="'. Yii::app()->createUrl('site/login').'">'.Yii::t('site','Login').'</a>' 
					:  '<span>'.SiteHelper::get_user_picture((int)Yii::app()->user->id,'icon_','profile').'|<a href="'. Yii::app()->createUrl('profile/'.Yii::app()->user->name).'">'.Yii::app()->user->name.'</a></span>'
					.'| <span><a href="'. Yii::app()->createUrl('site/logout').'">'.Yii::t('site','Logout').'</a></span>';?></span>										
				</div>
			</div>
		</div>
		<div class="clear_both"></div>
		<?php $this->widget('zii.widgets.CBreadcrumbs', array(
              'links'=>$this->breadcrumbs,
		)); ?><!-- breadcrumbs -->
	</div>
	<div id="main_body" class="bounded">
	<?php if (Yii::app()->user->hasFlash('layout_flash_success')):?>
		<div class="flash-success">
			<?php echo Yii::app()->user->getFlash('layout_flash_success'); ?>
		</div>
	<?php endif; ?>
	<?php if (Yii::app()->user->hasFlash('layout_flash_error')):?>
		<div class="flash-error">
			<?php echo Yii::app()->user->getFlash('layout_flash_error'); ?>
		</div>
	<?php endif; ?>
	<?php if (Yii::app()->user->hasFlash('layout_flash_notice')):?>
		<div class="flash-notice">
			<?php echo Yii::app()->user->getFlash('layout_flash_notice'); ?>
		</div>
	<?php endif; ?>
	
	<?php echo $content; ?>
	
	</div>
	<br class="clear_both">
	<hr class="page_hrline" style="margin-top:100px;">
	<div id="footer" class="bounded">
		
			<span style="margin-right:20px">&copy; <?php echo date('Y'); ?> Samesub</span>
			<span><a href="<? echo Yii::app()->getRequest()->getBaseUrl(true);?>/site/contact"><?php echo Yii::t('site','Contact us');?></a></span>
			<span><b> | </b><a href="<? echo Yii::app()->getRequest()->getBaseUrl(true);?>/site/page/view/about"><?php echo Yii::t('site','About');?></a></span>
			<span><b> | </b><a href="<? echo Yii::app()->getRequest()->getBaseUrl(true);?>/site/page/view/faq"><?php echo Yii::t('site','FAQ');?></a></span>
			<span><b> | </b><a href="<? echo Yii::app()->getRequest()->getBaseUrl(true);?>/developers"><?php echo Yii::t('site','Developers/API');?></a></span>
			<span><b> | </b><a href="<? echo Yii::app()->getRequest()->getBaseUrl(true);?>/site/page/view/terms"><?php echo Yii::t('site','Terms of Use');?></a></span>
			<span><b> | </b><a href="<? echo Yii::app()->getRequest()->getBaseUrl(true);?>/site/page/view/privacy"><?php echo Yii::t('site','Privacy Statement');?></a></span>
			<?php if(strtolower(Yii::app()->controller->action->id) == 'index' and strtolower(Yii::app()->controller->id) == 'site') echo '<span><b> | '.Yii::t('site','UTC NOW:').' </b></span><span id="utc_clock"></span><br />'; ?>
		<br/>
	</div>

</div>
<?php echo Yii::app()->params['webanalytics_html'];?>
</body>
</html>