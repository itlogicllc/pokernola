<?php

mysql_select_db($database_poker_db, $poker_db);
$query_game_players =   "SELECT *, CONCAT(players.first_name,' ',players.last_name) full_name
                         FROM game_players, players, games
                         WHERE game_players.player_id = players.player_id AND game_players.game_id = games.game_id AND games.game_date BETWEEN '" . $_SESSION['from_date'] . "' AND '" . $_SESSION['to_date'] . "'
                         ORDER BY games.game_date, full_name";
$game_players = mysql_query($query_game_players, $poker_db) or die(mysql_error());
$totalRows_game_players = mysql_num_rows($game_players);
?>
<?php

$game_players_array = array();

while ($row_game_players = mysql_fetch_assoc($game_players)) {
   $game_players_array[] = $row_game_players;
}
?>
<?php

//  RETURNS AN ARRAY OF PLAYERS WHO HAVE PLAYED IN A GIVEN GAME
function game_players_by_game($game) {
   global $game_players_array;
   $players_by_game_array = array();

   for ($i = 0; $i <= count($game_players_array); $i++) {
      if ($game_players_array[$i]['game_id'] == $game) {
         $players_by_game_array[] = $game_players_array[$i];
      }
   }
   return $players_by_game_array;
}

//  RETURNS  1 IF A GIVEN PLAYER IS REGISTERED FOR A GIVEN GAME, 0 IF NOT
function game_players_registered($game, $player) {
   global $game_players_array;

   for ($i = 0; $i <= count($game_players_array); $i++) {
      if (($game_players_array[$i]['player_id'] == $player) && ($game_players_array[$i]['game_id'] == $game)) {
         return 1;
      }
   }
   return 0;
}

//  RETURNS THE NUMBER OF TIMES A GIVEN PLAYER HAS PLAYED. IT ONLY INCLUDES COMPLETED GAMES, NOT FUTURE SCHEDULED GAMES
function game_players_played($player) {
   global $game_players_array;
   $count = 0;

   for ($i = 0; $i <= count($game_players_array); $i++) {
      if ($game_players_array[$i]['player_id'] == $player && $game_players_array[$i]['status'] == 0) {
         $count++;
      }
   }
   return $count;
}
?>

<?php

mysql_free_result($game_players);
?>