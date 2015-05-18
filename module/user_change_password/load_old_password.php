<?php

session_start();
ob_start();
if ($_SESSION['logged'] != 'yes') {
    $_REQUEST["msg"] = "TimeoutC";
    header("location:../../index.php");
}
//echo $_SESSION['shop_name_eng']; 
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

//echo $_POST['rowID'];
$tbl = _DB_PREFIX;
$db = new Database();

$user_pass = $_REQUEST["name"];
$user_pass = stripQuotes(removeBadChars($user_pass));
$crypt_user_pass = md5(md5($user_pass));
$sql = "SELECT
user_pass
FROM
$tbl" . "user_login
WHERE
user_pass='" . $crypt_user_pass . "'
";
$i = 0;
if ($db->open()) {
    $result = $db->query($sql);
    $result_array = $db->fetchAssoc();
}
if ($result_array['user_pass'] != "") {
    echo "Found";
} else {
    echo "Not Found";
}
?>