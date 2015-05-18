<?php

session_start();

require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$db = new Database();
$user_id = $_SESSION['user_id'];
$employee_id = $_SESSION['employee_id'];
$tbl = _DB_PREFIX;

$maxID = "VI-".$db->getMaxID_six_digit($tbl . 'varriety_info', 'varriety_id');
$rowfield = array(
    'varriety_id,' => "'$maxID',",
    'crop_id,' => "'" . $_POST["crop_id"] . "',",
    'product_type_id,' => "'" . $_POST["product_type_id"] . "',",
    'varriety_name,' => "'" . $_POST["varriety_name"] . "',",
    'description,' => "'" . $_POST["description"] . "',",
    'type,' => "'" . $_POST["type"] . "',",
    'hybrid,' => "'" . $_POST["hybrid"] . "',",
    'company_name,' => "'" . $_POST["company_name"] . "',",
    'order_variety,' => "'" . $_POST["order_variety"] . "',",
    'status,' => "'Active',",
    'del_status,' => "'0',",
    'entry_by,' => "'$user_id',",
    'entry_date' => "'" . $db->ToDayDate() . "'"
);

$db->data_insert($tbl . 'varriety_info', $rowfield);
$db->system_event_log('', $user_id, $employee_id, $maxID, '', $tbl . 'varriety_info', 'Save', '');
?>