<?php

require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$tbl = _DB_PREFIX;
$db = new Database();
$crop_id = $_POST['crop_id'];
$product_type_id = $_POST['product_type_id'];
$varriety_id = $_POST['varriety_id'];
$pack_size = $_POST['pack_size'];
$purchase_order_id = $_POST['purchase_order_id'];
$warehouse_id = $_POST['warehouse_id'];
if(!empty($warehouse_id))
{
    $warehouse="AND $tbl" . "product_stock.warehouse_id='$warehouse_id'";
    $warehouse_po="AND $tbl" . "product_purchase_order_request.warehouse_id='$warehouse_id'";
}
else
{
    $warehouse="";
    $warehouse_po="";
}

//$prices = $db->single_data_w($tbl . "product_stock", "sum(current_stock_qunatity) as current_stock_qunatity", "crop_id='$crop_id' AND product_type_id='$product_type_id' AND varriety_id='$varriety_id' AND pack_size='$pack_size'");
$sqlcsq = "SELECT
            SUM($tbl" . "product_stock.current_stock_qunatity*$tbl" . "product_pack_size.pack_size_name)/1000 as current_stock_qunatity
        FROM $tbl" . "product_stock
        LEFT JOIN $tbl" . "product_pack_size ON $tbl" . "product_pack_size.pack_size_id=$tbl" . "product_stock.pack_size
        WHERE crop_id='$crop_id' AND product_type_id='$product_type_id' AND varriety_id='$varriety_id' AND pack_size='$pack_size' $warehouse
";
if ($db->open()) {
    $result_csq = $db->query($sqlcsq);
    $prices = $db->fetchAssoc($result_csq);
    $crnt_stock = $prices['current_stock_qunatity'];
    echo $crnt_stock?$crnt_stock:0;
}

//$pricepo = $db->single_data_w($tbl . "product_purchase_order_request", "sum(approved_quantity) as approved_quantity", "crop_id='$crop_id' AND product_type_id='$product_type_id' AND varriety_id='$varriety_id' AND pack_size='$pack_size' AND status='Approved'");
$sqlaq="SELECT 
            SUM($tbl" . "product_purchase_order_request.quantity*$tbl" . "product_pack_size.pack_size_name)/1000 as approved_quantity
        FROM $tbl". "product_purchase_order_request
        LEFT JOIN $tbl" . "product_pack_size ON $tbl" . "product_pack_size.pack_size_id = $tbl" . "product_purchase_order_request.pack_size
        WHERE purchase_order_id='$purchase_order_id' AND crop_id='$crop_id' AND product_type_id='$product_type_id' AND varriety_id='$varriety_id' AND pack_size='$pack_size' AND $tbl". "product_purchase_order_request.status='Pending' $warehouse_po
        ";
if ($db->open()) {
    $result_aq = $db->query($sqlaq);
    $pricepo = $db->fetchAssoc($result_aq);
    $po_stock = $pricepo['approved_quantity'];
}

//$stock = $crnt_stock - $po_stock;
//echo $stock;
?>