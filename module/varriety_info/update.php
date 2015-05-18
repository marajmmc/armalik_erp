<?php

session_start();

require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$db = new Database();
$user_id = $_SESSION['user_id'];
$employee_id = $_SESSION['employee_id'];
$tbl = _DB_PREFIX;

$maxID = $_POST['rowID'];
$rowfield = array(
//    'crop_id' => "'" . $_POST["crop_id"] . "'",
//    'product_type_id' => "'" . $_POST["product_type_id"] . "'",
    'varriety_name' => "'" . $_POST["varriety_name"] . "'",
    'company_name' => "'" . $_POST["company_name"] . "'",
    'order_variety' => "'" . $_POST["order_variety"] . "'",
    'description' => "'" . $_POST["description"] . "'",
    'status' => "'" . $_POST["status"] . "'",
    'del_status' => "'0'",
    'entry_by' => "'$user_id'",
    'entry_date' => "'" . $db->ToDayDate() . "'"
);
$wherefield=array('varriety_id' => "'$maxID'");
$db->data_update($tbl . 'varriety_info', $rowfield, $wherefield);
$db->system_event_log('', $user_id, $employee_id, $maxID, '', $tbl . 'varriety_info', 'Update', '');

$rowfield = array(
    'varriety_id,' => "'$maxID',",
    //'product_type_id,' => "'" . $_POST["product_type_id"] . "',",
    //'crop_id,' => "'" . $_POST["crop_id"] . "',",
    'varriety_name,' => "'" . $_POST["varriety_name"] . "',",
    'channel,' => "'Variety',",
    'status,' => "'" . $_POST["status"] . "',",
    'del_status,' => "'0',",
    'entry_by,' => "'$user_id',",
    'entry_date' => "'" . $db->ToDayDate() . "'"
);

$db->data_insert($tbl . 'product_name_change_history', $rowfield);
$db->system_event_log('', $user_id, $employee_id, $maxID, '', $tbl . 'product_name_change_history', 'Insert', '');
?>