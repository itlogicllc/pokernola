<?php require_once('Connections/poker_db.php'); ?>
<?php require('includes/set_page.php'); ?>
<?php require('includes/set_access.php'); ?>
<?php get_access(1); ?>
<?php require('includes/get_games.php'); ?>
<?php
if ((isset($_POST['game_id'])) && ($_POST['game_id'] != "")) {
   $deleteSQL = sprintf("DELETE FROM games WHERE game_id=%s", GetSQLValueString($_POST['game_id'], "int"));

   mysql_select_db($database_poker_db, $poker_db);
   $Result1 = mysql_query($deleteSQL, $poker_db) or die(mysql_error());

   $deleteGoTo = "games.php";
   if (isset($_SERVER['QUERY_STRING'])) {
      $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
      $deleteGoTo .= $_SERVER['QUERY_STRING'];
   }
   header(sprintf("Location: %s", $deleteGoTo));
}

$games_list = games_list();
?>
<!DOCTYPE html>
<html>
   <head>
      <?php require('includes/set_head.php'); ?>
   </head>
   <body>
      <div data-role="page" id="delete_game">
         <div data-role="header" data-position="fixed">
            <h1>Delete Game</h1>
            <?php require('includes/set_games.php'); ?>
         </div>
         <div role="main" class="ui-content">
            <form id="delete_game" name="delete_game" method="POST" action="">
               <label for="game_id">Select Game to Delete:</label>
               <select name="game_id" id="game_id" data-native-menu="true">
                  <?php for ($i = 0; $i <= count($games_list) - 1; $i++) { ?>
                     <option value="<?php echo $games_list[$i]['game_id'] ?>"><?php echo date_to_php($games_list[$i]['game_name']); ?></option>
                  <?php } ?>
               </select>
               <br />
               <input type="submit" value="Delete Game" data-inline="true" />		
            </form>
         </div>
         <div data-role="footer" data-position="fixed">
            <?php require('includes/set_footer.php'); ?>
         </div>
      </div>
   </body>
</html>
