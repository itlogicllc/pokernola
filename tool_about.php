<?php 
	require('../db_connections/pokernola.php');
	require('includes/set_page.php');
?>
<!DOCTYPE html>
<html>
   <head>
		<?php require('includes/set_head.php'); ?>
		<title>About PokerNOLA</title>
   </head>
   <body>
      <div data-role="page" id="tools_about">
         <div data-role="header" data-position="fixed">
            <h1>About</h1>
				<?php require('includes/set_tools.php'); ?>
         </div>
         <div role="main" class="ui-content">
            <div data-role="collapsible-set">
					<div class="ui-body ui-body-a ui-corner-all normal">
						<img src="images/pokernola_logo.png" width="150" height="150" alt="PokerNOLA logo" />
						<h1>PokerNOLA</h1>
						<p>Designed and developed by ITLogic LLC with the help of some friends</p>
						<p class="copyright">Copyright &copy; 2013
							<script>
								var d=new Date(); 
								yr=d.getFullYear();
								if (yr!=2013) document.write("- "+yr+". All rights reserved.");
							</script>
						</p>
					</div>
					<br />
					 <div data-role="collapsible" data-collapsed="false">
						<h3>v1.2.1 - 1/21/2016</h3>
							<ul>
								<li>Fixed pagination for Players and Games</li>
								<li>Updated help file</li>
								<li>Updated tool tips</li>
								<li>minor bug fixes</li>
							</ul>
               </div>
					 <div data-role="collapsible" data-collapsed="">
						<h3>v1.2.0 - 1/18/2016</h3>
							<ul>
								<li>Enhanced the seasons system</li>
								<li>Reorganized and rewrote parts of code for clarity and efficiency</li>
								<li>Fixed rankings to show ties properly</li>
								<li>Enhanced navigation to be more user friendly</li>
								<li>Fixed several bugs</li>
							</ul>
               </div>
					<div data-role="collapsible" data-collapsed="">
						<h3>v1.1.0 - 1/25/2015</h3>
						<ul>
							<li>Added game registration system</li>
							<li>Added invitation system</li>
							<li>Added password reset system</li>
							<li>Added overall ranking by payout</li>
							<li>Added game icon notifications</li>
							<li>Added concept of games belonging to seasons</li>
							<li>Changed date picker to select seasons to view, all season or custom dates</li>
							<li>Changed split calculation to be based on each splitting player's chip count instead of percentage of chips owned</li>
							<li>Changed login system from a panel to a separate page</li>
							<li>Made login the landing page to force user login</li>
							<li>Minor bug fixed and navigation changes</li>
						</ul>
               </div>
               <div data-role="collapsible" data-collapsed="">
						<h3>v1.0.0 - 12/31/2013</h3>
							<ul>
								<li>Initial release</li>
							</ul>
               </div>
            </div>
         </div>
         <div data-role="footer" data-position="fixed">
				<?php require('includes/set_footer.php'); ?>
         </div>
      </div>
   </body>
</html>
