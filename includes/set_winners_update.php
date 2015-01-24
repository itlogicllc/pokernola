<?php 
if (isset($_SESSION['player_access']) && $_SESSION['player_access'] == 'admin') {
   echo '<a href="game_details.php?game_id='.(isset($_GET['game_id']) ? $_GET['game_id'] : '').'" data-role="button" data-icon="grid" data-iconpos="notext">Edit Game</a>';
   echo '<a href="game_delete.php" data-role="button" data-icon="minus" data-iconpos="notext">Delete Game</a>';
}
?>
<nav data-role="navbar">
	<ul>
		<li><a href="home.php" data-transition="flip" data-icon="home">Home</a></li>
		<li><a href="players.php" data-transition="flip" data-icon="user">Players</a></li>
		<li><a href="games.php" class="ui-btn-active ui-state-persist" data-transition="flip" data-icon="grid">Games</a></li>
		<li><a href="tools.php" data-transition="flip" data-icon="gear">Tools</a></li>
	</ul>
</nav>
