<?php

session_start();
if ($_SESSION['logged'] != 'yes') {
    $_REQUEST["msg"] = "TimeoutC";
    header("location:../../index.php");
}
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$db = new Database();
$dbc = new Database();
$dbins = new Database();
$user_id = $_SESSION['user_id'];
$employee_id = $_SESSION['employee_id'];
$tbl = _DB_PREFIX;

echo $sqlchallan = "SELECT
                challan_id,
                warehouse_id,
                invoice_id,
                purchase_order_id,
                zone_id,
                territory_id,
                distributor_id,
                crop_id,
                product_type_id,
                varriety_id,
                pack_size,
                price,
                quantity,
                total_price
            FROM `$tbl" . "product_purchase_order_challan`
            WHERE status='Pending' AND del_status='0' AND invoice_id='" . $_POST['inv_id'] . "'";
if ($dbc->open()) {
    $resultc = $dbc->query($sqlchallan);
    while ($rowc = $dbc->fetchAssoc($resultc)) {

        echo $mSQL_task = "update `$tbl" . "product_stock` set
                                `delivery_quantity`=delivery_quantity-'" . $rowc["quantity"] . "', 
				`current_stock_qunatity`=current_stock_qunatity+'" . $rowc["quantity"] . "', 
				`status`='Active', 
				`del_status`='0', 
				`entry_by`='" . $user_id . "', 
				`entry_date`='" . $db->ToDayDate() . "'
			where crop_id='" . $rowc['crop_id'] . "' AND product_type_id='" . $rowc['product_type_id'] . "' AND varriety_id='" . $rowc['varriety_id'] . "' AND pack_size='" . $rowc['pack_size'] . "' AND warehouse_id='" . $rowc['warehouse_id'] . "'";

        if ($db->open()) {
            $db->query($mSQL_task);
            $db->freeResult();
        }
        $db->system_event_log('', $user_id, $employee_id, $_POST['inv_id'], '', $tbl . 'product_stock', 'Update', '');

        $rowfield = array(
            'credit_limit_amount' => "credit_limit_amount+'" . $rowc['total_price'] . "'",
            'balance_amount' => "balance_amount+'" . $rowc['total_price'] . "'",
            'due_amount' => "due_amount-'" . $rowc['total_price'] . "'",
            'status' => "'Active'",
            'del_status' => "'0'",
            'entry_by' => "'$user_id'",
            'entry_date' => "'" . $db->ToDayDate() . "'"
        );
        $wherefield = array('distributor_id' => "'" . $rowc["distributor_id"] . "'");
        $db->data_update($tbl . 'distributor_balance', $rowfield, $wherefield);
        $db->system_event_log('', $user_id, $employee_id, $_POST['inv_id'], '', $tbl . 'distributor_balance', 'Update', '');
    }
}

$sqlchallan = "SELECT
                challan_id,
                warehouse_id,
                crop_id,
                product_type_id,
                varriety_id,
                pack_size,
                quantity
            FROM `$tbl" . "product_purchase_order_bonus`
            WHERE status='Pending' AND del_status='0' AND invoice_id='" . $_POST['inv_id'] . "'";
if ($dbc->open()) {
    $resultc = $dbc->query($sqlchallan);
    while ($rowc = $dbc->fetchAssoc($resultc)) {
        echo $mSQL_task = "update `$tbl" . "product_stock` set
                                `bonus_quantity`=bonus_quantity-'" . $rowc["quantity"] . "', 
				`current_stock_qunatity`=current_stock_qunatity+'" . $rowc["quantity"] . "', 
				`status`='Active', 
				`del_status`='0', 
				`entry_by`='" . $user_id . "', 
				`entry_date`='" . $db->ToDayDate() . "'
			where crop_id='" . $rowc['crop_id'] . "' AND product_type_id='" . $rowc['product_type_id'] . "' AND varriety_id='" . $rowc['varriety_id'] . "' AND pack_size='" . $rowc['pack_size'] . "' AND warehouse_id='" . $rowc['warehouse_id'] . "'";

        if ($db->open()) {
            $db->query($mSQL_task);
            $db->freeResult();
        }
        $db->system_event_log('', $user_id, $employee_id, $_POST['inv_id'], '', $tbl . 'product_stock', 'Update', '');
    }
}


$sql = "INSERT INTO $tbl" . "product_purchase_order_challan_delete
  SELECT
  '',
  challan_id,
warehouse_id,
invoice_id,
purchase_order_id,
challan_date,
zone_id,
territory_id,
distributor_id,
crop_id,
product_type_id,
varriety_id,
pack_size,
price,
quantity,
total_price,
status,
del_status,
  '$user_id',
  '" . $dbins->ToDayDate() . "'
  FROM $tbl" . "product_purchase_order_challan WHERE invoice_id='" . $_POST['inv_id'] . "'";
if ($dbins->open())
{
    $dbins->query($sql);
}

$delsqlr = "UPDATE $tbl" . "product_purchase_order_request SET status='In-Active', del_status='1', entry_date='".$db->ToDayDate()."' WHERE invoice_id='" . $_POST['inv_id'] . "'";
$delsqli = "UPDATE $tbl" . "product_purchase_order_invoice SET status='In-Active', del_status='1', entry_date='".$db->ToDayDate()."' WHERE invoice_id='" . $_POST['inv_id'] . "'";
$delsqlb = "UPDATE $tbl" . "product_purchase_order_bonus SET status='In-Active', del_status='1', entry_date='".$db->ToDayDate()."' WHERE invoice_id='" . $_POST['inv_id'] . "'";
$delsqlc = "UPDATE $tbl" . "product_purchase_order_challan SET status='In-Active', del_status='1', entry_date='".$db->ToDayDate()."' WHERE invoice_id='" . $_POST['inv_id'] . "'";
if ($db->open())
{
    $db->query($delsqlr);
    $db->query($delsqli);
    $db->query($delsqlb);
    $db->query($delsqlc);
}
//echo $delsqlr = "DELETE FROM $tbl" . "product_purchase_order_request WHERE invoice_id='" . $_POST['inv_id'] . "'";
//echo $delsqli = "DELETE FROM $tbl" . "product_purchase_order_invoice WHERE invoice_id='" . $_POST['inv_id'] . "'";
//echo $delsqlb = "DELETE FROM $tbl" . "product_purchase_order_bonus WHERE invoice_id='" . $_POST['inv_id'] . "'";
//echo $delsqlc = "DELETE FROM $tbl" . "product_purchase_order_challan WHERE invoice_id='" . $_POST['inv_id'] . "'";
//if ($db->open()) {
//    $db->query($delsqlr);
//    $db->query($delsqli);
//    $db->query($delsqlb);
//    $db->query($delsqlc);
//}
?>