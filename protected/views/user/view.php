<?php
$this->breadcrumbs=array(
	Yii::t('site','Users')=>array('index'),
	Yii::t('site','Profile'),
);

?>
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl;?>/css/nyroModal.css" type="text/css" media="screen" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl;?>/js/jquery.nyroModal.custom.min.js"></script>
<!--[if IE 6]>
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl;?>/js/jquery.nyroModal-ie6.min.js"></script>
<![endif]-->
<script type="text/javascript">
$(function() {
  $('.nyroModal').nyroModal(
  {
  anim: {	// Animation names to use
    def: donothing,			// Default animation set to use if sspecific are not defined or doesn't exist
    showBg: donothing,		// Set to use for showBg animation
    hideBg: donothing,		// Set to use for hideBg animation
    showLoad: donothing,	// Set to use for showLoad animation
    hideLoad: donothing,	// Set to use for hideLoad animation
    showCont: donothing,	// Set to use for showCont animation
    hideCont: donothing,	// Set to use for hideCont animation
    showTrans: donothing,	// Set to use for showTrans animation
    hideTrans: donothing,	// Set to use for hideTrans animation
    resize: donothing		// Set to use for resize animation
  }
  }
  );
  
  function donothing(){}
});

</script>

<?php
$this->pageTitle=Yii::app()->name . ' - '. $model->firstname.' '.$model->lastname.' ('.$model->username.')';
?>

<div id="left_container" class="form">
		<div style="float:left;" class="nyroModal"><?php echo SiteHelper::get_user_picture((int)$model->id,'medium_','image');?></div>
		<div style="float:left; padding-left:10px">
			<h2 style="padding:0px"><?php 			
			echo $model->firstname .' '.$model->lastname;?></h2>
			<?php echo CHtml::link($model->username,array('mysub/'.$model->username)); ?>
			<br><?php if ($model->sex) echo($model->sex == 1) ? Yii::t('site','Male') : Yii::t('site','Female');?>
			<br><?php if($model->country_id) echo ucwords(strtolower($model->ucountry->name)); ?>
		</div>
		<div class="clear_both"></div>
		<br><br>
		<div class="detail_section">
			<h3 class="detail_header"><?php echo Yii::t('user','User Information');?></h3>
			
			<table border="0" width="100%">
				<?php 
				echo '<tr>
					<td width="80" class="detail_cell"><b>'.Yii::t('user','Member Since').'</b></td>
					<td class="detail_cell">'.date("Y/m/d",$model->time_created).'</td>
				</tr>';

				if($model->region and $model->share_region == 1 ){
				echo '<tr>
					<td width="80" class="detail_cell"><b>'.Yii::t('site','Region').'</b></td>
					<td class="detail_cell">'.CHtml::encode($model->region).'</td>
				</tr>';
				}
				if($model->city and $model->share_city == 1 ){
				echo '<tr>
					<td width="80" class="detail_cell"><b>'.Yii::t('site','City').'</b></td>
					<td class="detail_cell">'.$model->city.'</td>
				</tr>';
				}
				if($model->email and $model->share_email == 1){
				echo '<tr>
					<td width="80" class="detail_cell"><b>'.Yii::t('site','Email').'</b></td>
					<td class="detail_cell">';
					if(Yii::app()->user->isGuest) {
						$this->widget('application.extensions.ETextImage.ETextImage',
							array(
								  'textImage' => $model->email,
								  'fontSize' => 12,
								  'fontFile' => 'arial',
								  'transparent'=>false,//buggy, when setting transparent, or dont understand well how it works
								  'foreColor'=>0x000000, 
								  'backColor'=>0xFFFFFF,
								 )
						   );
					   }else{
						echo CHtml::encode($model->email);
					   }
				echo '</td>
				</tr>';
				}
				if($model->birthdate and $model->share_birthdate == 1 ){
				$ageTime = $model->birthdate;
				$t = time();
				$age = ($ageTime < 0) ? ( $t + ($ageTime * -1) ) : $t - $ageTime;
				$year = 60 * 60 * 24 * 365;
				$ageYears = $age / $year;
				echo '<tr>
					<td width="80" class="detail_cell"><b>'.Yii::t('user','Birth Date').'</b></td>
					<td class="detail_cell">'.Yii::t('user','{birthdate} Age: {years_old} years old.',array('{birthdate}'=>date("Y/m/d",$model->birthdate),'{years_old}'=>floor($ageYears))).'</td>
				</tr>';
				}
				if($model->interests and $model->share_interests == 1 ){
				echo '<tr>
					<td width="80" class="detail_cell"><b>'.Yii::t('user','Interests').'</b></td>
					<td class="detail_cell">'.nl2br(CHtml::encode($model->interests)).'</td>
				</tr>';
				}
				if($model->activities and $model->share_activities == 1 ){
				echo '<tr>
					<td width="80" class="detail_cell"><b>'.Yii::t('user','Activities').'</b></td>
					<td class="detail_cell">'.nl2br(CHtml::encode($model->activities)).'</td>
				</tr>';
				}
				if($model->about and $model->share_about == 1 ){
				echo '<tr>
					<td width="80" class="detail_cell"><b>'.Yii::t('user','About').'</b></td>
					<td class="detail_cell">'.nl2br(CHtml::encode($model->about)).'</td>
				</tr>';
				}
			
			?>
			</table>
			
			<br>
			<?php if(Yii::app()->user->id == (int)$model->id) echo CHtml::link(Yii::t('user','Update Profile'),array('user/update')); ?>
		</div>
</div>
<div id="right_container">
	<div style="padding-left:30px;">
		<div class="detail_section">
			<h3 class="detail_header"><?php echo Yii::t('user','Statistics');?></h3>
			<div style="float:left;"><h4><?php echo Yii::t('user','Subs:');?></h4></div><div style="float:right;"><?php echo CHtml::link($stat_subs,array('mysub/'.$model->username)); ?></div>
			<div class="clear_both"></div>
			<div style="float:left;"><h4><?php echo Yii::t('user','Comments:');?></h4></div><div style="float:right;"><? echo $stat_comments;?></div>
			<div class="clear_both"></div>
			<div style="float:left;"><h4><?php echo Yii::t('user','Last time Online:');?></h4></div><div style="float:right;"><? echo  date("Y/m/d H:i:s", $stat_last_online);?></div>
			<div class="clear_both"></div>
			
		</div>
	</div>
</div>
