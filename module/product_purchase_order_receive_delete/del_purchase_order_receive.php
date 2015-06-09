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
                $tbl" . "product_purchase_order_challan.challan_id,
                $tbl" . "product_purchase_order_challan.warehouse_id,
                $tbl" . "product_purchase_order_challan.invoice_id,
                $tbl" . "product_purchase_order_challan.purchase_order_id,
                $tbl" . "product_purchase_order_challan.zone_id,
                $tbl" . "product_purchase_order_challan.territory_id,
                $tbl" . "product_purchase_order_challan.distributor_id,
                $tbl" . "product_purchase_order_challan.crop_id,
                $tbl" . "product_purchase_order_challan.product_type_id,
                $tbl" . "product_purchase_order_challan.varriety_id,
                $tbl" . "product_purchase_order_challan.pack_size,
                $tbl" . "product_purchase_order_challan.price,
                $tbl" . "product_purchase_order_challan.quantity,
                $tbl" . "product_purchase_order_challan.total_price,
                $tbl" . "product_purchase_order_challan_received.loss_quantity,
                $tbl" . "product_purchase_order_challan_received.extra_quantity
            FROM `$tbl" . "product_purchase_order_challan`
                LEFT JOIN $tbl" . "product_purchase_order_challan_received ON $tbl" . "product_purchase_order_challan_received.invoice_id = $tbl" . "product_purchase_order_challan.invoice_id AND $tbl" . "product_purchase_order_challan_received.crop_id = $tbl" . "product_purchase_order_challan.crop_id AND $tbl" . "product_purchase_order_challan_received.product_type_id = $tbl" . "product_purchase_order_challan.product_type_id AND $tbl" . "product_purchase_order_challan_received.varriety_id = $tbl" . "product_purchase_order_challan.varriety_id AND $tbl" . "product_purchase_order_challan_received.pack_size = $tbl" . "product_purchase_order_challan.pack_size
            WHERE $tbl" . "product_purchase_order_challan.status='Received' AND 
                    $tbl" . "product_purchase_order_challan.del_status='0' AND 
                    $tbl" . "product_purchase_order_challan.invoice_id='" . $_POST['inv_id'] . "'";

if ($dbc->open()) {
    $resultc = $dbc->query($sqlchallan);
    while ($rowc = $dbc->fetchAssoc($resultc))
    {
        $current_stock_qunatity = ($rowc["quantity"] - $rowc["loss_quantity"]) + $rowc["extra_quantity"];
        $mSQL_task = "update `$tbl" . "distributor_product_stock` set
                                `purchase_quantity`=purchase_quantity-'" . $rowc["quantity"] . "', 
				`current_stock_qunatity`=current_stock_qunatity-'" . $current_stock_qunatity . "', 
				`loss_quantity`=loss_quantity-'" . $rowc["loss_quantity"] . "', 
				`extra_quantity`=extra_quantity-'" . $rowc["extra_quantity"] . "', 
				`status`='Active', 
				`del_status`='0', 
				`entry_by`='" . $user_id . "', 
				`entry_date`='" . $db->ToDayDate() . "'
			where year_id='" . $_POST['year_id'] . "' AND crop_id='" . $rowc['crop_id'] . "' AND product_type_id='" . $rowc['product_type_id'] . "' AND varriety_id='" . $rowc['varriety_id'] . "' AND pack_size='" . $rowc['pack_size'] . "' AND distributor_id='" . $rowc['distributor_id'] . "'
				";

        if ($db->open())
        {
            $db->query($mSQL_task);
            $db->freeResult();
        }
        $db->system_event_log('', $user_id, $employee_id, $_POST['inv_id'], '', $tbl . 'distributor_product_stock', 'Update', '');

        $mSQL_task = "update `$tbl" . "product_stock` set
                                `delivery_quantity`=delivery_quantity-'" . $rowc["quantity"] . "', 
				`current_stock_qunatity`=current_stock_qunatity+'" . $rowc["quantity"] . "', 
				`status`='Active', 
				`del_status`='0', 
				`entry_by`='" . $user_id . "', 
				`entry_date`='" . $db->ToDayDate() . "'
			where year_id='" . $_POST['year_id'] . "' AND crop_id='" . $rowc['crop_id'] . "' AND product_type_id='" . $rowc['product_type_id'] . "' AND varriety_id='" . $rowc['varriety_id'] . "' AND pack_size='" . $rowc['pack_size'] . "' AND warehouse_id='" . $rowc['warehouse_id'] . "'";

        if ($db->open())
        {
            $db->query($mSQL_task);
            $db->freeResult();
        }
        $db->system_event_log('', $user_id, $employee_id, $_POST['inv_id'], '', $tbl . 'product_stock', 'Update', '');

        $rowfield = array
        (
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
if ($dbc->open())
{
    $resultc = $dbc->query($sqlchallan);
    while ($rowc = $dbc->fetchAssoc($resultc))
    {
        $mSQL_task = "update `$tbl" . "distributor_product_stock` set
                                `bonus_quantity`=bonus_quantity-'" . $rowc["quantity"] . "', 
				`current_stock_qunatity`=current_stock_qunatity-'" . $rowc["quantity"] . "', 
				`status`='Active', 
				`del_status`='0', 
				`entry_by`='" . $user_id . "', 
				`entry_date`='" . $db->ToDayDate() . "'
			where crop_id='" . $rowc['crop_id'] . "' AND product_type_id='" . $rowc['product_type_id'] . "' AND varriety_id='" . $rowc['varriety_id'] . "' AND pack_size='" . $rowc['pack_size'] . "' AND distributor_id='" . $rowc['distributor_id'] . "'
				";

        if ($db->open())
        {
            $db->query($mSQL_task);
            $db->freeResult();
        }
        echo $mSQL_task = "update `$tbl" . "product_stock` set
                                `bonus_quantity`=bonus_quantity-'" . $rowc["quantity"] . "', 
				`current_stock_qunatity`=current_stock_qunatity+'" . $rowc["quantity"] . "', 
				`status`='Active', 
				`del_status`='0', 
				`entry_by`='" . $user_id . "', 
				`entry_date`='" . $db->ToDayDate() . "'
			where year_id='" . $_POST['year_id'] . "' AND  crop_id='" . $rowc['crop_id'] . "' AND product_type_id='" . $rowc['product_type_id'] . "' AND varriety_id='" . $rowc['varriety_id'] . "' AND pack_size='" . $rowc['pack_size'] . "' AND warehouse_id='" . $rowc['warehouse_id'] . "'";

        if ($db->open())
        {
            $db->query($mSQL_task);
            $db->freeResult();
        }
        $db->system_event_log('', $user_id, $employee_id, $_POST['inv_id'], '', $tbl . 'product_stock', 'Update', '');
    }
}


//$sql = "INSERT INTO $tbl" . "product_purchase_order_challan_received_delete
//SELECT
//'',
//challan_received_id,
//challan_id,
//invoice_id,
//purchase_order_id,
//challan_received_date,
//zone_id,
//territory_id,
//distributor_id,
//crop_id,
//product_type_id,
//varriety_id,
//pack_size,
//price,
//approved_quantity,
//quantity,
//loss_quantity,
//total_price,
//extra_quantity,
//status,
//del_status,
//  '$user_id',
//  '" . $dbins->ToDayDate() . "'
//  FROM $tbl" . "product_purchase_order_challan_received WHERE invoice_id='" . $_POST['inv_id'] . "'";
//if ($dbins->open())
//{
//    $dbins->query($sql);
//}

$delsqlr = "UPDATE $tbl" . "product_purchase_order_request SET status='In-Active', del_status='1', entry_date='".$db->ToDayDate()."' WHERE invoice_id='" . $_POST['inv_id'] . "'";
$delsqli = "UPDATE $tbl" . "product_purchase_order_invoice SET status='In-Active', del_status='1', entry_date='".$db->ToDayDate()."' WHERE invoice_id='" . $_POST['inv_id'] . "'";
$delsqlb = "UPDATE $tbl" . "product_purchase_order_bonus SET status='In-Active', del_status='1', entry_date='".$db->ToDayDate()."' WHERE invoice_id='" . $_POST['inv_id'] . "'";
$delsqlc = "UPDATE $tbl" . "product_purchase_order_challan SET status='In-Active', del_status='1', entry_date='".$db->ToDayDate()."' WHERE invoice_id='" . $_POST['inv_id'] . "'";
$delsqlcr = "UPDATE $tbl" . "product_purchase_order_challan_received SET status='In-Active', del_status='1', entry_date='".$db->ToDayDate()."' WHERE invoice_id='" . $_POST['inv_id'] . "'";
if ($db->open())
{
    $db->query($delsqlr);
    $db->query($delsqli);
    $db->query($delsqlb);
    $db->query($delsqlc);
    $db->query($delsqlcr);
}
//echo $delsqlr = "DELETE FROM $tbl" . "product_purchase_order_request WHERE invoice_id='" . $_POST['inv_id'] . "'";
//echo $delsqli = "DELETE FROM $tbl" . "product_purchase_order_invoice WHERE invoice_id='" . $_POST['inv_id'] . "'";
//echo $delsqlb = "DELETE FROM $tbl" . "product_purchase_order_bonus WHERE invoice_id='" . $_POST['inv_id'] . "'";
//echo $delsqlc = "DELETE FROM $tbl" . "product_purchase_order_challan WHERE invoice_id='" . $_POST['inv_id'] . "'";
//echo $delsqlcr = "DELETE FROM $tbl" . "product_purchase_order_challan_received WHERE invoice_id='" . $_POST['inv_id'] . "'";
//if ($db->open()) {
//    $db->query($delsqlr);
//    $db->query($delsqli);
//    $db->query($delsqlb);
//    $db->query($delsqlc);
//    $db->query($delsqlcr);
//}
?>