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

	</div>

	<div class="row">
		<?php echo SiteHelper::subject_content($model); ?>
	</div>

	<div class="row">
		<?php echo SiteHelper::formatted($model->user_comment); ?>
	</div>
	


<?php echo SiteHelper::share_links($model->urn,$model->title); ?>
<br>
<h3 class="detail_header">Comments</h3>
<?php 
$comments=Comment::model()->with('user','country')->findAll("subject_id = {$model->id}");
$total_comments = count($comments);
if($total_comments == 0) echo "<h4>NO COMMENTS</h4>";
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
<br>
<div class="flash-notice" style="border:none">NOTE: Comments are allowed only on Live Subjects(<a href ="<?php echo Yii::app()->createUrl('site/index');?>">homepage</a>).</div>
</div><!-- form -->
<div id="right_container">
	<div style="padding-left:30px;">
		<div class="detail_section">
		<h3 class="detail_header">Author</h3>
		<div style="float:left;"><?php echo SiteHelper::get_user_picture((int)$model->user_id,'small_','mysub');?></div>
		<div style="float:left; padding-left:10px">
			<?php 
			$user = User::model()->with('ucountry')->findByPk($model->user_id);
			echo $user->firstname . ' '. $user->lastname.'<br>';
			echo CHtml::link($user->username,array('mysub/'.$user->username));?><br>
			
		</div>
		<div class="clear_both"></div>
		</div>
		<div class="detail_section">
			<h3 class="detail_header">Sub Information</h3>
			<div style="float:left;"><h4>Country:</h4></div><div style="float:right;"><?php echo $model->country->name; ?></div>
			<div class="clear_both"></div>
			<div style="float:left;"><h4>Submmited on:</h4></div><div style="float:right;"><?php echo CHtml::encode(date("Y/m/d H:i", $model->time_submitted)." UTC"); ?></div>
			<div class="clear_both"></div>
			<div style="float:left;"><h4>Show time(homepage):</h4></div><div style="float:right;"><?php echo CHtml::encode(date("Y/m/d H:i", $model->show_time)." UTC"); ?></div>
			<div class="clear_both"></div>
			<div style="float:left;"><h4>LIVE Views:</h4></div><div style="float:right;"><?php echo $model->live_views; ?></div>
			<div class="clear_both"></div>
			<div style="float:left;"><h4>Type:</h4></div><div style="float:right;"><?php echo ucwords($model->content_type->name); ?></div>
			<div class="clear_both"></div>
			<div style="float:left;"><h4>Priority:</h4></div><div style="float:right;"><?php echo ucwords($model->priority_type->name); ?></div>
			<div class="clear_both"></div>
			<div style="float:left;"><h4>Comments:</h4></div><div style="float:right;"><?php echo $total_comments; ?></div>
			<div class="clear_both"></div>
			<div style="float:left;"><h4>Page Views:</h4></div><div style="float:right;"><?php echo $model->views; ?></div>
			<div class="clear_both"></div>
		</div>
		<div id="detail_section">
			<h3 class="detail_header">Tags</h3>
			<div id="tags_list">
				<ul>
				<?php 
					$tags = ($model->tag) ? SiteHelper::make_tags($model->tag) : SiteHelper::make_tags($model->urn, true, 'urn'); 
				?>
				<?php foreach($tags as $tag) echo "<li>".$tag."</li>";?>
				</ul>
			</div>
		</div>
		<div class="clear_both"></div>
		<div class="detail_section">
		<h3 class="detail_header">Categories</h3>
				<div id="tags_list">
				<ul>
				<?php
					$tags = explode(',',$model->category);
					//$tags = ($model->category) ? SiteHelper::make_tags($model->category,true) : ''; 
				?>
				<?php if($model->category){ foreach($tags as $tag) echo "<li>".
				'<a href="'.Yii::app()->getRequest()->getBaseUrl(true).'/subject/index?'.urlencode('Subject[category]').'='.$tag.'&ajax=subject-grid">&#32;&#149;&#32;'.$tag.'</a>'
				."</li>"; 
				}else{ echo "No categories";}
				?>
				</ul>
			</div>
		</div>
	</div>
</div>