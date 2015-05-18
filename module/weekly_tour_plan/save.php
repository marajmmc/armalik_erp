<?php
session_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$db = new Database();
$user_id = $_SESSION['user_id'];
$employee_id = $_SESSION['employee_id'];
$tbl = _DB_PREFIX;

$maxID = "WP-" . $db->getMaxID_six_digit($tbl . 'weekly_tour_plan', 'week_plan_id');

$count = count($_POST['id']);
for ($i = 0; $i < $count; $i++) {
    $rowfield = array(
        'week_plan_id,' => "'$maxID',",
        'zone_id,' => "'" . $_POST["zone_id"] . "',",
//        'territory_id,' => "'" . $_POST["territory_id"] . "',",
        'employee_id,' => "'" . $_POST["employee_id"] . "',",
        'month_id,' => "'" . $_POST["month_id"] . "',",
        'week_id,' => "'" . $_POST["week_id"] . "',",
        'location,' => "'" . $_POST["location"][$i] . "',",
        'visit_purpose,' => "'" . $_POST["visit_purpose"][$i] . "',",
        'plan_date,' => "'" . $db->date_formate($_POST["plan_date"][$i]) . "',",
        'status,' => "'Active',",
        'del_status,' => "'0',",
        'entry_by,' => "'$user_id',",
        'entry_date' => "'" . $db->ToDayDate() . "'"
    );

    echo $db->data_insert($tbl . 'weekly_tour_plan', $rowfield);
    $db->system_event_log('', $user_id, $employee_id, '', $maxID, $tbl . 'weekly_tour_plan', 'Save', '');
}
?>