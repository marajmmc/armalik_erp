<?php

session_start();

require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$db = new Database();
$user_id = $_SESSION['user_id'];
$ei_id = $_SESSION['ei_id'];
$tbl = _DB_PREFIX;

$maxID = "FY-".$db->getMaxID_six_digit($tbl . 'year', 'year_id');
$rowfield = array(
    'year_id,' => "'$maxID',",
    'year_name,' => "'" . $_POST["year_name"] . "',",
    'description,' => "'" . $_POST["description"] . "',",
    'status,' => "'Active',",
    'del_status,' => "'0',",
    'entry_by,' => "'$user_id',",
    'entry_date' => "'" . $db->ToDayDate() . "'"
);

$db->data_insert($tbl . 'year', $rowfield);
$db->system_event_log('', $user_id, $ei_id, $maxID, '', $tbl . 'year', 'Save', '');
?>