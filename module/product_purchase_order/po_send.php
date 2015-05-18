<?php

session_start();

require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$db = new Database();
$user_id = $_SESSION['user_id'];
$ei_id = $_SESSION['employee_id'];
$tbl = _DB_PREFIX;

//$delsql="DELETE FROM $tbl" . "product_purchase_order_request WHERE id='".$_POST['elm_id']."'";
$update_sql="UPDATE $tbl" . "product_purchase_order_request SET status='Pending' WHERE purchase_order_id='".$_POST['elm_id']."'";
if($db->open()){
    $result=$db->query($update_sql);
    if($result)
    {
        echo "Success";
    }
    else
    {
        echo "Not_Success";
    }
}

$db->system_event_log('', $user_id, $ei_id, $_POST['elm_id'], '', $tbl . 'product_purchase_order_request', 'Delete', '');
?>