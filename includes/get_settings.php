<?php

	// Select all setting records from the database in decending order
	// based on the date they were created and save them to an array.
	$season_settings_query = "SELECT *
									  FROM settings 
									  ORDER BY settings_date DESC";

	$records = mysqli_query($db_connect, $season_settings_query);
	
	while ($record = mysqli_fetch_array($records)) {
		$season_settings_array[] = $record;
	}
	
	mysqli_free_result($records);
	
	// Refresh the recordset by rerunning the query and resetting the sason_settings_array
	// to the new recordset. Use after database transactions.
	function settings_refresh() {
		global $db_connect;
		global $season_settings_query;

		$records = mysqli_query($db_connect, $season_settings_query);

		while ($record = mysqli_fetch_array($records)) {
			$season_settings_array[] = $record;
		}

		$GLOBALS['season_settings_array'] = $season_settings_array;
		
		mysqli_free_result($records);
	}
	
	// Returns the latest created settings which are the current settings
	// for all games created.
	function settings_current() {
		global $season_settings_array;

		return $season_settings_array[0];
	}
	
	// Returns the settings record associted with given settings ID.
	function settings_by_id($id) {
		global $season_settings_array;

		for ($i = 0; $i <= count($season_settings_array) - 1; $i++) {
			if ($id == $season_settings_array[$i]['settings_id']) {
				return $season_settings_array[$i];
			}
		}
	}

	// Formerly setting_list.
	// Return array of all settings.
	function settings_all() {
		global $season_settings_array;

		return $season_settings_array;
	}
	