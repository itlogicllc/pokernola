<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>PokerNOLA Mobile Web App</title>
<link rel="stylesheet" href="//code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css">
<?php if ($page_access_type == 'admin') {
	echo '<link href="css/admin.min.css" rel="stylesheet" type="text/css" media="all">';
} else {
	echo '<link href="css/default.min.css" rel="stylesheet" type="text/css" media="all">';
} ?>
<link href="scripts/datebox/jqm-datebox.min.css" rel="stylesheet" type="text/css" media="all">
<link href="css/styles.css" rel="stylesheet" type="text/css" media="all">

<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
<script>
    $(document).bind('mobileinit', function () {
        $.mobile.ajaxEnabled = false;
        
//        $.mobile.loader.prototype.options.text = "loading...";
//        $.mobile.loader.prototype.options.textVisible = true;
//        $.mobile.loader.prototype.options.theme = "a";
//        $.mobile.loader.prototype.options.textonly = false;
//        $.mobile.loader.prototype.options.html = "<span id='custom_loader' class='ui-bar ui-overlay-c ui-corner-all'><img src='../images/pokernola_logo.png' /><h2>loading...</h2></span>";
    });
</script>
<script src="//code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
<script src="scripts/datebox/jqm-datebox.core.min.js"></script>
<script src="scripts/datebox/jqm-datebox.mode.calbox.min.js"></script>
<script src="scripts/datebox/jqm-datebox.mode.flipbox.min.js"></script>
<script src="scripts/datebox/jquery.mobile.datebox.i18n.en.utf8.min.js"></script>
<script src="scripts/highcharts.js"></script>
<script src="scripts/gray.js"></script>
<script src="scripts/custom.js"></script>




