<?php
/*	Set of functions that modify the database tables for boom books		*/


/* - - - -  - -  - - - - PROGRAM TEMPLATE INSERT QUERIES - - - - - - - */
/*
 *  INSERT VALUES INTO PROGRAMS
 *
 *
	array(	'name' => $name,
			'description' => $description
		)
 */
function boomb_insert_program( $args , $update = false ) {	
	global $wpdb;
	$table_prefix = $wpdb->prefix;
	//check for existing entries
	$programID = $wpdb->get_var( $wpdb->prepare( "SELECT programID FROM " . $table_prefix . "boomb_programs WHERE name = \"%s\"", $args['name'] ) );
	if ( isset( $programID ) ) {	
		if ( $update ) {
			$wpdb->update( 
				$table_prefix . 'boomb_programs', 
				$args, 
				array( 'programID' => $programID ), 
				array( '%s', '%s' ), 
				array( '%d' ) 
			);
		}
	} else {
		$wpdb->insert( $table_prefix.'boomb_programs', $args, array( '%s', '%s' ) );
		$programID = $wpdb->insert_id;
	}
	return $programID;
}

/*
 *  INSERT VALUES INTO PROGRAM SESSIONS TABLE
 *
 *
	array(	'programID' => $programID,
			'day' => $day,
			'time' => $time 
		)
 */
function boomb_insert_program_session( $args , $sessionID = NULL , $update = false  ) {
	global $wpdb;
	$table_prefix = $wpdb->prefix;
	//check for existing entries
	$sessionID = $wpdb->get_var( $wpdb->prepare( "SELECT sessionID FROM " . $table_prefix . "boomb_program_sessions WHERE sessionID = \"%d\"", $sessionID ) );
	if( isset( $sessionID ) ) {	
		if ( $update ) {
			$wpdb->update( 
				$table_prefix . 'boomb_program_sessions', 
				$args, 
				array( 'sessionID' => $sessionID ), 
				array( '%d', '%s','%s' ), 
				array( '%d' ) 
			);
		}
	} else {
		$wpdb->insert( $table_prefix.'boomb_program_sessions', $args, array( '%d', '%s','%s' ) );
		$sessionID = $wpdb->insert_id;
	}
	return $sessionID;
}

/*
 *  INSERT VALUES INTO PROGRAM SETS table
 *
 *
	array(	
			'sessionID' => $sessionID,
			'sport' => $sport,
		 	'setting' => $setting,
			'difficulty' => $difficulty,
			'duration' =>  $duration,
			'distance' => $distance,
			'details' => $details,
			'water' => $water	
		)
 */
function boomb_insert_program_set( $args , $setID = NULL , $update = false ) {
	global $wpdb;
	$table_prefix = $wpdb->prefix;
		$setID = $wpdb->get_var( $wpdb->prepare( "SELECT setID FROM " . $table_prefix . "boomb_program_sets WHERE setID = \"%d\"", $setID ) );

	if( isset( $setID ) ) {	
		if ( $update ) {
			$wpdb->update( 
				$table_prefix . 'boomb_program_sets', 
				$args, 
				array( 'setID' => $setID ), 
				array( '%d', '%s','%s','%d','%s','%f','%s','%f' ), 
				array( '%d' ) 
			);
		}
	} else {
		$wpdb->insert( $table_prefix . 'boomb_program_sets', $args, array( '%d', '%s','%s','%d','%s','%f','%s','%f' ) );
		$setID = $wpdb->insert_id;
	}		
	return $setID;
}
/* - - - -  - -  - - - - USER TABLE INSERT QUERIES - - - - - - - */
/*
 *INSERT VALUES INTO SETS TABLE
 *
	array( 'userID' => $user_ID,
		'start_date' => $date ,
		'start_time' => $time,
		'category' => $category,
		'status' => $status,
		'parent' => $parent
		)
 *
 */
function boomb_insert_user_session( $args , $sessionID = NULL , $update = false ) {
	global $wpdb;
	$table_prefix = $wpdb->prefix;
	//check for existing entries
	$sessionID = $wpdb->get_var( $wpdb->prepare( "SELECT sessionID FROM " . $table_prefix . "boomb_sessions WHERE sessionID = \"%d\"", $sessionID ) );
	if( isset( $sessionID ) ) {	
		if ( $update ) {
			$wpdb->update( 
				$table_prefix . 'boomb_sessions', 
				$args, 
				array( 'sessionID' => $sessionID ), 
				array( '%f', '%s','%s','%s','%d' ), 
				array( '%d' ) 
			);
		}
	} else {
		$wpdb->insert( $table_prefix.'boomb_sessions', $args, array( '%f', '%s','%s','%s', '%s', '%d' ) );
		$sessionID = $wpdb->insert_id;
	}
	return $sessionID;
}
/*
 *  INSERT VALUES INTO EFFORTS TABLE
 *
 *
	array( 
		'sessionID' => $sessionID,
		'sport' => $sport ,
	 	'setting' => $setting,
		'difficulty' => $difficulty,
		'duration' =>  $duration,
		'distance' => $distance,
		'details' => $details,
		'water' => $water	
		)
 */
function boomb_insert_user_set( $args , $setID = NULL , $update = false ) {
	global $wpdb;
	$table_prefix = $wpdb->prefix;
	$setID = $wpdb->get_var( $wpdb->prepare( "SELECT setID FROM " . $table_prefix . "boomb_sets WHERE setID = \"%d\"", $setID ) );
	if( isset( $setID ) ) {
		if ( $update ) {
			$wpdb->update(
				$table_prefix . 'boomb_sets',
				$args,
				array( 'setID' => $setID ),
				array( '%d', '%s','%s','%d','%s','%f','%s','%f' ),
				array( '%d' )
			);
		}
	} else {
		$wpdb->insert( $table_prefix.'boomb_sets' , $args , array( '%d', '%s','%s','%d','%s','%f','%s','%f' ) );
		$setID = $wpdb->insert_id;
	}
	return $setID;
}

/*	
 *
 *	Modify a set's status to complete
 *
 */
function boomb_complete_session( $sessionID ) {
	global $wpdb;
	$table_prefix = $wpdb->prefix;
	$wpdb->update( $table_prefix.'boomb_sessions', array( 'status' => 'complete'), array( 'sessionID' => $sessionID ), array( '%s'), array( '%d' ) );
}
/*	
 *
 *	Issue a Program to a User
 * essentially copies the database structure for a given program ID to a user.
 *
 */
function boomb_issue_program( $args ) {
extract($args);
$sets = get_program_session_results( $programID );
$sessions[] = array();
foreach( $sets as $set ) {		//re-arrange container
	$sessions[$set->sessionID]['day'] = $set->day;
	$sessions[$set->sessionID]['time'] = $set->time;
	$sessions[$set->sessionID][] = $set;	
}
	foreach( $sessions as $session ) {
		$dates[] = date( 'Y-m-d' , strtotime( $start_date ) + 60*60*24*$session['day'] );
		$args =	array(
				'userID' => $userID,
				'start_date' => date( 'Y-m-d' , strtotime( $start_date ) + 60*60*24*$session['day'] ),
				'start_time' => $session['time'],
				'category' => 'program',
				'status' => 'incomplete',
				'parent' => $programID
				);
		$sessionID = boomb_insert_user_session( $args );

		foreach ( $session as $key => $set ) {
			if ( $key === 'day' || $key === 'time' ) :
				continue;
			endif;
			$args = array( 
				'sessionID' => $sessionID,
				'sport' => $set->sport ,
			 	'setting' => $set->setting,
				'difficulty' => (int)$set->difficulty,
				'duration' =>  $set->duration,
				'distance' => (float)$set->distance,
				'details' => $set->details,
				'water' => (float)$set->water,
				'max_hr' => $set->max_hr,
				'avg_hr' => $set->avg_hr
				);
			boomb_insert_user_set( $args );
		}	
	}
	echo ' i lov boobs';
}
/* - - - -  - -  - - - - PROGRAM TABLE DELETE QUERIES - - - - - - - */
/*
 *
 *	DELETE SET FROM PROGRAM TABLES
 *
	array( 'sessionID' = $sessionID,
			'setID' = $setID
			);

 */
function boomb_delete_program_set( $args , $all = false) {
	extract($args);
	global $wpdb;
	$table_prefix = $wpdb->prefix;
	$set_count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM " . $table_prefix . "boomb_program_sets WHERE sessionID = \"%d\"", $sessionID ) );
	$where = ( $all )? "WHERE sessionID = \"" . $sessionID ."\"" : $where = "WHERE setID = \"" . $setID ."\"";		
	$wpdb->query( "DELETE FROM " . $table_prefix . "boomb_program_sets " . $where );
	if ( $set_count == 0)	
		$wpdb->query( "DELETE FROM " . $table_prefix . "boomb_program_sessions WHERE sessionID = \"" . $sessionID ."\"" );
}
/*
 *
 *	DELETE SET FROM USER TABLES
 *
	array( 'sessionID' = $sessionID,
			'setID' = $setID
			);
 */
function boomb_delete_user_set( $args , $all = false) {
	extract($args);
	global $wpdb;
	$table_prefix = $wpdb->prefix;
	$set_count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM " . $table_prefix . "boomb_sets WHERE sessionID = \"%d\"", $sessionID ) );
	$where = ( $all )? "WHERE sessionID = \"" . $sessionID ."\"" : $where = "WHERE setID = \"" . $setID ."\"";		
	$wpdb->query( "DELETE FROM " . $table_prefix . "boomb_sets " . $where );
	if ( $set_count == 0)	
		$wpdb->query( "DELETE FROM " . $table_prefix . "boomb_sessions WHERE sessionID = \"" . $sessionID ."\"" );
}

?>