<?php

session_start();

require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$db = new Database();
$user_id = $_SESSION['user_id'];
$employee_id = $_SESSION['employee_id'];
$tbl = _DB_PREFIX;

///////////  START PRODUCT QUANTITY ////////////////////
$year_id=$_POST["year_id"];
$warehouse_id=$_POST["warehouse_id"];
$crop_id=$_POST["crop_id"];
$product_type_id=$_POST["product_type_id"];
$variety_id=$_POST["varriety_id"];
$pack_size=$_POST["pack_size"];
$quantity=$_POST["quantity"];
$opening_balance=$_POST["opening_balance"];
if(empty($year_id) &&empty($warehouse_id) && empty($crop_id) && empty($product_type_id) && empty($variety_id) && empty($pack_size))
{
    echo "Please check year, warehouse, crop, product type, variety, pack size.";
    die();
}

$maxID = $_POST['rowID'];

$rowfield = array
(
    'year_id' => "'" . $year_id . "'",
    'warehouse_id' => "'" . $warehouse_id . "'",
    'crop_id' => "'" . $crop_id . "'",
    'product_type_id' => "'" . $product_type_id . "'",
    'varriety_id' => "'" . $variety_id . "'",
    'pack_size' => "'" . $pack_size . "'",
    //'quantity' => "'" . $quantity . "'",
    'opening_balance' => "'" . $opening_balance . "'",
    'mfg_date' => "'" . $db->date_formate($_POST["mfg_date"]) . "'",
    'exp_date' => "'" . $db->date_formate($_POST["exp_date"]) . "'",
    'status' => "'Active'",
    'del_status' => "'0'",
    'entry_by' => "'$user_id'",
    'entry_date' => "'" . $db->ToDayDate() . "'"
);
$where_field=array('product_id' => "'$maxID'");
$db->data_update($tbl . 'product_purchase_info', $rowfield, $where_field);
$db->system_event_log('', $user_id, $employee_id, $maxID, '', $tbl . 'product_purchase_info', 'Save', '');
echo "Data Save Successfully";
?>