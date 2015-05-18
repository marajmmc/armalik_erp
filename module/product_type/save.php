<?php

session_start();

require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$db = new Database();
$user_id = $_SESSION['user_id'];
$employee_id = $_SESSION['employee_id'];
$tbl = _DB_PREFIX;

$maxID = "PT-".$db->getMaxID_six_digit($tbl . 'product_type', 'product_type_id');
$rowfield = array(
    'product_type_id,' => "'$maxID',",
    'crop_id,' => "'" . $_POST["crop_id"] . "',",
    'product_type,' => "'" . $_POST["product_type"] . "',",
    'description,' => "'" . $_POST["description"] . "',",
    'order_type,' => "'" . $_POST["order_type"] . "',",
    'status,' => "'Active',",
    'del_status,' => "'0',",
    'entry_by,' => "'$user_id',",
    'entry_date' => "'" . $db->ToDayDate() . "'"
);

$db->data_insert($tbl . 'product_type', $rowfield);
$db->system_event_log('', $user_id, $employee_id, $maxID, '', $tbl . 'product_type', 'Save', '');
?>