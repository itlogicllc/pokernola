<?php 
	require('../db_connections/pokernola.php');
	require('includes/set_page.php');
	require('includes/set_access.php');
	//get_access();
?>
<!DOCTYPE html>
<html>
   <head>
      <?php require('includes/set_head.php'); ?>
		<title>Poker NOLA Help</title>
   </head>
   <body>
      <div data-role="page" id="pokernola_help">
         <div data-role="header" data-position="fixed">
            <h1>Help</h1>
            <?php require('includes/set_tools.php'); ?>
         </div>
         <div role="main" class="ui-content">
            <div data-role="collapsible-set">
<!--               <div data-role="collapsible" data-iconpos="right">
                  <h3><img src="images/icons/info-white.png" alt="Overview"/> Overview</h3>
                  <p>Poker NOLA is a scoring system for poker tournaments. It is a mobile application accessed at http://www.pokernola.com. It is a rules based program that keeps track of points and payouts of players as defined by the rules for that season of play. Only players who register with Poker NOLA will be tracked. Players may also play as guest but, their statistics will not be maintained.</p>

                  <p>It begins by the administrator creating a season. The season has a start and end date along with other various options that control the rules of play during that time frame. Once a season is defined, the administrator creates games to be played in that season. The games can be open or private. Open games are open to anyone who comes to play. Private games are only for registered players who register for those games before hand.</p>

                  <p>As games are completed and winners recorded they collect points based on their finishing positions. Those points are used to rank each player until the end of the season when the player with the most points wins. Poker NOLA is the tool that shows and keeps track of players ranking during the season as points are accumulated, along with various other statistics and charts that show the players progress.</p>
               </div>-->
               <div data-role="collapsible" data-iconpos="right">
                  <h3><img src="images/icons/calendar-white.png" alt="Date Picker"/> Date Picker</h3>
                  <p>Clicking or tapping on the calendar icon at the bottom left opens the date range picker. By default all data shown is for the current season. You can also see previous seasons by selecting them from the menu provided. If you wish to view all data between any custom dates you can enter those dates in the date pickers below the menu.</p>

                  <p>The currently displayed time frame is shown at the bottom of the footer. If you are viewing a season, the season name will be shown. If you are viewing custom dates, they will be shown as "from" and "to" dates.</p>

                  <p>Depending on where you are in the program, the calendar icon may change to a forbidden <img src="images/icons/forbidden-white.png" alt="Forbidden" /> icon. The date picker is unavailable while the forbidden icon is shown.</p>
               </div>
               <div data-role="collapsible" data-iconpos="right">
                  <h3><img src="images/icons/bars-white.png" alt="Profile" /> Profile</h3>
                  <p>A player, once registered, can log in and change their profile. When the icon at the bottom right is a lock <img src="images/icons/lock-white.png" alt="Log In" /> it means the user is not logged in. Clicking or tapping on the lock opens the log in panel where they can enter their email address and password to log in. By clicking or tapping on the &quot;Remember Me&quot; checkbox, their credentials will remembered for next time they want to log in on that same device. Un-checking the box will cause that device to forget your credentials.</p>

                  <p>After a user has successfully logged in, the icon changes to an edit <img src="images/icons/edit-white.png" alt="Edit"/> icon. Also, there will be a welcome message in the footer, above the time frame of data being viewed, using the logged in user's first name. Clicking or tapping on the edit icon will bring up that user's profile where they can update any of their information.</p>

                  <p>In the header of the player's profile is the <img src="images/icons/delete-white.png" alt="Log Out"/> icon. Clicking or tapping on this icon will log the user out of the system.</p>
               </div>
              <div data-role="collapsible" data-iconpos="right">
                  <h3><img src="images/icons/home-white.png" alt="Home" /> Home</h3>
                  <p>The home page simply shows some general statistics concerning the overall program along with some lists of top players.</p>
                  <p class="info">Note: Click or tap on a statistic label to get its definition.</p> 
               </div>
               <div data-role="collapsible" data-iconpos="right">
                  <h3><img src="images/icons/user-white.png" alt="Players" /> Players</h3>
                  <p>The Players page shows all ranked players and their points or payouts. The rankings are based on the sum of all points or monies earned cumulatively during the time frame being viewed. You can find a specific player using the search box above the list.</p>

                  <p>Clicking or tapping on a player brings you to that player's details page</p>

                  <p>On the player's details page, The View Ranking History Chart button shows a line chart of the player's ranking as it was at the end of each game in time frame being viewed. Below that is a list showing how many games the player won in each given place. Clicking or tapping on a list items causes it to expand and show each of those specific games. Each game listed shows the game name, payout, split status and points awarded. Clicking or tapping on the game will take you to that game's details.</p>

                  <p>By clicking or tapping on the plus <img src="images/icons/plus-white.png" alt="Add Player" /> icon at the top left in the header, a new player can register with Poker NOLA.</p>

                  <p class="info">Note: Click or tap on a statistic label to get its definition.</p>
               </div>
               <div data-role="collapsible" data-iconpos="right">
                  <h3><img src="images/icons/grid-white.png" alt="Games" /> Games</h3>
                  <p>The Games page shows the game schedule. It is a list of dates that represents when each game is to be, or has been played.</p>
                  <p>There are two types of games, open and private. An open game is one in which anyone can play up to the maximum amount of players allowed. A private game must be registered for prior to it starting. Only players registered in Poker NOLA are able to register for these games. The type of game is indicated by an icon in the game schedule:</p>

                  <p><img alt="Open Game" src="images/open.png"> - Open Game<br /><img alt="Open Game" src="images/private.png"> - Private Game<br /><img alt="Open Game" src="images/registered.png"> - Private Game that the currently logged in user has already registered for<br /><img alt="Played Game" src="images/played.png"> - Games the currently logged in player has played in</p>

                  <p>Games that have already been played in the schedule will be grayed out and stricken through, but are still able to be clicked on for more information.</p>

                  <p>When it is an open game, clicking or tapping on the game will bring you to the game's details. On the Game Details page are Winners, Players and Season Rules. The Winners list shows the winners for that game. Each list item shows the player's name, payout, split status and points earned for that game. The Player's list of all players who played in that game. In both cases, clicking or tapping on the player will bring you to that player's details. Season Rules show the season rules that dictate that game.</p>

                  <p>When it is a private game, clicking or tapping on the game will bring you to the Game Registration. If you are not logged in, you will get a notice that you must be logged in to register. If you are logged in and have not previously registered for the game, you will be presented with a button to select and register for that game. If you are logged in and have previously registered for that game, you will be presented with a button to select to unregister from that game.</P>

                  <p>Below the button is a list of players who have registered for the game. Above the list is a count of how many players have registered as opposed to how many players are allowed to play. Once that maximum number has been met, no new registrants are allowed. However, at that point, registration is still open. If an existing registrant unregisters, a new spot will open for another player. Once the game starts, the registration period will have ended and it will be treated as an open game, even though no new players will be allowed.</p>
                  <p class="info">Note: Click or tap on a statistic label to get its definition.</p>
               </div>
               <div data-role="collapsible" data-iconpos="right">
                  <h3><img src="images/icons/gear-white.png" alt="Tools" /> Tools</h3>
                  <p>Season Rules shows a list of rules that dictate how play is to be done for each game in that season. The first item in the list is the current season rules. Any new game created will use those rules until a new season is created.</p>
               </div>
            </div>
         </div>
         <div data-role="footer" data-position="fixed">
            <?php require('includes/set_footer.php'); ?>
         </div>
      </div>
   </body>
</html>
