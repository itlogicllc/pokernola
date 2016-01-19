<?php
	$date_pick_on = 1;

	$seasons = settings_all();

	$editFormAction = $_SERVER['PHP_SELF'];
	if (isset($_SERVER['QUERY_STRING'])) {
		$editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
	}
	
	// Create an array of from dates and one of to dates from each season
	for ($i = 0; $i <= count($seasons) - 1; $i++) {
		$from_dates_array[] = $seasons[$i]['start_date'];
		$to_dates_array[] = $seasons[$i]['end_date'];
	}
	
	// If there is no date, set the dates to today
	if (empty($from_dates_array)) {
		$min_from_date = date("Y-m-d");
	} else {
		$min_from_date = min($from_dates_array);
	}
	
	if (empty($from_dates_array)) {
		$max_to_date = date("Y-m-d");
	} else {
		$max_to_date = min($to_dates_array);
	}
	
	if (isset($_POST['date_pick'])) {
		$_SESSION['from_date'] = date_to_mysql($_POST['date_pick_from']);
		$_SESSION['to_date'] = date_to_mysql($_POST['date_pick_to']);

		echo '<script> window.location = "' . $editFormAction . '"; </script>';
		exit();
	}
?>
<script>
	function setDates(season_id) {
		switch (season_id) {
		<?php for ($i = 0; $i <= count($seasons) - 1; $i++) { ?>
			case "<?php echo $seasons[$i]['settings_id'] ?>":
				document.getElementById('date_pick_from').value = "<?php echo date_to_datepicker($seasons[$i]['start_date']); ?>"
				document.getElementById('date_pick_to').value = "<?php echo date_to_datepicker($seasons[$i]['end_date']); ?>"
				break;
		<?php } ?>
						
			default:
				document.getElementById('date_pick_from').value = "<?php echo date_to_datepicker($min_from_date); ?>"
				document.getElementById('date_pick_to').value = "<?php echo date_to_datepicker($max_to_date); ?>"
		}
	}
</script>

<div data-role="panel" id="date_panel" data-display="overlay" ui-panel-inner="true">
	<form name="date_form" id="date_form" action="<?php echo $editFormAction; ?>" method="post">
		<div class="label_div" style="color:#FFF">Date Range by Season:</div>
		<div data-role="controlgroup">
			<select name="date_pick" id="date_pick" onchange="setDates(this.value);">
				<option value="0">All</option> 
				<?php for ($i = 0; $i <= count($seasons) - 1; $i++) { ?>
						<option value="<?php echo $seasons[$i]['settings_id'] ?>" <?php
						if (($seasons[$i]['start_date'] == $_SESSION['from_date']) && ($seasons[$i]['end_date'] == $_SESSION['to_date'])) {
							echo "selected";
						}
						?>><?php echo $seasons[$i]['season_name'] ?></option>
				<?php } ?>
			</select>
		</div>
		<br />
		<div class="label_div" style="color:#FFF">Custom Date Range:</div>
		<div id="date_picker_range" data-role="controlgroup">
			<input id="date_pick_from" name="date_pick_from" type="text" data-role="datebox" data-options='{"mode":"calbox", "useFocus":true, "defaultValue":"<?php echo $_SESSION['from_date']; ?>", "showInitialValue":true}'>
			<input id="date_pick_to" name="date_pick_to" type="text" data-role="datebox" data-options='{"mode":"calbox", "useFocus":true, "defaultValue":"<?php echo $_SESSION['to_date']; ?>",  "showInitialValue":true}'>
		</div>
		<div id="date_pick_buttons" data-role="controlgroup" data-type="horizontal">
			<input name="submit" id="submit" type="submit" value="Update" data-inline="true" />
			<input name="cancel" id="cancel" type="button" value="Cancel" data-rel="close" />
		</div>
	</form>
</div>

