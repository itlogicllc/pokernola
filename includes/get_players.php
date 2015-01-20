<?php

mysql_select_db($database_poker_db, $poker_db);
$query_players =  "SELECT *, CONCAT(first_name,' ',last_name) full_name
                  FROM players
                  WHERE player_id > 0
                  ORDER BY full_name";
$players = mysql_query($query_players, $poker_db) or die(mysql_error());
$totalRows_players = mysql_num_rows($players);
?>
<?php

$players_array = array();

while ($row_players = mysql_fetch_assoc($players)) {
   $players_array[] = $row_players;
}
?>
<?php

function players_player($player) {
   global $players_array;
   $player_array = array();

   for ($i = 0; $i <= count($players_array) - 1; $i++) {
      if ($players_array[$i]['player_id'] == $player) {
         $player_array = $players_array[$i];
         return $player_array;
      }
   }
}

function players_by_email($email) {
   global $players_array;
   $player_array = array();

   for ($i = 0; $i <= count($players_array) - 1; $i++) {
      if ($players_array[$i]['email'] == $email) {
         $player_array = $players_array[$i];
         return $player_array;
      }
   }
   return $player_array;
}

function players_login($email, $password) {
   global $players_array;
   $player_array = array();

   for ($i = 0; $i <= count($players_array) - 1; $i++) {
      if ($players_array[$i]['email'] == $email && $players_array[$i]['password'] == $password) {
         $player_array = $players_array[$i];
         return $player_array;
      }
   }
}

function players_email_duplicate($email) {
   global $players_array;

   for ($i = 0; $i <= count($players_array) - 1; $i++) {
      if ($players_array[$i]['email'] == $email) {
         return 1;
      }
   }
   
   return 0;
}

function players_list() {
   global $players_array;

   return $players_array;
}

function players_count() {
   global $players_array;

   return count($players_array);
}

?>
<?php

mysql_free_result($players);
?>