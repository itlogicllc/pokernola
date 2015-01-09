<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_poker_db = "127.0.0.1";
$database_poker_db = "pokernola";
$username_poker_db = "pokernola";
$password_poker_db = "MyD0gg!e$4";
$poker_db = mysql_pconnect($hostname_poker_db, $username_poker_db, $password_poker_db) or trigger_error(mysql_error(),E_USER_ERROR); 
?>