<?php

session_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$db = new Database();
$user_id = $_SESSION['user_id'];
$employee_id = $_SESSION['employee_id'];
$tbl = _DB_PREFIX;

$maxID = "DE-" . $db->getMaxID_six_digit($tbl . 'farmer_info_old', 'farmer_id');
$rowfield = array(
    'farmer_id,' => "'$maxID',",
    'zone_id,' => "'" . $_POST["zone_id"] . "',",
    'territory_id,' => "'" . $_POST["territory_id"] . "',",
    'distributor_id,' => "'" . $_POST["distributor_id"] . "',",
    'dealer_id,' => "'" . $_POST["dealer_id"] . "',",
    'division_id,' => "'" . $_POST["division_id"] . "',",
    'district_id,' => "'" . $_POST["district_id"] . "',",
    'upazilla_id,' => "'" . $_POST["upazilla_id"] . "',",
    'farmer_name,' => "'" . $_POST["farmer_name"] . "',",
    'father_name,' => "'" . $_POST["father_name"] . "',",
    'contact_no,' => "'" . $_POST["contact_no"] . "',",
    'address,' => "'" . $_POST["address"] . "',",
    'status,' => "'Active',",
    'del_status,' => "'0',",
    'entry_by,' => "'$user_id',",
    'entry_date' => "'" . $db->ToDayDate() . "'"
);

$db->data_insert($tbl . 'farmer_info_old', $rowfield);
$db->system_event_log('', $user_id, $employee_id, $maxID, '', $tbl . 'farmer_info_old', 'Save', '');
?>