<?php

session_start();

require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$db = new Database();
$user_id = $_SESSION['user_id'];
$employee_id = $_SESSION['employee_id'];
$tbl = _DB_PREFIX;

$maxID = "CI-".$db->getMaxID_six_digit($tbl . 'crop_info', 'crop_id');
$rowfield = array(
    'crop_id,' => "'$maxID',",
    'crop_name,' => "'" . $_POST["crop_name"] . "',",
    'description,' => "'" . $_POST["description"] . "',",
    'order_crop,' => "'" . $_POST["order_crop"] . "',",
    'status,' => "'Active',",
    'del_status,' => "'0',",
    'entry_by,' => "'$user_id',",
    'entry_date' => "'" . $db->ToDayDate() . "'"
);

$db->data_insert($tbl . 'crop_info', $rowfield);
$db->system_event_log('', $user_id, $employee_id, $maxID, '', $tbl . 'crop_info', 'Save', '');
?>