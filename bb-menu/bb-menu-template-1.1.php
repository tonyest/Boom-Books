<!--new set form-->
<!--auto define on arrival: category=myset,status=completed,user=currentuser. userselect:date-->
<!--add efforts, fill details for each effort and submit-->


<?PHP
$program_set = $_GET['setID'];

error_log( 'variable contains = ' . print_r( get_program_set($program_set), true ) );
$warmup_effort = '';
/* currently returning per effort
	[setID] => 3
	            [userID] => 1
	            [start] => 2010-11-21 14:23:45
	            [category] => program
	            [status] => incomplete
	            [effortID] => 9
	            [discipline] => running
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
	            [discipline] => running
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
	
	echo HTML For JAVA TABS $effort=>discipline
	
		[setID] => ?AUTO
		            [userID] => 1 check to current user
		            [start] => today()
		            [category] => freeride	{hidden}
		            [status] => complete	{hidden}
		            [effortID] => ?AUTO
		            [discipline] => running
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
		$discipline = 'cycling';
		$setting = $_POST['category'];	
		$difficulty = $_POST['difficulty'];
		$duration = sec_to_time($_POST['duration']*60);
		$distance = $_POST['distance'];
		$details = $_POST['details'];
bb_insert_set($date,$category);
$setID = $wpdb->insert_id;
bb_insert_effort($setID,$discipline,$setting,$difficulty,$duration,$distance,$details);
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
		<select id="">
				<option value="">Select one...</option>
				<option value="Cycling">Cycling</option>
				<option value="Swimming">Swimming</option>
				<option value="Running">Running</option>
				<option value="Resistance">Resistance</option>
			</select>
			<button id="select">Add new effort</button>


		<input type="button" class="bb-effort add-effort" id="new-effort" value="Add new Effort"/>
		
		<span class="ui-icon ui-icon-plusthick" id="bb-new-set"></span>
		<h3>Update Effort</h3>
		<!--SEXY BIG RADIO BUTTONS FOR TYPE
		Dont forget program matching
		 -->
		<fieldset><table class="bb-effort" id="bb-effort-meta">			
<tr><td><strong>Location</strong><br/>   
	<select name="category" tabindex='' value"road">
			<option value="road">road</option>
			<option value="gym">gym</option>
			<option value="pool">pool</option>
			<option value="open_water">open water</option>
			<option value="wind_trainer">wind trainer</option>
	</select>
</td>
<td>
	<strong id="bb-duration">Time</strong><input type="text" name="duration" id="bb_duration" tabindex="" autocomplete"on" value="minutes" size="4">
</td></tr>

<tr>
<td rowspan="2">
		<strong>Describe the session</strong><textarea class="bb-form-text" name="details" id="description" rows="3" cols="10" tabindex="5" ></textarea>
</td><td>
	<strong id="bb-distance">Distance</strong><input type="text" name="distance" id="bb_distance" tabindex="" autocomplete"on" value="Kms"  size="4">
</td></tr>

<tr>
<td>
	<strong id="bb-water">Difficulty</strong><input type="text" name="difficulty" id="bb_water" tabindex="" autocomplete"on" value="difficulty" size="4">
	</td></tr>
</table></fieldset>	

	</div>
	<h3><a href="#">Section 3 </a></h3>
	<div>

		<div class="demo"><!--bb-program daterange" id="program_daterange">-->

			<div id="dialog" title="Tab data">
				<form>
					<fieldset class="ui-helper-reset">
						<table class="bb-author new-effort" id="author_form_table">
							<tr>
								<td class="bb-author new-effort col-1" >
									<select  name="discipline" tabindex='' class="bb-author new-effort" id="discipline" >
										<option value="">Select Discipline...</option>
										<option value="Cycling">Cycling</option>
										<option value="Swimming">Swimming</option>
										<option value="Running">Running</option>
										<option value="Resistance">Resistance</option>
									</select>
									<select name="setting" tabindex='' value="$effort=>setting" class="bb-author new-effort" id="setting" >
										<option value="">Select location...</option>
										<option value="road">road</option>
										<option value="gym">gym</option>
										<option value="pool">pool</option>
										<option value="open_water">open water</option>
										<option value="wind_trainer">wind trainer</option>
									</select>
								</td>
								<td class="bb-author new-effort col-2" >
									<strong>Time</strong>
									<input type="text" name="duration" autocomplete"on" class="bb-author new-effort" value="minutes" id="duration" />
								</td>
							</tr>
							<tr>
								<td rowspan="2" class="bb-author new-effort col-1" >
									<strong>Describe the session</strong><br />
									<textarea name="details" class="bb-author new-effort" id="details" ></textarea>
								</td>
								<td class="bb-author new-effort col-2">
									<strong>Distance</strong>
									<input type="text" name="distance" autocomplete"on" value="kilometers" class="bb-author new-effort" id="distance" />
								</td>
								</tr>
							<tr>
								<td class="bb-author new-effort col-2">
									<strong>Difficulty</strong>
									<input type="text" name="difficulty" autocomplete"on" value="out of 10" class="bb-author new-effort" id="difficulty"/>
								</td>
							</tr>
						</table>
					</fieldset>
				</form>
			</div>

			<button id="add_tab"> Add Effort </button>
			<div id="tabs">
				<ul>
					<li><span class="ui-icon ui-icon-note"></span><a href="#tabs-1">Current Set</a></li>
				</ul>
				<div id="tabs-1">
					<p>
						<?PHP echo 'Date: <input name="to" type="text" class="bb-datepicker" id="to" size="30" value="'.date('l d-M-Y',$today);?>">
						<table class="bb-author" id="bb_author_table">
							<tr class="bb-author header">
							 <th> </th> <th>Difficulty<br /><font size="1"> 0-10 </th> <th>Distance<br /><font size="1">km</font> </th> <th>Duration<br /><font size="1"> h:m:s </font> </th> <th>Location</th>
							</tr>
							<tr class="bb-author set-row">
								<td class="bb-author discipline">	cycling	</td>
								<td class="bb-author difficulty">	3	</td>
								<td class="bb-author distance">		34	</td>
								<td class="bb-author duration">		11:11:11	</td>
								<td class="bb-author setting">		road	</td>
							</tr>
							<tr class="bb-author set-row">
								<td class="bb-author discipline">	swimming	</td>
								<td class="bb-author difficulty">	2	</td>
								<td class="bb-author distance">		1.5	</td>
								<td class="bb-author duration">		11:11:11	</td>
								<td class="bb-author setting">		pool	</td>
							</tr>
							<tr class="bb-author set-row">
								<td class="bb-author discipline">	running	</td>
								<td class="bb-author difficulty">	8	</td>
								<td class="bb-author distance">		2	</td>
								<td class="bb-author duration">		11:11:11	</td>
								<td class="bb-author setting">		track	</td>
							</tr>
						</table>
					</p>
				</div>
			</div>

		</div><!-- End demo -->
	
	</div>
	<h3><a href="#">Section 4 - Stretching</a></h3>
	<div>
	<h3>Stretching</h3>
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