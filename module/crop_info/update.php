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
    'crop_name' => "'" . $_POST["crop_name"] . "'",
    'description' => "'" . $_POST["description"] . "'",
    'order_crop' => "'" . $_POST["order_crop"] . "'",
    'status' => "'" . $_POST["status"] . "'",
    'del_status' => "'0'",
    'entry_by' => "'$user_id'",
    'entry_date' => "'" . $db->ToDayDate() . "'"
);
$wherefield=array('crop_id' => "'$maxID'");
$db->data_update($tbl . 'crop_info', $rowfield, $wherefield);
$db->system_event_log('', $user_id, $employee_id, $maxID, '', $tbl . 'crop_info', 'Update', '');

$rowfield = array(
    'crop_id,' => "'$maxID',",
    'crop_name,' => "'" . $_POST["crop_name"] . "',",
    'channel,' => "'Crop',",
    'status,' => "'" . $_POST["status"] . "',",
    'del_status,' => "'0',",
    'entry_by,' => "'$user_id',",
    'entry_date' => "'" . $db->ToDayDate() . "'"
);

$db->data_insert($tbl . 'product_name_change_history', $rowfield);
$db->system_event_log('', $user_id, $employee_id, $maxID, '', $tbl . 'product_name_change_history', 'Insert', '');
?>