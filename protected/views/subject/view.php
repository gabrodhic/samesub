<?php
$this->pageTitle=Yii::app()->name . ' - '. $model->title;
$sub_data=SiteHelper::subject_content($model,'array');
$this->ogtags = SiteHelper::get_ogtags($this->pageTitle, 
($model->content_type_id == 2)?SiteHelper::subject_content($model) : $model->user_comment, $sub_data['image'],
 Yii::app()->params['weburl'].'/sub/'.$model->urn,
 $sub_data['url']);
?>

<h1><?php echo CHtml::encode($model->title); ?></h1>

<div id="left_container" class="form">



	<div class="row">
		<?php echo $model->country->name . " | "; ?>
		<?php echo CHtml::encode(date("Y/m/d H:i", $model->time_submitted)." UTC"); ?>
	</div>

	<div class="row">
		<?php echo SiteHelper::subject_content($model); ?>
	</div>

	<div class="row">
		<?php echo SiteHelper::formatted($model->user_comment); ?>
	</div>
	
	<div class="row">
		by <?php echo CHtml::link(User::model()->findByPk($model->user_id)->username,array('mysub/'.User::model()->findByPk($model->user_id)->username));?>
	</div>


<?php echo SiteHelper::share_links($model->urn,$model->title); ?>
<h4>Comments:</h4>

<?php 
$comments=Comment::model()->with('user','country')->findAll("subject_id = {$model->id}");
foreach($comments as $comment): ?>
<div class="comment" id="c<?php echo $comment->id; ?>">



	<div class="time">
		<?php echo '<span class="comment_number">'.str_pad($comment->sequence, 2, '0',STR_PAD_LEFT).'</span><span class="comment_country">'. $comment->country->code. '</span> '. date("Y/m/d H:i",$comment->time).' UTC '.
		CHtml::link($comment->user->username,array('mysub/'.$comment->user->username)); ?>
	</div>

	<div class="content">
		<?php echo nl2br(CHtml::encode($comment->comment)); ?>
	</div>

</div><!-- comment -->
<?php endforeach; ?>

</div><!-- form -->
<div id="right_container">
<div id="tags_container">
	<h4 style="margin-right:10px;">tags:</h4>
	<div id="tags_list">
		<ul>
		<?php 
			$tags = ($model->tag) ? SiteHelper::make_tags($model->tag) : SiteHelper::make_tags($model->urn, true, 'urn'); 
		?>
		<?php foreach($tags as $tag) echo "<li>".$tag."</li>";?>
		</ul>
	</div>
</div>
</div>