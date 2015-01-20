<?php
function get_access($admin = 0) {
	if (!isset($_SESSION['player_logged_in'])) {
		$insertGoTo = "access_denied.php?message=You must be logged in to access this page! Please log in and try again.";
  		if (isset($_SERVER['QUERY_STRING'])) {
    		$insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    		$insertGoTo .= $_SERVER['QUERY_STRING'];
  		}
  	header(sprintf("Location: %s", $insertGoTo));
	}
	elseif ($_SESSION['player_access'] != 'admin' && $admin == 1) {
		$insertGoTo = "access_denied.php?message=This is an ADMIN ONLY page! You are not authorized to proceed.";
  		if (isset($_SERVER['QUERY_STRING'])) {
    		$insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    		$insertGoTo .= $_SERVER['QUERY_STRING'];
  		}
  	header(sprintf("Location: %s", $insertGoTo));
	}	
}
?>
