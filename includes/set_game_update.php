<?php
	if (isset($_SESSION['player_access']) && $_SESSION['player_access'] == 'admin') {
		if ($game['registration'] == 1 && $game['status'] == 1 && $game_seconds_to_start > 0) {
			echo '<a href="game_details.php?game_id=' . $game_id . '" class="ui-btn ui-shadow ui-corner-all ui-icon-grid ui-btn-icon-notext ui-btn-b ui-btn-inline">Edit Game</a>';
		} else {
			echo '<a href="game_details.php?game_id=' . $game_id . '" class="ui-btn ui-shadow ui-corner-all ui-icon-grid ui-btn-icon-notext ui-btn-a ui-btn-inline">Edit Game</a>';
		}
		echo '<a href="game_delete.php?game_id=' . $game_id . '" class="ui-btn ui-shadow ui-corner-all ui-icon-minus ui-btn-icon-notext ui-btn-b ui-btn-inline">Delete Game</a>';
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
