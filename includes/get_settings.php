<?php 
mysql_select_db($database_poker_db, $poker_db);
$query_settings = "SELECT *
		   FROM settings 
		   ORDER BY settings_date DESC";
$settings = mysql_query($query_settings, $poker_db) or die(mysql_error());
$totalRows_settings = mysql_num_rows($settings);
?>
<?php
$settings_array = array();

while($row_settings = mysql_fetch_assoc($settings)) {
  $settings_array[] = $row_settings;
}
?>
<?php
function settings_current($settings_id = 0) {
	global $settings_array;
	
	if ($settings_id == 0) return $settings_array[0];
	
	for ($i = 0; $i <= count($settings_array) - 1; $i++) {
		if ($settings_id == $settings_array[$i]['settings_id']) {
			return $settings_array[$i];
		}
	}
}

function settings_list() {
	global $settings_array;
	
	return $settings_array;
}
 
mysql_free_result($settings);
?>