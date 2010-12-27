<?php insert_bb_header(); ?>
<h1>Boom Books - Dailys (BETA)</h1>
<br />

<?php// $date = insert_daterange();	?>
<?php
	$today = current_time_fixed( 'timestamp' , 0 );//normal
	echo	'Date: <input name="date" type="text" class="bb-datepicker" size="20" value="',date( 'D d-M-Y', $today ),'">';
?>
<table class="container">
	<tr>
		<td>
<div id="accordion">
	<h3><a href="#">Daily Details</a></h3>
	<div>	
		<form>
			<table class="bb-dailys dailys">
				<tr>
					<td>
						<label for="sleep" class="bb-dailys sleep">Sleep</label>
						<input type="text" size="8" name="sleep" autocomplete"on" value="">
					</td>
				</tr><tr>
					<td>
						<label for="RHR" class="bb-dailys RHR">RHR</label>
						<input type="text" size="8" name="RHR" autocomplete"on" value="">
					</td>
				</tr><tr>
					<td>
						<label for="water" class="bb-dailys water">Water</label>
						<input type="text" size="8" name="water" autocomplete"on" value="">
					</td>
				</tr>
			</table>
			<button type="submit" value="submit" name="journal">Submit Dailys</button>
		</form>
		<!--Add java here to  add new text field for selected time of day allowing multiple entries-->
	</div>
	<h3><a href="#">Journal</a></h3>
	<div>
		<form>
			<label for="meal">Meal</label>
			<select name="meal">
				<option value="" selected="selected">Select Meal...</option>
				<option value="snack">Snack</option>
				<option value="breakfast">Breakfast</option>
				<option value="lunch">Lunch</option>
				<option value="dinner">Dinner</option>
				<option value="supplement">Supplement</option>
				<option value="pre">Pre-workout</option>
				<option value="post">Post-workout</option>
			</select>
			<div>
				<label for="consumed">What did you eat?</label>
				<p>
					<textarea class="bb-dailys consumed" name="consumed" class="bb-dailys consumed" ></textarea>
				</p>
			</div>
				<button type="submit" value="submit" name="journal">Submit journal</button>
		</form>
	</div>

	<h3><a href="#">Stretches</a></h3>
		<div>
			<form>
				<table class="bb-dailys stretches">
					<tr>
						<td class="input">

							<ul class="stretches list">
								<li class="stretch-link"><a href="">stretch instructional link</a></li>
							</ul>
						</td><td class="map">
							<div class="stretches map" style="text-align:center; width:223px; margin-left:auto; margin-right:auto;">
								<img id="leonardo" src="http://localhost/images/leonardo man.jpeg" usemap="#leonardo" border="0" width="223" height="226" alt="" />
								<map id="leonardo_map" name="leonardo"><span>Click leo to add stretches</span>
								<area shape="poly" coords="127,128,125,165,114,165,111,138" alt="hamstring1" title="hamstrings" class="hamstring" />
								<area shape="poly" coords="111,137,111,169,102,170,96,136" alt="hamstring2" title="hamstrings" class="hamstring" />
								<area shape="poly" coords="106,134,92,125,83,151,92,159" alt="quad1" title="quads"  class="quad" />
								<area shape="poly" coords="142,152,134,160,114,135,128,126" alt="quad2" title="quads" class="quad" />
								<area shape="poly" coords="80,166,88,163,88,180,69,202,65,196" alt="calves" title="calves" />
								<area shape="poly" coords="156,196,143,178,138,164,143,158,154,172,162,195" alt="calves" title="calves" />
								<area shape="poly" coords="132,69,136,63,149,57,153,64,137,73" alt="biceps" title="biceps" />
								<area shape="poly" coords="85,65,68,60,69,65,78,72" alt="biceps" title="biceps" />
								</map>
								<button type="submit" value="submit" name="stretches">Submit Stretches</button>
							</div>
						</td>
					</tr>
				</table>
			</form>
		</div>	
		<!--
	<h3><a href="#">Section 4 - Stretching</a></h3>
	<div>
		-->
</div><!--accordion-->
</td>
<td></td>
</tr>
</table>

<p>
<!--
DAILY DETAILS
		.Sexy gui (what a rack!)
		user-options
			-add common items (add an item:item is displayed next to normal text area as icon and is click-able.  When icon is clicked item is automatically added into this time of day.  eg: user selects breakfast, cereal with yoghurt.  Icon is created.  Every breakfast, click this and it's done shar fucken blam)
PLUS
	links to dietary advice and businesses.
	-Feature recipe? tonyest kitchen? bang on.
	-recipe tag cloud cabonara ->(tony's, mary's, boothy's, gordon ramsay's)
	-dinner builder [select chicken, africa]-(adventurous?okra, spicy?rub, fragrant?rub, classic?biryani)
	-vitamin/mineral interactive/flashy counter.
			
PLUS
	links to stretching instructionals.


</p>

-->