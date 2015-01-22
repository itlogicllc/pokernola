<!DOCTYPE html>
<html>
   <head>
      <?php require('includes/set_head.php'); ?>
   </head>
   <body>
      <div data-role="index" id="home">
         <div data-role="header" data-position="fixed">
            <h1>Notice!</h1>
            <?php require('includes/set_home.php'); ?>
         </div>
         <div role="main" class="ui-content">
            <div class="ui-body ui-body-a ui-corner-all info" align="center">
               <p>If you are a currently registered player with Poker NOLA, please email your first and last names to player@pokernola.com</p>
               <p>You will receive a reply with instruction on setting up your new password and why it is necessary to do so. If you know other registered players, please pass this message on to them.</p>
               <p>To continue on to Poker NOLA, click one of the menu buttons above.</p>
               <p>Thanks!</p>
            </div>
            <div data-role="footer" data-position="fixed">
               <?php //require('includes/set_footer.php'); ?>
            </div>
         </div>
      </div>
   </body>
</html>
