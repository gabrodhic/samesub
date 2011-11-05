<?php
$this->breadcrumbs=array(
	'Users'=>array('index'),
	'Profile',
);
?>

<?php
$this->pageTitle=Yii::app()->name . ' - '. $model->firstname.' '.$model->lastname.' ('.$model->username.')';
?>

<div id="left_container" class="form">
		<div style="float:left;"><?php echo SiteHelper::get_user_picture((int)$model->id,'medium_','image');?></div>
		<div style="float:left; padding-left:10px">
			<h2 style="padding:0px"><?php 			
			echo $model->firstname .' '.$model->lastname;?></h2>
			<?php echo CHtml::link($model->username,array('mysub/'.$model->username)); ?>
		</div>
		<div class="clear_both"></div>
		<br>
		<div class="detail_section">
			<h3 class="detail_header">User Information</h3>
			
			<table border="0" width="100%">
				<?php if ($model->sex) { 
				echo '<tr>
					<td width="80" class="detail_cell"><b>Member Since</b></td>
					<td class="detail_cell">'.date("Y/m/d",$model->time_created).'</td>
				</tr>';
				echo '<tr>
						<td width="80" class="detail_cell"><b>Sex</b></td>
						<td class="detail_cell">'; echo($model->sex == 1) ? 'Male' : 'Female';
				echo '</td>
					</tr>';
				 } 
				if($model->country_id){
				echo '<tr>
					<td width="80" class="detail_cell"><b>Country Name</b></td>
					<td class="detail_cell">'.ucwords(strtolower($model->ucountry->name)).'</td>
				</tr>';
				}
				if($model->region and $model->share_region == 1 ){
				echo '<tr>
					<td width="80" class="detail_cell"><b>Region</b></td>
					<td class="detail_cell">'.CHtml::encode($model->region).'</td>
				</tr>';
				}
				if($model->city and $model->share_city == 1 ){
				echo '<tr>
					<td width="80" class="detail_cell"><b>City</b></td>
					<td class="detail_cell">'.$model->city.'</td>
				</tr>';
				}
				if($model->email and $model->share_email == 1 ){
				echo '<tr>
					<td width="80" class="detail_cell"><b>Email</b></td>
					<td class="detail_cell">';
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
					<td width="80" class="detail_cell"><b>Birth Date</b></td>
					<td class="detail_cell">'.date("Y/m/d",$model->birthdate).' Age: '.floor($ageYears) . ' years old.</td>
				</tr>';
				}
				if($model->interests and $model->share_interests == 1 ){
				echo '<tr>
					<td width="80" class="detail_cell"><b>Interests</b></td>
					<td class="detail_cell">'.nl2br(CHtml::encode($model->interests)).'</td>
				</tr>';
				}
				if($model->activities and $model->share_activities == 1 ){
				echo '<tr>
					<td width="80" class="detail_cell"><b>Activities</b></td>
					<td class="detail_cell">'.nl2br(CHtml::encode($model->activities)).'</td>
				</tr>';
				}
				if($model->about and $model->share_about == 1 ){
				echo '<tr>
					<td width="80" class="detail_cell"><b>About</b></td>
					<td class="detail_cell">'.nl2br(CHtml::encode($model->about)).'</td>
				</tr>';
				}
			
			?>
			</table>
			
			<br>
			<?php if(Yii::app()->user->id == (int)$model->id) echo CHtml::link('Update Profile',array('user/update')); ?>
		</div>
</div>
<div id="right_container">
	<div style="padding-left:30px;">
		<div class="detail_section">
			<h3 class="detail_header">Statistics</h3>
			<div style="float:left;"><h4>Subs:</h4></div><div style="float:right;"><?php echo CHtml::link($stat_subs,array('mysub/'.$model->username)); ?></div>
			<div class="clear_both"></div>
			<div style="float:left;"><h4>Comments:</h4></div><div style="float:right;"><? echo $stat_comments;?></div>
			<div class="clear_both"></div>
			<div style="float:left;"><h4>Site usage counter:</h4></div><div style="float:right;"><? echo $stat_usage_counter;?></div>
			<div class="clear_both"></div>
			<div style="float:left;"><h4>Last time Online:</h4></div><div style="float:right;"><? echo  date("Y/m/d H:i:s", $stat_last_online);?></div>
			<div class="clear_both"></div>
			
		</div>
	</div>
</div>
