<?php

session_start();

require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$db = new Database();
$user_id = $_SESSION['user_id'];
$tbl = _DB_PREFIX;

$maxID = "ED-".$db->getMaxID_six_digit($tbl . 'employee_designation', 'designation_id');
$rowfield = array(
    'designation_id,' => "'$maxID',",
    'designation_title_en,' => "'" . $_POST["designation_title_en"] . "',",
    'designation_title_bn,' => "'" . $_POST["designation_title_bn"] . "',",
    'status,' => "'Active',",
    'del_status,' => "'0',",
    'entry_by,' => "'$user_id',",
    'entry_date' => "'" . $db->ToDayDate() . "'"
);

$db->data_insert($tbl . 'employee_designation', $rowfield);
?>