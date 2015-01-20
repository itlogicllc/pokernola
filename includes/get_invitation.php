<?php

mysql_select_db($database_poker_db, $poker_db);
$query_invitations =  "SELECT *
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
?>
<?php

mysql_free_result($invitations);
?>