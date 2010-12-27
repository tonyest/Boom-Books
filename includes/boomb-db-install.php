<?php
/*
 *
 *	install sessions databse table
 *
 */
function install_boomb_sessions() {
	global $wpdb;
	/* Boom Books 'bb_sets' table constructor */
	$table_name = $wpdb->prefix . "bb_sets";
	if($wpdb->get_var("show tables like '$table_name'") != $table_name) {

		$sql = "CREATE TABLE " . $table_name . " (
			setID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			userID bigint(20) UNSIGNED NOT NULL,
			start_date date NOT NULL,
			start_time time NOT NULL,
			category tinytext NOT NULL,			
			status tinytext NOT NULL,
			name tinytext,
			description text,
			parent bigint(20) UNSIGNED,
			PRIMARY KEY  (setID),
			FOREIGN KEY (userID) REFERENCES wp_users(ID)
		);";
	dbDelta($sql);
	}
}
/*
 *
 *	install sets databse table
 *
 */
function install_boomb_sets() {
	global $wpdb;
	/* Boom Books 'bb_efforts' table constructor */
	$table_name = $wpdb->prefix . "bb_efforts";
	if($wpdb->get_var("show tables like '$table_name'") != $table_name) {

		$sql = "CREATE TABLE " . $table_name . " (
			effortID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			setID bigint(20) UNSIGNED NOT NULL,
			sport tinytext NOT NULL,
			setting tinytext NOT NULL,			
			difficulty tinyint(2) UNSIGNED DEFAULT '0' NOT NULL,
			duration time NOT NULL,
			distance float(6,2) UNSIGNED DEFAULT '0' NOT NULL,			
			details text NOT NULL,
			max_hr int UNSIGNED,
			avg_hr int UNSIGNED,
			water float(4,2) UNSIGNED DEFAULT '0' NOT NULL,
			PRIMARY KEY  (effortID,setID),
			FOREIGN KEY (setID) REFERENCES wp_bb_sets(setID)
		);";
	dbDelta($sql);
	}
}
/*
 *
 *	install stretches databse table
 *
 */
function install_boomb_stretches() {
	global $wpdb;
	/* Boom Books 'bb_stretches' table constructor */
	$table_name = $wpdb->prefix . "bb_stretches";
	if($wpdb->get_var("show tables like '$table_name'") != $table_name) {

		$sql = "CREATE TABLE " . $table_name . " (
			stretchesID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			setID bigint(20) UNSIGNED NOT NULL,
			muscle tinytext NOT NULL,
			duration time NOT NULL,		
			PRIMARY KEY  (stretchesID,setID),
			FOREIGN KEY (setID) REFERENCES wp_bb_sets(setID)
		);";
	dbDelta($sql);
	}
}
/*
 *
 *	install dailys databse table
 *
 */
function install_boomb_dailys() {
	global $wpdb;
	/* Boom Books 'bb_daily' table constructor */
	$table_name = $wpdb->prefix . "bb_dailys";
	if($wpdb->get_var("show tables like '$table_name'") != $table_name) {

		$sql = "CREATE TABLE " . $table_name . " (
			dailyID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			userID bigint(20) UNSIGNED NOT NULL,
			date date NOT NULL,
			RHR tinyint(3) UNSIGNED DEFAULT '0' NOT NULL,
			water tinyint(3) UNSIGNED DEFAULT '0' NOT NULL,
			sleep tinyint(3) UNSIGNED DEFAULT '0' NOT NULL,		
			PRIMARY KEY  (dailyID,userID,date),
			FOREIGN KEY (userID) REFERENCES wp_users(ID)
		);";
	dbDelta($sql);
	}
}
/*
 *
 *	install journals databse table
 *
 */
function install_boomb_journals() {
	global $wpdb;
	/* Boom Books 'bb_journal' table constructor */
	$table_name = $wpdb->prefix . "bb_journals";
	if($wpdb->get_var("show tables like '$table_name'") != $table_name) {

		$sql = "CREATE TABLE " . $table_name . " (
			journalID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			setID bigint(20) UNSIGNED,
			effortID biging(20) UNSIGNED,
			dailyID bigint(20) UNSIGNED NOT NULL,
			meal tinytext NOT NULL,
			time time NOT NULL,
			foods text NOT NULL,	
			PRIMARY KEY  (journalID,dailyID),
			FOREIGN KEY (dailyID) REFERENCES wp_bb_dailys(dailyID)
		);";
   dbDelta($sql);
	}
}

/* - - - - - - - - - - - - - - PROGRAM TABLES - - - - - - - - - - - - -*/
/*
 *
 *	install templates table
 *
 */
function install_boomb_programs() {
	global $wpdb;
	/* Boom Books 'bb_sets' table constructor */
	$table_name = $wpdb->prefix . "boomb_programs";
	if($wpdb->get_var("show tables like '$table_name'") != $table_name) {

		$sql = "CREATE TABLE " . $table_name . " (
			programID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			timestamp datetime NOT NULL,
			name tinytext,
			description text,
			PRIMARY KEY  (programID)
		);";
	dbDelta($sql);
	}
}
/*
 *
 *	install template sessions databse table
 *
 */
function install_boomb_program_sessions() {
	global $wpdb;
	/* Boom Books 'bb_sets' table constructor */
	$table_name = $wpdb->prefix . "boomb_program_sessions";
	if($wpdb->get_var("show tables like '$table_name'") != $table_name) {

		$sql = "CREATE TABLE " . $table_name . " (
			sessionID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			programID bigint(20) UNSIGNED NOT NULL,
			day bigint(20) UNSIGNED NOT NULL,
			time time NOT NULL,
			PRIMARY KEY  (sessionID),
			FOREIGN KEY (programID) REFERENCES " . $wpdb->prefix . "boomb_programs(programID)
		);";
	dbDelta($sql);
	}
}
/*
 *
 *	install boomb template sets database table
 *
 */
function install_boomb_program_sets() {
	global $wpdb;
	/* Boom Books 'bb_efforts' table constructor */
	$table_name = $wpdb->prefix . "boomb_program_sets";
	if($wpdb->get_var("show tables like '$table_name'") != $table_name) {

		$sql = "CREATE TABLE " . $table_name . " (
			setID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			sessionID bigint(20) UNSIGNED NOT NULL,
			sport tinytext NOT NULL,
			setting tinytext NOT NULL,			
			difficulty tinyint(2) UNSIGNED DEFAULT '0' NOT NULL,
			duration time NOT NULL,
			distance float(6,2) UNSIGNED DEFAULT '0' NOT NULL,			
			details text NOT NULL,
			max_hr int UNSIGNED,
			avg_hr int UNSIGNED,
			water float(4,2) UNSIGNED DEFAULT '0' NOT NULL,
			PRIMARY KEY  (setID,sessionID),
			FOREIGN KEY (sessionID) REFERENCES " . $wpdb->prefix . "boomb_program_sessions(sessionID)
		);";
	dbDelta($sql);
	}
}

/* - - - - - - - - - - - - - USER TABLES - - - - - - - - - - - - - -*/
/*
 *
 *	install user sessions databse table
 *
 */
function install_boomb_user_sessions() {
	global $wpdb;
	/* Boom Books 'bb_sets' table constructor */
	$table_name = $wpdb->prefix . "boomb_sessions";
	if($wpdb->get_var("show tables like '$table_name'") != $table_name) {

		$sql = "CREATE TABLE " . $table_name . " (
			sessionID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			userID bigint(20) UNSIGNED NOT NULL,
			start_date date NOT NULL,
			start_time time NOT NULL,
			category tinytext NOT NULL,			
			status tinytext NOT NULL,
			name tinytext,
			description text,
			parent bigint(20) UNSIGNED,
			PRIMARY KEY  (sessionID),
			FOREIGN KEY (userID) REFERENCES " . $wpdb->prefix . "users(ID)
		);";
	dbDelta($sql);
	}
}
/*
 *
 *	install user sets databse table
 *
 */
function install_boomb_user_sets() {
	global $wpdb;
	/* Boom Books 'bb_efforts' table constructor */
	$table_name = $wpdb->prefix . "boomb_sets";
	if($wpdb->get_var("show tables like '$table_name'") != $table_name) {

		$sql = "CREATE TABLE " . $table_name . " (
			setID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			sessionID bigint(20) UNSIGNED NOT NULL,
			sport tinytext NOT NULL,
			setting tinytext NOT NULL,			
			difficulty tinyint(2) UNSIGNED DEFAULT '0' NOT NULL,
			duration time NOT NULL,
			distance float(6,2) UNSIGNED DEFAULT '0' NOT NULL,			
			details text NOT NULL,
			max_hr int UNSIGNED,
			avg_hr int UNSIGNED,
			water float(4,2) UNSIGNED DEFAULT '0' NOT NULL,
			PRIMARY KEY  (setID,sessionID),
			FOREIGN KEY (sessionID) REFERENCES " . $wpdb->prefix . "boomb_sessions(sessionID)
		);";
	dbDelta($sql);
	}
}



?>