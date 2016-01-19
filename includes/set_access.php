<?php

	function get_access($admin = 0) {
		if (!isset($_SESSION['player_logged_in'])) {
			header("Location: index.php?message=must_login");
			exit();
		} elseif ($_SESSION['player_access'] != 'admin' && $admin == 1) {
			header("Location: access_denied.php?message=admin_only");
			exit();
		}
	}
	