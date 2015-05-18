<?php

session_start();

require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$db = new Database();
$user_id = $_SESSION['user_id'];
$employee_id = $_SESSION['employee_id'];
$tbl = _DB_PREFIX;

$maxID = "ZI-".$db->getMaxID_six_digit($tbl . 'zone_info', 'zone_id');
$rowfield = array(
    'zone_id,' => "'$maxID',",
    'zone_name,' => "'" . $_POST["zone_name"] . "',",
    'description,' => "'" . $_POST["description"] . "',",
    'status,' => "'Active',",
    'del_status,' => "'0',",
    'entry_by,' => "'$user_id',",
    'entry_date' => "'" . $db->ToDayDate() . "'"
);

$db->data_insert($tbl . 'zone_info', $rowfield);
$db->system_event_log('', $user_id, $employee_id, $maxID,'', $tbl . 'zone_info', 'Save', '');
?>