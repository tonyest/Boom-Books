<!--returns results of queries-->
<!--userselect:reporting dates [from,to] . default this month?-->
	<!--page1:all sets done in period-->
		<!--page[n]:sets of type 'n' in period
	
	average duration:	SEC_TO_TIME ( SUM( TIME_TO_SEC(duration) ) / COUNT(duration) ) ) AS duration
		-->



<?
/*
 *simulate today's date and range for simplicity in testing
 *
*/
	$today=current_time_fixed( 'timestamp',0);//normal
//	$today= strtotime('2010-11-16 00:00:00');//testing
	
/*
 *simulate today's date and range for simplicity in testing
 *
*/
if(!empty($_POST['from'])){
$from = $_POST['from'];
}else{
$from = date( 'l d-M-Y', $today);	
}
if(!empty($_POST['to'])){
$to = $_POST['to'];
}else{
$to = date( 'l d-M-Y', $today + ( 60*60*24*7*2) ) ;
}

function reports ($from,$to){
	global $wpdb;
	$range=time_diff($from,$to);// default reports display range 2 weeks one month avg		
	$current_user = wp_get_current_user();
error_log(date('Y-m-d h:m:s',strtotime($from)));
/* get query formatted sets for each discipline*/
	$sets = $wpdb->get_results(
	"SELECT discipline , CAST(AVG(difficulty) AS UNSIGNED) AS difficulty , 
	SEC_TO_TIME ( SUM(TIME_TO_SEC(duration) ) ) AS duration, CAST(SUM(distance) AS decimal(6,2)) AS distance 
	FROM wp_bb_sets JOIN wp_bb_efforts 
		ON wp_bb_sets.setID=wp_bb_efforts.setID 
	WHERE category='myset' ".
	"AND start >= '".date('Y-m-d',strtotime($from))."' AND start <= '".date('Y-m-d',strtotime($to)).
	"' GROUP BY discipline");

	echo $current_user->user_firstname.' '.$current_user->user_lastname. '<br />',
 	'<table class="bb-reports" id="bb_reports_totals">',
 		'<tr><th colspan="9" class="bb-reports-table-header">Totals</th></tr>';
	foreach($sets as $set){
	 echo	'<tr>',
			'<th class="bb-reports discipline">',$set->discipline,'</th>',
			'<tr><th class="bb-reports table-totals">Total Distance</th><td>',$set->distance,' km</td></tr>',
			'<tr><th class="bb-reports table-totals">Total Duration</th><td>',$set->duration,' hrs</td></tr>',			
			'</tr>';
	}
 	echo '</table>';

}
?>
<h1>Boom Books Reports (Beta)</h1>

	<br />
	<br />
	<br />
	<div class="bb-reports" id="reports_daterange"><form name="bb-reports" action=<?PHP echo'"http://localhost/wp_BT/wp-admin/admin.php?page=reports"';?> id="menu_reports_form" method="post">
		<?PHP echo 'From: <input name="from" type="text" class="bb-datepicker" id="from" size="30" value="'.$from;?>">
		<?PHP echo 'To: <input name="to" type="text" class="bb-datepicker" id="to" size="30" value="'.$to;?>">
		<input type="submit" name="src_dates" id="src_dates"value="search dates">
	</form></div><!--.bb-reports #reports_daterange-->
	<br />

	<?php
	reports($from,$to);
	?>

<br />
<br />
