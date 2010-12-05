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

function program ( $from , $to ){
global $wpdb;
	$range=time_diff($from,$to);// default program display range 2 weeks one month avg		
	$current_user = wp_get_current_user();
/* get query formatted sets between period */
	$sets = $wpdb->get_results("SELECT wp_bb_sets.setID , start , GROUP_CONCAT(DISTINCT discipline SEPARATOR ' , ') AS discipline , GROUP_CONCAT( DISTINCT setting SEPARATOR ' / ') AS setting , CAST(AVG(difficulty) AS UNSIGNED) AS difficulty , SEC_TO_TIME ( SUM( TIME_TO_SEC(duration) ) ) AS duration, CAST(SUM(distance)AS decimal(6,1)) AS distance , status FROM wp_bb_sets JOIN wp_bb_efforts ON wp_bb_sets.setID=wp_bb_efforts.setID WHERE userID=".$current_user->ID." AND category='program' AND STR_TO_DATE('".$from."','%W %d-%M-%Y') <= start <= STR_TO_DATE('".$to."','%W %d-%M-%Y') GROUP BY wp_bb_efforts.setID");

	echo $current_user->user_firstname,' ',$current_user->user_lastname,'<br />';
	echo '<form name="bb_program" action="',WP_ADMIN_URL,'?page=program" id="bb_program" method="post">',
	//output program table
	'<table class="bb-program" id="bb_program_table">',
		'<tr class="bb-program header"> <th></th> <th></th> <th>Duration<br /><font size="1"> h:m:s </font></th> <th>Distance<br /><font size="1">km</font></th> <th>Location</th> <th>Difficulty<br /><font size="1"> 1-10 </th> <th>Status</th> </tr>';
	if ( '1' != date('d',strtotime($from) ) )
		echo '<tr class="bb-program month-divider"> <th colspan="7">' , date('F',strtotime($from)) , '</th> </tr>' ;
//iterate through daterange, format and output matching set
	for ( $day = 0; $day <= $range; $day += 1 ) {
		
		if( '01' == date('d',strtotime($from)+( 60*60*24*$day ) ) ) {
			echo '<tr class="bb-program month-divider"><th colspan="7">',date('F',strtotime($from)+( 60*60*24*$day ) ),'</th></tr>';
		}//insert Month name between rollover

		if( 'Monday' == date('l',strtotime($from)+( 60*60*24*$day ) ) ) {
			echo '<tr class="bb-program week-divider"><th colspan="7"><hr /></th></tr>';
		}//insert Month name between rollover

		$rowreturn = false;
		//iterate through array & output results rows
		foreach( $sets as $set ) {
			if( strstr( $set->start , date('Y-m-d',strtotime($from)+( 60*60*24*$day ) ) ) ){

				if( 'incomplete' == $set->status ) {
					echo '<tr class="bb-program set-row-incomplete"><td class="bb-program set-date">';
					echo '<a href="',WP_ADMIN_URL,'?page=bb_submit&setID=',$set->setID,'">';
				}else{
					echo '<tr class="bb-program set-row-complete"><td class="bb-program set-date">';
				}
				echo	date( 'd . l', strtotime($from)+( 60*60*24*$day )),
							' - ',am_pm($set->start),
				 '</td><td class="bb-program discipline">',
					 $set->discipline,
				 '</td><td class="bb-program duration">',
					 itr($set->duration,'zero_time'),
				 '</td><td class="bb-program distance">',
					 itr($set->distance,'zero'),
				 '</td><td class="bb-program setting">',
					 $set->setting,
				 '</td><td class="bb-program difficulty">',
					 itr($set->difficulty,'zero'),
				 '</td><td class="bb-program status">',
					 $set->status,
				 '</td></tr>';
				$rowreturn=true;
			}
		}
		if( false == $rowreturn ){
			echo '<tr class="rest"><td class="bb-program date">',
				date( 'd . l', strtotime($from)+(60*60*24*$day)),
			'</td><td class="bb-program rest-day">Rest Day</td></tr>';
		}
	}
	echo '</table></form>';
}//end program()

?>
<p><h1>Boom Books Program (Beta)</h1></p>

	
<div class="bb-program daterange" id="program_daterange"><form name="bb-program-daterange" action="" id="program_daterange_form" method="post"><p>
	<?PHP echo 'From: <input name="from" type="text" class="bb-datepicker" id="from" size="30" value="'.$from;?>">
	<?PHP echo 'To: <input name="to" type="text" class="bb-datepicker" id="to" size="30" value="'.$to;?>">
	<button type="submit" name="src_dates" value="range">Search Dates</button>
</p></form></div><!--.bb-program #program_daterange-->

	<div><p>
		<form name="bb-program-month" action="" id="reports_month" method="post">
	<!--	<button type="submit" name="src_dates" value="this_month">View this Month</button></form> -->
	</p></div>

	<p><?php
	program($from,$to);
	?></p>

			<!--query for program between dates-->
			<!--select set to go confirm submit efforts, -->
			<!--each program-set links to program-set-submit page with selected data-->
			<!--Free-set:submit a new set/effort combination of your own-->
			<!--sets show with 'status'=['pending','partial','completed']-->
			<!--once submitted('partial','completed') you cannot re-submit a program-set
						SET DETAILS TO SHOW IN THICKBOX?
			-->


