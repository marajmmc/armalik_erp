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
$dbins = new Database();
$user_id = $_SESSION['user_id'];
$ei_id = $_SESSION['employee_id'];
$tbl = _DB_PREFIX;

echo $sql = "INSERT INTO $tbl" . "product_purchase_order_request_delete
SELECT
'',
ait_product_purchase_order_request.purchase_order_id,
ait_product_purchase_order_request.purchase_order_date,
ait_product_purchase_order_request.invoice_id,
ait_product_purchase_order_request.warehouse_id,
ait_product_purchase_order_request.zone_id,
ait_product_purchase_order_request.territory_id,
ait_product_purchase_order_request.distributor_id,
ait_product_purchase_order_request.crop_id,
ait_product_purchase_order_request.product_type_id,
ait_product_purchase_order_request.varriety_id,
ait_product_purchase_order_request.pack_size,
ait_product_purchase_order_request.price,
ait_product_purchase_order_request.quantity,
ait_product_purchase_order_request.approved_quantity,
ait_product_purchase_order_request.total_price,
ait_product_purchase_order_request.remark,
ait_product_purchase_order_request.read_status,
ait_product_purchase_order_request.`status`,
ait_product_purchase_order_request.del_status,
'$user_id',
'" . $dbins->ToDayDate() . "'
FROM $tbl" . "product_purchase_order_request WHERE purchase_order_id='" . $_POST['elm_id'] . "'";
if ($dbins->open()) {
    $dbins->query($sql);
}


$delsql="DELETE FROM $tbl" . "product_purchase_order_request WHERE purchase_order_id='".$_POST['elm_id']."'";
//echo $delsql="UPDATE $tbl" . "product_purchase_order_request SET del_status='1' WHERE id='".$_POST['elm_id']."'";
if($db->open())
{
    $result=$db->query($delsql);
    echo "ok";
}


?>