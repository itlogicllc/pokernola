<?php
	require('../db_connections/pokernola.php');
	require('includes/set_page.php');
	
	$page_access_type = 'player';
	set_page_access($page_access_type);

	// *** Logout the current user.
	if (!isset($_SESSION)) {
		session_start();
	}
	$_SESSION['from_date'] = NULL;
	$_SESSION['to_date'] = NULL;
	$_SESSION['player_email'] = NULL;
	$_SESSION['player_logged_in'] = NULL;
	$_SESSION['player_first'] = NULL;
	$_SESSION['player_access'] = NULL;
	
	unset($_SESSION['from_date']);
	unset($_SESSION['to_date']);
	unset($_SESSION['player_email']);
	unset($_SESSION['player_logged_in']);
	unset($_SESSION['player_first']);
	unset($_SESSION['player_access']);
	
	header("Location: index.php");
	exit();