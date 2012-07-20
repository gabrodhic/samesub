<?php
$this->pageTitle=Yii::app()->name . ' - '. $model->title;

?>
<br />
<br />
<br />
<br />


<div>
	

	

<?php if($model->position ): ?>

	<?php 
	if($model->position > SiteLibrary::utc_time()){
	?>
	<script>
	<?php $time = SiteLibrary::utc_time(); ?>

	var utc_time = <?php echo $time;?>;
	var utc_hour = <?php echo date("H",$time); ?>;
	var utc_min = <?php echo date("i",$time); ?>;
	var utc_sec = <?php echo date("s",$time); ?>;
	
	var sub_utc_time = <?php echo $model->position;?>;
	var sub_utc_hour = <?php echo date("H",$model->position); ?>;
	var sub_utc_min = <?php echo date("i",$model->position); ?>;
	var sub_utc_sec = <?php echo date("s",$model->position); ?>;

	var tick;
	var clock_time = null;
	var BigDay = null;
	function clock() {
  
	if( clock_time != null ){
		clock_time.setSeconds(clock_time.getSeconds() + 1);
	}else{
		clock_time=new Date(utc_time * 1000);
		clock_time.setHours(utc_hour,utc_min,utc_sec,0);
	}
	
	if( BigDay == null ){
		BigDay=new Date(sub_utc_time * 1000);
		BigDay.setHours(sub_utc_hour,sub_utc_min,sub_utc_sec,0);
	}
	
	msPerDay = 24 * 60 * 60 * 1000 ;
	timeLeft = (BigDay.getTime() - clock_time.getTime());
	e_daysLeft = timeLeft / msPerDay;
	daysLeft = Math.floor(e_daysLeft);
	e_hrsLeft = (e_daysLeft - daysLeft)*24;
	hrsLeft = Math.floor(e_hrsLeft);	
	minsLeft = Math.floor((e_hrsLeft - hrsLeft)*60);
	
	e_minsLeft = ((e_hrsLeft - hrsLeft)*60);
	secsLeft = Math.round((e_minsLeft - minsLeft)*60);//floor can give same value by the time 1 sec has passed so, we use round for seconds
	
	//document.write("There are only<BR> <H4>" + daysLeft + " days " + hrsLeft +" hours and " + minsLeft + " minutes left and secs " + secsLeft + "</H4> Until December 25th 2020<P>");
	$('#countdown_day').html(daysLeft);
	$('#countdown_hour').html(hrsLeft);
	$('#countdown_min').html(minsLeft);
	$('#countdown_sec').html(secsLeft);	
	//$('#countdown').html(daysLeft + " days " + hrsLeft +" hours and " + minsLeft + " minutes left and secs " + secsLeft);	
	if(timeLeft < 1){	//if has come to cero, stop the countdown
		alert('Subject is on the homepage now!');
	}else{
		tick=window.setTimeout("clock()",1000);
	}
	}
	clock();
	
	
	
	</script>
	<p style="text-align: center;font-size: 30px; font-family: Impact"><?php echo Yii::t('subject','Time remaining');?></p>
	<?php 
	}else{
	?>
	<p style="color:red; text-align: center;font-size: 30px; font-family: Impact"><?php echo Yii::t('subject','Subject already shown on: {date} UTC', array('{date}'=>date("Y/m/d", $model->position). ' '.  date("H",$model->position).':'.date("i",$model->position)));?> </p>
	<?php
	}
	?>
<?php else: ?>

	<p style="color:red; text-align: center;font-size: 30px; font-family: Impact"><?php echo Yii::t('subject','Subject not authorized yet');?></p>

<?php endif; ?>
<br />
<br />

<table border="0" width="100%" id="table1" style="text-align: center;">
	<tr>
		<th width="25%" style="text-align: center"><?php echo Yii::t('subject','days');?></th>
		<th width="25%" style="text-align: center"><?php echo Yii::t('subject','hours');?></th>
		<th width="25%" style="text-align: center"><?php echo Yii::t('subject','minutes');?></th>
		<th width="25%" style="text-align: center"><?php echo Yii::t('subject','seconds');?></th>
	</tr>
	<tr>
		<td id="countdown_day" style="text-align: center;font-size: 90px; font-family: Impact">--</td>
		<td id="countdown_hour" style="text-align: center;font-size: 90px; font-family: Impact">--</td>
		<td id="countdown_min" style="text-align: center;font-size: 90px; font-family: Impact">--</td>
		<td id="countdown_sec" style="text-align: center;font-size: 90px; font-family: Impact">--</td>
	</tr>
	</table>
<br /><br /><br /><br /><br />
<h4><?php echo Yii::t('subject','Subject');?>: <?php echo CHtml::encode($model->title); ?></h4>
<br /><br /><br />
</div>