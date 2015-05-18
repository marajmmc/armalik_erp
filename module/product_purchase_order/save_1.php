<?php

session_start();

require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$db = new Database();
$user_id = $_SESSION['user_id'];
$ei_id = $_SESSION['employee_id'];
$tbl = _DB_PREFIX;

$total_pack_price='';
$total_quantity='';
$total_price='';

$maxID = "PO-" . $db->Get_CustMaxID($tbl . 'product_purchase_order_summery', 'purchase_order_id','8','y');

$count = count($_POST['id']);
for ($i = 0; $i < $count; $i++) {
    $total_pack_price=$total_pack_price+$_POST["price"][$i];
    $total_quantity=$total_quantity+$_POST["quantity"][$i];
    $total_price=$total_price+$_POST["total_price"][$i];
    
    $rowfield = array(
        'purchase_order_id,' => "'$maxID',",
        'crop_id,' => "'" . $_POST["crop_id"][$i] . "',",
        'varriety_id,' => "'" . $_POST["varriety_id"][$i] . "',",
        'pack_size,' => "'" . $_POST["pack_size"][$i] . "',",
        'price,' => "'" . $_POST["price"][$i] . "',",
        'quantity,' => "'" . $_POST["quantity"][$i] . "',",
        'total_price,' => "'" . $_POST["total_price"][$i] . "',",
        'status,' => "'Active',",
        'del_status,' => "'0',",
        'entry_by,' => "'$user_id',",
        'entry_date' => "'" . $db->ToDayDate() . "'"
    );

    $db->data_insert($tbl . 'product_purchase_order_details', $rowfield);
    $db->system_event_log('', $user_id, $ei_id, $maxID, '', $tbl . 'product_purchase_order_details', 'Save', '');
}

$rowfield = array(
    'purchase_order_id,' => "'$maxID',",
    'purchase_order_date,' => "'" . $db->date_formate($_POST["purchase_order_date"]) . "',",
    'distributor_id,' => "'" . $_POST["distributor_id"] . "',",
    'total_pack_price,' => "'" . $total_pack_price . "',",
    'total_quantity,' => "'" .$total_quantity . "',",
    'total_price,' => "'" . $total_price . "',",
    'status,' => "'Marketing',",
    'del_status,' => "'0',",
    'entry_by,' => "'$user_id',",
    'entry_date' => "'" . $db->ToDayDate() . "'"
);

$db->data_insert($tbl . 'product_purchase_order_summery', $rowfield);
$db->system_event_log('', $user_id, $ei_id, $maxID, '', $tbl . 'product_purchase_order_summery', 'Save', '');
?>