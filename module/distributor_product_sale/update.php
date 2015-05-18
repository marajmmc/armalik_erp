<?php

session_start();

require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$db = new Database();
$user_id = $_SESSION['user_id'];
$ei_id = $_SESSION['employee_id'];
$tbl = _DB_PREFIX;

$total_pack_price = '';
$total_quantity = '';
$total_price = '';

//$maxID = "PO-" . $db->Get_CustMaxID($tbl . 'product_purchase_order_request', 'purchase_order_id', '8', 'y');

$count = count($_POST['id']);

for ($i = 0; $i < $count; $i++) {
    if ($_POST['id'][$i] != "") {
        $rowfield = array(
            'crop_id' => "'" . $_POST["crop_id"][$i] . "'",
            'varriety_id' => "'" . $_POST["varriety_id"][$i] . "'",
            'pack_size' => "'" . $_POST["pack_size"][$i] . "'",
            'price' => "'" . $_POST["price"][$i] . "'",
            'quantity' => "'" . $_POST["quantity"][$i] . "'",
            'total_price' => "'" . $_POST["total_price"][$i] . "'",
            'status' => "'Send'",
            'del_status' => "'0'",
            'entry_by' => "'$user_id'",
            'entry_date' => "'" . $db->ToDayDate() . "'"
        );
        $wherefield = array('id' => "'" . $_POST["id"][$i] . "'");
        $db->data_update($tbl . 'product_purchase_order_request', $rowfield, $wherefield);
        $db->system_event_log('', $user_id, $ei_id, $_POST['purchase_order_id'], $_POST["id"][$i], $tbl . 'product_purchase_order_request', 'Update', '');
    } else {
        $rowfield = array(
            'purchase_order_id,' => "'" . $_POST['purchase_order_id'] . "',",
            'purchase_order_date,' => "'" . $db->date_formate($_POST["purchase_order_date"]) . "',",
            'zone_id,' => "'" . $_POST["zone_id"] . "',",
            'territory_id,' => "'" . $_POST["territory_id"] . "',",
            'distributor_id,' => "'" . $_POST["distributor_id"] . "',",
            'crop_id,' => "'" . $_POST["crop_id"][$i] . "',",
            'varriety_id,' => "'" . $_POST["varriety_id"][$i] . "',",
            'pack_size,' => "'" . $_POST["pack_size"][$i] . "',",
            'price,' => "'" . $_POST["price"][$i] . "',",
            'quantity,' => "'" . $_POST["quantity"][$i] . "',",
            'total_price,' => "'" . $_POST["total_price"][$i] . "',",
            'status,' => "'Send',",
            'del_status,' => "'0',",
            'entry_by,' => "'$user_id',",
            'entry_date' => "'" . $db->ToDayDate() . "'"
        );

        $db->data_insert($tbl . 'product_purchase_order_request', $rowfield);
        $db->system_event_log('', $user_id, $ei_id, $_POST['purchase_order_id'], '', $tbl . 'product_purchase_order_request', 'Save', '');
    }
}
?>