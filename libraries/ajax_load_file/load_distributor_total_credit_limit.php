<?php
session_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$tbl = _DB_PREFIX;
$db = new Database();

$distributor_id = $_POST['distributor_id'];

$amount = "";
$blnc = $db->single_data($tbl . "distributor_credit_limit", "sum(credit_limit) as credit_limit", "distributor_id", $distributor_id);
$desblnc = $blnc['credit_limit'];
if ($desblnc == "") {
    $amount = "Credit Limit: 0.00";
} else {
    $amount = "Credit Limit: " . $desblnc;
}
echo $amount;
?>