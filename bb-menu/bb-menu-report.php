<!--returns results of queries-->
<!--userselect:reporting dates [from,to] . default this month?-->
	<!--page1:all sets done in period-->
		<!--page[n]:sets of type 'n' in period
	
	average duration:	SEC_TO_TIME ( SUM( TIME_TO_SEC(duration) ) / COUNT(duration) ) ) AS duration
		-->	
<?
	$today = current_time_fixed( 'timestamp' , 0 );//normal
	$from = date( 'l d-M-Y', $today );	
	$to = date( 'l d-M-Y', $today + ( 60*60*24*7*2) ) ;
	switch ( $_POST['src_dates'] ){
		case 'range' :	
			if(!empty($_POST['from']) && isset($_POST['from'])){
				$from = $_POST['from'];
			}
			if(!empty($_POST['to']) && isset($_POST['to'])){
				$to = $_POST['to'];
			}
			break;
		case 'this_month':
			$from = date( ' \0\1-M-Y', $today );
			$to = date( ' t-M-Y', $today );
			break;
	}


?>
<p>
	<h1>Boom Books Reports (Beta)</h1>
</p>
<br />
<br />
<br />
<div>
	<div class="bb-reports-range" id="reports_daterange"><form name="bb-reports" action="" id="menu_reports_form" method="post">
		<?PHP echo 'From: <input name="from" type="text" class="bb-datepicker" id="from" size="30" value="'.$from;?>">
		<?PHP echo 'To: <input name="to" type="text" class="bb-datepicker" id="to" size="30" value="'.$to;?>">
		<button type="submit" name="src_dates" id="src_dates"value="range">search dates</button>
	</form></div><!--.bb-reports #reports_daterange-->
	<p>
		<form name="bb-reports-month" action="" id="reports_month" method="post">
		<button type="submit" name="src_dates" value="this_month">Report this Month</button></form>
	</p>
</div>	
<br />
	<br />
	<br />
<div id="tabs">
	<ul>
		<li><a href="#tabs-totals">Totals</a></li>
		<li><a href="#tabs-cycling">Cycling</a></li>
		<li><a href="#tabs-swimming">Swimming</a></li>
		<li><a href="#tabs-running">Running</a></li>
		<li><a href="tabs-all-sets">All Sets</a></li>
	</ul>
	<div id="tabs-totals">
		<?php	report_totals($from,$to);	?>
	</div>
	
<?php	report_discipline($from,$to);	?>
<?php	report_all_sets($from,$to);	?>
</div><!-- tabs end -->

<?php

	function report_totals ($from,$to){
		global $wpdb;
		$range=time_diff($from,$to);// default reports display range 2 weeks one month avg	
		$current_user = wp_get_current_user();
	//error_log(date('Y-m-d h:m:s',strtotime($from)));
	/* get query formatted sets for each discipline*/
		$sets = $wpdb->get_results(
		"SELECT discipline , CAST(AVG(difficulty) AS UNSIGNED) AS difficulty , 
		SEC_TO_TIME ( SUM(TIME_TO_SEC(duration) ) ) AS duration, CAST(SUM(distance) AS decimal(6,2)) AS distance 
		FROM wp_bb_sets JOIN wp_bb_efforts 
			ON wp_bb_sets.setID=wp_bb_efforts.setID 
		WHERE category='session' AND userID=".$current_user->ID.
		" AND start >= '".date( 'Y-m-d',strtotime($from) )."' AND start <= '".date('Y-m-d',strtotime($to)).
		"' GROUP BY discipline");
error_log(print_r($sets,true));

		$program = $wpdb->get_results(
		"SELECT discipline , CAST(AVG(difficulty) AS UNSIGNED) AS difficulty , 
		SEC_TO_TIME ( SUM(TIME_TO_SEC(duration) ) ) AS duration, CAST(SUM(distance) AS decimal(6,2)) AS distance 
		FROM wp_bb_sets JOIN wp_bb_efforts 
			ON wp_bb_sets.setID=wp_bb_efforts.setID 
		WHERE category='session' AND userID=".$current_user->ID.
		" AND start >= '".date( 'Y-m-d',strtotime($from) )."' AND start <= '".date('Y-m-d',strtotime($to)).
		"' GROUP BY discipline");
error_log(print_r($sets,true));

		echo  '<table class="bb-reports" id="bb_reports_totals">',
	 		'<tr><th colspan="9" class="bb-reports-table-header"><h3>Summary - ',$current_user->user_firstname,' ',$current_user->user_lastname,'</h3></th></tr>';
		foreach( $sets as $set ) {
		 echo	'<tr>',
				'<th class="bb-reports discipline">',$set->discipline,'</th>',
				'<tr><th class="bb-reports table-totals">Total Distance</th><td>',$set->distance,' km</td></tr>',
				'<tr><th class="bb-reports table-totals">Total Duration</th><td>',$set->duration,' hrs</td></tr>',			
				'</tr>';
		}
	 	echo '</table>';
	
		echo  '<table class="bb-reports" id="bb_reports_totals">',
	 		'<tr><th colspan="9" class="bb-reports-table-header"><h3>Summary - ',$current_user->user_firstname,' ',$current_user->user_lastname,'</h3></th></tr>';
		foreach( $program as $set ) {
		 echo	'<tr>',
				'<th class="bb-reports discipline">',$set->discipline,'</th>',
				'<tr><th class="bb-reports table-totals">Total Distance</th><td>',$set->distance,' km</td></tr>',
				'<tr><th class="bb-reports table-totals">Total Duration</th><td>',$set->duration,' hrs</td></tr>',			
				'</tr>';
		}
	 	echo '</table>';
	
	}

	function report_discipline( $from , $to ) {
			global $wpdb;
			$range=time_diff($from,$to);// default reports display range 2 weeks one month avg		
			$current_user = wp_get_current_user();
		/* get query formatted sets for each discipline*/
			$sets = $wpdb->get_results(
			"SELECT * , DATE_FORMAT(start, '%D %b \, %W %p') AS thedate
			FROM wp_bb_sets JOIN wp_bb_efforts 
				ON wp_bb_sets.setID=wp_bb_efforts.setID 
			WHERE category='session' AND userID=".$current_user->ID.
			" AND start >= '".date( 'Y-m-d',strtotime($from) )."' AND start <= '".date('Y-m-d',strtotime($to)).
			"' ORDER BY start" , 'OBJECT' );

	$current_user = wp_get_current_user();
	$cycling = array();
	$swimming = array();
	$running = array();
		foreach( $sets as $set ) {
			switch( $set->discipline ) {
				case 'cycling' :
					array_push($cycling, $set);
					break;
				case 'swimming' :
					array_push($swimming, $set);
					break;
				case 'running' :
					array_push($running, $set);
					break;
			}
		}
	$disciplines = compact('cycling','swimming','running');
		foreach ( $disciplines as $discipline => $sets ){
			
			echo '<div id="tabs-',$discipline,'">';
			echo $current_user->user_firstname.' '.$current_user->user_lastname. '<br />',
		 	'<table class="bb-reports disciplines" id="bb_report_disciplines">',
		 		'<tr><th>Date</th> <th>Location</th> <th>Distance</th> <th>duration</th> <th>difficulty</th> <th>water</th> <th>food</th> </tr>	<tr><td colspan="9"><hr></td></tr>';
				foreach( $sets as $set ) {
					?>
						<tr class="bb-report set-row">
							<td class="bb-report date">		<?php echo $set->thedate ?></td>
							<td class="bb-report setting">		<?php echo $set->setting; ?></td>
							<td class="bb-report distance">		<?php echo $set->distance ?> km</td>
							<td class="bb-report duration">		<?php echo $set->duration ?> hrs</td>
							<td class="bb-report difficulty">	<?php echo $set->difficulty ?></td>
							<td class="bb-report water">		<?php echo $set->water ?> L</td>
							<td class="bb-report food">		<?php echo $set->foods ?></td>
						<td class="bb-report details" >	<p class="details"><?php echo $set->details ?></p><a class="toggle-text">hide details</a></td></tr>
					<?php
				}
			echo '</table></div>';
		}
	}

	function report_all_sets($from,$to) {
		return false;	
	}
	/*
			SELECT ( TIME_TO_SEC(session.duration) / TIME_TO_SEC(program.duration) ) AS pc_duration , (session.distance / program.distance) AS pc_distance, session.setID as setID
			FROM (SELECT wp_bb_sets.setID, distance , duration
			FROM wp_bb_sets
			JOIN wp_bb_efforts
				ON wp_bb_sets.setID = wp_bb_efforts.setID
			WHERE category = 'program'
			) AS program
			JOIN (SELECT wp_bb_sets.setID, distance , duration, parent
			FROM wp_bb_sets
			JOIN wp_bb_efforts
				ON wp_bb_sets.setID = wp_bb_efforts.setID
			WHERE category = 'session'
			) AS session
				ON program.setID = session.parent
	
	*/
?>