<?PHP	
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
function am_pm ($date) {
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

?>