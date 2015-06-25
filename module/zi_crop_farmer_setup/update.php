<?php

session_start();

require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$db = new Database();
$tbl = _DB_PREFIX;
$user_id = $_SESSION['user_id'];
$employee_id = $_SESSION['employee_id'];
$user_zone = $_SESSION['zone_id'];
$user_division_query = $db->single_data($tbl.'zone_info', 'division_id', 'zone_id', "$user_zone");
$user_division = $user_division_query['division_id'];


$division_id = $user_division;
$zone_id = $user_zone;
$territory_id = $_POST['territory_id'];
$district_id = $_POST['district_id'];
$upazilla_id = $_POST['upazilla_id'];
$crop_id = $_POST['crop_id'];
$type_id = $_POST['type_id'];
$variety_id = $_POST['variety_id'];
$farmers_name = $_POST['farmers_name'];
$farmers_address = $_POST['farmers_address'];
$farmers_contact = $_POST['farmers_contact'];

$data = array(
    'division_id' => "'$division_id'",
    'zone_id' => "'$zone_id'",
    'territory_id' => "'$territory_id'",
    'district_id' => "'$district_id'",
    'upazilla_id' => "'$upazilla_id'",
    'crop_id' => "'$crop_id'",
    'product_type_id' => "'$type_id'",
    'variety_id' => "'$variety_id'",
    'farmers_name' => "'$farmers_name'",
    'farmers_address' => "'$farmers_address'",
    'contact_no' => "'$farmers_contact'"
);

$maxID = $_POST['rowID'];

$whereField = array('id' => "'$maxID'");
$db->data_update($tbl . 'zi_crop_farmer_setup', $data, $whereField);
$db->system_event_log('', $user_id, $employee_id, $maxID, '', $tbl . 'zi_crop_farmer_setup', 'Update', '');

?>
