<?php
	if (isset($_SESSION['player_access']) && $_SESSION['player_access'] == 'admin') {
		echo '<a href="game_add.php?player_id=' . $player_logged_in_id . '" class="ui-btn ui-shadow ui-corner-all ui-icon-plus ui-btn-icon-notext ui-btn-b ui-btn-inline">Add Game</a>';
		echo '<a href="game_delete.php?player_id=' . $player_logged_in_id . '" class="ui-btn ui-shadow ui-corner-all ui-icon-minus ui-btn-icon-notext ui-btn-b ui-btn-inline">Delete Game</a>';
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
