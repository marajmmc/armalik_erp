<?php

@session_start();
session_unset();
session_destroy();
session_start();
ini_set("error_reporting", "E_ALL & ~E_NOTICE");
require_once("config.inc.php");
require_once("database.inc.php");
require_once("functions.inc.php");

ob_start(); // Turn on output buffering
$sessionid = $_SESSION['session_id'];
system('ipconfig /all'); //Execute external program to display output
$mycom = ob_get_contents(); // Capture the output into a variable
ob_clean(); // Clean (erase) the output buffer

$findme = "Physical";
$pmac = strpos($mycom, $findme); // Find the position of Physical text
$mac = substr($mycom, ($pmac + 36), 17); // Get Physical Address
//echo $mac;

$tbl = _DB_PREFIX;
$db = new Database();
$time = $db->getCurrentDateTime();
$user_name = $_REQUEST["user_session_login"];
if (!$db->open()) {
    echo $db->open();
} else {
    $user_name = stripQuotes(removeBadChars($user_name));
    $rst = $db->single_data($tbl . "user_login", "*", "user_name", $user_name);
    $rstp = $db->single_data_w($tbl . "password_reset_request", "*", "user_id='".$rst['user_id']."' AND status='Request' AND del_status='0'");

    if ($rst['user_name']) {
        if ($rstp['user_id']) {
            echo "You have already requested!";
        } else {
            $sql = "INSERT INTO $tbl" . "password_reset_request(user_id, status,entry_date)VALUES('$rst[user_id]', 'Request','" . $db->ToDayDate() . "')";
            if ($db->open()) {
                $result = $db->query($sql);
                if ($result == "1") {
                    echo "Send Request Successfully";
                } else {
                    echo "Query Execution Error";
                }
            }
        }
    } else {
        echo "Wrong user name! try again";
    }
}
?>
    