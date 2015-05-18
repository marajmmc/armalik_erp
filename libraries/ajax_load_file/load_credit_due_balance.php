<?php

require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$tbl = _DB_PREFIX;
$db = new Database();

$distributor_id = $_POST['distributor_id'];
$blnc = $db->single_data($tbl . "distributor_balance", "SUM(credit_limit_amount) AS credit_limit_amount, SUM(due_amount) AS due_amount", "distributor_id", $distributor_id);
if ($blnc['credit_limit_amount'] == "" || $blnc['credit_limit_amount'] == "0" || $blnc['credit_limit_amount'] == "0.00") {
    $descrdl = '0.00';
} else {
    $descrdl = $blnc['credit_limit_amount'];
}
if ($blnc['due_amount'] == "" || $blnc['due_amount'] == "0" || $blnc['due_amount'] == "0.00") {
    $desblnc = "0.00";
} else {
    $desblnc = $blnc['due_amount'];
}

$cl = $db->single_data_w($tbl . "distributor_credit_limit", "SUM(credit_limit) AS credit_limit", "status='Active' AND del_status='0' AND distributor_id='$distributor_id'");
if ($cl['credit_limit'] == "" || $cl['credit_limit'] == "0" || $cl['credit_limit'] == "0.00") {
    $clvalue = "0.00";
} else {
    $clvalue = $cl['credit_limit'];
}

$ob = $db->single_data_w($tbl . "distributor_info", "due_balance", "status='Active' AND del_status='0' AND distributor_id='$distributor_id'");
if ($ob['due_balance'] == "" || $ob['due_balance'] == "0" || $ob['due_balance'] == "0.00") {
    $obvalue = "0.00";
} else {
    $obvalue = $ob['due_balance'];
}
echo $descrdl . "~" . $desblnc . "~" . $clvalue . "~" . $obvalue;
?>