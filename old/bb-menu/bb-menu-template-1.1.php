<!--new set form-->
<!--auto define on arrival: category=myset,status=completed,user=currentuser. userselect:date-->
<!--add efforts, fill details for each effort and submit-->


<?PHP
$program_set = $_GET['setID'];

//error_log( 'variable contains = ' . print_r( get_user_program_set($program_set), true ) );
$warmup_effort = '';
/* currently returning per effort
	[setID] => 3
	            [userID] => 1
	            [start] => 2010-11-21 14:23:45
	            [category] => program
	            [status] => incomplete
	            [effortID] => 9
	            [sport] => run
	            [setting] => track
	            [difficulty] => 9
	            [duration] => 00:00:00
	            [distance] => 10.00
	            [details] => olympic distance tri simulation run leg
				[parent]=>   {[UNDEFINED:program.setID?]}
			
			
NEW:
	[setID] => ?AUTO
	            [userID] => 1 check to current user
	            [start] => today()
	            [category] => freeride
	            [status] => complete
	            [effortID] => ?AUTO
	            [sport] => run
	            [setting] => track
	            [difficulty] => 9
	            [duration] => 00:00:00
	            [distance] => 10.00
	        old.[details] => olympic distance tri simulation run leg
			new.[details] => ?
				[parent] => 3
			
		foreach ( $program_set as $effort ){
			insert_effort($effort);
		}
			insert_effort($warmup_effort);		
		
	function insert_effort($effort){
	$e_id=$effort=>effortID;
	
	echo HTML For JAVA TABS $effort=>sport
	
		[setID] => ?AUTO
		            [userID] => 1 check to current user
		            [start] => today()
		            [category] => freeride	{hidden}
		            [status] => complete	{hidden}
		            [effortID] => ?AUTO
		            [sport] => run
		            [setting] => track
		            [difficulty] => 9
		            [duration] => 00:00:00
		            [distance] => 10.00
		        old.[details] => olympic distance tri simulation run leg
				new.[details] => ?
					[parent] => 3	{hidden}


<fieldset><table class="bb-effort" id="bb-effort-meta">			
	<tr><td><strong>Location</strong><br/>   
		<select name="category" tabindex='' value=$effort=>setting >
				<option value="road">road</option>
				<option value="gym">gym</option>
				<option value="pool">pool</option>
				<option value="open_water">open water</option>
				<option value="wind_trainer">wind trainer</option>
		</select>
	</td><td>
		<strong>Time</strong><input type="text" name="duration" id="bb_duration"$eid autocomplete"on" value=$effort=>duration size="4">
	</td></tr>
	<tr><td rowspan="2">
		<strong>Describe the session</strong><textarea class="bb-form-text" name="details" id="description" rows="3" cols="10" tabindex="5" ></textarea>
	</td>
	<td>
		<strong>Distance</strong><input type="text" name="distance" id="bb_distance"$eid autocomplete"on" value=$effort=>distance  size="4">
	</td></tr>
	<tr><td>
		<strong>Difficulty</strong><input type="text" name="difficulty" id="bb_water"$eid autocomplete"on" value=$effort=>duration size="4">
	</td></tr>
</table></fieldset>
<fixed>$effort=>details</fixed>
	}
		
*/
/*
 *	PROCESSOR FOR SUBMIT
 *
 *
 */
if($_POST['log']=='submit'){
	global $wpdb;
//error_log( 'variable contains = ' . print_r( $_POST, true ) );
//deconstruct variables
	$date = strtotime($_POST['date']);
	$date = date_i18n('Y-m-d H:i:s',$date,false);
	$category = $_POST['category'];
		$sport = 'cycle';
		$setting = $_POST['category'];	
		$difficulty = $_POST['difficulty'];
		$duration = sec_to_time($_POST['duration']*60);
		$distance = $_POST['distance'];
		$details = $_POST['details'];
//bb_insert_set($date,$category);
$setID = $wpdb->insert_id;
//bb_insert_effort($setID,$sport,$setting,$difficulty,$duration,$distance,$details);
// $wpdb->print_error();

}
?>

<div class="bb-admin-menu"><form name="bb" action=<?PHP echo '"http://localhost/wp_BT/wp-admin/admin.php?page=boom_books"';?> id="bb-admin-form" method="post">
<h1 id="title" align="center">Boom Books (Beta)</h1>

<?PHP
echo 'Date: <input name="date" type="text" class="bb-datepicker" id="datepicker" size="30" value="'.date( 'l d-M-Y', current_time_fixed( 'timestamp',0));
?>">
<div id="timenow" class="timenowclass"></div>

<!--               ACCORDION                       -->
<div id="accordion">
	<h3><a href="#">Section 1 - Daily Details</a></h3>
	<div>

				<fieldset><table>
		<tr>
		<td><h4 id="bb-sleep">Sleep</h4><input type="text" name="bb_sleep" id="sleep" tabindex="1" autocomplete"on" value"" size="5"><td>
		<td><h4 id="bb-RHR">RHR</h4><input type="text" name="bb_RHR" id="RHR" tabindex="2" autocomplete"on" value""  size="5"><td>
		<td><h4 id="bb-Water">Water</h4><input type="text" name="bb_h20" id="h20" tabindex="3" autocomplete"on" value""  size="5"><td>
		</tr>
				</fieldset></table>
				<!--Add java here to  add new text field for selected time of day allowing multiple entries-->
				<h4>Time of Day</h4>
				<select name="tod" tabindex='4' value"breakfast">
					<option value="brekky (pre-train)">brekky (pre-train)</option>
					<option value="Brekky (post-train)">Brekky (post-train)</option>
					<option value="Morning Tea">Morning Tea</option>
					<option value="Lunch">Lunch</option>
					<option value="Afternoon tea">Afternoon tea</option>
					<option value="Dinner">Dinner</option>
					<option value="Supper">Supper</option>
				</select>
				<h4>What did you eat?</h4>
					What did you eat?
				<textarea class="bb_textarea" name="consumed" id="" class="bb-consumed" rows="3" cols="10" tabindex="5" ></textarea>

				<strong id="bb-food">foods during effort</strong>
				<textarea class="bb_textarea" name="food" id="" tabindex="" rows="3" cols="10" autocomplete"on"></textarea>

	</div>
	<h3><a href="#">Section 2 - Sets</a></h3>
	<div>


	</div>
	<h3><a href="#">Section 3 </a></h3>
	<div>

	
	</div>
	<h3><a href="#">Section 4 - Stretching</a></h3>
	<div>


	</div>
</div><!--accordion-->
</div><!--bb-contents-->

<input type="reset" value="Reset" class="button">
	<span id="log-action">
		<input type="submit" name="log" id="log" tabindex="5" class="button-primary" value="submit">
		<!--<img class="waiting" src="http://localhost/wp_BT/wp-admin/images/wpspin_light.gif">-->
	</span>
<br class="clear">
</form></div>