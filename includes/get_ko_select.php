<?php 
for ($i2 = 0; $i2 <= count($players_list) - 1; $i2++) {
	if (!(strcmp($players_list[$i2]['player_id'], $ko_list[$i]['ko_id']))) { ?>
		<option value="<?php echo $players_list[$i2]['player_id'] ?>" selected="selected"><?php echo $players_list[$i2]['full_name'] ?></option>
<?php } } ?>