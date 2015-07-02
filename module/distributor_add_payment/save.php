<?php

session_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$db = new Database();
$user_id = $_SESSION['user_id'];
$employee_id = $_SESSION['employee_id'];
$tbl = _DB_PREFIX;
$total_price = $_POST["amount"];
$maxID = "AP-" . $db->getMaxID_six_digit($tbl . 'distributor_add_payment', 'payment_id');
$rowfield = array(
    'payment_id,' => "'$maxID',",
    'payment_date,' => "'" . $db->date_formate($_POST["payment_date"]) . "',",
    'zone_id,' => "'" . $_POST["zone_id"] . "',",
    'territory_id,' => "'" . $_POST["territory_id"] . "',",
    'distributor_id,' => "'" . $_POST["distributor_id"] . "',",
    'payment_type,' => "'" . $_POST["payment_type"] . "',",
    'amount,' => "'" . $total_price . "',",
    'cheque_no,' => "'" . $_POST["cheque_no"] . "',",
    'bank_id,' => "'" . $_POST["bank_id"] . "',",
    'armalik_bank_id,' => "'" . $_POST["armalik_bank_id"] . "',",
//    'status,' => "'Payment',",
    'status,' => "'Active',",
    'del_status,' => "'0',",
    'entry_by,' => "'$user_id',",
    'entry_date' => "'" . $db->ToDayDate() . "'"
);

$db->data_insert($tbl . 'distributor_add_payment', $rowfield);
$db->system_event_log('', $user_id, $employee_id, $maxID, '', $tbl . 'distributor_add_payment', 'Save', '');

///////////  START DISTRIBUTOR UPDATE ////////////////////

$dstbtor = $db->single_data($tbl . "distributor_balance", "count(distributor_id) as distributor_id", "distributor_id", $_POST["distributor_id"]);
if ($dstbtor['distributor_id'] != 0) {
    $rowfield = array(
        'payment_amount' => "payment_amount+'" . $total_price . "'",
        'balance_amount' => "balance_amount+'" . $total_price . "'",
        'due_amount' => "due_amount-'" . $total_price . "'",
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
        'payment_amount,' => "payment_amount+'" . $total_price . "',",
        'balance_amount,' => "balance_amount+'" . $total_price . "',",
        'due_amount,' => "due_amount-'" . $total_price . "',",
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