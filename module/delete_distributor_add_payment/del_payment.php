<?php

session_start();
ob_start();
if ($_SESSION['logged'] != 'yes') {
    $_REQUEST["msg"] = "TimeoutC";
    header("location:../../index.php");
}
//echo $_SESSION['shop_name_eng']; 
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$rowID = $_POST['rowID'];
$tbl = _DB_PREFIX;
$db = new Database();
$user_id = $_SESSION['user_id'];
$employee_id = $_SESSION['employee_id'];


$pay = $db->single_data($tbl . "distributor_add_payment", "*", "id", $rowID);
echo $pay['amount'];

$rowfield = array(
    'payment_amount' => "payment_amount-'" . $pay['amount'] . "'",
    'balance_amount' => "balance_amount-'" . $pay['amount'] . "'",
    'due_amount' => "due_amount+'" . $pay['amount'] . "'",
    'status' => "'Active'",
    'del_status' => "'0'",
    'entry_by' => "'$user_id'",
    'entry_date' => "'" . $db->ToDayDate() . "'"
);
$wherefield = array('distributor_id' => "'" . $pay["distributor_id"] . "'");
$db->data_update($tbl . 'distributor_balance', $rowfield, $wherefield);
$db->system_event_log('', $user_id, $employee_id, '', '', $tbl . 'distributor_balance', 'Update', '');

$sql = "INSERT INTO $tbl" . "distributor_add_payment_delete (
    SELECT
        '',
        $tbl" . "distributor_add_payment.payment_id,
        $tbl" . "distributor_add_payment.payment_date,
        $tbl" . "distributor_add_payment.zone_id,
        $tbl" . "distributor_add_payment.territory_id,
        $tbl" . "distributor_add_payment.distributor_id,
        $tbl" . "distributor_add_payment.payment_type,
        $tbl" . "distributor_add_payment.amount,
        $tbl" . "distributor_add_payment.cheque_no,
        $tbl" . "distributor_add_payment.bank_id,
        $tbl" . "distributor_add_payment.`status`,
        $tbl" . "distributor_add_payment.del_status,
        $tbl" . "distributor_add_payment.entry_by,
        $tbl" . "distributor_add_payment.entry_date
    FROM `$tbl" . "distributor_add_payment`
    WHERE id='$rowID')";

if ($db->open()) {
    $db->query($sql);
}

$delsql = "DELETE FROM $tbl" . "distributor_add_payment WHERE id='$rowID'";
if ($db->open()) {
    $db->query($delsql);
}
?>