<?php

mysql_select_db($database_poker_db, $poker_db);
$query_invitations =   "SELECT *, CONCAT(invitation_first,' ',invitation_last) full_name
                        FROM invitations";

$invitations = mysql_query($query_invitations, $poker_db) or die(mysql_error());
$totalRows_invitations = mysql_num_rows($invitations);
?>
<?php

$invitations_array = array();

while ($row_invitations = mysql_fetch_assoc($invitations)) {
   $invitations_array[] = $row_invitations;
}
?>
<?php

function invitations_invitation($player, $code) {
   global $invitations_array;
   $invitation_array = array();

   for ($i = 0; $i <= count($invitations_array) - 1; $i++) {
      if ($invitations_array[$i]['player_id'] == $player && $invitations_array[$i]['invitation_code'] == $code) {
         $invitation_array = $invitations_array[$i];
         return $invitation_array;
      }
   }
}

function invitations_invited($player) {
   global $invitations_array;
   $invitation_array = array();

   for ($i = 0; $i <= count($invitations_array) - 1; $i++) {
      if ($invitations_array[$i]['player_id'] == $player) {
         $invitation_array[] = $invitations_array[$i];
      }
   }
    return $invitation_array;
}

function invitations_email_duplicate($email) {
   global $invitations_array;

   for ($i = 0; $i <= count($invitations_array) - 1; $i++) {
      if ($invitations_array[$i]['invitation_email'] == $email) {
         return 1;
      }
   }
   return 0;
}

function invitations_by_email($email) {
   global $invitations_array;
   $invitation_array = array();

   for ($i = 0; $i <= count($invitations_array) - 1; $i++) {
      if ($invitations_array[$i]['invitation_email'] == $email) {
         $invitation_array = $invitations_array[$i];
         return $invitation_array;
      }
   }
   return $invitation_array;
}

function invitations_by_id($invitation_id) {
   global $invitations_array;
   $invitation_array = array();

   for ($i = 0; $i <= count($invitations_array) - 1; $i++) {
      if ($invitations_array[$i]['invitation_id'] == $invitation_id) {
         $invitation_array = $invitations_array[$i];
         return $invitation_array;
      }
   }
   return $invitation_array;
}

function invitations_last() {
   global $database_poker_db;
   global $poker_db;
   
   $invitations_array = array();
   
   mysql_select_db($database_poker_db, $poker_db);
   $query_invitations =   "SELECT *
                           FROM invitations
                           ORDER BY invitation_id DESC
                           LIMIT 1";

   $invitations = mysql_query($query_invitations, $poker_db) or die(mysql_error());

   $invitations_array[] = mysql_fetch_assoc($invitations);
   
   return $invitations_array;
}

?>
<?php

mysql_free_result($invitations);
?>