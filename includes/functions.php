<?PHP	

/*
 *INSERT VALUES INTO SETS TABLE
 *
 *$args[col1_name,col1_val,col2_name,col2_value]//xx
 *
 */
function bb_insert_set($user_ID ,$date,$category,$description,$status,$parent){
	global $wpdb;
//	 $user_ID = bb_userID();

	$wpdb->insert( 'wp_bb_sets',
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
 *$args[col1_name,col1_val,col2_name,col2_value]//xx
 *
 */
function bb_insert_effort($setID,$discipline,$setting,$difficulty,$duration,$distance,$details){
	global $wpdb;
	$wpdb->insert( 'wp_bb_efforts',
		array( 'setID' => $setID,
	 		'discipline' => $discipline ,
		 	'setting' => $setting,
			'difficulty' => $difficulty,
			'duration' =>  $duration,
			'distance' => $distance,
			'details' => $details	
		),array( '%d', '%s','%s','%d','%s','%f','%s') );
}
/*
 *  INSERT VALUES INTO STRETCHES TABLE
 *
 *$args[col1_name,col1_val,col2_name,col2_value]//xx
 *
 */
function bb_insert_stretches($setID,$muscle,$duration){
	global $wpdb;
	$wpdb->insert( 'wp_bb_stretches',
		array( 'setID' => $setID,
	 		'muscle' => $muscle ,
		 	'duration' => $duration
		),array( '%d', '%s','%s') );
}
/*
 *  INSERT VALUES INTO DAILYS TABLE
 *
 *$args[col1_name,col1_val,col2_name,col2_value]//xx
 *
 */
function bb_insert_daily($date,$RHR,$water,$sleep){
	global $wpdb;
	 $user_ID = wp_get_current_user();
	$wpdb->insert( 'wp_bb_dailys',
		array( 'userID' => $user_id,
	 		'date' => $date ,
		 	'RHR' => $RHR,
			'water' => $water,
			'sleep' => $sleep
		),array( '%d', '%s','%i','%i','%i') );
}
/*
 *  INSERT VALUES INTO JOURNALS TABLE
 *
 *$args[col1_name,col1_val,col2_name,col2_value]
 *
 */
function bb_insert_journal($dailyID,$setID,$meal,$time,$foods){
	global $wpdb;
	$wpdb->insert( 'wp_bb_journals',
		array( 'dailyID' => $dailyID,
	 		'setID' => $setID ,
		 	'meal' => $meal,
			'time' => $time,
			'foods' => $foods,
		),array( '%d', '%d','%s','%s','%s') );
}

/*
 *  CALCULATE SQL TIME FORMAT FROM SECONDS	
 *
 *
 *
 */
function sec_to_time($secs = 'db_insert'){
	
	return ('db_insert' == $secs)? (floor($secs/(60*60))%(60*60)).(floor($secs/60)%60).($secs%60) : (floor($secs/(60*60))%(60*60)).":".(floor($secs/60)%60).":".($secs%60) ;
}

/*
 *  time difference between two dates	
 *
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
 * Query for program type set
 * returns full set if no args.
 * 
 *
 */
function get_program_set($setID){
	global $wpdb;
	$current_user = wp_get_current_user();

	/* get query formatted sets between period */
		$sets = $wpdb->get_results("SELECT * FROM wp_bb_sets JOIN wp_bb_efforts ON wp_bb_sets.setID=wp_bb_efforts.setID WHERE userID=".$current_user->ID." AND category='program' AND wp_bb_efforts.setID=".$setID);
		return $sets;
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
	error_log( 'variable contains = ' . print_r( $_POST, true ) );
	global $wpdb;
	$current_user = wp_get_current_user();

	$set = array_shift($_POST);
		$user_ID = $current_user->ID;
		$date = date_i18n( 'Y-m-d H:i:s' , strtotime( $set['date'] ) + ( $set['time']*60*60 ),false);
		$category = $set['category'];
		$description = $set['description'];
		$status = 'complete';
		$parent = NULL;//setID of referred set (program)
		
error_log( 'decon set = ' . print_r( compact('user_ID','date','category','description','status','parent'), true ) );
	//input to set and retrieve auto setID
	bb_insert_set($user_ID,$date,$category,$description,$status,$parent);
	

	//validate as assigned
	//validate insertion

	error_log( 'variable contains = ' . print_r( $_POST, true ) );
	$setID = $wpdb->insert_id;
		foreach ( $_POST as $effort ){
		$discipline =  $effort['discipline'];
		$setting =  $effort['setting'];
		$duration =  sec_to_time( $effort['duration']*60 );
		$details =  $effort['details'];
		$distance =  (float)$effort['distance'];
		$difficulty =  (int)$effort['difficulty'];
		$foods =  $effort['foods'];
		$water =  (int)$effort['water'];
			if ( 'swimming' == $discipline )
				$distance = $distance/1000;
				
error_log( 'recurse decon effort = ' . print_r( compact('setID','discipline','setting','difficulty','duration','distance','details'), true ) );

		bb_insert_effort($setID,$discipline,$setting,$difficulty,$duration,$distance,$details);
	}

	//return flag check something
	echo 'suck my kiss';

	die();
}
add_action('wp_ajax_bb_submit', 'bb_submit_callback');

 ?>