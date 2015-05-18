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
$user_id = $_SESSION['user_id'];
$ei_id = $_SESSION['employee_id'];
$warehouse_id=$_POST["warehouse_id"];
$tbl = _DB_PREFIX;

$total_pack_price = '';
$total_quantity = '';
$total_price = '';
$total_value_ins=0;
$total_value_up=0;
//$maxID = "PO-" . $db->Get_CustMaxID($tbl . 'product_purchase_order_request', 'purchase_order_id', '8', 'y');
$maxID=$_POST['purchase_order_id'];
$count = count($_POST['id']);

for ($i = 0; $i < $count; $i++) {
    if ($_POST['id'][$i] != "") {
        $total_value_ins=$_POST["price"][$i]*$_POST["quantity"][$i];
        $rowfield = array(
            'crop_id' => "'" . $_POST["crop_id"][$i] . "'",
            'purchase_order_date' => "'" . $db->date_formate($_POST["purchase_order_date"]) . "'",
            'warehouse_id' => "'" . $warehouse_id . "'",
            'product_type_id' => "'" . $_POST["product_type_id"][$i] . "'",
            'varriety_id' => "'" . $_POST["varriety_id"][$i] . "'",
            'pack_size' => "'" . $_POST["pack_size"][$i] . "'",
            'price' => "'" . $_POST["price"][$i] . "'",
            'quantity' => "'" . $_POST["quantity"][$i] . "'",
            'total_price' => "'" . $total_value_ins . "'",
            'status' => "'Edit_Ready'",
            'del_status' => "'0'",
            'entry_by' => "'$user_id'",
            'entry_date' => "'" . $db->ToDayDate() . "'"
        );
        $wherefield = array('id' => "'" . $_POST["id"][$i] . "'");
        $db->data_update($tbl . 'product_purchase_order_request', $rowfield, $wherefield);
        $db->system_event_log('', $user_id, $ei_id, $_POST['purchase_order_id'], $_POST["id"][$i], $tbl . 'product_purchase_order_request', 'Update', '');
    }
    else
    {
        $total_value_up=$_POST["price"][$i]*$_POST["quantity"][$i];

        $rowfield = array(
            'purchase_order_id,' => "'" . $_POST['purchase_order_id'] . "',",
            'purchase_order_date,' => "'" . $db->date_formate($_POST["purchase_order_date"]) . "',",
            'warehouse_id,' => "'" . $warehouse_id . "',",
            'zone_id,' => "'" . $_POST["zone_id"] . "',",
            'territory_id,' => "'" . $_POST["territory_id"] . "',",
            'distributor_id,' => "'" . $_POST["distributor_id"] . "',",
            'crop_id,' => "'" . $_POST["crop_id"][$i] . "',",
            'product_type_id,' => "'" . $_POST["product_type_id"][$i] . "',",
            'varriety_id,' => "'" . $_POST["varriety_id"][$i] . "',",
            'pack_size,' => "'" . $_POST["pack_size"][$i] . "',",
            'price,' => "'" . $_POST["price"][$i] . "',",
            'quantity,' => "'" . $_POST["quantity"][$i] . "',",
            'total_price,' => "'" . $total_value_up . "',",
            'status,' => "'Edit_Ready',",
            'del_status,' => "'0',",
            'entry_by,' => "'$user_id',",
            'entry_date' => "'" . $db->ToDayDate() . "'"
        );

        $db->data_insert($tbl . 'product_purchase_order_request', $rowfield);
        $db->system_event_log('', $user_id, $ei_id, $_POST['purchase_order_id'], '', $tbl . 'product_purchase_order_request', 'Save', '');
    }
}

$bonus_count = count($_POST['bonus_id']);
for ($i = 0; $i < $bonus_count; $i++) {
    if ($_POST['bonus_id'][$i] != "") {
        $rowfield = array(
            'invoice_date' => "'" . $db->date_formate($_POST["purchase_order_date"]) . "'",
            'warehouse_id' => "'" . $warehouse_id . "'",
            'zone_id' => "'" . $_POST["zone_id"] . "'",
            'territory_id' => "'" . $_POST["territory_id"] . "'",
            'distributor_id' => "'" . $_POST["distributor_id"] . "'",
            'crop_id' => "'" . $_POST["bonus_crop_id"][$i] . "'",
            'product_type_id' => "'" . $_POST["bonus_product_type_id"][$i] . "'",
            'varriety_id' => "'" . $_POST["bonus_varriety_id"][$i] . "'",
            'pack_size' => "'" . $_POST["bonus_pack_size"][$i] . "'",
            'quantity' => "'" . $_POST["bonus_quantity"][$i] . "'",
            'status' => "'Active'",
            'del_status' => "'0'",
            'entry_by' => "'$user_id'",
            'entry_date' => "'" . $db->ToDayDate() . "'"
        );
        $wherefield = array('id' => "'" . $_POST["bonus_id"][$i] . "'");
        $db->data_update($tbl . 'product_purchase_order_bonus', $rowfield, $wherefield);
        $db->system_event_log('', $user_id, $ei_id, $maxID, '', $tbl . 'product_purchase_order_bonus', 'Save', '');
    } else {
        $rowfield = array(
            'purchase_order_id,' => "'" . $maxID . "',",
            'invoice_date,' => "'" . $db->date_formate($_POST["purchase_order_date"]) . "',",
            'warehouse_id,' => "'" . $warehouse_id . "',",
            'zone_id,' => "'" . $_POST["zone_id"] . "',",
            'territory_id,' => "'" . $_POST["territory_id"] . "',",
            'distributor_id,' => "'" . $_POST["distributor_id"] . "',",
            'crop_id,' => "'" . $_POST["bonus_crop_id"][$i] . "',",
            'product_type_id,' => "'" . $_POST["bonus_product_type_id"][$i] . "',",
            'varriety_id,' => "'" . $_POST["bonus_varriety_id"][$i] . "',",
            'pack_size,' => "'" . $_POST["bonus_pack_size"][$i] . "',",
            'quantity,' => "'" . $_POST["bonus_quantity"][$i] . "',",
            'status,' => "'Active',",
            'del_status,' => "'0',",
            'entry_by,' => "'$user_id',",
            'entry_date' => "'" . $db->ToDayDate() . "'"
        );

        $db->data_insert($tbl . 'product_purchase_order_bonus', $rowfield);
        $db->system_event_log('', $user_id, $ei_id, $maxID, '', $tbl . 'product_purchase_order_bonus', 'Save', '');
    }
}
?>