<?php 
	require('../db_connections/pokernola.php');
	require('includes/set_page.php');
	require('includes/get_players.php');
	require('includes/get_winners.php');
	require('includes/get_games.php');
	require('includes/get_rankings.php');
	require('includes/get_game_players.php');
	require('includes/get_invitation.php');
	require('includes/get_payouts.php');
?>

<!DOCTYPE html>
<html>
<head>
<?php require('includes/set_head.php'); ?>
</head>
<body>
<div data-role="page" id="test">
<?php 
	// Make sure the query string has a game_id, if not redirect to access denied.
	if (!empty($_GET['game_id'])) {
		$game_id = trim($_GET['game_id']);
	} else {
		header("Location: access_denied.php?message=unauthorized");
		exit();
	}
	
	//$game_pagation = games_played_all();
	var_dump(rankings_range());
	
?>
</div>
</body>
</html>
