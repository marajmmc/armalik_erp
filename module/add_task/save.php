<?php

session_start();

require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$db = new Database();
$user_id = $_SESSION['user_id'];
$employee_id = $_SESSION['employee_id'];
$tbl = _DB_PREFIX;
$incom_status = $_POST['incom_system'] . "~" . $_POST['incom_email'] . "~" . $_POST['incom_sms'];
$com_status = $_POST['com_system'] . "~" . $_POST['com_email'] . "~" . $_POST['com_sms'];
$maxID = "TM-" . $db->getMaxID_six_digit($tbl . 'task_management', 'task_plan_id');

$rowfield = array(
    'task_plan_id,' => "'$maxID',",
    'employee_id,' => "'" . $_POST["employee_id"] . "',",
    'task_name,' => "'" . $_POST["task_name"] . "',",
    'start_date,' => "'" . $db->date_formate($_POST["start_date"]) . "',",
    'end_date,' => "'" . $db->date_formate($_POST["end_date"]) . "',",
    'task_description,' => "'" . $_POST["task_description"] . "',",
    'incomplete_status,' => "'" . $incom_status . "',",
    'complete_status,' => "'" . $com_status . "',",
    'status,' => "'" . $_POST['status'] . "',",
    'del_status,' => "'0',",
    'entry_by,' => "'$user_id',",
    'entry_date' => "'" . $db->ToDayDate() . "'"
);

echo $db->data_insert($tbl . 'task_management', $rowfield);
$db->system_event_log('', $user_id, $employee_id, '', $maxID, $tbl . 'task_management', 'Save', '');
?>