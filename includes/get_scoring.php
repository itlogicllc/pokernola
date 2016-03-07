<div class="ui-corner-all custom-corners">
	<div class="ui-bar ui-bar-a ui-corner-all normal">
		<h2>
			<?php 
				$end_date = $settings_array[$i]['end_date'];
				$ended_early_date = $settings_array[$i]['ended_early'];
				$todays_date = date("Y-m-d");
				
				echo $settings_array[$i]['season_name'];
				
				if ($end_date < $todays_date) {
					echo "<br><span class='alert'>This season has ended</span>";
				} else if ($ended_early_date != '0000-00-00') {
					echo "<br><span class='alert'>This season ended on " . date_to_php($ended_early_date) . "</span>";
				}
			?>
		</h2>
	</div>
	<br />
	<div class="ui-bar ui-bar-a">
		<h2>SEASON DURATION</h2>
	</div>
	<div class="ui-body ui-body-a">
		<p> This season is from <span class="scoring"><?php echo date_to_php($settings_array[$i]['start_date']); ?></span> to <span class="scoring"><?php echo date_to_php($settings_array[$i]['end_date']); ?></span></p>
	</div>
	<div class="ui-bar ui-bar-a">
		<h2>MAXIMUM PLAYERS</h2>
	</div>
	<div class="ui-body ui-body-a">
		<p> The maximum amount of players allowed in a tournament is <span class="scoring"><?php echo $settings_array[$i]['max_players']; ?></span> players.</p>
	</div>
	<div class="ui-bar ui-bar-a">
		<h2>POINTS</h2>
	</div>
	<div class="ui-body ui-body-a">
		<p>Points are awarded to, up to, the 10 final players of each game.</p>
		<p>The points for each place are:</p>
		<ol>
			<li class="scoring"><?php echo $settings_array[$i]['pt1']; ?></li>
			<li class="scoring"><?php echo $settings_array[$i]['pt2']; ?></li>
			<li class="scoring"><?php echo $settings_array[$i]['pt3']; ?></li>
			<li class="scoring"><?php echo $settings_array[$i]['pt4']; ?></li>
			<li class="scoring"><?php echo $settings_array[$i]['pt5']; ?></li>
			<li class="scoring"><?php echo $settings_array[$i]['pt6']; ?></li>
			<li class="scoring"><?php echo $settings_array[$i]['pt7']; ?></li>
			<li class="scoring"><?php echo $settings_array[$i]['pt8']; ?></li>
			<li class="scoring"><?php echo $settings_array[$i]['pt9']; ?></li>
			<li class="scoring"><?php echo $settings_array[$i]['pt10']; ?></li>
		</ol>
		<p>In addition, the points will be multiplied <span class="scoring"><?php echo $settings_array[$i]['multiplier']; ?></span> times for every <span class="scoring"><?php echo $settings_array[$i]['threshold']; ?></span> players in the game. The points will be increased <span class="scoring"><?php echo ($settings_array[$i]['max_increase'] > 0) ? $settings_array[$i]['max_increase'] : 'unlimited'; ?></span> times.</p>
	</div>
	<div class="ui-bar ui-bar-a">
		<h2>PAYOUTS</h2>
	</div>
	<div class="ui-body ui-body-a">
		<p>The top 3 players are considered to be "In the Money" (ITM). Any game pot will be divided between those 3 players.</p>
		<p>The percentage of payouts are:</p>
		<ol>
			<li class="scoring"><?php echo $settings_array[$i]['first_pay'] * 100 . "%"; ?></li>
			<li class="scoring"><?php echo $settings_array[$i]['second_pay'] * 100 . "%"; ?></li>
			<li class="scoring"><?php echo $settings_array[$i]['third_pay'] * 100 . "%"; ?></li>
		</ol>
	</div>
	<div class="ui-bar ui-bar-a">
		<h2>SPLITS</h2>
	</div>
	<div class="ui-body ui-body-a">
		<p>When all players agree, the game will end in a split between each of those players. The sum of unclaimed payouts will be divided and distributed <span class="scoring"><?php echo ($settings_array[$i]['split_type'] == 'even' ? 'evenly' : 'by percentage of total chips held'); ?></span> between each player in the split.</p>
		<p>The sum of unclaimed points <span class="scoring"><?php echo ($settings_array[$i]['split_points'] == '0' ? 'will not' : 'will also'); ?></span> be divided and distributed amongst each splitting player.</p>
	</div>
	<div class="ui-bar ui-bar-a">
		<h2>PLAYER PRIORITY</h2>
	</div>
	<div class="ui-body ui-body-a">
		<p>Player priority is turned <span class="scoring"><?php echo ($settings_array[$i]['credits_per_degree'] == 0 ? 'OFF' : 'ON'); ?>.</span></p>
		<?php if($settings_array[$i]['credits_per_degree'] > 0) { ?>
		<p>There are <span class="scoring"><?php echo $settings_array[$i]['credits_per_degree']; ?></span> credits per degree and 10 degrees per level.</p>
		<?php } ?>
	</div>
</div>
