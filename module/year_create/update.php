<?php

session_start();

require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$db = new Database();
$user_id = $_SESSION['user_id'];
$ei_id = $_SESSION['ei_id'];
$tbl = _DB_PREFIX;

$maxID = $_POST['rowID'];
$rowfield = array(
    'year_name' => "'" . $_POST["year_name"] . "'",
    'description' => "'" . $_POST["description"] . "'",
    'status' => "'" . $_POST["status"] . "'",
    'del_status' => "'0'",
    'entry_by' => "'$user_id'",
    'entry_date' => "'" . $db->ToDayDate() . "'"
);
$wherefield=array('year_id' => "'$maxID'");
$db->data_update($tbl . 'year', $rowfield, $wherefield);
$db->system_event_log('', $user_id, $ei_id, $maxID, '', $tbl . 'year', 'Update', '');
?>