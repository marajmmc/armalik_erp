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
$tbl = _DB_PREFIX;
$db = new Database();
$checkstatus=$db->single_data($tbl . "user_login", "user_status", "id", $_POST['id']);
if($checkstatus['user_status']=="Active"){
    $status="InActive";
}else{
    $status="Active";
}
$sql = "update $tbl" . "user_login set user_status='$status' WHERE id='" . $_POST['id'] . "'
                        ";
if ($db->open()) {
    $result = $db->query($sql);
    echo "ok";
}
?>