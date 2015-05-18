<?php

session_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$tbl = _DB_PREFIX;
$db = new Database();

//$distributor_id = $_POST['distributor_id'];
//
//$amount = "";
//$blnc = $db->single_data($tbl . "distributor_balance", "due_amount", "distributor_id", $distributor_id);
//$desblnc = $blnc['due_amount'];
//if ($desblnc == "") {
//    $amount = "Due: 0.00";
//} else {
//    $amount = "Due: ".$desblnc;
//}
//echo $amount;

$amount = "";
if ($_POST['distributor_id'] != "")
{
    $distributor_id = "AND $tbl" . "distributor_balance.distributor_id='" . $_POST['distributor_id'] . "'";
    $sql = "SELECT
            CONCAT_WS(' - ', $tbl" . "distributor_info.distributor_id, $tbl" . "distributor_info.customer_code, $tbl" . "distributor_info.distributor_name) AS distributor_name,
            $tbl" . "distributor_balance.id,
            $tbl" . "distributor_balance.distributor_id,
            $tbl" . "distributor_info.due_balance,
            (SELECT Sum($tbl" . "product_purchase_order_invoice.total_price) FROM $tbl" . "product_purchase_order_invoice WHERE $tbl" . "product_purchase_order_invoice.read_status='0' AND
            $tbl" . "product_purchase_order_invoice.`status`='Delivery' AND
            $tbl" . "product_purchase_order_invoice.del_status='0' AND
            $tbl" . "product_purchase_order_invoice.distributor_id = $tbl" . "distributor_balance.distributor_id) AS sales_purchase_amt,
            (SELECT Sum($tbl" . "distributor_add_payment.amount) FROM $tbl" . "distributor_add_payment WHERE $tbl" . "distributor_add_payment.`status`='Active' AND $tbl" . "distributor_add_payment.distributor_id = $tbl" . "distributor_balance.distributor_id) AS total_paid_amt
        FROM $tbl" . "distributor_balance
            LEFT JOIN $tbl" . "distributor_info ON $tbl" . "distributor_info.distributor_id = $tbl" . "distributor_balance.distributor_id
        WHERE $tbl" . "distributor_info.status='Active' AND $tbl" . "distributor_balance.del_status='0'
            $distributor_id
        GROUP BY $tbl" . "distributor_balance.distributor_id
            ";
    if($db->open())
    {
        $result=$db->query($sql);
        $result_array=$db->fetchArray($result);
        $balance = ($result_array['due_balance'] + $result_array['sales_purchase_amt']) - $result_array['total_paid_amt'];
        if ($balance == "")
        {
            $amount = "Due: 0.00";
        }
        else
        {
            $amount = "Due: ".$balance;
        }
        echo $amount;
    }
}
else
{
    $distributor_id = "";
    echo $amount = "Due: 0.00";
}

?>