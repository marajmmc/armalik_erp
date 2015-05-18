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
$sql = "SELECT
upazilla_name
FROM
$tbl" . "upazilla_new
WHERE
zilla_id='" . $_POST['zilla_id'] . "' AND upazilla_name='" . $_POST['upazilla_name'] . "'
";
$i = 0;
if ($db->open()) {
    $result = $db->query($sql);
    $result_array = $db->fetchAssoc();
}
if ($result_array['upazilla_name']!=""){echo "Found";}else{echo "Not Found";}
?>