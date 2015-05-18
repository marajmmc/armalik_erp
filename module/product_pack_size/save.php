<?php

session_start();

require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$db = new Database();
$user_id = $_SESSION['user_id'];
$ei_id = $_SESSION['ei_id'];
$tbl = _DB_PREFIX;

$maxID = "PS-".$db->getMaxID_six_digit($tbl . 'product_pack_size', 'pack_size_id');
$rowfield = array(
    'pack_size_id,' => "'$maxID',",
    'pack_size_name,' => "'" . $_POST["pack_size_name"] . "',",
    'description,' => "'" . $_POST["description"] . "',",
    'status,' => "'Active',",
    'del_status,' => "'0',",
    'entry_by,' => "'$user_id',",
    'entry_date' => "'" . $db->ToDayDate() . "'"
);

$db->data_insert($tbl . 'product_pack_size', $rowfield);
$db->system_event_log('', $user_id, $ei_id, $maxID, '', $tbl . 'product_pack_size', 'Save', '');
?>