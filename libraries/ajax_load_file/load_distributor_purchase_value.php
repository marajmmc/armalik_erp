<?php

session_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$tbl = _DB_PREFIX;
$db = new Database();

$distributor_id = $_POST['distributor_id'];

$amount = "";
//$blnc = $db->single_data($tbl . "distributor_balance", "credit_limit_amount", "distributor_id", $distributor_id);
$pay = $db->single_data($tbl . "distributor_add_payment", "SUM(amount) AS amount", "distributor_id", $distributor_id);
$ob = $db->single_data_w($tbl . "distributor_info", "due_balance", "status='Active' AND del_status='0' AND distributor_id='$distributor_id'");
$sql = "SELECT
SUM($tbl" . "product_purchase_order_invoice.total_price) AS total_price
FROM `$tbl" . "product_purchase_order_invoice`
WHERE read_status='0' AND `status`='Delivery' AND del_status='0' AND distributor_id='$distributor_id'
GROUP BY $tbl" . "product_purchase_order_invoice.distributor_id";

if ($db->open()) {
    $result = $db->query($sql);
    $blnc = $db->fetchAssoc($result);
    
    $desblnc = ($ob['due_balance']+$blnc['total_price'])-$pay['amount'];
}

if ($desblnc == "") {
    $amount = "Previous credit status: 0.00";
} else {
    $amount = "Previous credit status: " . $desblnc;
}
echo $amount;
?>