<?php require_once('Connections/poker_db.php'); ?>
<?php require('includes/set_page.php'); ?>
<?php require('includes/get_players.php'); ?>
<?php require('includes/get_winners.php'); ?>
<?php require('includes/get_games.php'); ?>
<?php require('includes/get_rankings.php'); ?>
<?php require('includes/get_game_players.php'); ?>
<!DOCTYPE html>
<html>
<head>
<?php require('includes/set_head.php'); ?>
</head>
<body>
<div data-role="page" id="test">
<?php 
echo(games_played_count() . "<br />");
?>
</div>
</body>
</html>
