<a href="player_invite.php" data-role="button" data-icon="mail" data-iconpos="notext">Invite Player</a>
<?php if (isset($_SESSION['player_access']) && $_SESSION['player_access'] == 'admin') echo '<a href="player_delete.php" data-role="button" data-icon="minus" data-iconpos="notext">Delete Player</a>'; ?>
<nav data-role="navbar">
    <ul>
        <li><a href="index.php" data-transition="flip" data-icon="home">Home</a></li>
        <li><a href="players.php" class="ui-btn-active ui-state-persist" data-transition="flip" data-icon="user">Players</a></li>
        <li><a href="games.php" data-transition="flip" data-icon="grid">Games</a></li>
        <li><a href="tools.php" data-transition="flip" data-icon="gear">Tools</a></li>
    </ul>
</nav>
