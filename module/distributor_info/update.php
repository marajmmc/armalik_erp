<?php

session_start();

require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$db = new Database();
$user_id = $_SESSION['user_id'];
$employee_id = $_SESSION['employee_id'];
$tbl = _DB_PREFIX;
$due_amount = ($_POST["due_balance"] - $_POST['before_due_balance']);
$maxID = $_POST['rowID'];
$rowfield = array(
    'zone_id' => "'" . $_POST["zone_id"] . "'",
    'territory_id' => "'" . $_POST["territory_id"] . "'",
    'distributor_name' => "'" . stripQuotes(removeBadChars($_POST["distributor_name"])) . "'",
    'customer_code' => "'" . stripQuotes(removeBadChars($_POST["customer_code"])) . "'",
    'owner_name' => "'" . stripQuotes(removeBadChars($_POST["owner_name"])) . "'",
    'due_balance' => "due_balance+'" . $due_amount . "'",
    'address' => "'" . stripQuotes(removeBadChars($_POST["address"])) . "'",
    'phone' => "'" . stripQuotes(removeBadChars($_POST["phone"])) . "'",
    'email' => "'" . stripQuotes(removeBadChars($_POST["email"])) . "'",
    'agreement_status' => "'" . stripQuotes(removeBadChars($_POST["agreement_status"])) . "'",
    'remark' => "'" . stripQuotes(removeBadChars($_POST["remark"])) . "'",
    'status' => "'" . $_POST["status"] . "'",
    'del_status' => "'0'",
    'entry_by' => "'$user_id'",
    'entry_date' => "'" . $db->ToDayDate() . "'"
);
$wherefield = array('distributor_id' => "'$maxID'");
$db->data_update($tbl . 'distributor_info', $rowfield, $wherefield);
$db->system_event_log('', $user_id, $employee_id, $maxID, '', $tbl . 'distributor_info', 'Update', '');

$dis = $db->single_data($tbl . "distributor_balance", "distributor_id", "distributor_id", $maxID);

if ($dis['distributor_id'] == "") {
    $rowfield = array(
        'distributor_id,' => "'" . $maxID . "',",
        'due_amount,' => "due_amount+'" . $due_amount . "',",
        'status,' => "'Active',",
        'del_status,' => "'0',",
        'entry_by,' => "'$user_id',",
        'entry_date' => "'" . $db->ToDayDate() . "'"
    );
    $db->data_insert($tbl . 'distributor_balance', $rowfield);
    $db->system_event_log('', $user_id, $employee_id, '', '', $tbl . 'distributor_balance', 'Save', '');
} else {
    $rowfield = array(
        'due_amount' => "due_amount+'" . $due_amount . "'",
        'status' => "'Active'",
        'del_status' => "'0'",
        'entry_by' => "'$user_id'",
        'entry_date' => "'" . $db->ToDayDate() . "'"
    );
    $wherefield = array('distributor_id' => "'" . $maxID . "'");
    $db->data_update($tbl . 'distributor_balance', $rowfield, $wherefield);
    $db->system_event_log('', $user_id, $employee_id, '', '', $tbl . 'distributor_balance', 'Save', '');
}
?>