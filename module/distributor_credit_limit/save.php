<?php

session_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$db = new Database();
$user_id = $_SESSION['user_id'];
$employee_id = $_SESSION['employee_id'];
$tbl = _DB_PREFIX;
$creditlimit = $_POST["credit_limit"];
$dueamount = $_POST["due_balance"];
$balance=$creditlimit-$dueamount;
$maxID = "CL-" . $db->getMaxID_six_digit($tbl . 'distributor_credit_limit', 'credit_limit_id');
$rowfield = array(
    'credit_limit_id,' => "'$maxID',",
    'zone_id,' => "'" . $_POST["zone_id"] . "',",
    'territory_id,' => "'" . $_POST["territory_id"] . "',",
    'distributor_id,' => "'" . $_POST["distributor_id"] . "',",
    'credit_limit,' => "'" . $_POST["credit_limit"] . "',",
    'check_no,' => "'" . $_POST["check_no"] . "',",
    'amount,' => "'" . $_POST["amount"] . "',",
    'bank_id,' => "'" . $_POST["bank_id"] . "',",
    'branch_id,' => "'" . $_POST["branch_id"] . "',",
    'comment,' => "'" . $_POST["comment"] . "',",
    'status,' => "'Active',",
    'del_status,' => "'0',",
    'entry_by,' => "'$user_id',",
    'entry_date' => "'" . $db->ToDayDate() . "'"
);

$db->data_insert($tbl . 'distributor_credit_limit', $rowfield);
$db->system_event_log('', $user_id, $employee_id, $maxID, '', $tbl . 'distributor_credit_limit', 'Save', '');

///////////  START DISTRIBUTOR UPDATE ////////////////////

$dstbtor = $db->single_data($tbl . "distributor_balance", "count(distributor_id) as distributor_id", "distributor_id", $_POST["distributor_id"]);

if ($dstbtor['distributor_id'] != 0) {
    $rowfield = array(
        'credit_limit_amount' => "credit_limit_amount+'" . $creditlimit . "'",
        'due_amount' => "'" . $creditlimit . "'-due_amount",
        'balance_amount' => "balance_amount+'" . $balance . "'",
        'status' => "'Active'",
        'del_status' => "'0'",
        'entry_by' => "'$user_id'",
        'entry_date' => "'" . $db->ToDayDate() . "'"
    );
    $wherefield = array('distributor_id' => "'" . $_POST["distributor_id"] . "'");
    $db->data_update($tbl . 'distributor_balance', $rowfield, $wherefield);
    $db->system_event_log('', $user_id, $employee_id, '', '', $tbl . 'distributor_balance', 'Update', '');
} else {
    $rowfield = array(
        'distributor_id,' => "'" . $_POST["distributor_id"] . "',",
        'credit_limit_amount,' => "credit_limit_amount+'" . $creditlimit . "',",
        'due_amount,' => "'" . $creditlimit . "'-due_amount,",
        'balance_amount,' => "balance_amount+'" . $balance . "',",
        'status,' => "'Active',",
        'del_status,' => "'0',",
        'entry_by,' => "'$user_id',",
        'entry_date' => "'" . $db->ToDayDate() . "'"
    );
    $db->data_insert($tbl . 'distributor_balance', $rowfield);
    $db->system_event_log('', $user_id, $employee_id, '', '', $tbl . 'distributor_balance', 'Save', '');
}

///////////  END DISTRIBUTOR UPDATE ////////////////////
?>