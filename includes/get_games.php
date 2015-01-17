<?php

mysql_select_db($database_poker_db, $poker_db);
$query_games = "SELECT *
		FROM games 
		WHERE game_date BETWEEN '" . $_SESSION['from_date'] . "' AND '" . $_SESSION['to_date'] . "'
		ORDER BY game_name DESC";
$games = mysql_query($query_games, $poker_db) or die(mysql_error());
$totalRows_games = mysql_num_rows($games);
?>
<?php

$games_array = array();

while ($row_games = mysql_fetch_assoc($games)) {
   $games_array[] = $row_games;
}
?>
<?php

function games_game($game_id = 0) {
   global $games_array;

   if ($game_id == 0)
      return $games_array[0];

   for ($i = 0; $i <= count($games_array); $i++) {
      if ($games_array[$i]['game_id'] == $game_id) {
         return $games_array[$i];
      }
   }
}

function games_list() {
   global $games_array;

   return $games_array;
}

function games_count() {
   global $games_array;

   return count($games_array);
}

//  RETURNS THE NUMBER OF GAMES PLAYED. IT ONLY INCLUDES COMPLETED GAMES, NOT FUTURE SCHEDULED GAMES
function games_played_count() {
   global $games_array;
   $count = 0;

   for ($i = 0; $i <= count($games_array) - 1; $i++) {
      if ($games_array[$i]['status'] == 0) {
         $count++;
      }
   }
   return $count;
}

//  RETURNS A LIST OF GAMES PLAYED. IT ONLY INCLUDES COMPLETED GAMES, NOT FUTURE SCHEDULED GAMES
function games_played_list() {
   global $games_array;
   $games_played = array();

   for ($i = 0; $i <= count($games_array) - 1; $i++) {
      if ($games_array[$i]['status'] == 0) {
         $games_played[$i] = $games_array[$i];
      }
   }
   return $games_played;
}

function games_pot_sum() {
   global $games_array;
   $pot_sum = 0;

   for ($i = 0; $i <= count($games_array) - 1; $i++) {
      $pot_sum = $games_array[$i]['total_pot'] + $pot_sum;
   }
   //return money_format('%n', $pot_sum);
   return "$" . number_format($pot_sum, 2);
}

function games_largest_pot() {
   global $games_array;
   $pot_amount = 0;

   for ($i = 0; $i <= count($games_array) - 1; $i++) {
      if ($games_array[$i]['total_pot'] > $pot_amount) {
         $pot_amount = $games_array[$i]['total_pot'];
      }
   }
   //return money_format('%n', $pot_amount);
   return "$" . number_format($pot_amount, 2);
}

function games_most_players() {
   global $games_array;
   $most_players = 0;

   for ($i = 0; $i <= count($games_array) - 1; $i++) {
      if ($games_array[$i]['num_players'] > $most_players) {
         $most_players = $games_array[$i]['num_players'];
      }
   }
   return $most_players;
}

function games_new_name() {
   global $games_array;
   $game_date = date('Y-m-d', time());
   $count = 0;

   for ($i = 0; $i <= count($games_array); $i++) {
      if ($games_array[$i]['game_date'] == $game_date) {
         $count++;
      }
   }

   if ($count > 0)
      $game_name = date('m-d-Y', time()) . " (" . ($count + 1) . ")";
   else
      $game_name = date('m-d-Y', time());

   return $game_name;
}

?>
<?php

mysql_free_result($games);
?>