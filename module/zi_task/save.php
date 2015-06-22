<?php

session_start();

require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$db = new Database();
$user_id = $_SESSION['user_id'];
$employee_id = $_SESSION['employee_id'];
$user_zone = $_SESSION['zone_id'];
$tbl = _DB_PREFIX;

print_r($_POST);

$territory_id = $_POST['territory_id'];
$district_id = $_POST['district_id'];
$distributor_id = $_POST['distributor_id'];
$purchase_order = $_POST['purchase_order'];
$collection = $_POST['collection'];
$entry_date = $_POST['entry_date'];
$activities = $_POST['activities'];
$problem = $_POST['problem'];
$recommendation = $_POST['recommendation'];
$solution = $_POST['solution'];


if (@$_FILES["activities_file"]['name'] != "") {
    $ext = end(explode(".", @$_FILES["activities_file"]['name']));
    $activities_image_url = time() . "." . $ext;
    copy(@$_FILES['activities_file']['tmp_name'], "images/zi_task/$activities_image_url");
}

if (@$_FILES["problem_file"]['name'] != "") {
    $ext = end(explode(".", @$_FILES["problem_file"]['name']));
    $problem_image_url = time() . "." . $ext;
    copy(@$_FILES['problem_file']['tmp_name'], "images/zi_task/$problem_image_url");
}

$data = array(
    'zone_id,' => "'$user_zone',",
    'territory_id,' => "'$territory_id',",
    'district_id,' => "'$district_id',",
    'distributor_id,' => "'$distributor_id',",
    'purchase_order,' => "'$purchase_order',",
    'collection,' => "'$collection',",
    'date,' => "'$entry_date',",
    'activities,' => "'$activities',",
    'activities_image,' => "'$activities',",
    'problem,' => "'$problem',",
    'problem_image,' => "'$problem_image_url',",
    'recommendation,' => "'$recommendation',",
    'solution,' => "'$solution',",
    'entry_by,' => "'$user_id',",
    'entry_date' => "'" . $db->ToDayDate() . "'"
);

$db->data_insert($tbl . 'zi_task', $data);
$db->system_event_log('', $user_id, $employee_id, '', '', $tbl . 'zi_task', 'Save', '');
?>