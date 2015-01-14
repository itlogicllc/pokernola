<?php

mysql_select_db($database_poker_db, $poker_db);
$query_winners = "SELECT *, CONCAT(players.first_name,' ',players.last_name) full_name
		  FROM winners, games, players
		  WHERE winners.game_id = games.game_id AND winners.player_id = players.player_id AND games.game_date BETWEEN '" . $_SESSION['from_date'] . "' AND '" . $_SESSION['to_date'] . "'
		  ORDER BY games.game_name DESC,winners.place";
$winners = mysql_query($query_winners, $poker_db) or die(mysql_error());
$totalRows_winners = mysql_num_rows($winners);

$winners_array = array();

while ($row_winners = mysql_fetch_assoc($winners)) {
   $winners_array[] = $row_winners;
}

function winner_range_count($player, $from, $to) {
   global $winners_array;
   $win_count = 0;

   for ($i = 0; $i <= count($winners_array); $i++) {
      if ($winners_array[$i]['player_id'] == $player && $winners_array[$i]['place'] >= $from && $winners_array[$i]['place'] <= $to) {
         $win_count++;
      }
   }
   return $win_count;
}

function winner_game_payout($player, $game) {
   global $winners_array;
   $payout = 0;

   for ($i = 0; $i <= count($winners_array); $i++) {
      if ($winners_array[$i]['player_id'] == $player && $winners_array[$i]['game_id'] == $game) {
         $payout = ($winners_array[$i]['total_pot'] * $winners_array[$i]['split_diff']);
         break;
      }
   }
   //return money_format('%n', $payout);
   "$" . number_format($payout, 2);
}

function winner_game_points($player, $game) {
   global $winners_array;
   $points = 0;

   for ($i = 0; $i <= count($winners_array); $i++) {
      if ($winners_array[$i]['player_id'] == $player && $winners_array[$i]['game_id'] == $game) {
         $points = $winners_array[$i]['points'];
         break;
      }
   }
   return $points;
}

function winner_total_payout($player) {
   global $winners_array;
   $payout = 0;

   for ($i = 0; $i <= count($winners_array); $i++) {
      if ($winners_array[$i]['player_id'] == $player) {
         $payout = ($winners_array[$i]['total_pot'] * $winners_array[$i]['split_diff']) + $payout;
      }
   }
   //return money_format('%n', $payout);
   "$" . number_format($payout, 2);
}

function winner_total_points($player) {
   global $winners_array;
   $points = 0;

   for ($i = 0; $i <= count($winners_array); $i++) {
      if ($winners_array[$i]['player_id'] == $player) {
         $points = $winners_array[$i]['points'] + $points;
      }
   }
   return number_format($points, 2);
}

function winner_by_place($player, $place) {
   global $winners_array;
   $winner_place_array = array();
   $pointer = 0;

   for ($i = 0; $i <= count($winners_array) - 1; $i++) {
      if ($winners_array[$i]['player_id'] == $player && $winners_array[$i]['place'] == $place) {
         $winner_place_array[$pointer] = $winners_array[$i];
         $pointer++;
      }
   }
   return $winner_place_array;
}

function winners_by_game($game) {
   global $winners_array;
   $winners_game_array = array();
   $pointer = 0;

   for ($i = 0; $i <= count($winners_array); $i++) {
      if ($winners_array[$i]['game_id'] == $game && $winners_array[$i]['place'] != 0) {
         $winners_game_array[$pointer] = $winners_array[$i];
         $pointer++;
      }
   }
   return $winners_game_array;
}

function winners_split_players_by_game($game) {
   global $winners_array;
   $split_players = array();
   $pointer = 0;

   for ($i = 0; $i <= count($winners_array); $i++) {
      if ($winners_array[$i]['game_id'] == $game && $winners_array[$i]['split'] == 1) {
         $split_players[$pointer] = $winners_array[$i];
         $pointer++;
      }
   }
   return $split_players;
}

function winners_split_even_payout_by_game($game) {
   global $winners_array;
   $split_payout = 0;
   $split_amount = count(winners_split_players_by_game($game));

   for ($i = 0; $i <= count($winners_array); $i++) {
      if ($winners_array[$i]['game_id'] == $game && $winners_array[$i]['split'] == 1) {
         $split_payout = $split_payout + $winners_array[$i]['split_diff'];
      }
   }
   return $split_payout / $split_amount;
}

function winners_split_even_points_by_game($game) {
   global $winners_array;
   $split_points = 0;
   $split_amount = count(winners_split_players_by_game($game));

   for ($i = 0; $i <= count($winners_array); $i++) {
      if ($winners_array[$i]['game_id'] == $game && $winners_array[$i]['split'] == 1) {
         $split_points = $split_points + $winners_array[$i]['points'];
      }
   }
   return $split_points / $split_amount;
}

function winners_ko_by_game($game) {
   global $winners_array;
   $winners_game_array = array();
   $pointer = 0;

   for ($i = 0; $i <= count($winners_array); $i++) {
      if ($winners_array[$i]['game_id'] == $game && $winners_array[$i]['place'] == 0) {
         $winners_game_array[$pointer] = $winners_array[$i];
         $pointer++;
      }
   }
   return $winners_game_array;
}

mysql_free_result($winners);
?>