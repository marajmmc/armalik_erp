<?php
session_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$db = new Database();
$user_id = $_SESSION['user_id'];
$employee_id = $_SESSION['employee_id'];
$tbl = _DB_PREFIX;

$maxID = "DE-" . $db->getMaxID_six_digit($tbl . 'dealer_info', 'dealer_id');
$rowfield = array(
    'dealer_id,' => "'$maxID',",
    'zone_id,' => "'" . $_POST["zone_id"] . "',",
    'territory_id,' => "'" . $_POST["territory_id"] . "',",
    'distributor_id,' => "'" . $_POST["distributor_id"] . "',",
    'dealer_name,' => "'" . $_POST["dealer_name"] . "',",
    'owner_name,' => "'" . $_POST["owner_name"] . "',",
    'market_name,' => "'" . $_POST["market_name"] . "',",
    'address,' => "'" . $_POST["address"] . "',",
    'phone_no,' => "'" . $_POST["phone_no"] . "',",
    'email,' => "'" . $_POST["email"] . "',",
    'status,' => "'Active',",
    'del_status,' => "'0',",
    'entry_by,' => "'$user_id',",
    'entry_date' => "'" . $db->ToDayDate() . "'"
);

$db->data_insert($tbl . 'dealer_info', $rowfield);
$db->system_event_log('', $user_id, $employee_id, $maxID, '', $tbl . 'dealer_info', 'Save', '');
?>