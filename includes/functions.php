<?PHP	

/*
 *INSERT VALUES INTO SETS TABLE
 *
 *
 */
function bb_insert_set($user_ID ,$date,$category,$description,$status,$parent){
	global $wpdb;
	$wpdb->insert( '$wpdb->bb_sets',
		array( 'userID' => $user_ID,
			'start' => $date ,
			'category' => $category,
			'description' => $description,
			'status' => $status,
			'parent' => $parent
		), array( '%f', '%s','%s','%s','%s','%d' ) );
}

/*
 *  INSERT VALUES INTO EFFORTS TABLE
 *
 *
 */
function bb_insert_effort( $setID , $discipline , $setting , $difficulty , $duration , $distance , $details , $water ) {
	global $wpdb;
	$wpdb->insert( '$wpdb->bb_efforts',
		array( 'setID' => $setID,
	 		'discipline' => $discipline ,
		 	'setting' => $setting,
			'difficulty' => $difficulty,
			'duration' =>  $duration,
			'distance' => $distance,
			'details' => $details,
			'water' => $water	
		),array( '%d', '%s','%s','%d','%s','%f','%s','%d' ) );
}
/*
 *  INSERT VALUES INTO STRETCHES TABLE
 *
 *
 */
function bb_insert_stretches( $setID , $muscle , $duration ) {
	global $wpdb;
	$wpdb->insert( '$wpdb->bb_stretches',
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
	 $user_ID = wp_get_current_user();
	$wpdb->insert( '$wpdb->bb_dailys',
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
	$wpdb->insert( '$wpdb->bb_journals',
		array( 'dailyID' => $dailyID,
	 		'setID' => $setID ,
		 	'meal' => $meal,
			'time' => $time,
			'foods' => $foods,
		),array( '%d', '%d','%s','%s','%s') );
}
/*	
 *
 *	Modify a set's status to complete
 *
 */
function bb_complete_set( $setID ){
	global $wpdb;
	$table_prefix = $wpdb->prefix;
	$wpdb->update( $table_prefix.'bb_sets', array( 'status' => 'complete'), array( 'setID' => $setID ), array( '%s'), array( '%d' ) );
}
/*
 * 	
 * CALCULATE SQL TIME FORMAT FROM SECONDS
 *
 *
 */
function sec_to_time( $secs = 'db_insert') {
	
	return ('db_insert' == $secs)? (floor($secs/(60*60))%(60*60)).(floor($secs/60)%60).($secs%60) : (floor($secs/(60*60))%(60*60)).":".(floor($secs/60)%60).":".($secs%60) ;
}
/*
 * CUSTOM FUNCTION FOR CURRENT TIME
 *	 	better format etc
 *
 *
 */	
function current_time_fixed( $type, $gmt = 0 ) {
	$t =  ( $gmt ) ? gmdate( 'Y-m-d H:i:s' ) : gmdate( 'Y-m-d H:i:s', ( time() + ( get_option( 'gmt_offset' ) * 3600 ) ) );
	switch ( $type ) {
		case 'mysql':
			return $t;
			break;
		case 'timestamp':
			return strtotime($t);
			break;
	}
}
/*
 *  	
 *	time difference between two dates
 *
 *
 */
function time_diff($from,$to){
return (strtotime($to) - strtotime($from) )/ (60*60*24);
}
/*
 *  If Then Return
 * customizable for different conditions
 *
 */
function itr ($arg,$condition='empty') {
	switch( $condition ){
		case 'empty':
		if( !empty($arg) )
			return $arg;
		break;
		
		case 'isset':
		if( isset($arg) )
			return $arg;
		break;
		
		case 'zero_time':
			return ('00:00:00' == $arg)? '-' : $arg ;
		break;
		
		case 'zero':
			return ( 0 == $arg)? '-' : $arg ;
		break;
	}
}
/*
 *  AM or PM?
 * custom function for reporting
 * takes a date and returns whether it's morning or night
 *
 */
function am_pm ($date){
	return  date('A',strtotime($date));
}
/*
 * Zero pad an int to 2 places
 * 
 *
 */
function zero_pad ($number){
	return (($number < 10 )? '0' : '') . $number;
}
/*
 * Query for program type set
 * returns full set if no args.
 * 
 *
 */
function get_bb_set( $setID , $category = 'program' ) {
	global $wpdb;
	$current_user = wp_get_current_user();
	
	/* get query formatted sets between period */
		$set = $wpdb->get_results("SELECT * FROM $wpdb->bb_sets JOIN $wpdb->bb_efforts ON $wpdb->bb_sets.setID=$wpdb->bb_efforts.setID WHERE userID=".$current_user->ID." AND category='".$category."' AND $wpdb->bb_efforts.setID=".$setID);
		return $set;
}

/*
 *BOOM BOOKS custom type template
 *load a custom template type for boom books custom type
 *
 *				DISABLED
 */
//add_filter( "single_template", "get_custom_post_type_template" ) ;
//add_action('template_redirect', 'add_bb_single'); // boom book custom template
function get_custom_post_type_template() {
     global $post;
     if ($post->post_type == 'boom_book') {
          return WP_CONTENT_DIR . '/plugins/boom-books/bb-single.php';
     }
}


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
 * args: $_POST
 *
 */
function bb_submit_callback() {
	unset($_POST['action']);//roll over and knock the top off!
//	error_log( 'variable contains = ' . print_r( $_POST, true ) );
	global $wpdb;
	$current_user = wp_get_current_user();

	$set = array_shift($_POST);
		$user_ID =		$current_user->ID;
		$date =			date_i18n( 'Y-m-d H:i:s' , strtotime( $set['date'] ) + ( $set['time']*60*60 ),false);
		$category =		$set['category'];
		$description =	$set['description'];
		$status =		'complete';
		$parent =		$set['parent'];//setID of referred set (program)
		
//error_log( 'decon set = ' . print_r( compact('user_ID','date','category','description','status','parent'), true ) );
	//input to set and retrieve auto setID
	bb_insert_set($user_ID,$date,$category,$description,$status,$parent);
	if( isset($set['parent']) ){
		bb_complete_set( $parent );
	}

//	error_log( 'variable contains = ' . print_r( $_POST, true ) );
	$setID = $wpdb->insert_id;
		foreach ( $_POST as $effort ){
		$discipline =	$effort['discipline'];
		$setting =		$effort['setting'];
		$duration =		$effort['h'].$effort['m'].$effort['s'];
		$details =		$effort['details'];
		$distance =		(float)$effort['distance'];
		$difficulty =	(int)$effort['difficulty'];
		$foods =		$effort['foods'];
		$meal = 		'during';
		$water =		(int)$effort['water'];
		if ( 'swimming' == $discipline )
			$distance = $distance/1000;
				
//error_log( 'recurse decon effort = ' . print_r( compact('setID','discipline','setting','difficulty','duration','distance','details'), true ) );
			bb_insert_effort($setID,$discipline,$setting,$difficulty,$duration,$distance,$details,$water);
			
		if ( isset($foods) && !empty($foods) ) {
			$effortID = $wpdb->insert_id;
			static $dailyID;

			if ( isset($dailyID) && empty($dailyID) )
				$dailyID = $wpdb->get_var($wpdb->prepare("SELECT dailyID FROM $wpdb->bb_dailys WHERE date=".$date." AND userID=".$userID));
				
			if ( !isset($dailyID) || empty($dailyID) ) {	
				bb_insert_daily( $date );
				$dailyID = $wpdb->insert_id;		
			}
			bb_insert_journal( $setID , $effortID , $dailyID , $meal , $time, $foods );
		}
	}
	echo ( 'program' == $set['category'] )? 'Program submitted.' : 'Your efforts are immortalised.' ;
	die();
}
add_action('wp_ajax_bb_submit', 'bb_submit_callback');
		
 ?>