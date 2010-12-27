<?php

/*  Set of functions that return results from the boomb books database  */

/*
 * Query for programs
 * returns full set if no args.
 * 
 *
 */
function get_program_results( $from , $to , $current_user ) {
	global $wpdb;
	$table_prefix = $wpdb->prefix;
	/* get query formatted sets between period */
		$sets = $wpdb->get_results("SELECT {$table_prefix}boomb_sessions.sessionID , start_date ,TIME_TO_SEC(start_time) AS start_time, GROUP_CONCAT(DISTINCT sport SEPARATOR ' , ') AS sport , GROUP_CONCAT( DISTINCT setting SEPARATOR ' / ') AS setting , CAST(AVG(difficulty) AS UNSIGNED) AS difficulty , SEC_TO_TIME ( SUM( TIME_TO_SEC(duration) ) ) AS duration, CAST(SUM(distance)AS decimal(6,1)) AS distance , status , details
		FROM
		{$table_prefix}boomb_sessions 
		JOIN
		{$table_prefix}boomb_sets
		ON wp_boomb_sessions.sessionID = wp_boomb_sets.sessionID 
		WHERE
			userID={$current_user->ID} AND category='program' AND start_date BETWEEN STR_TO_DATE('{$from}','%W %d-%M-%Y')AND STR_TO_DATE('{$to}','%W %d-%M-%Y') GROUP BY {$table_prefix}boomb_sets.sessionID");
			
		return $sets;
}
/*
 * General Query for sessions
 * 
 * @args: userID , $range{ $from , $to } , $category , $setID
 * 
 *
 */
function get_session_results( $args ) {
	global $wpdb;
	extract($args);
	$current_user = wp_get_current_user();	
	$table_prefix = $wpdb->prefix;

	$where .= ( isset($userID) )? " AND userID=\"{$userID}\"" : " AND userID=\"{$current_user->ID}\"" ;
	$where .= ( isset($range) )? ' AND start_date BETWEEN STR_TO_DATE("'. $range['from']. '" , "%W %d-%M-%Y" ) AND STR_TO_DATE("'. $range['to']. '" , "%W %d-%M-%Y" )' : '' ;
	$where .= ( isset($category) )? " AND category=\"{$category}\"" : 'AND category="session"' ;
	$where .= ( isset($sessionID) )? " AND {$table_prefix}boomb_sessions.sessionID=\"{$sessionID}\"" : "" ;

	$sets = $wpdb->get_results("SELECT {$table_prefix}boomb_sessions.sessionID, setID , DATE_FORMAT(start_date,'%a %d-%b-%Y') AS start_date, start_time, sport , setting , difficulty , duration , distance , details, parent, category
		FROM
		{$table_prefix}boomb_sets JOIN {$table_prefix}boomb_sessions 
		ON
		{$table_prefix}boomb_sessions.sessionID={$table_prefix}boomb_sets.sessionID 
		WHERE
			1=1 {$where}" );

	return $sets;
}
/*
 * General Query for sessions
 * 
 * @args: userID , $range{ $from , $to } , $category , $setID
 * 
 *
 */
function get_program_session_results( $programID ) {
	global $wpdb;
	$table_prefix = $wpdb->prefix;
	$sets = $wpdb->get_results("SELECT {$table_prefix}boomb_program_sessions.sessionID, setID , day, time, sport , setting , difficulty , duration , distance , details, max_hr, avg_hr, water
		FROM
		{$table_prefix}boomb_program_sets JOIN {$table_prefix}boomb_program_sessions 
		ON
		{$table_prefix}boomb_program_sessions.sessionID={$table_prefix}boomb_program_sets.sessionID 
		WHERE
			 programID = \"{$programID}\"" 
		);
	return $sets;
}
/*
 * Get stats on a session
 * 
 * @args: userID , $range{ $from , $to } , $category , $setID
 * 
 *
 */
function get_session_stats( $args ) {
	global $wpdb;
	extract($args);
	$current_user = wp_get_current_user();	
	$table_prefix = $wpdb->prefix;

	$where .= ( isset($userID) )? " AND userID=\"{$userID}\"" : " AND userID=\"{$current_user->ID}\"" ;
	$where .= ( isset($range) )? ' AND start_date BETWEEN STR_TO_DATE("'. $range['from']. '" , "%W %d-%M-%Y" ) AND STR_TO_DATE("'. $range['to']. '" , "%W %d-%M-%Y" )' : '' ;
	$where .= ( isset($category) )? " AND category=\"{$category}\"" : 'AND category="session"' ;
	$where .= ( isset($setID) )? " AND {$table_prefix}boomb_sessions.setID=\"{$setID}\"" : "" ;
	$where .= ( isset($parent) && $parent )? ' AND parent IS NOT NULL ' : '' ;

	$sets = $wpdb->get_results("SELECT sport, DATE_FORMAT(MAX(start_date),'%a %d-%b-%Y') AS start_date,DATE_FORMAT(MIN(start_date),'%a %d-%b-%Y') AS end_date, MAX(start_time) as time , MAX(setting) AS setting , AVG(difficulty) AS difficulty , SEC_TO_TIME(SUM(TIME_TO_SEC(duration))) AS duration, SUM(distance) AS distance
		FROM 
		{$table_prefix}boomb_sets JOIN {$table_prefix}boomb_sessions 
		ON 
		{$table_prefix}boomb_sessions.setID={$table_prefix}boomb_sets.setID 
		WHERE 1=1 {$where}".
		" GROUP BY sport" , 'OBJECT_K' );
		
	return $sets;	
}
/*
 * Query for daily
 * returns full set if no args.
 * 
 *
 */
function get_daily ( $userID , $date ) {
	global $wpdb;
	/* get query formatted sets between period */
		$row = $wpdb->get_results("SELECT * FROM $wpdb->bb_dailys WHERE userID=\"{$userID}\" AND date='".date('Y-m-d',$date)."'",'OBJECT_K');
		return $row;
}
/*
 * Query for program type set
 * returns full set if no args.
 * 
 *
 */
function get_user_set( $setID , $category = 'program' ) {
	global $wpdb;
	$current_user = wp_get_current_user();
	/* get query formatted sets between period */
		$set = $wpdb->get_results("SELECT * FROM $wpdb->boomb_sessions JOIN $wpdb->boomb_sets ON $wpdb->boomb_sessions.setID=$wpdb->boomb_sets.setID WHERE userID=\"{$current_user->ID}\" AND category=\"{$category}\" AND $wpdb->boomb_sets.setID={$setID}");
		return $set;
}
/*
 * 
 * Query for templates
 * 
 *
 */
function get_program_list() {
	global $wpdb;
	$table_prefix = $wpdb->prefix;	
		$programs = $wpdb->get_results(" SELECT * FROM " . $wpdb->prefix ."boomb_programs ");
		return $programs;
}
/*
 * 
 * Query for a template's program
 *
 *
 */
function get_program_sets( $programID ) {
	global $wpdb;
	$table_prefix = $wpdb->prefix;		
	$sessions = $wpdb->get_results(
			"	SELECT	day , time , details, setID, 
						GROUP_CONCAT(sport SEPARATOR ' , ') AS sport ,
						GROUP_CONCAT(setting SEPARATOR ' / ') AS setting ,
						GROUP_CONCAT(difficulty SEPARATOR ' / ') AS difficulty ,
						GROUP_CONCAT(duration SEPARATOR ' / ') AS duration ,
						GROUP_CONCAT(CAST(distance AS decimal(6,1)) SEPARATOR ' / ') AS distance ,
						CAST(AVG(difficulty) AS UNSIGNED) AS avg_difficulty ,
						SEC_TO_TIME ( SUM( TIME_TO_SEC(duration) ) ) AS sum_duration, 
						SUM( TIME_TO_SEC(duration) ) AS sum_duration_secs, 
						CAST(SUM(distance)AS decimal(6,1)) AS sum_distance,
						{$table_prefix}boomb_program_sets.sessionID 
			FROM {$table_prefix}boomb_program_sessions 
			JOIN {$table_prefix}boomb_program_sets 
			ON {$table_prefix}boomb_program_sessions.sessionID = {$table_prefix}boomb_program_sets.sessionID
			WHERE programID={$programID} GROUP BY {$table_prefix}boomb_program_sessions.sessionID"
			);
		$range = $wpdb->get_results(
			" SELECT MIN(day) AS `from` , MAX(day) AS `to`
			FROM {$table_prefix}boomb_program_sessions
			WHERE programID=\"{$programID}\""
			);
		return array( 'sessions' => $sessions , 'range' => $range[0] );
	
	/*
	global $wpdb;
	$table_prefix = $wpdb->prefix;		
	$sets = $wpdb->get_results(
		"	SELECT *
			FROM " . $wpdb->prefix ."boomb_program_sessions 
			JOIN " . $wpdb->prefix ."boomb_program_sets 
			ON " . $wpdb->prefix ."boomb_program_sessions.sessionID = " . $wpdb->prefix ."boomb_program_sets.sessionID
			WHERE programID=". $programID );
		$range = $wpdb->get_results(
			" SELECT MIN(day) AS `from` , MAX(day) AS `to`
			FROM " . $wpdb->prefix ."boomb_program_sessions
			WHERE programID=\"" . $programID . "\""
			);
		return array( 'sets' => $sets , 'range' => $range[0] );
		*/
}




















/*
 *INSERT VALUES INTO SETS TABLE
 *
 *
 */

function bb_insert_set( $user_ID , $date , $time , $category , $status , $parent ) {
	global $wpdb;
	$table_prefix = $wpdb->prefix;
	$wpdb->insert( $table_prefix.'boomb_sessions',
		array( 'userID' => $user_ID,
			'start_date' => $date ,
			'start_time' => $time,
			'category' => $category,
			'status' => $status,
			'parent' => $parent
		), array( '%f', '%s','%s','%s','%d' ) );
}

/*
 *  INSERT VALUES INTO EFFORTS TABLE
 *
 *
 */
function bb_insert_effort( $setID , $sport , $setting , $difficulty , $duration , $distance , $details , $water ) {
	global $wpdb;
	$table_prefix = $wpdb->prefix;	
	$wpdb->insert( $table_prefix.'boomb_sets',
		array( 'setID' => $setID,
	 		'sport' => $sport ,
		 	'setting' => $setting,
			'difficulty' => $difficulty,
			'duration' =>  $duration,
			'distance' => $distance,
			'details' => $details,
			'water' => $water	
		),array( '%d', '%s','%s','%d','%s','%f','%s','%f' ) );
}
/*
 *  INSERT VALUES INTO STRETCHES TABLE
 *
 *
 */
function bb_insert_stretches( $setID , $muscle , $duration ) {
	global $wpdb;
	$table_prefix = $wpdb->prefix;
	$wpdb->insert( $table_prefix.'bb_stretches',
		array( 'setID' => $setID,
	 		'muscle' => $muscle ,
		 	'duration' => $duration
		),array( '%d', '%s','%s') );
}
/*
 *  INSERT VALUES INTO DAILYS TABLE
 *
 *
 */
function bb_insert_daily( $date , $RHR = 0 , $water = 0 , $sleep = 0 ) {
	global $wpdb;
	$table_prefix = $wpdb->prefix;
	 $user_ID = wp_get_current_user();
	$wpdb->insert( $table_prefix.'bb_dailys',
		array( 'userID' => $user_id,
	 		'date' => $date ,
		 	'RHR' => $RHR,
			'water' => $water,
			'sleep' => $sleep
		),array( '%d', '%s','%i','%f','%f') );
}
/*
 *  INSERT VALUES INTO JOURNALS TABLE
 *
 *
 */
function bb_insert_journal($dailyID,$setID,$meal,$time,$foods){
	global $wpdb;
	$table_prefix = $wpdb->prefix;
	$wpdb->insert( $table_prefix.'bb_journals',
		array( 'dailyID' => $dailyID,
	 		'setID' => $setID ,
		 	'meal' => $meal,
			'time' => $time,
			'foods' => $foods,
		),array( '%d', '%d','%s','%s','%s') );
}

?>