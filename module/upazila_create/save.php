<?php

session_start();

require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$db = new Database();
$user_id = $_SESSION['user_id'];
$ei_id = $_SESSION['ei_id'];
$tbl = _DB_PREFIX;

//$maxID = "ED-".$db->getMaxID_six_digit($tbl . 'ait_upazilla', 'upazilaid');
$rowfield = array
(
    'zilla_id,' => "'" . $_POST["zilla_id"] . "',",
    'upazilla_name,' => "'" . $_POST["upazilla_name"] . "',",
    'status,' => "'Active',",
    'del_status,' => "'0',",
    'entry_by,' => "'$user_id',",
    'entry_date' => "'" . $db->ToDayDate() . "'",
);

$db->data_insert($tbl . 'upazilla_new', $rowfield);
$db->system_event_log('', $user_id, $ei_id, $maxID, '', $tbl . 'upazilla_new', 'Save', '');
?>