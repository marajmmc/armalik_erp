<?php

session_start();

require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$db = new Database();
$user_id = $_SESSION['user_id'];
$employee_id = $_SESSION['employee_id'];
$tbl = _DB_PREFIX;

$maxID = $_POST['rowID'];
$rowfield = array(
    'zone_id' => "'" . $_POST["zone_id"] . "'",
    'territory_id' => "'" . $_POST["territory_id"] . "'",
//    'distributor_id' => "'" . $_POST["distributor_id"] . "'",
//    'dealer_id' => "'" . $_POST["dealer_id"] . "'",
    'division_id' => "'" . $_POST["division_id"] . "'",
    'district_id' => "'" . $_POST["district_id"] . "'",
    'upazilla_id' => "'" . $_POST["upazilla_id"] . "'",
    'farmer_name' => "'" . $_POST["farmer_name"] . "'",
    'father_name' => "'" . $_POST["father_name"] . "'",
    'contact_no' => "'" . $_POST["contact_no"] . "'",
    'address' => "'" . $_POST["address"] . "'",
    'status' => "'" . $_POST["status"] . "'",
    'del_status' => "'0'",
    'entry_by' => "'$user_id'",
    'entry_date' => "'" . $db->ToDayDate() . "'"
);
$wherefield=array('farmer_id' => "'$maxID'");
$db->data_update($tbl . 'farmer_info', $rowfield, $wherefield);
$db->system_event_log('', $user_id, $employee_id, $maxID, '', $tbl . 'farmer_info', 'Update', '');
?>