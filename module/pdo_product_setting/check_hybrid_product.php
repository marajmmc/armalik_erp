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
$chk=$db->single_data_w
(
    $tbl."pdo_product_characteristic_setting",
    "variety_id",
    "variety_id='".$_POST['variety_id']."'"
);
if ($chk['variety_id']!=""){echo "Found";}else{echo "Not Found";}
?>