<?php

require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$tbl = _DB_PREFIX;
$db = new Database();
$purchase_order_id=$_POST['purchase_order_id'];
$crop_id=$_POST['crop_id'];
$product_type_id=$_POST['product_type_id'];
$varriety_id=$_POST['varriety_id'];
$pack_size=$_POST['pack_size'];

$warehouse_id = $_POST['warehouse_id'];
if(!empty($warehouse_id))
{
    $warehouse="AND $tbl" . "product_stock.warehouse_id='$warehouse_id'";
}
else
{
    $warehouse="";
}

$ps=$db->single_data_w($tbl . "product_pack_size", "pack_size_name", "pack_size_id='$pack_size' AND del_status='0' AND status='Active'");
$pack_size_name=$ps['pack_size_name'];

$prices = $db->single_data_w($tbl . "product_stock", "sum(current_stock_qunatity) as current_stock_qunatity", "crop_id='$crop_id' AND product_type_id='$product_type_id' AND varriety_id='$varriety_id' AND pack_size='$pack_size' $warehouse");
$crnt_stock= $prices['current_stock_qunatity'];

$cstock=($crnt_stock*$pack_size_name)/1000;
echo $cstock;

//$pricepo = $db->single_data_w($tbl . "product_purchase_order_request", "sum(quantity) as quantity", "purchase_order_id='$purchase_order_id' AND crop_id='$crop_id' AND product_type_id='$product_type_id' AND varriety_id='$varriety_id' AND pack_size='$pack_size' AND status='Pending'");
//$po_stock= $pricepo['quantity'];
//
//$stock=$crnt_stock-$po_stock;
//
//$cstock=($stock*$pack_size_name)/1000;
//echo $cstock;
?>