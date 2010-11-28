<?PHP




function myquery($query){
	$user="root";
	$password="root";
	$database="testconnect";
	mysql_connect(localhost,$user,$password);
	@mysql_select_db($database) or die( "Unable to select database");
	$result = mysql_query($query);
	mysql_close();
	return $result;
}


/*boombooks database creator*/
function mk_boombooks_db(){
	$user="root";
	$password="root";
	$database="testconnect";
	mysql_connect(localhost,$user,$password);
	@mysql_select_db($database) or die( "Unable to select database");
	$query="CREATE TABLE contacts (
			id int(6) NOT NULL auto_increment,
			first varchar(15) NOT NULL,
			last varchar(15) NOT NULL,
			PRIMARY KEY (id),
			UNIQUE id (id),
			KEY id_2 (id)
	)";
	mysql_query($query);
	mysql_close();
}



/*      global $jal_db_version;
$jal_db_version = "1.0";               */

/*
function jal_install () {
   global $wpdb;
   global $jal_db_version;

   $table_name = $wpdb->prefix . "liveshoutbox";
   if($wpdb->get_var("show tables like '$table_name'") != $table_name) {
      
      $sql = "CREATE TABLE " . $table_name . " (
	  id mediumint(9) NOT NULL AUTO_INCREMENT,
	  time bigint(11) DEFAULT '0' NOT NULL,
	  name tinytext NOT NULL,
	  text text NOT NULL,
	  url VARCHAR(55) NOT NULL,
	  UNIQUE KEY id (id)
	);";

      require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
      dbDelta($sql);

      $rows_affected = $wpdb->insert( $table_name, array( 'time' => current_time('mysql'), 'name' => $welcome_name, 'text' => $welcome_text ) );
 
      add_option("jal_db_version", $jal_db_version);

   }
}





if( !defined( 'BB_PLUGIN_DIR' ) )
	define( 'BB_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . basename(dirname(__FILE__)) );
if( !defined( 'BB_PLUGIN_URL' ) )
	define( 'BB_PLUGIN_URL', WP_PLUGIN_URL . '/' . basename(dirname(__FILE__)) );
*/

/*
public function get_index_id() {
	global $wpdb;

	$index_id = $wpdb->get_var( "SELECT ID FROM $wpdb->posts WHERE post_name = '" . $this->name . "'" );

	if( $index_id == NULL)
		return false; 
	else 
		return $index_id;
}

public function get_index_permalink() {

	$index_id = $this->get_index_id();

	if( $index_id == false )
		return false; 
	else
		return get_permalink( $index_id );
}*/



/*
Database fields:

PERSONAL DATA
User / e-mail
UID
age
height
weight


DAILYS
	int		Sleep
	int		RHR
	int		Water
	string	Brekky (pre-train)
	string	Brekky (post-train)
	string	Morning Tea
	string	Lunch
	string	Afternoon tea
	string	Dinner
	string	Supper

LOG
	date		Date
	int			Day
	string		time of day array [dusk,morning,midday,afternoon,dusk,night]
	string		Exercise environment [road,gym,pool,open water,wind trainer]
	string		Describe it
	string		activity [cycling,running,swimming,resistance training]
	datetime 	time
	int			distance
	string		Feeling Before, During and After
	int			Water consumed
	string		Foods consumed

Stretch array [Hamstrings],[Quadriceps],[Glutes],[Hip-Flexors],[ITB],[Calves],[Back],[Lower Back],[Triceps],[Biceps],[Chest]	

//Program 
PROGRAMME GENERATOR
	datepicker
	activity drop down
	environment drop down + other
	Description [textbox]
	Sets Distance/Duration (dropdown number)*[distance].[duration]
	Distance total {calculated}
	Duration total {calculated}
<<<<<database fields**********************************calculated fields>>>>>>>
//Summaries
SUMMARY/REPORTING - {single day/duration[week,month,year]}
	activity
		.total
			.distance+group-placing
			.duration+group-placing
			.grp_distance
			.grp_duration

	avg
		.stretching
		.RHR
		.h2o
		.sleep
		
TYPES
	report
	program
	log
	dailys
function exercise_query(username/UID,t1,t2,[format...' '])
function 



*/