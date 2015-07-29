<?php

session_start();

require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$db = new Database();
$user_id = $_SESSION['user_id'];
$employee_id = $_SESSION['employee_id'];
$tbl = _DB_PREFIX;

$maxID = "OC-" . $db->getMaxID_six_digit($tbl . 'other_distributor_info', 'distributor_id');

$rowfield = array
(
    'distributor_id,' => "'$maxID',",
    'zone_id,' => "'" . $_POST["zone_id"] . "',",
    'territory_id,' => "'" . $_POST["territory_id"] . "',",
    'zilla_id,' => "'" . $_POST["zilla_id"] . "',",
    'distributor_name,' => "'" . stripQuotes(removeBadChars($_POST["distributor_name"])) . "',",
    'customer_code,' => "'" . stripQuotes(removeBadChars($_POST["customer_code"])) . "',",
    'owner_name,' => "'" . stripQuotes(removeBadChars($_POST["owner_name"])) . "',",
    'address,' => "'" . stripQuotes(removeBadChars($_POST["address"])) . "',",
    'phone,' => "'" . stripQuotes(removeBadChars($_POST["phone"])) . "',",
    'email,' => "'" . stripQuotes(removeBadChars($_POST["email"])) . "',",
    'remark,' => "'" . stripQuotes(removeBadChars($_POST["remark"])) . "',",
    'status,' => "'Active',",
    'del_status,' => "'0',",
    'entry_by,' => "'$user_id',",
    'entry_date' => "'" . $db->ToDayDate() . "'"
);

$db->data_insert($tbl . 'other_distributor_info', $rowfield);
$db->system_event_log('', $user_id, $employee_id, $maxID, '', $tbl . 'other_distributor_info', 'Save', '');

?>