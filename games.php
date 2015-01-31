<?php require_once('Connections/poker_db.php'); ?>
<?php require('includes/set_page.php'); ?>
<?php require('includes/get_games.php'); ?>
<?php require('includes/get_game_players.php'); ?>
<?php
$games_list = games_list();
$current_settings = settings_current();
?>
<!DOCTYPE html>
<html>
   <head>
      <?php require('includes/set_head.php'); ?>
   </head>
   <body>
      <div data-role="page" id="players">
         <?php require('includes/set_panel_date.php'); ?>
         <div data-role="header" data-position="fixed">
            <h1>Games</h1>
            <?php require('includes/set_games.php'); ?>
         </div>
         <div role="main" class="ui-content">
            <ul data-role="listview" data-inset="true" data-split-icon="adminedit">
               <li data-role="list-divider">Game Schedule</li>
               <?php
               for ($i = 0; $i <= count($games_list) - 1; $i++) {
                  if ($games_list[$i]['status'] == 0) {
                     $expired = true;
                  } else {
                     $expired = false;
                  }
                  ?>
                  <li>
                     <a href="<?php echo ($games_list[$i]['registration'] == 1) ? 'game_registration.php' : 'game_details.php'; ?>?game_id=<?php echo $games_list[$i]['game_id']; ?>">
                     <?php if ($games_list[$i]['registration'] == 1) {
                              if (isset($_SESSION['player_logged_in'])) {
                                 if (game_players_registered($games_list[$i]['game_id'], $_SESSION['player_logged_in']) == 1) {
												if ($expired) { ?>
													<img class="ui-li-icon" alt="open" src="images/played.png" >
												<?php } else { ?>
													<img class="ui-li-icon" alt="registered" src="images/registered.png">
												<?php } ?>
											<?php } else { ?>
                                 <img class="ui-li-icon" alt="regonly" src="images/regonly.png">
											<?php } ?>
										<?php } else { ?>
                                 <img class="ui-li-icon" alt="regonly" src="images/regonly.png"> 
										<?php } ?>
							<?php } elseif($expired && isset($_SESSION['player_logged_in']) && game_players_registered($games_list[$i]['game_id'], $_SESSION['player_logged_in']) == 1) { ?>
										<img class="ui-li-icon" alt="open" src="images/played.png" >
							<?php } else { ?>
										<img class="ui-li-icon" alt="open" src="images/open.png" > 
                     <?php } ?>
                        <span <?php if ($expired) {
                     echo 'class="expired"';
                  } ?>><?php echo date_to_php($games_list[$i]['game_name']) ?></span>
                     </a>
               <?php if (isset($_SESSION['player_access']) && $_SESSION['player_access'] == 'admin') { ?><a href="winners_update.php?game_id=<?php echo $games_list[$i]['game_id']; ?>&force_refresh=<?php echo mt_rand(1, 1000); ?>" title="Winners"></a><?php } ?>
                  </li>
<?php } ?>
            </ul>
         </div>
         <div data-role="footer" data-position="fixed">
<?php require('includes/set_footer.php'); ?>
         </div>
      </div>
   </body>
</html>
