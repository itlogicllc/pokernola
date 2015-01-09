<div class="ui-corner-all custom-corners">
    <div class="ui-bar ui-bar-a ui-corner-all" align="center"><h2><?php echo $settings_array[$i]['season_name']; ?></h2></div><br />
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
        <p> Points are awarded to, up to, the 10 final players of each game.<br />
            <br />
            The points for each place are:
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
        </p>
        <p>In addition, the points will be multiplied <span class="scoring"><?php echo $settings_array[$i]['multiplier']; ?></span> times for every <span class="scoring"><?php echo $settings_array[$i]['threshold']; ?></span> players in the game.</p>
    </div>
    <div class="ui-bar ui-bar-a">
        <h2>PAYOUTS</h2>
    </div>
    <div class="ui-body ui-body-a">
        <p> The top 3 players are considered to be "In the Money" (ITM). Any game pot will be divided between those 3 players.<br />
            <br />
            The percentage of payouts are:
        <ol>
            <li class="scoring"><?php echo $settings_array[$i]['first_pay'] * 100 . "%"; ?></li>
            <li class="scoring"><?php echo $settings_array[$i]['second_pay'] * 100 . "%"; ?></li>
            <li class="scoring"><?php echo $settings_array[$i]['third_pay'] * 100 . "%"; ?></li>
        </ol>
        </p>
    </div>
    <div class="ui-bar ui-bar-a">
        <h2>SPLITS</h2>
    </div>
    <div class="ui-body ui-body-a">
        <p>When all players agree, the game will end in a split between each of those players. The total of any unclaimed points and payouts will be divided and distributed <span class="scoring"><?php echo ($settings_array[$i]['split_type'] == 'even' ? 'evenly' : 'by percentage of total chips held'); ?></span> between each player in the split.</p>
    </div>
</div>
