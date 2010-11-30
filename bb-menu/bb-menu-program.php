<?
/* TODO
 * validate date ranges 
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

function program ( $from , $to ){
global $wpdb;
	$range=time_diff($from,$to);// default program display range 2 weeks one month avg		
	$current_user = wp_get_current_user();

/* get query formatted sets between period */
	$sets = $wpdb->get_results("SELECT wp_bb_sets.setID , start , GROUP_CONCAT(DISTINCT discipline SEPARATOR ' , ') AS discipline , GROUP_CONCAT( DISTINCT setting SEPARATOR ' / ') AS setting , CAST(AVG(difficulty) AS UNSIGNED) AS difficulty , SEC_TO_TIME ( SUM( TIME_TO_SEC(duration) ) ) AS duration, CAST(SUM(distance)AS decimal(6,1)) AS distance , status FROM wp_bb_sets JOIN wp_bb_efforts ON wp_bb_sets.setID=wp_bb_efforts.setID WHERE userID=".$current_user->ID."   AND STR_TO_DATE('".$from."','%W %d-%M-%Y') <= start <= STR_TO_DATE('".$to."','%W %d-%M-%Y') GROUP BY wp_bb_efforts.setID");
//AND category='program'
	echo $current_user->user_firstname,' ',$current_user->user_lastname,'<br />';
	echo '<form name="bb_program" action="',WP_ADMIN_URL,'?page=program" id="bb_program" method="post">',
	//output program table
	'<table class="bb-program" id="bb_program_table">',
		'<tr class="bb-program header"> <th></th> <th></th> <th>Duration<br /><font size="1"> h:m:s </font></th> <th>Distance<br /><font size="1">km</font></th> <th>Location</th> <th>Difficulty<br /><font size="1"> 1-10 </th> <th>Status</th> </tr>',
	
	'<tr class="bb-program month-divider"> <th colspan="7">',date('F',strtotime($from)),'</th> </tr>';
//iterate through daterange, format and output matching set
	for ( $day = 0; $day <= $range; $day += 1){
		
		if( '1' == date('d',strtotime($from)+( 60*60*24*$day ) ) ){
			echo '<tr class="bb-program month-divider"><th colspan="7">',date('F',strtotime($from)+( 60*60*24*$day ) ),'</th></tr>';
		}//insert Month name between rollover
		
		if( 'Monday' == date('l',strtotime($from)+( 60*60*24*$day ) ) ){
			echo '<tr class="bb-program week-divider"><th colspan="7"><hr /></th></tr>';
		}//insert Month name between rollover

		$rowreturn=false;
		//iterate through array & output results rows
		foreach($sets as $set){
			if( strstr( $set->start , date('Y-m-d',strtotime($from)+( 60*60*24*$day ) ) ) ){
				echo '<tr class="bb-program set-row"><td class="bb-program set-date">',
					'<a href="',WP_ADMIN_URL,'?page=boom_books&setID=',$set->setID,'">',//if status = completed disable link TODO
						date( 'd . l', strtotime($from)+( 60*60*24*$day )),
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
		if(false==$rowreturn){
			echo '<tr><td class="bb-program date">',
				date( 'd . l', strtotime($from)+(60*60*24*$day)),
			'</td><td class="bb-program rest-day">Rest Day</td></tr>';
		}
	}
	echo '</table>';
}//end program()

?>
<p><h1>Boom Books Program (Beta)</h1></p>

	<p>
		<div class="bb-program daterange" id="program_daterange"><form name="bb-program-daterange" action=<?PHP echo'"',WP_ADMIN_URL,'?page=program"';?> id="program_daterange_form" method="post">
		<?PHP echo 'From: <input name="from" type="text" class="bb-datepicker" id="from" size="30" value="'.$from;?>">
		<?PHP echo 'To: <input name="to" type="text" class="bb-datepicker" id="to" size="30" value="'.$to;?>">
		<input type="submit" name="src_dates" id="src_dates"value="search dates">
		</form></div><!--.bb-program #program_daterange-->
	</p>

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
	<div class="bb-program freeride" id="program_freeride">
	<form name="bb-program-freeride" action=
		<?PHP echo'"',WP_ADMIN_URL,'?page=boom_books"';?>
		id="program_freeride_form" method="get">
	<p><input type="hidden" name="check" id="check" value="checkval" /></p>
<p><input type="submit" name="freeride" id="freeride" value="free ride" /></p>
			</form></div><!--.bb-program #program_daterange-->

