
<!--links from program query-->
<!--complete effort details & submit chosen set ->default date=date chosen-->
<!--effort values[distance,duration,difficult] are changeable-->
<!--cannot add new efforts-->
<!--default +1 effort for warm-up&down?-->
<!-- PROGRAM type sets have a 'status'['planned','partial','completed']-->

<?php
	$today=current_time_fixed( 'timestamp',0);//normal
?>

<h1>Boom Books Program Author (Beta)</h1>

<div class="bb-submit" id="bb_update"><?php 
if( itr($_GET['setID'],'isset') ){
echo '<div class="updated" align="center"><p><strong>Complete training details then submit</strong></p></div>';
}
?></div>
	<input type="hidden" name="set[category]" value="program" class="bb-submit new-effort" id="category">
	<br />
	<br />
	<br />

	<div class="bb-submit"><!--bb-program daterange" id="program_daterange">-->
		<button class="bb-submit" id="add_effort"> Add Effort </button>
		<select  name="set[sport]" tabindex='' class="bb-submit new-effort" id="sport" >
			<option value="">Select sport...</option>
			<option value="cycle">cycle</option>
			<option value="swim">swim</option>
			<option value="run">run</option>
		<!--	<option value="resistance">Resistance</option>	-->
		</select>
		<div id="tabs">
			<ul>
				<li><span class="ui-icon ui-icon-note"></span><a href="#tabs-1">Current Set</a></li>
			</ul>
			<div id="tabs-1">
				<?PHP echo 'Date: <input name="set[date]" type="text" class="bb-submit bb-datepicker" id="date" size="30" value="'.date('l d-M-Y',$today);?>">
				<span class="bb-submit" id="time">
				<input type="radio" name="set[time]" class="bb-submit" value="0" CHECKED >AM</input>
				<input type="radio" name="set[time]" class="bb-submit" value="12" >PM</input>
				</span>
				<table class="bb-submit" id="bb_submit_table">
					<tr class="bb-submit set-row header">
					 	<th> </th> 
						<th>Location</th>  
						<th>Distance<br /><!-- <font size="1">km</font> </th> -->
						<th>Duration<br /><!-- <font size="1"> h:m:s </font> </th>-->
						<th>Difficulty<br /><!-- <font size="1"> 1-10 </th> -->
						<th>water</th>
					</tr>
 					<tr><td colspan="6"><hr></td></tr>
					<tr class="bb-author total-row">
						<td class="bb-author total">		Summary		</td>
						<td class="bb-author setting">					</td>
						<td class="bb-author distance">		kilometers	</td>
						<td class="bb-author duration"><span id="h">h</span>:<span id="m">m</span>:<span id="s">s</span></td>
						<td class="bb-author difficulty">	average		</td>
						<td class="bb-author water">		liters		</td>
					</tr>
				</table>
			</div>
		</div>
	</div><!-- End submit -->
<?php 
echo '<a href="',bloginfo('wpurl'),'/wp-admin/admin-ajax.php" id="wpurl_ajax"></a>';
?>
<p>
<button id="submit_set" type="submit">Submit</button>
</p>
