<?PHP
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


<h1 id="title" align="center">Boom Books</h1>
		<?PHP
echo 'Date: <input name="date" type="text" id="datepicker" size="30" value="'.date( 'l d-M-Y', current_time_fixed( 'timestamp',0));
?>">		<div id="timenow" class="timenowclass"></div>


<!--                                      -->
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
	</div>
	<h3><a href="#">Section 2 - Meals</a></h3>
	<div>
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
				<div class="bb-textarea-wrap">What did you eat?
			<textarea class="bb_textarea" name="consumed" id="" class="bb-consumed" rows="3" cols="10" tabindex="5" ></textarea>
				</div>
	</div>
	<h3><a href="#">Section 3 - Set</a></h3>
	<div>
		
		<input name="newset" type="button" value ="Add New Set" id="bb-new-set"></input><span class="ui-icon ui-icon-plusthick" id="bb-new-set"></span>
		<h3>New Exercise log</h3>
		<!--SEXY BIG RADIO BUTTONS FOR TYPE
		Dont forget program matching
		 -->
		Location    <select name="category" tabindex='' value"road">
				<option value="road">road</option>
				<option value="gym">gym</option>
				<option value="pool">pool</option>
				<option value="open_water">open water</option>
				<option value="wind_trainer">wind trainer</option>
			</select>
		<div class="bb-textarea-wrap">Describe the session
		<textarea class="bb_textarea" name="details" id="" class="bb-decribe" rows="3" cols="10" tabindex="5" ></textarea>
			</div>
			<div class="bb-table"><fieldset><table>
<tr>
<td><strong id="bb-duration">Time</strong>
	<input type="text" name="duration" id="bb_duration" tabindex="1" autocomplete"on" value="minutes" size="4"></td>
<td><strong id="bb-distance">Distance</strong>
	<input type="text" name="distance" id="bb_distance" tabindex="2" autocomplete"on" value="Kms"  size="4"></td>
<td><strong id="bb-water">difficulty</strong>
	<input type="text" name="difficulty" id="bb_water" tabindex="1" autocomplete"on" value="difficulty" size="4"></td>
</tr>
		</table></fieldset></div>
		<strong id="bb-food">foods during effort</strong>
		<textarea class="bb_textarea" name="food" id="" tabindex="" rows="3" cols="10" autocomplete"on"></textarea>

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