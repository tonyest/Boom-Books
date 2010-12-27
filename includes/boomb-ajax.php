<?php
/*
 *  CALLBACK FUNCTION FOR AUTHOR SET SUBMIT
 *
 * args: $_POST
 *
 */
function boomb_user_submit_callback() {
	global $wpdb;
	$current_user = wp_get_current_user();
	$user_ID = $current_user->ID;
	$args =	array(
			'userID' => $user_ID,
			'start_date' => date('Y-m-d',strtotime($_POST['start_date']) ),
			'start_time' => $_POST['start_time'],
			'category' => 'session',
			'status' => 'complete',
			'parent' => ( !empty($_POST['setID']) )? $_POST['setID'] : null
			);
	$sessionID = boomb_insert_user_session( $args , $_POST['sessionID'] , true );
	if ( $_POST['category'] == 'program' ) :
		boomb_complete_session( $_POST['parent'] );
	endif;
	foreach ( $_POST['sets'] as $set ) {
		$args = array( 
			'sessionID' => $sessionID,
			'sport' => $set['sport'] ,
		 	'setting' => $set['setting'],
			'difficulty' => (int)$set['difficulty'],
			'duration' =>  $set['duration'],
			'distance' => (float)$set['distance'],
			'details' => $set['details'],
			'water' => (float)$set['water']	
			);
		$setID  = ( $_POST['category'] == 'program' )? null : $set['setID'] ;
		boomb_insert_user_set( $args , $setID , true );
	}
	if ( isset($_POST['deleted_setIDs']) ) :
		foreach ( $_POST['deleted_setIDs'] as $set ) {
				$args = array( 'sessionID' => $_POST['sessionID'] ,'setID' =>  $set['setID']	);
				boomb_delete_user_set( $args );
		}
	endif;

	echo get_reports();
	die();
}
add_action('wp_ajax_boomb_user_submit', 'boomb_user_submit_callback');
/*
 *  CALLBACK FUNCTION FOR AUTHOR SET SUBMIT
 *
 * args: $_POST
 *
 */
function boomb_program_submit_callback() {
	global $wpdb;	
	$args = array(
			'programID' => $_POST['programID'],
			'day' => $_POST['day'],
			'time' => $_POST['time'] 
		);
	$sessionID = boomb_insert_program_session( $args , $_POST['sessionID'] , true  );
	foreach ( $_POST['sets'] as $set ) {

		$args = array(	
				'sessionID' => $sessionID,
				'sport' => $set['sport'],
			 	'setting' => $set['setting'],
				'difficulty' => (int)$set['difficulty'],
				'duration' =>  $set['duration'],
				'distance' => (float)$set['distance'],
				'details' => $set['details'],
				'water' => (float)$set['water']	
			);
		boomb_insert_program_set( $args , $set['setID'] , true );
	}
	if ( isset($_POST['deleted_setIDs']) ) {
		foreach ( $_POST['deleted_setIDs'] as $set ) {
				$args = array( 'sessionID' => $_POST['sessionID'] ,'setID' =>  $set['setID']	);
				boomb_delete_program_set( $args );
		}
	}
		echo get_author( $_POST['programID'] );
	die();
}
add_action('wp_ajax_boomb_program_submit', 'boomb_program_submit_callback');
/*
 *  CALLBACK FUNCTION FOR USER SET QUERY
 *
 * args: $_POST
 *
 */
function get_session_callback() {
	global $wpdb;
	$sessionID = $_POST['sessionID'];
	$category = $_POST['category'];
	$sets = get_session_results( array( 'sessionID' => $sessionID , 'category' => $category ) );
	foreach( $sets as &$set ){
		$jquery_obj = array();
		foreach($set as $name => $value){
			array_push($jquery_obj , array( 'name' => $name , 'value' => $value));
		}
		$set = $jquery_obj;
	}
	unset($set);	// break the reference with the last element

	if (isset($sets) && !empty($sets) )
		echo json_encode($sets);
	else
		echo 'nothing returned';

	die();
}
add_action('wp_ajax_get_session', 'get_session_callback');
/*
 *  CALLBACK FUNCTION FOR PROGRAM SET QUERY
 *
 * args: $_POST
 *
 */
function get_program_sessions_callback() {
	global $wpdb;
	$setID = $_POST['setID'];
	$sessionID = $_POST['sessionID'];
	$table_prefix = $wpdb->prefix;
			$sets = $wpdb->get_results(
			"SELECT	".$table_prefix."boomb_program_sessions.sessionID ,
					day, time, sport , setting , difficulty , duration , distance ,details, setID
			FROM ".$table_prefix."boomb_program_sessions 
			JOIN ".$table_prefix."boomb_program_sets
				ON ".$table_prefix."boomb_program_sessions.sessionID=".$table_prefix."boomb_program_sets.sessionID 
			WHERE ".$table_prefix."boomb_program_sessions.sessionID='".$sessionID."'"
			);
	//prepare for json and javascript editor format
	foreach( $sets as &$set ){
		$jquery_obj = array();
		foreach($set as $name => $value){
			array_push($jquery_obj , array( 'name' => $name , 'value' => $value));
		}
		$set = $jquery_obj;
	}
	unset($set);	// break the reference with the last element

	if (isset($sets) && !empty($sets))
		echo json_encode($sets);
	else
		echo 'nothing returned';
	die();
}
add_action('wp_ajax_get_program_sessions', 'get_program_sessions_callback');

/*
 *  CALLBACK FUNCTION FOR AUTHOR SESSION DELETE
 *
 * args: $_POST
 *
 */
function boomb_delete_session_callback() {
	global $wpdb;	
	$args = array( 'sessionID' => $_POST['sessionID'] , 'setID' => null );
	if (isset($_POST['sessionID'])) {
		boomb_delete_program_set( $args , true );
		echo get_author( $_POST['programID'] );
	}
	die();
}
add_action('wp_ajax_boomb_delete_session', 'boomb_delete_session_callback');


function boomb_ajax_content_callback () {
	error_log(print_r($_POST,true));
	foreach ( $_POST['users'] as $user ) {
		$args = array(
				'userID' => $user,
				'programID' => $_POST['programID'],
				'start_date' =>	$_POST['start_date']		
		);
		boomb_issue_program( $args );
	}
	echo get_program_index();
	die();
}
add_action('wp_ajax_boomb_ajax_content', 'boomb_ajax_content_callback');






//callback function for 18n ajax internationalises a string in ajax
function bb_ajax_i18n_callback() {
	
	$string = $_POST ['string'];
	echo __( $string , 'cp');
	die();
}
add_action('wp_ajax_bb_ajax_i18n_update', 'bb_ajax_i18n_callback');
/*
 *  CALLBACK FUNCTION FOR AUTHOR SET SUBMIT
 *
 * args: $_POST					I FUCKED IT
 *
 */
function boomb_submit_callback() {
	global $wpdb;

		$setID 	= $_POST['setID'];
		$day 		= $_POST['start_date'];
		$time 		= $_POST['start_time'];
//		$sessionID = boomb_insert_program_session( $setID , $start_date , $start_time );

	foreach ( $_POST['sets'] as $set ) {
		$sport =	$set['sport'];
		$setting =		$set['setting'];
		$duration =		$set['duration'];
		$details =		$set['details'];
		$distance =		(float)$set['distance'];
		$difficulty =	(int)$set['difficulty'];
		$water =		(float)$set['water'];

//		boomb_insert_program_set( $sessionID , $sport , $setting , $difficulty , $duration , $distance , $details , $water );
		
	}
		echo 'program submitted';
	die();
}
add_action('wp_ajax_boomb_submit', 'boomb_submit_callback');
?>