<div id="column_2_container">
	<div id="column_left">
		<div><?php echo SiteHelper::get_user_picture((int)$model->user_id,'small_','profile');?></div>
		<div>
			<?php 
			$user = User::model()->with('ucountry')->findByPk($model->user_id);
			echo CHtml::link($user->username,array('profile/'.$user->username));?>
			<br>
			<?php echo $user->firstname . ' '. $user->lastname; ?>
			<?php if ($user->sex) {
				echo "<br><h4>".Yii::t('user','Sex').":</h4>";
				echo ($user->sex == 1) ? Yii::t('site','Male') : Yii::t('site','Female'); 
			}
			?>
					
			<?php 
				if($user->country_id){
				echo "<br><h4>".Yii::t('site','Country').":</h4>";
				echo ucwords(strtolower($user->ucountry->name)); 
			}
			?>
			<?php 
				if($user->about and $user->share_about == 1){
				echo "<br><h4>".Yii::t('user','About').":</h4>";
				echo $user->about; 
			}
			?>
			<br><br>
			<?php 
			echo CHtml::link(Yii::t('user','View Profile'),array('profile/'.$user->username)).'<br>';
			if(Yii::app()->user->id == (int)$model->user_id) echo CHtml::link(Yii::t('user','Update Profile'),array('user/update')); ?>
		</div>
	</div>
	<div id="column_right">
		<h1><?php echo Yii::t('subject','{username} subs',array('{username}'=>$username));?></h1>
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