<!--returns results of queries-->
<!--userselect:reporting dates [from,to] . default this month?-->
	<!--page1:all sets done in period-->
		<!--page[n]:sets of type 'n' in period
	
	average duration:	SEC_TO_TIME ( SUM( TIME_TO_SEC(duration) ) / COUNT(duration) ) ) AS duration
		-->	
<div class="main" style="display:block;width:63%;float:left;border: 1px solid #999;padding:0.5em;margin:0.5em;">

	<div class="postbox-container" style="width:98%;clear:both;">
		<div class="postbox" style="padding:1em;">
			<h3>Boom Books Reports (Beta)</h3>
			<?php $date = insert_daterange();?>
		</div>
	</div>
	<div class="postbox-container" style="width:98%;clear:both;">
		<div class="postbox" style="padding:1em;">
		<h3>Boom Books Reports (Beta)</h3>
			<div id="tabs">
				<ul>
					<li><a href="#tabs-totals">Totals</a></li>
					<li><a href="#tabs-cycling">Cycling</a></li>
					<li><a href="#tabs-swimming">Swimming</a></li>
					<li><a href="#tabs-running">Running</a></li>
					<li><a href="tabs-all-sets">All Sets</a></li>
				</ul>
				<div id="tabs-totals">
					<?php	report_totals($date['from'],$date['to']);	?>
				</div>	
				<?php	report_discipline($date['from'],$date['to']);	?>
				<?php	report_all_sets($date['from'],$date['to']);	?>
			</div><!-- tabs end -->
		</div>
	</div>
</div>
<div class="sidebar" style="display:block; width:32%;float:left;border: 1px solid #999;padding:0.5em;margin0.5em;">
<?php get_bb_sidebar(); ?>
	<!-- 	-->
</div>
<?php


?>