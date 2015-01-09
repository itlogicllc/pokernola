<?php require('includes/get_settings.php'); ?>
<?php

if (!function_exists("GetSQLValueString")) {

    function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") {
        if (PHP_VERSION < 6) {
            $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
        }

        $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

        switch ($theType) {
            case "text":
                $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
                break;
            case "long":
            case "int":
                $theValue = ($theValue != "") ? intval($theValue) : "NULL";
                break;
            case "double":
                $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
                break;
            case "date":
                $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
                break;
            case "defined":
                $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
                break;
        }
        return $theValue;
    }

}
?>
<?php

$settings_array[0] = settings_current();

if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['from_date'])) {
    $_SESSION['from_date'] = $settings_array[0]['start_date'];
}

if (!isset($_SESSION['to_date'])) {
    $_SESSION['to_date'] = $settings_array[0]['end_date'];
}

setlocale(LC_MONETARY, "en_US");

$date_pick_on = 0;
?>
<?php

if (isset($_POST['email']) && isset($_POST['password'])) {
    if ($_POST['remember'] == "yes") {
        setcookie("pokernola_player", $_POST['email'], time() + (86400 * 365), "/"); // 86400 = 1 day
        setcookie("pokernola_pass", $_POST['password'], time() + (86400 * 365), "/"); // 86400 = 1 day
    } else {
        setcookie("pokernola_player", "", time() + (86400 * 365), "/"); // 86400 = 1 day
        setcookie("pokernola_pass", "", time() + (86400 * 365), "/"); // 86400 = 1 day
    }
}
?>
<?php

function date_to_php($date) {
    $php_date = date_format(date_create($date), "m-d-Y");

    return $php_date;
}

function date_to_mysql($date) {
    $mysql_date = date('Y-m-d', strtotime($date));

    return $mysql_date;
}

function date_to_datepicker($date) {
    $datepicker_date = date('m/d/Y', strtotime($date));

    return $datepicker_date;
}

?>
