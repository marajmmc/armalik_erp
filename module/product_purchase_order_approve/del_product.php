<?php

session_start();
if ($_SESSION['logged'] != 'yes') {
    $_REQUEST["msg"] = "TimeoutC";
    header("location:../../index.php");
}
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$db = new Database();
$user_id = $_SESSION['user_id'];
$ei_id = $_SESSION['employee_id'];
$tbl = _DB_PREFIX;

//$delsql="DELETE FROM $tbl" . "product_purchase_order_request WHERE id='".$_POST['elm_id']."'";
echo $delsql="UPDATE $tbl" . "product_purchase_order_request SET del_status='1' WHERE id='".$_POST['elm_id']."'";
if($db->open()){
    $result=$db->query($delsql);
}


?>