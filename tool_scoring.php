<?php
	require('../db_connections/pokernola.php');
	require('includes/set_page.php');
	
	$page_access_type = 'member';
	set_page_access($page_access_type);
	
	$settings_array = settings_all();
	$todays_date = date("Y-m-d");
?>
<!DOCTYPE html>
<html>
	<head>
		<?php require('includes/set_head.php'); ?>
		<title>PokerNOLA Scoring</title>
	</head>
	<body>
		<div data-role="page" id="tools">
			<div data-role="header" data-position="fixed">
				<h1>Season Rules</h1>
				<?php require('includes/set_tools.php'); ?>
			</div>
			<div role="main" class="ui-content">
				<div data-role="collapsible-set">
					<?php
						for ($i = 0; $i <= count($settings_array) - 1; $i++) {
						
						$end_date = $settings_array[$i]['end_date'];
						$ended_early_date = $settings_array[$i]['ended_early'];
					?>
					<div data-role="collapsible" data-iconpos="right">
						<h3>
							<?php
								if ($end_date < $todays_date || $ended_early_date != '0000-00-00') {
									echo "<img src='images/icons/forbidden-white.png' alt='Ended' /> ";
								} else {
									echo "<img src='images/icons/check-white.png' alt='Current' /> ";
								}
								echo $settings_array[$i]['season_name'];
							?>
						</h3>
						<?php require('includes/get_scoring.php'); ?>
					</div>
					<?php } ?>
				</div>
			</div>
			<div data-role="footer" data-position="fixed">
				<?php require('includes/set_footer.php'); ?>
			</div>
		</div>
	</body>
</html>
