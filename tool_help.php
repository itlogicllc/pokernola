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
		<title>PokerNOLA Help</title>
   </head>
   <body>
      <div data-role="page" id="pokernola_help">
         <div data-role="header" data-position="fixed">
            <h1>Help</h1>
            <?php require('includes/set_tools.php'); ?>
         </div>
         <div role="main" class="ui-content">
            <div data-role="collapsible-set">
               <div data-role="collapsible" data-iconpos="right">
                  <h3><img src="images/icons/calendar-white.png" alt="Date Picker"/> Date Picker</h3>
                  <p>Clicking or tapping on the calendar icon at the bottom left opens the date range picker. By default all data shown is for the current season. You can also see previous seasons by selecting them from the menu provided. If you wish to view all data between any custom dates you can enter those dates in the date pickers below the menu.</p>
                  <p>The currently displayed time frame is shown at the bottom of the footer. If you are viewing a season, the season name will be shown. If you are viewing custom dates, they will be shown as "from" and "to" dates.</p>
                  <p>Depending on where you are in the program, the calendar icon may change to a forbidden <img src="images/icons/forbidden-white.png" alt="Forbidden" /> icon. The date picker is unavailable while the forbidden icon is shown.</p>
               </div>
					
					
					<div data-role="collapsible" data-iconpos="right">
                  <h3><img src="images/icons/lock-white.png" alt="Login"/> Login</h3>
                  <p>If the player has not already been forced to log in, they may do so by selecting the login button. This button will take them to the login page where the player will enter their email address and password to login.</p>
						<p>By selecting the Remember Me option, the player will not need to enter their credentials every time they want to login to PokerNOLA. PokerNOLA will remember their information and the appropriate fields will already be filled out. Then, the player only needs to select the login button.</p>
						<p>In the event the player forgets their password, or just chooses to change it, they can select the Forgot Password button. A form will be shown that simply asks for the player's email address. The email address the player is registered in PokerNOLA with must be used. A link will be sent to the given email address and, when followed, the player will be able to choose a new password.</p> 
               </div>
					
					
					<div data-role="collapsible" data-iconpos="right">
                  <h3><img src="images/icons/mail-white.png" alt="Invitations"/> Send Invitation</h3>
                  <p>Invitations are how new members join PokerNOLA. As a member of PokerNOLA, you have the ability to send invitations to other friends who you feel would be good additions to the poker house.</p>
						<p>The Send Invitation button can be found in the player's Profile and on the Players page. When selected, a form will be shown. Simply enter the invitee's first name, last name and email address in the fields provided. When the form is submitted an email invitation will be sent to the given email address. The invitee will receive the invitation and be provided a link. Once they follow the link they will be able to create a password to PokerNOLA and become a new registered member.</p>
						<p>Within the player's profile, they can view and manage a list of invitations that they have sent.</p> 
               </div>
					
					
               <div data-role="collapsible" data-iconpos="right">
                  <h3><img src="images/icons/bars-white.png" alt="Profile" /> Profile</h3>
						<p>This brings the player to their profile page. The player's profile includes a Details page and an Invitations page.</p>
						<p>In the header of the Profile page are the send invitation <img src="images/icons/mail-white.png" alt="mail"/> and logout <img src="images/icons/power-white.png" alt="logout"/> icons.</p>
                  <p>On the Details page the player can view and edit their profile. If the player changes their email address, that new email address will be used next time they log in.</p>
						<p>On the Invitations page, the player can view and manage invitations they have sent. The header above the list of invitations says who invited them. In the invitations list, invitations with a check <img src="images/icons/check-white.png" alt="check" /> are ones that have been accepted. Invitations with a clock <img src="images/icons/clock-white.png" alt="check" /> are ones that are still pending. If a pending invitation is selected, that invitation will be canceled and the invitee will no longer be able to accept it.
               </div>
					
					
              <div data-role="collapsible" data-iconpos="right">
                  <h3><img src="images/icons/home-white.png" alt="Home" /> Home</h3>
                  <p>The Home page simply shows some general statistics concerning the overall program along with some lists of top players. If the header above a statistic is selected, a tool tip will pop up and define what that statistic means.</p>
               </div>
					
					
               <div data-role="collapsible" data-iconpos="right">
                  <h3><img src="images/icons/user-white.png" alt="Players" /> Players</h3>
                  <p>The Players page shows all players who have won points. There is a By Points page and a By Payouts page.</p>
						<p>In the header of the Player page is the send invitation <img src="images/icons/mail-white.png" alt="mail"/> icon.</p>
						<p>The By Points page shows a list of players ranked by the overall sum of points they have won.</p>
						<p>The By Payouts page shows a list of players ranked by the overall sum of payouts they have won.</p>
                  <p>If you select a player on either page, you will be takes to the details page for that player.</p>
                  <p>On the player's details page, there is a header with the player's name. If there are arrows on either side of the player's name, they can be selected to quickly move to the next or previous player's details in the points ranking list. There are also statistics shown concerning that player. If the header above a statistic is selected, a tool tip will pop up and define what that statistic means.</p>
						<p>Below the statistics is a View Rankings History Chart button. When selected, a line graph will pop up showing the player's ranking history by points. Each data point on the graph indicates where that player ranked at the end of each game played. This allows the player to see their ranking progress over a range of time.</p>
						<p>Finally, there is a list of each time that player has placed. It shows which place they came in and how many times they did it. When selecting one of the list item, it opens to show the specific games in which that player placed and some details about that game and their winnings. If that game is selected, it will display that game's details.</p>
               </div>
					
					
               <div data-role="collapsible" data-iconpos="right">
                  <h3><img src="images/icons/grid-white.png" alt="Games" /> Games</h3>
                  <p>The Games page shows the game schedule. It is a list of dates that represents when each game is to be, or has been played.</p>
                  <p>There are two types of games, open and closed. An open game is one in which anyone can play up to the maximum amount of players allowed. A closed game must be registered for prior to playing. Only players registered with PokerNOLA are able to register for closed games. The type of game is indicated by an icon in the game schedule:</p>
                  <p>
							<img alt="Open Game" src="images/open.png"> - Open Game<br />
							<img alt="Closed Game" src="images/private.png"> - Closed Game<br />
							<img alt="Registered" src="images/registered.png"> - Closed Game the logged in player has registered for as a player<br />
							<img alt="Alternate" src="images/alternate.png"> - Closed Game the logged in player has registered for as an alternate<br />
							<img alt="Played Game" src="images/played.png"> - Game the logged in player has played in
						</p>
                  <p>Games in the schedule that have already been completed will be grayed out and stricken through.</p>
						<p>When selecting completed or open games, the game's details will be shown</p>
                  <p>On the games's details page, there is a header with the games's date. If there are arrows on either side of the game date, they can be selected to quickly move to the next or previous game details in the schedule of games that have been completed. There are also statistics shown concerning that game. If the header above a statistic is selected, a tool tip will pop up and define what that statistic means.</p>
						<p>Below the statistics are a list of Winners of that game, a list of Players who played in that game, a list of Alternates for that game and the Season Rules that governed that game. When selecting the Winners list, it will open to show each player who won, which place they were in, how many points they won and the amount of any payouts they received. If there was a split between players, that will be indicated on the list item along with what percentage of the total pot they won. If a player is selected, that player's details page will be shown. If the Players list is selected, it will open to show all the players who played in that game. If a player is selected, that player's details page will be shown, and likewise for the Alternates list. If the Season Rules list is selected, it will open to show what rules governed the game.</p>
                  <p>When selecting a closed game, the game Registration page will be shown which will enable the member to register for the selected game. See the Game Registration section for details on how to register for games.</p> 
					</div>
						
						
					<div data-role="collapsible" data-iconpos="right">
                  <h3><img src="images/icons/check-white.png" alt="Game Registration" /> Game Registration</h3>
						<p>There are open games, signified by the <img alt="Open Game" src="images/open.png"> icon in the Game Schedule, and closed games, signified by the <img alt="Closed Game" src="images/private.png"> icon in the Game Schedule. Open games are those in which anyone can play. Closed games are those in which only PokerNOLA members can and must registered for. Only if the closed game has not reached its maximum allowed players will guest players be allowed to play.</p>
						<p> To register for a game, the player must first select the closed game in the Game Schedule they want to register for. If the player is logged in and has not previously registered for the game, they will see a Register button. When selected, their name will be added to the Registered Players list, provided that the game has not already reached its maximum allowed players. The number of maximum allowed players is indicated above the registered players list.</p>
						<p>If the maximum allowed players has already been reached, the Register button will read Register Alternate. When selecting this button, the logged in player will be added to the Alternates List. The Alternates list is displayed in the order in which players registered. Alternates are not able to play in the selected game, however, by being on the alternates list, they are in line to become registered players. If a registered player unregisters, then the first alternate in line will automatically be moved to the Registered Players List and sent an email notifying them of the change.</p>
							<p>When a player is logged in and registered as a player, they will see the <img alt="Registered" src="images/registered.png"> icon next to the appropriate game in the Game Schedule. When a player is logged in and registered as an alternate, they will see the <img alt="Alternate" src="images/alternate.png"> icon next to the appropriate game in the Game Schedule. If the player is registered as an alternate and is automatically moved to the Registered Players List, their alternate icon will automatically change to a registered icon.</p>
						<p>If the player is logged in and has already registered for the game, they will see an Unregister button. If they are already registered as an alternate, the button will read Unregister Alternate instead. In either case, the player will be removed from the appropriate list when the button is selected.</p>
						<p>Below the registration button are the Registered List and Alternates List. By selecting the Registered or Alternates button you can toggle between the two lists to see which players are there.</p>
						<p>Once it has been decided that the registration time has ended, selecting the game in the Game Schedule will show the game's details instead of the registration button and no more registrants will be allowed.</P>
               </div>
					
					
               <div data-role="collapsible" data-iconpos="right">
                  <h3><img src="images/icons/gear-white.png" alt="Tools" /> Tools</h3>
                  <p>The Tools page shows buttons to access other functions of PokerNOLA.</p>
						<p>The Season Rules button will show a page that lists all of the seasons played by the poker house. Each list item has an icon. The Ended <img src="images/icons/forbidden-white.png" alt="Forbidden" /> icon signifies seasons that have ended and the current <img src="images/icons/check-white.png" alt="Forbidden" /> icon signifies which season is the season currently being played. Selecting one of the seasons list items opens to show the rules of that season.</p>
						<p>The contact button opens a form where the player can compose and submit a simple email to PokerNOLA to ask questions, make comments, get help or any other reason they should need to contact PokerNOLA.</p>
						<p>The Help button is where you are now.</p>
						<p>The About button simply shows some copyright and version information about PokerNOLA</p>
               </div>
            </div>
         </div>
         <div data-role="footer" data-position="fixed">
            <?php require('includes/set_footer.php'); ?>
         </div>
      </div>
   </body>
</html>
