<?php

@session_start();
ob_start();
$employee_id = $_SESSION['employee_id'];
$sessionid = $_SESSION['session_id'];
$_SESSION['logged'] = 'no';
session_unset();
session_destroy();
session_start();
ini_set("error_reporting", "E_ALL & ~E_NOTICE");

require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$tbl = _DB_PREFIX;
$db = new Database();
///////////  start time function /////////

//$timezone = new DateTimeZone("Asia/Dhaka" );
//$date = new DateTime();
//$date->setTimezone($timezone );
//$time=$date->format( 'Y-m-d H:i:s' );
$time=$db->getCurrentDateTime();
///////////  start time function /////////


if ($db->open()) {
    $db->query("update `$tbl" . "user_sync` set
                    logout='$time'
                where 
                    employee_id='$employee_id'"
    );
}
header("Location:../../index.php");
?>
