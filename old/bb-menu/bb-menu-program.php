
<h1>Boom Books - Dailys (BETA)</h1>

<div class="bb-main" style="display:block;width:63%;float:left;clear:none;border: 1px solid #999;padding:0.5em;margin:0.5em;">

	<div class="postbox-container" style="width:98%;clear:both;">
		<div class="postbox" style="padding:1em;">
			<h3>Boom Books Program (Beta)</h3>
			<?php $date = insert_daterange();?>
		</div>
	</div>

	<div class="postbox-container" style="width:98%;clear:both;">
		<div class="postbox" style="padding:1em;">		
			<?php get_user_program($date['from'],$date['to']); ?>
		</div>
	</div>
	<div class="postbox-container" style="width:98%;clear:both;">
		<div class="postbox" style="padding:1em;">
			<h3>Boom Books submission (Beta)</h3>
			<div class="bb-submit" id="bb_update"><?php 
			if( itr($_GET['setID'],'isset') )
				echo '<div class="updated" align="center"><p><strong>Complete training details then submit</strong></p></div>';
			?></div>
			<div class="hidden inputs">
				<input type="hidden" name="set[category]" value="session" class="bb-submit new-effort" id="category">
				<input type="hidden" name="set[parent]" value="<?php echo $_GET['setID']; ?>" class="bb-submit new-effort" id="parent">
			</div>
			<select  name="set[sport]"  class="bb-submit new-effort" id="sport" >
				<option value="">Select sport...</option>
				<option value="cycle">cycle</option>
				<option value="swim">swim</option>
				<option value="run">run</option>
			<!--	<option value="resistance">Resistance</option>	-->
			</select>
			<button class="bb-submit" id="add_effort"> Add Effort </button>
			<div id="tabs"> <!-- tabs selection -->
				<ul>
				<li><span class="ui-icon ui-icon-note"></span><a href="#tabs-1">Current Set</a></li>
				<?php program_tab_header($program) ?>
			</ul>
				<div id="tabs-1">
					<?PHP //echo 'Date: <input name="set[date]" type="text" class="bb-submit bb-datepicker" id="date" size="30" value="'.date('l d-M-Y',$today),'">';?>
				<!--	<span class="bb-submit" id="time">
						<input type="radio" name="set[time]" class="bb-submit" value="0" CHECKED >AM</input>
						<input type="radio" name="set[time]" class="bb-submit" value="12" >PM</input>
					</span> 
				-->
					<table class="bb-submit" id="bb_submit_table">
						<tr class="bb-submit set-row header">
						 	<th> </th> 
							<th>Location</th>  
							<th>Distance<br /><!-- <font size="1">km</font> </th> -->
							<th>Duration<br /><!-- <font size="1"> h:m:s </font> </th>-->
							<th>Difficulty<br /><!-- <font size="1"> 1-10 </th> -->
							<th>water</th>
						</tr>
						<?php program_set_rows($program) ?>
						<!--Dynamically generated rows inserted here-->
						<tr><td colspan="6"><hr style="margin:0 2em 0 5em;"></td></tr>
						<tr class="bb-author total-row">
							<td class="bb-author total">		Summary		</td>
							<td class="bb-author setting">					</td>
							<td class="bb-author distance">		kilometers	</td>
							<td class="bb-author duration"><span id="h">h</span>:<span id="m">m</span>:<span id="s">s</span></td>
							<td class="bb-author difficulty">	average		</td>
							<td class="bb-author water">		liters		</td>
						</tr>
					</table>
				</div><!--tabs-1-->
				<?php program_tabs ($program) ?>
			</div>
			<?php echo '<a href="',bloginfo('wpurl'),'/wp-admin/admin-ajax.php" id="wpurl_ajax"></a>'; ?>
			<button id="submit_set" type="submit">Submit</button>
			<div class ="details">
				<?php
					if ( isset($_GET['setID']) && !empty($_GET['setID']) ){
						echo '<h3>Program Details</h3>';
						$effort_no = 1;
						foreach( $program as $effort ) {
							echo '<h4>',$effort_no,' - ', $effort->sport , '</h4>';
							if( isset($effort->details) &&  empty($effort->details) )
								echo 'no details set';
							$details = $effort->details;
							echo '<p>', $details , '</p>';
							$effort_no++;
						}
					}
				?>
			</div>
		</div><!-- End submit -->
	</div>
</div>

<div class="sidebar" style="display:block;clear:none;width:28%;float:left;border: 1px solid #999;padding:0.5em;margin0.5em;">
<?php get_bb_sidebar(); ?>
	<!-- 	-->
</div>






