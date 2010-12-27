<?php
/*
 * Get boomb stats widget content
 * 
 * 
 * 
 *
 */
function boomb_stats_widget_content() {
	$today = current_time_fixed( 'timestamp' , 0 );//normal
	$from = date( 'l d-M-Y', $today );	
	$to = date( 'l d-M-Y', $today+(60*60*24*1)) ;
	$current_user = wp_get_current_user();
	$current_user->ID;
	printf( __( 'Hello %1$s Welcome to Boom Books' , 'boomb' ) , $current_user->display_name );
	$sets = get_program_results( $from , $to , $current_user );
	if ( isset($sets) && !empty($sets)) {
		foreach ( $sets as $set ) {
			if( isset($set->start_date) && strstr( $set->start_date , date('Y-m-d',$today ) ) ) {
				$sess_today = date('a',$set->start_time).'&nbsp;'. $set->sport.'-'.$set->setting.'&nbsp;'.$set->duration.'&nbsp;'.$set->distance.' @;'.$set->difficulty;
			} else {
				$sess_today = 'Rest Day';
			}
			if( isset($set->start_date) && strstr( $set->start_date , date('Y-m-d',$today+(60*60*24*1)) ) ) {
				$sess_tmrw = date('a',$set->start_time).'&nbsp;'. $set->sport.'-'.$set->setting.'&nbsp;'.$set->duration.'&nbsp;'.$set->distance.' @'.$set->difficulty.'%';
			} else {
				$sess_tmrw = 'Rest Day';
			}
		}
	} else {
		$sess_today = 'Rest Day';
		$sess_tmrw = 'Rest Day';				
	}
		?>	
		<div>
			<a href="<?php echo wp_logout_url( home_url() ); ?>" title="Logout" class="logout">Logout</a>
		</div>
		<script>
		jQuery(document).ready( function($) {
			//		close postboxes that should be closed
			$("div.stats-sets h3 a").click(function(event) {
				event.preventDefault();
				$(this).parent().siblings().toggle();
			});
		});
	</script>
		<div class="navigation" style="margin:0.5em 0 1em 1em;">
		<h3>BoomB Navigation</h3>
		<ul class="navigation">
			<li><a href="?page=user_program">Program</a></li>
			<li><a href="?page=reports">Reports</a></li>
			<ul>
				<li><a href="?page=reports_stats">Report Stats</a></li>
			</ul>
			<li><a href="?page=program_index">Coach</a></li>
		</ul>
	</div>
		<div class="stats-sets today">
			<h3><a href="today">Today's Set</a></h3>
			<ul class="stats-sets today">
				<li><a class="set"><?php echo $sess_today;?></a> <a class="delete"></a></li>		
			</ul>
		</div>
		<div class="stats-sets tomorrow">
			<h3><a href="tomorrow">Tomorrow's Set</a></h3>
			<ul class="stats-sets tomorrow">
				<li><a class="set"><?php echo $sess_tmrw;?></a> <a class="delete"></a></li>		
			</ul>
		</div>
	<?php
}

/*
 * Get boomb content
 * 
 * 
 * 
 *
 */
add_action( 'boomb_content' , 'boomb_content_index');
function boomb_content_index ( $page ) {
	switch ( $page ) :
			case 'reports'				: get_reports();
				break;
			case 'user_program'			: get_user_program();
				break;
			case 'program_index'		: get_program_index();
				break;
			case 'author' 				: get_author( $_GET['programID'] );
				break;
			case 'edit_user_program'	: edit_user_program();
				break;
			case 'reports_stats'		: get_reports_stats();
				break;
			endswitch;
}
/*
 * Get boomb editor widget content
 * 
 * 
 * 
 *
 */
function boomb_editor_widget_content() {
	if ( 'author' == $_GET['page'])
		get_program_editor();
	else
		get_user_session_editor();
	?>	
		<div class="editor">
			<?php if ( 'author' == $_REQUEST['page'] ): ?>	
			<button type="submit" value="<?php echo bloginfo('wpurl'),'/wp-admin/admin-ajax.php' ?>" class="session_submit" id="submit_program">Submit to Program</button>
		<?php else: ?>
			<button type="submit" value="<?php echo bloginfo('wpurl'),'/wp-admin/admin-ajax.php' ?>" class="session_submit" id="submit_session">Submit Session</button>
		<?php endif; ?>
		</div>
<?php
}

/*
 *	INSERT DATERANGE
 *	inserts date range form elements into boom books pages
 *	
 *
 */
function insert_daterange() {
	 $today = current_time_fixed( 'timestamp' , 0 );//normal
	 $from = date( 'D d-M-Y', $today );	
	 $to = date( 'D d-M-Y', $today + ( 60*60*24*6*1) ) ;
	switch ( $_POST['src_dates'] ){
		case 'range' :	
			if(!empty($_POST['from']) && isset($_POST['from'])){
				$from = $_POST['from'];
			}
			if(!empty($_POST['to']) && isset($_POST['to'])){
				$to = $_POST['to'];
			}
			break;
		case 'this_month':
			$from = date( 'D d-M-Y' , strtotime(date( ' \0\1-M-Y', $today )));
			$to = date( 'D d-M-Y' , strtotime(date( ' t-M-Y', $today )));
			break;

	}
	echo '<div class="date-month" style=""><form name="bb-reports-month" action="" id="reports_month" method="post">',
			'<button type="submit" name="src_dates" value="this_month">Search this Month</button></form></div>';
			//',date( 'F' , $today ),
	echo '<div class="date-range" style="white-space:nowrap;"><form name="date-range" action="" method="post">',
			'From: <input name="from" type="text" class="bb-datepicker" id="from" size="30" value="',$from,'">',
			'&nbsp;To: <input name="to" type="text" class="bb-datepicker" id="to" size="30" value="',$to,'">',
			'<button type="submit" name="src_dates" value="range">search dates</button>',
			'</form></div>';

	return compact('today','from','to');
}
/*
 * 
 * 
 * Get Program page
 * 
 *
 */
function get_user_program() {

	$date = insert_daterange();
	extract($date);
	
	$range=time_diff($from,$to);// default program display range 2 weeks one month avg		
	$current_user = wp_get_current_user();
/* get query formatted sets between period */
	$sessions = get_program_results( $from , $to , $current_user );

	echo $current_user->user_firstname,' ',$current_user->user_lastname,'<br />';
	?>
	<form name="bb_program" action="<?php echo WP_ADMIN_URL,'?page=program';?>" id="bb_program" method="post">
		<table class="bb-program" id="bb_program_table">
			<tr class="bb-program header">
				<th></th> <th></th> <th>Duration<br /><font size="1"> h:m:s </font></th> <th>Distance<br /><font size="1">km</font></th> <th>Location</th> <th>Difficulty<br /><font size="1"> % </th> <th>Status</th>
			</tr>
	<?php
	if ( '1' != date('d',strtotime($from) ) ):
		?>
		<tr class="bb-program month-divider">
			<th colspan="7"><h3><?php echo date('F',strtotime($from)); ?> </h3></th>
		</tr>
		<?php
		endif;
//iterate through daterange, format and output matching set
	for ( $day = 0; $day <= $range; $day += 1 ) {
		if( '01' == date('d',strtotime($from)+( 60*60*24*$day ) ) ):
			echo '<tr class="bb-program month-divider"><th colspan="7"><h3>',date('F',strtotime($from)+( 60*60*24*$day ) ),'</h3></th></tr>';
		endif;//insert Month name between rollover

		if( 'Monday' == date('l',strtotime($from)+( 60*60*24*$day ) ) ):
			echo '<tr class="bb-program week-divider"><th colspan="7"><hr /></th></tr>';
		endif;
		$rowreturn = false;
		//iterate through array & output results rows
		foreach( $sessions as $session ) {
			if( strstr( $session->start_date , date('Y-m-d',strtotime($from)+( 60*60*24*$day ) ) ) ) :

				if( 'incomplete' == $session->status ) :
					?>
					<tr class="bb-program set-row-incomplete" href="<?php echo bloginfo('wpurl'),'/wp-admin/admin-ajax.php'; ?>">
						<td class="bb-program set-date">
							<input type="hidden" name="sessionID" value="<?php echo $session->sessionID; ?>" />
							<input type="hidden" name="category" value="program" />
					<?php
				else :
					?>
					<tr class="bb-program set-row-complete">
						<td class="bb-program set-date"><?php
				endif;
				echo	date( 'd . D', strtotime($from)+( 60*60*24*$day )),' - ',date('a',$session->start_time);?>
				 </td><td class="bb-program sport" style="white-space:normal;">
					 <?php echo $session->sport; ?>
				 </td><td class="bb-program duration">
					 <?php echo itr($session->duration,'zero_time'); ?>
				 </td><td class="bb-program distance">
					 <?php echo itr($session->distance,'zero'); ?>
				 </td><td class="bb-program setting" style="white-space:normal;">
					 <?php echo $session->setting; ?>
				 </td><td class="bb-program difficulty">
					 <?php echo itr($session->difficulty,'zero'); ?>
				 </td><td class="bb-program status">
					 <?php echo $session->status; ?>
				 </td></tr>
				<?php 
				$rowreturn=true;
			endif;
		}
		if( false == $rowreturn ) :
		?>
		<tr class="rest">
			<td class="bb-program date">
				<?php echo date( 'd . D', strtotime($from)+(60*60*24*$day)); ?>
			</td>
			<td class="bb-program rest-day">
				Rest Day
			</td>
		</tr>
		<?php
		endif;
	}
	echo '</table></form>';
}//end program()
/*
 *
 * 
 *  Get Editor form for boomb widget
 * 
 *
 */
function get_editor() {
	?>
	<div class="">
		<h3>New Session</h3>
		<input type="text" name="start_date" value="d-M-Y" class="datepicker" style="width:10em;" />&nbsp;@<input type="text" name="start_time" value="hh:mm:ss" class="time default" style="width:7em;" />
	</div>
	<div class="">
		<ul class="session">
			<li><a href="add" class="add">[+]</a> <a href="reset" class="reset" style="float:right;">reset</a> </li>
		</ul>
	</div>
	<div class="bb-widget">
		<form class="editor">
			<ul class="editor">
					<legend><h3>Set Editor</h3></legend>
				<li><label for="sport">Sport</label>	
					<select name="sport" value="sport" >
						<option value="">Select a Sport</option>
						<option value="cycle">Cycling</option>
						<option value="swim">Swimiming</option>
						<option value="run">Running</option> 
					</select></li>
				<li><label for="setting">Setting</label>	
					<select name="setting" value="setting" >
						<option value="">Select a Setting</option>
						<option value="road">Road</option>
						<option value="wind">Wind Trainer</option>
						<option value="velo">Velodrome</option>
						<option value="track">Track</option>
						<option value="gym">Gym</option>
						<option value="pool">Pool</option>
						<option value="openwater">Open Water</option>
					</select></li>
				<li><label for="distance">Distance</label>		<input type="text" name="distance" value="km" /></li>
				<li><label for="duration">Duration</label>		<input type="text" name="duration" value="hh:mm:ss" /></li>
				<li><label for="difficulty">Difficulty</label>	<input type="text" name="difficulty" value="RPE" /></li>
				<li><label for="water">Water</label>			<input type="text" name="water" value="Litres" /></li>
				<li><label for="foods">Foods</label>			<input type="text" name="foods" value="comma separated values" /></li>
				<li><label for="details">Details</label>	<p><textarea type="text" name="details" value=""></textarea></p></li>
			</ul>
				<button type="button" value="index" id="save_set">Save Changes</button>
		</form>
	</div>
	<?php
}
/*
 *
 * 
 *  Get Editor form for boomb widget
 * 
 *
 */
function get_user_session_editor() {
	?>
	<div class="">
		<h3>New Session</h3>
		<input type="text" name="start_date" value="d-M-Y" class="datepicker" style="width:10em;" />&nbsp;@<input type="text" name="start_time" value="hh:mm:ss" class="time default" style="width:7em;" />
	</div>
	<div class="">
		<ul class="session">
			<li><a href="add" class="add">[+]</a> <a href="reset" class="reset" style="float:right;">reset</a> </li>
		</ul>
	</div>
	<div class="bb-widget">
		<form class="editor">
			<input type="hidden" name="setID" value="" class="setID" />
			<ul class="editor">
					<legend><h3>Set Editor</h3></legend>
				<li><label for="sport">Sport</label>	
					<select name="sport" value="sport" >
						<option value="">Select a Sport</option>
						<option value="cycle">Cycling</option>
						<option value="swim">Swimiming</option>
						<option value="run">Running</option> 
					</select></li>
				<li><label for="setting">Setting</label>	
					<select name="setting" value="setting" >
						<option value="">Select a Setting</option>
						<option value="road">Road</option>
						<option value="wind">Wind Trainer</option>
						<option value="velo">Velodrome</option>
						<option value="track">Track</option>
						<option value="gym">Gym</option>
						<option value="pool">Pool</option>
						<option value="openwater">Open Water</option>
					</select></li>
				<li><label for="distance">Distance</label>		<input type="text" name="distance" value="km" /></li>
				<li><label for="duration">Duration</label>		<input type="text" name="duration" value="hh:mm:ss" /></li>
				<li><label for="difficulty">Difficulty</label>	<input type="text" name="difficulty" value="RPE" /></li>
				<li><label for="water">Water</label>			<input type="text" name="water" value="Litres" /></li>
				<li><label for="foods">Foods</label>			<input type="text" name="foods" value="comma separated values" /></li>
				<li><label for="details">Details</label>	<p><textarea type="text" name="details" value=""></textarea></p></li>
			</ul>
				<button type="button" value="index" id="save_set">Save Changes</button>
		</form>
	</div>
	<?php
}

/*
 * 
 *
 * Get Reports page with all results in range
 * 
 *
 */
function get_program_editor() {
	?>
	<div class="editor-header">
		<div>
		<h3 style="display:inline;">Add New Session</h3>
		<a href="<?php echo bloginfo('wpurl'),'/wp-admin/admin-ajax.php' ?>" title="delete-session" class="delete-session" style="float:right;">delete session</a>
		</div>
		<input type="text" name="day" value="day no." class="day default" style="width:10em;" />
		&nbsp;@<input type="text" name="time" value="hh:mm:ss" class="time default" style="width:7em;" />
	</div>
	<div class="">
		<ul class="session">
			<li><a href="add" class="add">[+]</a> <a href="reset" class="reset" style="float:right;">reset</a> </li>
		</ul>
	</div>
	<div class="bb-widget">
		<form class="editor">
		<input type="hidden" name="setID" value="" class="setID" />
			<ul class="editor">
					<legend><h3>Set Editor</h3></legend>
				<li><label for="sport">Sport</label>	
					<select name="sport" value="sport" >
						<option value="">Select a Sport</option>
						<option value="cycle">Cycling</option>
						<option value="swim">Swimiming</option>
						<option value="run">Running</option> 
					</select></li>
				<li><label for="setting">Setting</label>	
					<select name="setting" value="setting" >
						<option value="">Select a Setting</option>
						<option value="road">Road</option>
						<option value="wind">Wind Trainer</option>
						<option value="velo">Velodrome</option>
						<option value="track">Track</option>
						<option value="gym">Gym</option>
						<option value="pool">Pool</option>
						<option value="openwater">Open Water</option>
					</select></li>
				<li><label for="distance">Distance</label>		<input type="text" name="distance" value="km" /></li>
				<li><label for="duration">Duration</label>		<input type="text" name="duration" value="hh:mm:ss" /></li>
				<li><label for="difficulty">Difficulty</label>	<input type="text" name="difficulty" value="RPE" /></li>
				<li><label for="water">Water</label>			<input type="text" name="water" value="Litres" /></li>
				<li><label for="foods">Foods</label>			<input type="text" name="foods" value="comma separated values" /></li>
				<li><label for="details">Details</label>	<p><textarea type="text" name="details" value=""></textarea></p></li>
			</ul>
				<button type="button" value="index" id="save_set">Save Changes</button>
		</form>
	</div>
	<?php
}
/*
 * 
 *
 * Get Reports page with all results in range
 * 
 *
 */
function get_reports() {
	$date = insert_daterange();
	extract($date);
	$current_user = wp_get_current_user();
	$args = array(	'category' => 'session',
					'range' => array('from' => $from , 'to' => $to ),
					'userID' => $current_user->ID 
					);
	$sets = get_session_results( $args );
	$range=time_diff($from,$to);// default session display range 2 weeks one month avg	
	$sessions[] = array();
	foreach( $sets as $set ) {		//re-arrange container
		$sessions[$set->sessionID]['start_date'] = $set->start_date;
		$sessions[$set->sessionID]['start_time'] = $set->start_time;
		$sessions[$set->sessionID][] = $set;	
	}		
		echo $current_user->user_firstname,' ',$current_user->user_lastname,'<br />';
		?>
		<table class="boomb-session" id="full_report_range">
			<tr class="header">
				<th /> <th /><th>Sport</th> <th>Duration<br /><font size="1"> h:m:s </font></th> <th>Distance<br /><font size="1">km</font></th> <th>Location</th> <th>Difficulty<br /><font size="1"> % </th> <th>Status</th> 
			</tr>
			<tr class="month-divider">
				<th colspan="7"><h3><?php echo date( 'F Y',strtotime( $from ) ); ?> </h3></th> 
			</tr>
		<?php
	//iterate through daterange, format and output matching set
	for ( $day = 0; $day <= $range; $day += 1 ) {
		//insert Month name between rollover
		if( $day != 0 && date('d',strtotime($from)+( 60*60*24*$day ) ) == '01' ):
			echo '<tr class="month-divider"><th colspan="7"><h3>',
			date('F Y',strtotime($from)+( 60*60*24*$day ) ),'</h3></th></tr>';
		endif;//insert Month name between rollover
		//iterate through array & output results rows
		foreach( $sessions as $sessionID => $session ) {
			if( strstr( $session['start_date'] , date('D d-M-Y',strtotime($from)+( 60*60*24*$day ) ) ) ):
			?>
			<tr class="divider"><th colspan="7"><hr /></th></tr>
			<tr class="session" href="<?php echo bloginfo('wpurl') , '/wp-admin/admin-ajax.php"'; ?>" >
				<td class="date">
				<input type="hidden" name="sessionID" value="<?php echo $sessionID; ?>" />
				<input type="hidden" name="category" value="session" />
				<?php echo date( 'l d', strtotime($from)+( 60*60*24*$day ) ),' - ',date('a',$session->start_time); ?>
				</td>
			</tr>
		<?php
				foreach ( $session as $key => $set ) {
					if ( $key === 'start_date' || $key === 'start_time' ) :
						continue;
					endif;
					?>
			 		<tr class="set">
						<td />
						<td class="sport" style="white-space:normal;"><?php echo $set->sport ?></td>
						<td class="duration"><?php echo itr($set->duration,'zero_time') ?></td>
						<td class="distance"><?php echo itr($set->distance,'zero') ?></td>
						<td class="setting" style="white-space:normal;"><?php echo $set->setting ?></td>
						<td class="difficulty"><?php echo itr($set->difficulty,'zero') ?></td>
					</tr>
					<?php
				}
			endif;
		}
	}
	?> 
	</table>
	<?php
}
/*
 * 
 *
 * Get Reports stats
 * 
 *
 */
function get_reports_stats() {
	/*
		calculate percentage of program completed
	*/
	$current_user = wp_get_current_user();
	$args = array(	'category' => 'session',
					'userID' => $current_user->ID,
					'parent' => true
					);
	$session_stats = get_session_stats($args);
	$args = array(	'category' => 'program',
					'userID' => $current_user->ID
					);
	$program_stats = get_session_stats($args);
	error_log( print_r( $session_stats,true ) );
	foreach ( $program_stats as $key => $program )  {
		$progress[$key]['distance'] = $session_stats[$key]->distance/$program->distance;
		$progress[$key]['duration'] = $session_stats[$key]->duration/$program->duration;
		$progress[$key]['distance_diff'] = $program->distance - $session_stats[$key]->distance;
		$progress[$key]['duration_diff'] = $program->duration - $session_stats[$key]->duration;
		$progress[$key]['difficulty_diff'] = $program->difficulty - $session_stats[$key]->difficulty;
		$progress[$key]['setting'] = $session_stats[$key]->setting;
		$progress[$key]['start_date'] = $session_stats[$key]->start_date;
		$progress[$key]['end_date'] = $session_stats[$key]->end_date;				
		$progress[$key]['time'] = $session_stats[$key]->time;	
	}
	echo print_r($progress,true);
	return;



	/*
	TIERS
		x kilometers in y time  (avg speed)
		frequency of sets with (dist || dur || spd) > z

	*/
}
/*
 * 
 *
 * Get List of program templates
 * 
 *
 */
function get_program_index() {
	$programs = get_program_list();	
	?>
	<script>
	jQuery(document).ready( function($) {
		$('table#program_index tr').click( function () {
			window.location = "?page=author&programID="+$(this).attr('href');
		});
	});
	</script>
	<a href>select - new - delete</a>
		<table class="program" id="program_index">
			<tr>
				<th>Program</th><th>Description</th>
			</tr>
			<?php foreach ($programs as $program): ?>
			<tr class="row" href=<?php echo $program->programID;?>>
				<td><?php echo $program->name; ?></td>
				<td>  <?php echo $program->description; ?></td> 
		 	</tr>
			<?php endforeach; ?>
		</table>
		<h3>Create new Program</h3>
		<form action="?page=author"  method="post">
				<label for="name">Enter program name</label>
				<p>
					<input type="text" name="program_name"></input>
				</p>
				<label for="description">Enter program description</label>
				<p>
					<textarea name="program_description"></textarea>
				</p>
				<button type="submit">Submit</button>
		</form>
	<?php
}

function index_program_sessions() {
	$programID = $_POST['programID'];
	$sessions = get_program_sessions( $programID );
	
	$raw_sessions = get_session_results( $args );
	$range=time_diff($from,$to);// default session display range 2 weeks one month avg	
	$sessions[] = array();
	foreach( $raw_sessions as $raw_session ) {		//re-arrange container
		$sessions[$set->setID]['start_date'] = $raw_session->start_date;
		$sessions[$set->setID]['start_time'] = $raw_session->start_time;
		$sessions[$set->setID][] = $raw_session;	
	}		
		echo '<table class="boomb-program">',
			'<tr class="header"> <th /> <th /> <th>Sport</th> <th>Duration<br /><font size="1"> h:m:s </font></th> <th>Distance<br /><font size="1">km</font></th> <th>Location</th> <th>Difficulty<br /><font size="1"> % </th> <th>Status</th> </tr>';
		//iterate through array & output results rows
		foreach( $sessions as $setID => $session ) {

			echo 	'<tr class="divider"><th colspan="7"><hr /></th></tr>';
			echo 	'<tr class="session" href="',
						bloginfo('wpurl'),'/wp-admin/admin-ajax.php"><td class="date">',
						'<input type="hidden" name="setID" value="',$setID,'"><input type="hidden" name="category" value="session">';
			echo 	date( 'l d', strtotime($from)+( 60*60*24*$day )),
						' - ',date('a',$session->start_time),'</td></tr>';
				foreach ( $session as $key => $set ) {
					if ( $key === 'start_date' || $key === 'start_time' ) :
						continue;
					endif;
			echo 	'<tr class="set"><td><td><td class="sport" style="white-space:normal;">',
						 $set->sport,
					'</td><td class="duration">',
						 itr($set->duration,'zero_time'),
					'</td><td class="distance">',
						 itr($set->distance,'zero'),
					'</td><td class="setting" style="white-space:normal;">',
						 $set->setting,
					'</td><td class="difficulty">',
						 itr($set->difficulty,'zero'),
					'</td></tr>';
				}
		}

	echo '</table>';
}

function grade_results() {
	
	//filter results against targets
	
	
	return;
}
/*
 *
 * 
 *  Get author page
 * 
 *
 */
function get_author( $programID ) {
	?>
	<script>
		jQuery(document).ready( function($) {
			$('.userselect').hide();
		});
	</script>
	<?php
	if(	isset($programID) && !empty($programID) ) {
		$results = get_program_sets($programID);
	} else if (	isset( $_POST['program_name'] )
	 		&&	!empty( $_POST['program_name'] ) 
			&&	isset( $_POST['program_description'] ) 
			&&	!empty( $_POST['program_description'] ) 
	) {
		$programID = boomb_insert_program ( array( 'name' => $_POST['program_name'] , 'description' => $_POST['program_description'] ) );
		$results = get_program_sets( $programID );
	} else {
		echo 'program name exists<br />';
		get_program_index();
		return;
	}

		extract( $results ); // { $sets{0,1,2,...} , $range{ $from , $to } }

		$from = $range->from;
		$to = $range->to;
		?><div class="program"><?php
		echo "<input type=\"hidden\" value=\"" . $programID . "\" name=\"programID\" id=\"programID\" />";	//program ID is the constant identifier for this set
		//output list of parent sessions containing children sets
		if ( !isset($sessions) || empty($sessions) )
			echo 'program empty: add new sessions to begin';
		echo	'<table class="program" id="program_author">' ,
				'<tr> <th>Days<br /><font size="1">after start</font></th> <th>Sport</th> <th>setting</th> <th>duration<br /><font size="1">h:m:s</font></th> <th>distance<br /><font size="1">km</font></th> <th>difficulty<br /><font size="1">%</font></th> </tr>';
		for ( $day = $from; $day <= $to; $day += 1 ) {
			foreach ( $sessions as $session ) {		
					if ( $day == $session->day ):
					?>
						<tr class="program-session row" href=<?php echo "\"",bloginfo('wpurl'),"/wp-admin/admin-ajax.php\""; ?> >
							<td><input type="hidden" name="sessionID" value="<?php echo $session->sessionID; ?>" /><?php echo $session->day; ?></td>
							<td><?php echo (empty($session->sport))?'-':$session->sport; ?></td>
							<td><?php echo (empty($session->setting))?'-':$session->setting; ?></td>
							<td><?php echo (0==$session->sum_duration_secs)?'-':$session->sum_duration ?></td>
							<td><?php echo (0==$session->sum_distance)?'-':$session->sum_distance ?></td>
							<td><?php echo (0==$session->avg_difficulty)?'-':$session->avg_difficulty; ?></td>
						</tr>	
				<?php
				endif;	
			}
		}
		?>
			</table>
			<div class="gotime">
				<button type="button" class="select" id="issue_select">Issue this Program</button>
				<select name="action">
					<option value="user">to users</option>
					<option value="group">to group</option>
				</select>

			</div>
		
		<?php
		?></div><?php //program
	// global $wp_roles;
	global $wp_user;

	// get user by user ID
	if ( isset($_POST['users']) && !empty($_POST['users']) ):
		foreach ( $_POST['users'] as $key => $id ) {	
			switch( $_POST['action'] ):
			case 'user'	: 	

				break;
				
			case 'group'	:	
				break;	
			endswitch;
		}
	endif;
	?>
	<div class="userselect">
		<h3>Issue Program to users</h3>
		<form action="" method="get" class="issue">
			<input type="hidden" name="programID" value="<?php echo $programID; ?>" />
			<div>
				<label for="start_date">Program Launch Date</label><br />
				<input type="text" name="start_date" value="<?php echo date( 'D d-M-Y' , current_time_fixed( 'timestamp' , 0 ) ); ?>" class="datepicker" style="width:10em;" />
			</div>
				<select size="14" name="users[]" style="height:14em;" MULTIPLE>
				<?php
				$blogusers = get_users_of_blog();
				foreach ($blogusers as $bloguser) {
					$user = new WP_User( $bloguser->ID );
					?>
					<option name="users[]" value="<?php echo $bloguser->ID; ?>">
						<?php echo $bloguser->user_login; ?>
					</option>
				<?php } ?>
				</select>
			<div>
				<button type="submit" class="issue" id="issue" >Issue Program</button>
			</div>
		</form>
	</div>
	<?php
}
/*
 * 
 *
 * inserts a multi-userselect form
 * 
 *
 */
function get_userselect() {
	// global $wp_roles;
	global $wp_user;

	// get user by user ID
	if ( isset($_POST['users']) && !empty($_POST['users']) ):
		foreach ( $_POST['users'] as $key => $id ) {	
			switch( $_POST['action'] ):
			case 'enrol'	: 	
			$user = new WP_User( $id );
			$user->add_role( 'boomb_member' );
				break;
				
			case 'group'	:	
				break;
				
			case 'dismiss'	:	
			$user = new WP_User( $id );
			$user->remove_role( 'boomb_coach' );
			$user->remove_role( 'boomb_member' );			
				break;
				
			case 'coach'	:
			$user = new WP_User( $id );
			$user->add_role( 'boomb_coach' );
				break;
				
		endswitch;
		}
	endif;
	?>
	<script>
	jQuery(document).ready( function($) {
		//check - all
		$('input.all').toggle(function () {
			$(this).parent().find(':checkbox').attr('checked', this.checked);
		} , function() {
			$(this).parent().find(':checkbox').attr('checked', '');			
		});
	});
	</script>
	<div>
		<div>
			<input type="checkbox" value="all" class="all">check all </input>
		</div>
		<form action="" method="post" size="10">
		<input type="hidden" name="page" value="author" />
			<select size="5" name="users[]" style="height:10em;" MULTIPLE>
			<?php
			$blogusers = get_users_of_blog();
			foreach ($blogusers as $bloguser) {
				$user = new WP_User( $bloguser->ID );
				?>
				<option name="users[]" value="<?php echo $bloguser->ID; ?>">
				<?php echo $bloguser->user_login; ?>
					<?php //echo get_user_meta($bloguser->ID, 'first_name' , true); ?>
					<?php //echo get_user_meta($bloguser->ID, 'last_name' , true); ?>
				</option>
			<?php } ?>
			</select>
		<div>
			<select name="action">
				<option >actions...</option>
				<option value="enrol">enrol in boom</option>
				<option value="group">assign to group</option>
				<option value="dismiss">remove from boomb</option>
				<option value="coach">make coach</option>
			</select>
			<button type="submit">go!</button>
		</div>
	</form></div>
	<?php	
}


function insert_stretches_form() {
	?>
	<form>
		<table class="stretches">
			<tr>
				<td class="stretches map">
					<div class="stretches map" style="text-align:center; width:223px;">
						<img id="leonardo" src="http://localhost/images/leonardo man.jpeg" usemap="#leonardo" border="0" width="223" height="226" alt="" />
						<map id="leonardo_map" name="leonardo"><span>Click leo to add stretches</span>
						<area shape="poly" coords="127,128,125,165,114,165,111,138" alt="hamstring" title="hamstrings" class="hamstrings" />
						<area shape="poly" coords="111,137,111,169,102,170,96,136" alt="hamstring" title="hamstrings" class="hamstrings" />
						<area shape="poly" coords="106,134,92,125,83,151,92,159" alt="quad" title="quads"  class="quads" />
						<area shape="poly" coords="142,152,134,160,114,135,128,126" alt="quad" title="quads" class="quads" />
						<area shape="poly" coords="80,166,88,163,88,180,69,202,65,196" alt="calves" title="calves" />
						<area shape="poly" coords="156,196,143,178,138,164,143,158,154,172,162,195" alt="calves" title="calves" />
						<area shape="poly" coords="132,69,136,63,149,57,153,64,137,73" alt="biceps" title="biceps" />
						<area shape="poly" coords="85,65,68,60,69,65,78,72" alt="biceps" title="biceps" />
						</map>
					</div>
				</td><div>
				<td class="stretches inputs">
					<ul class="stretches inputs">
						<!--dynamically generated stretch list item here -->
					</ul>
				</div></td>
			</tr>
			<tr>
				<td colspan="2">
					<button type="submit" value="submit" name="stretches">Submit Stretches</button>
					<span style="white-space:nowrap;">	
						<a href="">stretch instructional link</a>
					</span>
				</td>
			</tr>
		</table>
	</form>
	<?php
}

function report_totals($from,$to){
	global $wpdb;
	$range=time_diff($from,$to);// default reports display range 2 weeks one month avg	
	$current_user = wp_get_current_user();
//error_log(date('Y-m-d h:m:s',strtotime($from)));
/* get query formatted sets for each sport*/
	$sets = $wpdb->get_results(
	"SELECT sport , CAST(AVG(difficulty) AS UNSIGNED) AS difficulty , 
	SEC_TO_TIME ( SUM(TIME_TO_SEC(duration) ) ) AS duration, CAST(SUM(distance) AS decimal(6,2)) AS distance , $wpdb->bb_sets.setID
	FROM wp_bb_sets JOIN wp_bb_efforts 
		ON wp_bb_sets.setID=wp_bb_efforts.setID 
	WHERE category='session' AND userID=".$current_user->ID.
	" AND start_date >= '".date( 'Y-m-d',strtotime($from) )."' AND start_date <= '".date('Y-m-d',strtotime($to)).
	"' GROUP BY sport",'OBJECT_K');
	
	$program_sets = $wpdb->get_results(
	"SELECT sport , CAST(AVG(difficulty) AS UNSIGNED) AS difficulty , 
	SEC_TO_TIME ( SUM(TIME_TO_SEC(duration) ) ) AS duration, CAST(SUM(distance) AS decimal(6,2)) AS distance , $wpdb->bb_sets.setID , SUM(TIME_TO_SEC(duration) ) AS seconds
	FROM wp_bb_sets JOIN wp_bb_efforts 
		ON wp_bb_sets.setID=wp_bb_efforts.setID 
	WHERE category='session' AND userID=".$current_user->ID." AND parent IS NOT NULL AND parent != 0".
	" AND start_date >= '".date( 'Y-m-d',strtotime($from) )."' AND start_date <= '".date('Y-m-d',strtotime($to)).
	"' GROUP BY sport",'OBJECT_K');
	
	
$programs = $wpdb->get_results(
"SELECT sport , CAST(AVG(difficulty) AS UNSIGNED) AS difficulty , 
SEC_TO_TIME ( SUM(TIME_TO_SEC(duration) ) ) AS duration, CAST(SUM(distance) AS decimal(6,2)) AS distance , $wpdb->bb_sets.setID , SUM(TIME_TO_SEC(duration) ) AS seconds
FROM wp_bb_sets JOIN wp_bb_efforts 
	ON wp_bb_sets.setID=wp_bb_efforts.setID 
WHERE category='program' AND userID=".$current_user->ID.
" AND start_date >= '".date( 'Y-m-d',strtotime($from) )."' AND start_date <= '".date('Y-m-d',strtotime($to)).
"' GROUP BY sport",'OBJECT_K');

	$program = $wpdb->get_results(
		"SELECT session.setID as setID , CAST( session.duration / program.duration AS DECIMAL(6,2))*100 AS pc_duration , CAST(session.distance / program.distance AS DECIMAL(6,2))*100 AS pc_distance
		FROM (
			SELECT $wpdb->bb_sets.setID, CAST(SUM(distance) AS DECIMAL(6,2)) AS distance , SUM(TIME_TO_SEC(duration) ) AS duration
			FROM $wpdb->bb_sets
			JOIN $wpdb->bb_efforts
				ON $wpdb->bb_sets.setID = $wpdb->bb_efforts.setID
			WHERE category = 'program' GROUP BY setID
			) AS program
		JOIN (
			SELECT $wpdb->bb_sets.setID, CAST(SUM(distance) AS DECIMAL(6,2)) AS distance , SUM(TIME_TO_SEC(duration) ) AS duration, parent
			FROM $wpdb->bb_sets
			JOIN $wpdb->bb_efforts
				ON $wpdb->bb_sets.setID = $wpdb->bb_efforts.setID
			WHERE category = 'session' GROUP BY setID
		) AS session
			ON program.setID = session.parent" , 'OBJECT_K');

	echo  '<table class="bb-reports" id="bb_reports_totals">',
 		'<tr class="bb-reports header-row"><th colspan="9" class="bb-reports-table-header"><h3>Summary - ',$current_user->user_firstname,' ',$current_user->user_lastname,'</h3></th></tr>',
'<tr class="bb-reports sub-header-row"><th /><th>your totals</th<th>program totals</th></tr>';

	foreach( $sets as $set ) {
		if ( $program_sets[$set->sport] ) {
		$progress_distance = '<div class="pc_distance" title="'.round( $program_sets[$set->sport]->distance / $programs[$set->sport]->distance *100 ).'%"></div>' ;
		} else {
			$progress_distance = '';
		}
//			error_log(print_r($set->sport.'  '.$program_sets[$set->sport]->distance.'<-set  program->'. $programs[$set->sport]->distance ,true));
//			error_log($program_sets[$set->sport]->distance / $programs[$set->sport]->distance *100);
	//	error_log($program_sets[$set->sport]->seconds.'   '.$programs[$set->sport]->seconds);

			$progress_duration = ( $program[$set->setID] )?
			 	'<div class="pc_duration" title="'.round( $program_sets[$set->sport]->seconds / $programs[$set->sport]->seconds *100).'%"></div>' : '' ;
			
	 echo	'<tr><th class="bb-reports sport">',$set->sport,'</th></tr>',
	
			'<tr><th class="bb-reports table-totals">Total Distance</th>',
			'<td>',$set->distance,' km</td><td>',$programs[$set->sport]->distance,' km</td></tr>',
			'<tr><td /><td colspan="2">',$progress_distance,'</td></tr>',
			
			'<tr><th class="bb-reports table-totals">Total Duration</th>',
			'<td>',$set->duration,' hrs','</td><td>'.$programs[$set->sport]->duration,' hrs</td>',
			'<tr><td /><td colspan="2">',$progress_duration,'</td></tr>',
			'<tr><td colspan="3"><hr /></td></th>';
//			error_log($set->parent);
	}
 	echo '</table>';


}

function report_sport( $from , $to ) {
		global $wpdb;
		$range=time_diff($from,$to);// default reports display range 2 weeks one month avg		
		$current_user = wp_get_current_user();
	/* get query formatted sets for each sport*/
		$sets = $wpdb->get_results(
		"SELECT * , DATE_FORMAT(start_date, '%D %b \, %W %p') AS thedate
		FROM wp_bb_sets JOIN wp_bb_efforts 
			ON wp_bb_sets.setID=wp_bb_efforts.setID 
		WHERE category='session' AND userID=".$current_user->ID.
		" AND start_date >= '".date( 'Y-m-d',strtotime($from) )."' AND start_date <= '".date('Y-m-d',strtotime($to)).
		"' ORDER BY start_date" , 'OBJECT' );

$current_user = wp_get_current_user();
$cycle = array();
$swim = array();
$run = array();
	foreach( $sets as $set ) {
		switch( $set->sport ) {
			case 'cycle' :
				array_push($cycle, $set);
				break;
			case 'swim' :
				array_push($swim, $set);
				break;
			case 'run' :
				array_push($run, $set);
				break;
		}
	}
$sports = compact('cycle','swim','run');
	foreach ( $sports as $sport => $sets ){
		
		echo '<div id="tabs-',$sport,'">';
		echo $current_user->user_firstname.' '.$current_user->user_lastname. '<br />',
	 	'<table class="bb-reports sports" id="bb_report_sports">',
	 		'<tr><th>Date</th> <th>Location</th> <th>Distance</th> <th>duration</th> <th>difficulty</th> <th>water</th> <th>food</th><th><a class="toggle-text">show all</a></th></tr>	<tr><td colspan="9"><hr /></td></tr>';
			foreach( $sets as $set ) {
				?>
					<tr class="bb-report set-row">
						<td class="bb-report date">		<?php echo $set->thedate ?></td>
						<td class="bb-report setting">		<?php echo $set->setting; ?></td>
						<td class="bb-report distance">		<?php echo $set->distance ?> km</td>
						<td class="bb-report duration">		<?php echo $set->duration ?> hrs</td>
						<td class="bb-report difficulty">	<?php echo $set->difficulty ?></td>
						<td class="bb-report water">		<?php echo $set->water ?> L</td>
						<td class="bb-report food">		<?php echo $set->foods ?></td>
					<td class="bb-report details" ><a class="toggle-text">show details</a>	<p class="details"><?php echo $set->details ?></p></td></tr>
				<?php
			}
		echo '</table></div>';
	}
}

function get_users () {
	get_userselect();
}




function forororo(){
	?>
	<div>
		<input type="checkbox" value="all" class="all">check all </input>
		<form action="" method="post" size="10">
		<input type="hidden" name="page" value="author" />
		<?php 
		$blogusers = get_users_of_blog();		
		foreach ($blogusers as $bloguser) {
			$user = new WP_User( $bloguser->ID );
		?>
				<input type="checkbox" name="users[]" value="<?php echo $bloguser->ID; ?>" <?php checked( $user->has_cap( 'boomb_edit_session' ) , true ); ?> />
				<?php echo $bloguser->user_login;?>
				<?php //echo get_user_meta($bloguser->ID, 'first_name' , true);?>
				<?php //echo get_user_meta($bloguser->ID, 'last_name' , true);?>
			<br />
			<?php } ?>

		<div>
			<select name="action">
				<option >actions...</option>
				<option value="enrol">enrol in boom</option>
				<option value="group">assign to group</option>
				<option value="dismiss">remove from boomb</option>
				<option value="coach">make coach</option>
			</select>
			<button type="submit">go!</button>
		</div>
	</form></div>
<?php 
}

function get_bb_sidebar() {
	$today=current_time_fixed( 'timestamp',0);//normal
	if (isset($_GET['setID']) && !empty($_GET['setID']))
		$program = get_user_set($_GET['setID']);
		
	$from = date( 'D d-M-Y' , strtotime(date( ' \0\1-M-Y', $today )));
	$to = date( 'D d-M-Y' , strtotime(date( ' t-M-Y', $today )));
include(BB_PLUGIN_DIR.'/bb-sidebar.php');
}
?>