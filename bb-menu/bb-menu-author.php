<?php
	$today=current_time_fixed( 'timestamp',0);//normal
?>

<h1>Boom Books Author (Beta)</h1>

<div class="bb-author" id="bb_update"></div>

	<select  name="set[category]" tabindex='' class="bb-author new-effort" id="category" >
		<option value="myset">Select program or myset...</option>
		<option value="program">Program</option>
		<option value="myset">Myset</option>
	</select>

	<br />
	<br />
	<br />

	<div class="bb-author"><!--bb-program daterange" id="program_daterange">-->
		<button class="bb-author" id="add_effort"> Add Effort </button>
		<select  name="set[discipline]" tabindex='' class="bb-author new-effort" id="discipline" >
			<option value="">Select Discipline...</option>
			<option value="cycling">Cycling</option>
			<option value="swimming">Swimming</option>
			<option value="running">Running</option>
		<!--	<option value="resistance">Resistance</option>	-->
		</select>
		<div id="tabs">
			<ul>
				<li><span class="ui-icon ui-icon-note"></span><a href="#tabs-1">Current Set</a></li>
			</ul>
			<div id="tabs-1">
				<?PHP echo 'Date: <input name="set[date]" type="text" class="bb-author bb-datepicker" id="date" size="30" value="'.date('l d-M-Y',$today);?>">
				<span class="bb-author" id="time">
				<input type="radio" name="set[time]" class="bb-author" value="0" CHECKED >AM</input>
				<input type="radio" name="set[time]" class="bb-author" value="12" >PM</input>
				</span>
				<table class="bb-author" id="bb_author_table">
					<tr class="bb-author set-row header">
					 	<th> </th> 
						<th>Location</th>  
						<th>Distance<br /><!-- <font size="1">km</font> </th> -->
						<th>Duration<br /><!-- <font size="1"> h:m:s </font> </th>-->
						<th>Difficulty<br /><!-- <font size="1"> 1-10 </th> -->
						<th>water</th>
					</tr>
					<!--Dynamically generated rows inserted here-->
					<tr><td colspan="6"><hr></td></tr>
					<tr class="bb-author total-row">
						<td class="bb-author total">	Totals	</td>
						<td class="bb-author setting">			</td>
						<td class="bb-author distance">		kilometers	</td>
						<td class="bb-author duration">		minutes	</td>
						<td class="bb-author difficulty">	average	</td>
						<td class="bb-author water">		liters	</td>
					</tr>
				</table>
			</div>
		</div>
	</div><!-- End author -->
<?php 
echo '<a href="',bloginfo('wpurl'),'/wp-admin/admin-ajax.php" id="wpurl_ajax"></a>';
?>
<p>
<button id="author_submit_set" type="submit">Submit</button>
</p>