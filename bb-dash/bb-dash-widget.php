<div class="bb-contents">
		<form name="bb" action="#" id="bb-dash">
			<div class="demo">
<?PHP
echo '<p>Date: <input type="text" id="datepicker" size="30" value="'.date( 'D d-M-Y', current_time_fixed( 'timestamp',0));?>">
<span id="timenow" class="timenowclass"></span></p>


	</div><!-- End demo -->
	<div class="bb-dailys">
		<fieldset><table>
			<tr><td>
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
		<div class="bb-textarea-wrap">
			<textarea name="consumed" id="content" class="bb-consumed" rows="3" cols="10" tabindex="5" ></textarea>
		</div>
		</div><!--bb-dailys-->

	<!--
	LOG
		string		time of day array [dusk,morning,midday,afternoon,dusk,night]
		string		Exercise environment [road,gym,pool,open water,wind trainer]
		string		Describe it
		string		activity [cycling,running,swimming,resistance training]
		datetime 	time
		int			distance
		string		Feeling Before, During and After
		int			Water consumed
		string		Foods consumed

		-->
	
	<div class="bb-log">
		<p><h3>Exercise log</h3></p>
		 <h4>Environment</h4>
		<select name="environment" tabindex='' value"Road">
		  <option value="road">road</option>
		  <option value="gym">gym</option>
		  <option value="pool">pool</option>
		  <option value="open_water">open water</option>
		<option value="wind_trainer">wind trainer</option>
		</select>
		
		<div class="bb-textarea-wrap">
			<textarea name="describe" id="content" class="bb-decribe" rows="3" cols="10" tabindex="5" ></textarea>
		</div>
	</div>
	<fieldset><table>
		<tr>
<td><strong id="bb-time">Time</strong><input type="text" name="bb_time" id="time" tabindex="1" autocomplete"on" value"" size="5"></td>
	<td><strong id="bb-dist">Distance</strong><input type="text" name="bb_dist" id="dist" tabindex="2" autocomplete"on" value""  size="5"></td>
	<td><strong id="bb-h20">Water during effort</strong><input type="text" name="bb_h20" id="h20" tabindex="1" autocomplete"on" value"" size="5"></td>
	</tr>
	</fieldset></table>
<strong id="bb-food">foods during effort</strong><input type="text" name="bb_food" id="content" tabindex="2" autocomplete"on" value"">	

</div>
			<p class="log">
				<input type="hidden" name="action" id="bb-action" value="log-bb-save">
				<input type="hidden" name="log_ID" value="3">
				<input type="hidden" name="log_type" value="log">
				<input type="hidden" id="_wpnonce" name="_wpnonce" value="63714955a2"><input type="hidden" name="_wp_http_referer" value="/wp_BT/wp-admin/index-extra.php?jax=dashboard_quick_press">
				<input type="reset" value="Reset" class="button">
				<span id="log-action">
					<input type="submit" name="log" id="log" accesskey="p" tabindex="5" class="button-primary" value="log">
		<!--		<img class="waiting" src="http://localhost/wp_BT/wp-admin/images/wpspin_light.gif">-->
				</span>
				<br class="clear">
			</p>

		</form>
		</div>
		
		<br/><br/>
		<div class="demo">
<span id='checker'></span>
		<form id="reservation">
			<label for="minbeds">Minimum number of beds</label>
			<select class="minibeds_slider" id="minbeds">
				<option>1</option>
				<option>2</option>
				<option>3</option>
				<option>4</option>
				<option>5</option>
				<option>6</option>
			</select>
			<div id='slider'></div>
		</form>

		</div><!-- End demo -->
		<br/><br/>
				<br/><br/>
						<br/><br/>
								<br/><br/>
	

