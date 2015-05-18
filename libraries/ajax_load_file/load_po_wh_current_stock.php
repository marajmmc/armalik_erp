<?php
session_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$tbl = _DB_PREFIX;
$db = new Database();
$crop_id=$_POST['crop_id'];
$product_type_id=$_POST['product_type_id'];
$varriety_id=$_POST['varriety_id'];
$pack_size=$_POST['pack_size'];
$warehouse_id=$_SESSION['warehouse_id'];

$prices = $db->single_data_w($tbl . "product_stock", "sum(current_stock_qunatity) as current_stock_qunatity", "crop_id='$crop_id' AND product_type_id='$product_type_id' AND varriety_id='$varriety_id' AND pack_size='$pack_size' AND warehouse_id='$warehouse_id'");
$crnt_stock= $prices['current_stock_qunatity'];

$pricepo = $db->single_data_w($tbl . "product_purchase_order_request", "sum(approved_quantity) as approved_quantity", "crop_id='$crop_id' AND product_type_id='$product_type_id' AND varriety_id='$varriety_id' AND pack_size='$pack_size' AND status='Approved'");
$po_stock= $pricepo['approved_quantity'];

$stock=$crnt_stock-$po_stock;
echo $stock;
?>