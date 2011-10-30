<div id="column_2_container">
	<div id="column_left">
		<div><?php echo SiteHelper::get_user_picture((int)$model->user_id,'medium_','mysub');?></div>
		<div>
			<?php 
			$user = User::model()->with('ucountry')->findByPk($model->user_id);
			echo CHtml::link($user->username,array('mysub/'.$user->username));?>
			<br>
			<?php echo $user->firstname . ' '. $user->lastname; ?>
			<?php if ($user->sex) {
				echo "<br><h4>Sex:</h4>";
				echo ($user->sex == 1) ? 'Male' : 'Female'; 
			}
			?>
					
			<?php 
				if($user->country_id){
				echo "<br><h4>Country:</h4>";
				echo ucwords(strtolower($user->ucountry->name)); 
			}
			?>
			<?php 
				if($user->about){
				echo "<br><h4>About:</h4>";
				echo $user->about; 
			}
			?>
			<br><br>
			<?php if(Yii::app()->user->id == (int)$model->user_id) echo CHtml::link('Update Profile',array('user/update')); ?>
		</div>
	</div>
	<div id="column_right">
		<h1><?php echo $username;?> subs</h1>
		<?php echo SiteHelper::share_links($username,$username.' subs','/mysub/'); ?>
		<?php
		$dataProvider=$model->search();
		$dataProvider->pagination->pageSize=5;
		$this->widget('zii.widgets.CListView', array(
			'dataProvider'=>$dataProvider,
			'itemView'=>'_view',
		)); ?>
	</div>
</div>