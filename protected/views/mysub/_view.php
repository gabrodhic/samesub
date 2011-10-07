<div style="padding-bottom:75px;">

	<div class="row">
		<a style="font-size: 20px;color: #blue;font-weight: bold;font-family: arial;" href="<?php echo Yii::app()->createUrl('sub/'.$data->urn);?>"><?php echo CHtml::encode($data->title); ?></a>
	</div>
	
	<div class="row">
		<?php echo $data->country->name . " | "; ?>
		<?php echo CHtml::encode(date("Y/m/d H:i", $data->time_submitted)." UTC"); ?>
	</div>

	<div class="row">
		<?php echo SiteHelper::subject_content($data); ?>
	</div>

	<div class="row">
		<?php echo SiteHelper::formatted($data->user_comment); ?>
	</div>
	
	<div class="row">
		<?php echo SiteHelper::share_links($data->urn,$data->title); ?>
	</div>
	
	
	<?php 
	$comments=Comment::model()->with('user')->findAll(array('condition'=>"subject_id = {$data->id}", 'limit'=>5, 'order'=>'t.id DESC'));
	foreach($comments as $comment): $i++;?>
	<?php if($i == 1) echo "<h4>Last 5 Comments:</h4>";?>
	<div class="comment" id="c<?php echo $comment->id; ?>">

		<div class="time">
			<?php echo date("Y/m/d H:i",$comment->time)." UTC ". 
			CHtml::link($comment->user->username,array('mysub/'.$comment->user->username)); ?>
		</div>

		<div class="content">
			<?php echo nl2br(CHtml::encode($comment->comment)); ?>
		</div>

	</div><!-- comment -->
	<?php endforeach; ?>
	<?php if(! $i) echo "NO COMMENTS";?>

</div>