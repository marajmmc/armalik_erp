<?php
session_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$tbl = _DB_PREFIX;
$db = new Database();

$distributor_id = $_POST['distributor_id'];

$amount = "";
$blnc = $db->single_data($tbl . "distributor_balance", "credit_limit_amount", "distributor_id", $distributor_id);
$desblnc = $blnc['credit_limit_amount'];
if ($desblnc == "") {
    $amount = "Total Purchase Value: 0.00";
} else {
    $amount = "Total Purchase Value: " . $desblnc;
}
echo $amount;
?>