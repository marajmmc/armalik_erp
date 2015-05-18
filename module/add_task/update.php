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
$incom_status = $_POST['incom_system'] . "~" . $_POST['incom_email'] . "~" . $_POST['incom_sms'];
$com_status = $_POST['com_system'] . "~" . $_POST['com_email'] . "~" . $_POST['com_sms'];

$rowfield = array(
    'employee_id' => "'" . $_POST["employee_id"] . "'",
    'task_name' => "'" . $_POST["task_name"] . "'",
    'start_date' => "'" . $db->date_formate($_POST["start_date"]) . "'",
    'end_date' => "'" . $db->date_formate($_POST["end_date"]) . "'",
    'task_description' => "'" . $_POST["task_description"] . "'",
    'incomplete_status' => "'" . $incom_status . "'",
    'complete_status' => "'" . $com_status . "'",
    'status' => "'" . $_POST['status'] . "'",
    'del_status' => "'0'",
    'entry_by' => "'$user_id'",
    'entry_date' => "'" . $db->ToDayDate() . "'"
);
$wherefield = array('task_plan_id' => "'" . $maxID . "'");
$db->data_update($tbl . 'task_management', $rowfield, $wherefield);
$db->system_event_log('', $user_id, $employee_id, $maxID, '', $tbl . 'task_management', 'Update', '');

//for ($i = 0; $i < $count; $i++) {
//    if ($_POST['id'][$i] != "") {
//        $rowfield = array(
//            'employee_id' => "'" . $_POST["employee_id"] . "'",
//            'task_name' => "'" . $_POST["task_name"] . "'",
//            'start_date' => "'" . $db->date_formate($_POST["start_date"]) . "'",
//            'end_date' => "'" . $db->date_formate($_POST["end_date"]) . "'",
//            'task_description' => "'" . $_POST["task_description"] . "'",
//            'incomplete_status' => "'" . $incom_status . "'",
//            'complete_status' => "'" . $com_status . "'",
//            'task_status' => "'" . $_POST["task_status"][$i] . "'",
//            'comment' => "'" . $_POST["comment"][$i] . "'",
//            'status' => "'Active'",
//            'del_status' => "'0'",
//            'entry_by' => "'$user_id'",
//            'entry_date' => "'" . $db->ToDayDate() . "'"
//        );
//        $wherefield = array('id' => "'" . $_POST["id"][$i] . "'");
//        $db->data_update($tbl . 'task_management', $rowfield, $wherefield);
//        $db->system_event_log('', $user_id, $employee_id, $_POST["id"][$i], $maxID, $tbl . 'task_management', 'Update', '');
//    } else {
//        $rowfield = array(
//            'task_plan_id,' => "'$maxID',",
//            'employee_id,' => "'" . $_POST["employee_id"] . "',",
//            'task_name,' => "'" . $_POST["task_name"] . "',",
//            'start_date,' => "'" . $db->date_formate($_POST["start_date"]) . "',",
//            'end_date,' => "'" . $db->date_formate($_POST["end_date"]) . "',",
//            'task_description,' => "'" . $_POST["task_description"] . "',",
//            'incomplete_status,' => "'" . $incom_status . "',",
//            'complete_status,' => "'" . $com_status . "',",
//            'task_status,' => "'" . $_POST["task_status"][$i] . "',",
//            'comment,' => "'" . $_POST["comment"][$i] . "',",
//            'status,' => "'Active',",
//            'del_status,' => "'0',",
//            'entry_by,' => "'$user_id',",
//            'entry_date' => "'" . $db->ToDayDate() . "'"
//        );
//
//        $db->data_insert($tbl . 'task_management', $rowfield);
//        $db->system_event_log('', $user_id, $employee_id, $maxID, '', $tbl . 'task_management', 'Save', '');
//    }
//}
?>