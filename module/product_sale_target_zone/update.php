<?php

session_start();

require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$db = new Database();
$user_id = $_SESSION['user_id'];
$ei_id = $_SESSION['employee_id'];
$tbl = _DB_PREFIX;

$zone_id=$_POST['zone_id'];
$year_id=$_POST['year_id'];
$crop_id=$_POST['crop_id'];
$product_type_id=$_POST['product_type_id'];
$variety_id=$_POST['varriety_id'];
$price=$_POST['price'];
$quantity=$_POST['quantity'];
$value=$_POST['value'];
$maxID=$_POST['rowID'];

if(!empty($crop_id) && !empty($product_type_id) && !empty($variety_id) && !empty($zone_id) && !empty($year_id))
{
    $rowfield = array
    (
        'sale_target_id,' => "'$maxID',",
        'zone_id,' => "'" . $zone_id . "',",
        'year_id,' => "'" . $year_id . "',",
        'crop_id,' => "'" . $crop_id . "',",
        'product_type_id,' => "'" . $product_type_id . "',",
        'varriety_id,' => "'" . $variety_id . "',",
        'price,' => "'" . $price . "',",
        'quantity,' => "'" . $quantity . "',",
        'value,' => "'" . $value . "',",
        'channel,' => "'Zone',",
        'status,' => "'Active',",
        'del_status,' => "'0',",
        'entry_by,' => "'$user_id',",
        'entry_date' => "'" . $db->ToDayDate() . "'"
    );
    $result=$db->data_insert($tbl . 'product_sale_target', $rowfield);
    $db->system_event_log('', $user_id, $ei_id, $maxID, '', $tbl . 'product_sale_target', 'Save', '');
    echo "Success";
}
else
{
    echo "Please Select Crop, Product Type, Variety.";
}

?>