<?php
session_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$tbl = _DB_PREFIX;
$db = new Database();

$distributor_id = $_POST['distributor_id'];

$amount = "";
$blnc = $db->single_data($tbl . "product_purchase_order_invoice", "sum(total_price) as total_price", "distributor_id", $distributor_id);
$desblnc = $blnc['total_price'];
if ($desblnc == "") {
    $amount = "Sales Up Status Befor Today: 0.00";
} else {
    $amount = "Sales Up Status Befor Today: " . $desblnc;
}
echo $amount;
?>