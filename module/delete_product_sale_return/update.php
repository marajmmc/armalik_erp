<?php

session_start();

require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$db = new Database();
$db_chkp=new Database();
$user_id = $_SESSION['user_id'];
$employee_id = $_SESSION['employee_id'];
$tbl = _DB_PREFIX;

$challan_return_no = $_POST['rowID'];

$db_return = new Database();

$return_quantity=0;
$per_product_price=0;
$crop_id='';
$product_type_id='';
$variety_id='';
$pack_size='';
$distributor_id='';
$warehouse_id='';

$all_product_status=true;
$all_elm=true;

$sql_challan_return="
                SELECT
                    appocr.invoice_id,
                    appocr.purchase_order_id,
                    appocr.challan_date,
                    appocr.zone_id,
                    appocr.territory_id,
                    appocr.distributor_id,
                    appocr.crop_id,
                    appocr.product_type_id,
                    appocr.varriety_id,
                    appocr.pack_size,
                    appocr.price,
                    appocr.quantity,
                    appocr.return_quantity,
                    appocr.total_price,
                    appocr.warehouse_id,
                    appocr.`status`,
                    appocr.del_status
                FROM
                  ait_product_purchase_order_challan_return as appocr
                WHERE
                  appocr.return_challan_id='$challan_return_no'
                  AND appocr.del_status=0
                  AND appocr.status='Complete'
                  ";
if($db_return->open())
{
    $result_return=$db_return->query($sql_challan_return);
    while($row_return=$db_return->fetchAssoc($result_return))
    {
        $return_quantity=$row_return['return_quantity'];
        $per_product_price=$row_return['total_price'];
        $crop_id=$row_return['crop_id'];
        $product_type_id=$row_return['product_type_id'];
        $variety_id=$row_return['varriety_id'];
        $pack_size=$row_return['pack_size'];
        $distributor_id=$row_return['distributor_id'];
        $year_id=$row_return['year_id'];
        $warehouse_id=$row_return['warehouse_id'];
        $invoice_id=$row_return['invoice_id'];

        if(empty($warehouse_id) && empty($crop_id) && empty($product_type_id) && empty($variety_id) && empty($pack_size))
        {
            $all_elm = FALSE;
            break;
        }

        $valid_product = $db_chkp->get_valid_product_stock_table($year_id, $warehouse_id, $crop_id, $product_type_id, $variety_id, $pack_size);
        if (!$valid_product)
        {
            $all_product_status = FALSE;
            break;
        }
    }
}

if($all_elm && $all_product_status)
{
    $sql_challan_return="
                SELECT
                    appocr.invoice_id,
                    appocr.purchase_order_id,
                    appocr.challan_date,
                    appocr.zone_id,
                    appocr.territory_id,
                    appocr.distributor_id,
                    appocr.crop_id,
                    appocr.product_type_id,
                    appocr.varriety_id,
                    appocr.pack_size,
                    appocr.price,
                    appocr.quantity,
                    appocr.return_quantity,
                    appocr.total_price,
                    appocr.warehouse_id,
                    appocr.`status`,
                    appocr.del_status
                FROM
                  ait_product_purchase_order_challan_return as appocr
                WHERE
                  appocr.return_challan_id='$challan_return_no'
                  AND appocr.del_status=0
                  AND appocr.status='Complete'
                  ";
    if($db_return->open())
    {
        $result_return=$db_return->query($sql_challan_return);
        while($row_return=$db_return->fetchAssoc($result_return))
        {
            $return_quantity=$row_return['return_quantity'];
            $per_product_price=$row_return['total_price'];
            $crop_id=$row_return['crop_id'];
            $product_type_id=$row_return['product_type_id'];
            $variety_id=$row_return['varriety_id'];
            $pack_size=$row_return['pack_size'];
            $distributor_id=$row_return['distributor_id'];
            $warehouse_id=$row_return['warehouse_id'];
            $invoice_id=$row_return['invoice_id'];

            /////////// Start Update for purchase order INVOICE table
            $db_inv=new Database();
            $updateinv = "UPDATE $tbl" . "product_purchase_order_invoice SET
                approved_quantity=approved_quantity+'" . $return_quantity . "',
                total_price=total_price+'" . $per_product_price . "'
                WHERE
                    invoice_id='" . $invoice_id . "' AND
                    crop_id='" . $crop_id . "' AND
                    product_type_id='" . $product_type_id . "' AND
                    varriety_id='" . $variety_id . "' AND
                    pack_size='" . $pack_size . "' AND
                    distributor_id='" .$distributor_id . "'
                ";
            if ($db_inv->open())
            {
                $db_inv->query($updateinv);
            }
            /////////// End Update for purchase order INVOICE table

            /////////// Start Update for purchase order CHALLAN table
            $db_chln=new Database();
            $updateCh = "UPDATE $tbl" . "product_purchase_order_challan SET
                quantity=quantity+'" . $return_quantity . "',
                total_price=total_price+'" . $per_product_price . "'
                WHERE
                    invoice_id='" . $invoice_id . "' AND
                    crop_id='" . $crop_id . "' AND
                    product_type_id='" . $product_type_id . "' AND
                    varriety_id='" . $variety_id . "' AND
                    pack_size='" . $pack_size . "' AND
                    distributor_id='" .$distributor_id . "'
                ";
            if ($db_chln->open())
            {
                $db_chln->query($updateCh);
            }
            /////////// End Update for purchase order CHALLAN table

            /////////// Start Save for purchase order CHALLAN RECEIVED table
            $db_rcv=new Database();
            $updateChR = "UPDATE $tbl" . "product_purchase_order_challan_received SET
                quantity=quantity+'" . $return_quantity . "',
                total_price=total_price+'" . $per_product_price . "'
                WHERE
                    invoice_id='" . $invoice_id . "' AND
                    crop_id='" . $crop_id . "' AND
                    product_type_id='" . $product_type_id . "' AND
                    varriety_id='" . $variety_id . "' AND
                    pack_size='" . $pack_size . "' AND
                    distributor_id='" .$distributor_id . "'
                    ";
            if ($db_rcv->open())
            {
                $db_rcv->query($updateChR);
            }

            /////////// End Save for purchase order CHALLAN RECEIVED table

            ///////////  START PRODUCT STOCK UPDATE ////////////////////

            $pid = $db->single_data_w($tbl . 'product_stock', "count(id) as product_id", "crop_id='" . $crop_id . "' AND product_type_id='" . $product_type_id . "' AND varriety_id='".$variety_id."' AND pack_size='" . $pack_size . "' AND warehouse_id='" . $warehouse_id . "'");
            if ($pid['product_id'] > 0)
            {
                $mSQL_task = "update `$tbl" . "product_stock` set
                                    `return_quantity`=return_quantity-'" . $return_quantity . "',
                                    `current_stock_qunatity`=current_stock_qunatity-'" . $return_quantity . "',
                                    `status`='Active',
                                    `del_status`='0',
                                    `entry_by`='" . $user_id . "',
                                    `entry_date`='" . $db->ToDayDate() . "'
                                where
                                    crop_id='" . $crop_id . "' AND
                                    product_type_id='" . $product_type_id . "' AND
                                    varriety_id='" . $variety_id . "' AND
                                    pack_size='" . $pack_size . "' AND
                                    warehouse_id='" . $warehouse_id . "'
                                ";

                if ($db->open()) {
                    $db->query($mSQL_task);
                    $db->freeResult();
                }
                $db->system_event_log('', $user_id, $employee_id, $invoice_id, '', $tbl . 'product_stock', 'Update', '');
            }
            /*
            else
            {
                $rowfield = array(
                    'warehouse_id,' => "'" . $_POST["warehouse_id"][$i] . "',",
                    'crop_id,' => "'" . $_POST["crop_id"][$i] . "',",
                    'product_type_id,' => "'" . $_POST["product_type_id"][$i] . "',",
                    'varriety_id,' => "'" . $_POST["varriety_id"][$i] . "',",
                    'pack_size,' => "'" . $_POST["pack_size"][$i] . "',",
                    'return_quantity,' => "return_quantity+'" . $_POST["return_quantity"][$i] . "',",
                    'current_stock_qunatity,' => "current_stock_qunatity+'" . $_POST["quantity"][$i] . "',",
                    'status,' => "'Active',",
                    'del_status,' => "'0',",
                    'entry_by,' => "'$user_id',",
                    'entry_date' => "'" . $db->ToDayDate() . "'"
                );

                $db->data_insert($tbl . 'product_stock', $rowfield);
                $db->system_event_log('', $user_id, $employee_id, '', '', $tbl . 'product_stock', 'Save', '');
            }
            */
            ///////////  END PRODUCT STOCK UPDATE ////////////////////

            ///////////  START DISTRIBUTOR UPDATE ////////////////////

            $dstbtor = $db->single_data($tbl . "distributor_balance", "count(distributor_id) as distributor_id", "distributor_id", $distributor_id);
            if ($dstbtor['distributor_id'] != 0)
            {
                $rowfield = array
                (
                    'credit_limit_amount' => "credit_limit_amount+'" . $per_product_price . "'",
                    'balance_amount' => "balance_amount-'" . $per_product_price . "'",
                    'due_amount' => "due_amount+'" . $per_product_price . "'",
                    'status' => "'Active'",
                    'del_status' => "'0'",
                    'entry_by' => "'$user_id'",
                    'entry_date' => "'" . $db->ToDayDate() . "'"
                );
                $wherefield = array('distributor_id' => "'" . $distributor_id . "'");
                $db->data_update($tbl . 'distributor_balance', $rowfield, $wherefield);
                $db->system_event_log('', $user_id, $employee_id, '', '', $tbl . 'distributor_balance', 'Update', '');
            }
            //        else
            //        {
            //            $rowfield = array
            //            (
            //                'distributor_id,' => "'" . $distributor_id . "',",
            //                'credit_limit_amount,' => "credit_limit_amount+'" . $total_price . "',",
            //                'balance_amount,' => "balance_amount+'" . $total_price . "',",
            //                'due_amount,' => "due_amount-'" . $total_price . "',",
            //                'status,' => "'Active',",
            //                'del_status,' => "'0',",
            //                'entry_by,' => "'$user_id',",
            //                'entry_date' => "'" . $db->ToDayDate() . "'"
            //            );
            //
            //            $db->data_insert($tbl . 'distributor_balance', $rowfield);
            //            $db->system_event_log('', $user_id, $employee_id, '', '', $tbl . 'distributor_balance', 'Save', '');
            //        }

            ///////////  END DISTRIBUTOR UPDATE ////////////////////
        }

        /////////// Start Save for purchase order CHALLAN RECEIVED table
        $db_rcv=new Database();
        $updateChR = "UPDATE $tbl" . "product_purchase_order_challan_return SET
                $tbl" . "product_purchase_order_challan_return.status='In-Active',
                $tbl" . "product_purchase_order_challan_return.del_status=1
                WHERE
                    return_challan_id='" . $challan_return_no . "'
                    ";
        if ($db_rcv->open())
        {
            $db_rcv->query($updateChR);
        }
        /////////// End Save for purchase order CHALLAN RECEIVED table
    }
    echo "Success";
}
else
{
    echo "Not Success";
}


?>