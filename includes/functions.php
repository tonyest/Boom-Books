<?PHP	

/*
 *INSERT VALUES INTO SETS TABLE
 *
 *$args[col1_name,col1_val,col2_name,col2_value]//xx
 *
 */
function bb_insert_set($date,$category){
	global $wpdb;
	 $user_ID = bb_userID();

	$wpdb->insert( 'wp_bb_sets',
		array( 'userID' => $user_ID,
			'start' => $date ,
			'category' => $category
		), array( '%d', '%s','%s' ) );
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
function bb_insert_dailys($date,$RHR,$water,$sleep){
	global $wpdb;
	 $user_ID = bb_userID();
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
 *  GRAB CURRENT USER ID	
 *
 *
 *
 */
function bb_userID() {
	global $current_user;
	get_currentuserinfo();
	return $current_user->ID;
}

/*
 *  CALCULATE SQL TIME FORMAT FROM SECONDS	
 *
 *
 *
 */
function sec_to_time($secs){
	return (floor($secs/(60*60))%(60*60)).":".(floor($secs/60)%60).":".($secs%60);
}

/*
 *  CALCULATE SQL TIME FORMAT FROM SECONDS	
 *
 *
 *
 */
function time_diff($from,$to){
return (strtotime($to) - strtotime($from) )/ (60*60*24);
}

/*
 *  If Then Return
 * customizeable for different cases
 *
 */
function itr ($arg,$type='empty') {
	switch($type){
		case 'empty':
		if(!empty($arg))
			return $arg;
		break;
		
		case 'isset':
		if(isset($arg))
			return$arg;
		break;
		
		case 'zero':
			if(0 == $arg)
				return '-';
			else
				return $arg;
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
function bb_query(){

 $querystr = "
    SELECT wposts.* 
    FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta
    WHERE wposts.ID = wpostmeta.post_id 
    AND wpostmeta.meta_key = 'tag' 
    AND wpostmeta.meta_value = 'email' 
    AND wposts.post_status = 'publish' 
    AND wposts.post_type = 'boom_book' 
    ORDER BY wposts.post_date DESC
 ";

 $pageposts = $wpdb->get_results($querystr, OBJECT);
 


$querystr = "
	SELECT * FROM $wpdb->posts
	LEFT JOIN $wpdb->postmeta ON($wpdb->posts.ID = $wpdb->postmeta.post_id)
	LEFT JOIN $wpdb->term_relationships ON($wpdb->posts.ID = $wpdb->term_relationships.object_id)
	LEFT JOIN $wpdb->term_taxonomy ON($wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id)
	LEFT JOIN $wpdb->terms ON($wpdb->terms.term_id = $wpdb->term_taxonomy.term_id)
	WHERE $wpdb->terms.name = 'slides'
	AND $wpdb->term_taxonomy.taxonomy = 'category'
	AND $wpdb->posts.post_status = 'publish'
	AND $wpdb->posts.post_type = 'post'
	AND $wpdb->postmeta.meta_key = 'order'
	ORDER BY $wpdb->postmeta.meta_value ASC
";
}*/
 ?>