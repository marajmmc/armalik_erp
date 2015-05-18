<?php

session_start();

require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$db = new Database();
$user_id = $_SESSION['user_id'];
$employee_id = $_SESSION['employee_id'];
$tbl = _DB_PREFIX;

$maxID = "DI-".$db->getMaxID_six_digit($tbl . 'division_info', 'division_id');
$rowfield = array(
    'division_id,' => "'$maxID',",
    'division_name,' => "'" . $_POST["division_name"] . "',",
    'description,' => "'" . $_POST["description"] . "',",
    'status,' => "'Active',",
    'del_status,' => "'0',",
    'entry_by,' => "'$user_id',",
    'entry_date' => "'" . $db->ToDayDate() . "'"
);

$db->data_insert($tbl . 'division_info', $rowfield);
$db->system_event_log('', $user_id, $employee_id, $maxID,'', $tbl . 'division_info', 'Save', '');
?>