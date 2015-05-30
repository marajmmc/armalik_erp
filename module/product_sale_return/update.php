<?php

session_start();

require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$db = new Database();
$dbud = new Database();
$user_id = $_SESSION['user_id'];
$employee_id = $_SESSION['employee_id'];
$warehouse_id = $_SESSION['warehouse_id'];
$tbl = _DB_PREFIX;

//$total_pack_price = 0;
//$total_quantity = 0;
$total_price = 0;
$per_product_price=0;

$valid_product=true;
$all_product_status=true;

$purchase_order_id=$_POST['purchase_order_id'];
$invoice_id=$_POST['invoice_id'];
$zone_id=$_POST['zone_id'];
$territory_id=$_POST['territory_id'];
$distributor_id=$_POST['distributor_id'];
$warehouse_id=$_POST['warehouse_id'];

$db_po_received=new Database();
$valid_receipt_po = $db_po_received->single_data_w($tbl . 'product_purchase_order_challan_received', "invoice_id", "invoice_id='" . $invoice_id . "'");
if(empty($valid_receipt_po))
{
    echo "This challan not received.";
    die();
}
if(empty($purchase_order_id))
{
    echo "Purchase order number empty";
    die();
}
if(empty($invoice_id))
{
    echo "Invoice number empty";
    die();
}
if(empty($zone_id))
{
    echo "Zone empty";
    die();
}
if(empty($territory_id))
{
    echo "Territory empty";
    die();
}
if(empty($distributor_id))
{
    echo "Customer empty";
    die();
}
if(empty($warehouse_id))
{
    echo "Warehouse empty";
    die();
}

if(!empty($purchase_order_id) && !empty($invoice_id) && !empty($zone_id) && !empty($territory_id) && !empty($distributor_id) && !empty($warehouse_id))
{
    $count = count($_POST['id']);
    if($count>0)
    {
        ////////// start checked validate product
        $db_chkp=new Database();
        for ($i = 0; $i < $count; $i++)
        {
            $crop_id=$_POST["crop_id"][$i];
            $product_type_id=$_POST["product_type_id"][$i];
            $varriety_id=$_POST["varriety_id"][$i];
            $pack_size=$_POST["pack_size"][$i];

            $valid_product = $db_chkp->get_valid_product_stock_table($warehouse_id, $crop_id, $product_type_id, $varriety_id, $pack_size);
            if (!$valid_product)
            {
                $all_product_status = FALSE;
                break;
            }
        }
        ////////// end checked validate product
        $db_distributor=new Database();
        $valid_distributor = $db_distributor->single_data_w($tbl . 'distributor_info', "distributor_id", "distributor_id='" . $distributor_id . "'");

        if($all_product_status && !empty($valid_distributor))
        {
            $maxID = "PR-" . $db->Get_CustMaxID($tbl . 'product_purchase_order_challan_return', 'return_challan_id', '8', '');
            for ($i = 0; $i < $count; $i++)
            {
                $crop_id=$_POST["crop_id"][$i];
                $product_type_id=$_POST["product_type_id"][$i];
                $varriety_id=$_POST["varriety_id"][$i];
                $pack_size=$_POST["pack_size"][$i];
                $price=$_POST["price"][$i];
                $quantity=$_POST["quantity"][$i];
                $return_quantity=$_POST["return_quantity"][$i];
                $per_product_price=($price*$return_quantity);

                /////////// Start Save for purchase order return table
                $total_price = $total_price + $_POST["total_price"][$i];
                $rowfield = array(
                    'return_challan_id,' => "'" . $maxID . "',",
                    'purchase_order_id,' => "'" . $purchase_order_id . "',",
                    'invoice_id,' => "'" . $invoice_id . "',",
                    'challan_date,' => "'" . $db->date_formate($_POST["challan_date"]) . "',",
                    'zone_id,' => "'" . $zone_id . "',",
                    'territory_id,' => "'" . $territory_id . "',",
                    'distributor_id,' => "'" . $distributor_id . "',",
                    'crop_id,' => "'" . $crop_id . "',",
                    'product_type_id,' => "'" . $product_type_id . "',",
                    'varriety_id,' => "'" . $varriety_id . "',",
                    'pack_size,' => "'" . $pack_size . "',",
                    'price,' => "'" . $price . "',",
                    'quantity,' => "'" . $quantity . "',",
                    'return_quantity,' => "'" . $return_quantity . "',",
                    'total_price,' => "'" . $per_product_price . "',",
                    'warehouse_id,' => "'" . $warehouse_id . "',",
                    'status,' => "'Complete',",
                    'del_status,' => "'0',",
                    'entry_by,' => "'$user_id',",
                    'entry_date' => "'" . $db->ToDayDate() . "'"
                );

                $db->data_insert($tbl . 'product_purchase_order_challan_return', $rowfield);
                $db->system_event_log('', $user_id, $employee_id, $maxID, '', $tbl . 'product_purchase_order_challan_return', 'Save', '');
                /////////// End Save for purchase order return table

                /////////// Start Save for purchase order INVOICE table
                $db_inv=new Database();
                $updateinv = "UPDATE $tbl" . "product_purchase_order_invoice SET
                approved_quantity=approved_quantity-'" . $return_quantity . "',
                total_price=total_price-'" . $per_product_price . "'
                WHERE
                    invoice_id='" . $invoice_id . "' AND
                    crop_id='" . $crop_id . "' AND
                    product_type_id='" . $product_type_id . "' AND
                    varriety_id='" . $varriety_id . "' AND
                    pack_size='" . $pack_size . "' AND
                    distributor_id='" .$distributor_id . "' AND
                    warehouse_id='".$warehouse_id."'
                ";
                if ($db_inv->open())
                {
                    $db_inv->query($updateinv);
                }
                /////////// End Save for purchase order INVOICE table

                /////////// Start Save for purchase order CHALLAN table
                $db_chln=new Database();
                $updateCh = "UPDATE $tbl" . "product_purchase_order_challan SET
                quantity=quantity-'" . $return_quantity . "',
                total_price=total_price-'" . $per_product_price . "'
                WHERE
                    invoice_id='" . $invoice_id . "' AND
                    crop_id='" . $crop_id . "' AND
                    product_type_id='" . $product_type_id . "' AND
                    varriety_id='" . $varriety_id . "' AND
                    pack_size='" . $pack_size . "' AND
                    distributor_id='" .$distributor_id . "' AND
                    warehouse_id='".$warehouse_id."'
                    ";
                if ($db_chln->open())
                {
                    $db_chln->query($updateCh);
                }
                /////////// End Save for purchase order CHALLAN table

                /////////// Start Save for purchase order CHALLAN RECEIVED table

                $updateChR = "UPDATE $tbl" . "product_purchase_order_challan_received SET
                quantity=quantity-'" . $return_quantity . "',
                total_price=total_price-'" . $per_product_price . "'
                WHERE
                    invoice_id='" . $invoice_id . "' AND
                    crop_id='" . $crop_id . "' AND
                    product_type_id='" . $product_type_id . "' AND
                    varriety_id='" . $varriety_id . "' AND
                    pack_size='" . $pack_size . "' AND
                    distributor_id='" .$distributor_id . "' AND
                    warehouse_id='".$warehouse_id."'
                    ";
                if ($db->open())
                {
                    $db->query($updateChR);
                }

                /////////// End Save for purchase order CHALLAN RECEIVED table

                ///////////  START PRODUCT STOCK UPDATE ////////////////////

                $pid = $db->single_data_w($tbl . 'product_stock', "count(id) as product_id", "crop_id='" . $crop_id . "' AND product_type_id='" . $product_type_id . "' AND varriety_id='".$varriety_id."' AND pack_size='" . $pack_size . "' AND warehouse_id='" . $warehouse_id . "'");
                if ($pid['product_id'] > 0)
                {
                    $mSQL_task = "update `$tbl" . "product_stock` set
                                    `return_quantity`=return_quantity+'" . $return_quantity . "',
                                    `current_stock_qunatity`=current_stock_qunatity+'" . $return_quantity . "',
                                    `status`='Active',
                                    `del_status`='0',
                                    `entry_by`='" . $user_id . "',
                                    `entry_date`='" . $db->ToDayDate() . "'
                                where
                                    crop_id='" . $crop_id . "' AND
                                    product_type_id='" . $product_type_id . "' AND
                                    varriety_id='" . $varriety_id . "' AND
                                    pack_size='" . $pack_size . "' AND
                                    warehouse_id='" . $warehouse_id . "'
                                ";

                    if ($db->open()) {
                        $db->query($mSQL_task);
                        $db->freeResult();
                    }
                    $db->system_event_log('', $user_id, $employee_id, $maxID, '', $tbl . 'product_stock', 'Update', '');
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
            }

            ///////////  START DISTRIBUTOR UPDATE ////////////////////

            $dstbtor = $db->single_data($tbl . "distributor_balance", "count(distributor_id) as distributor_id", "distributor_id", $_POST["distributor_id"]);
            if ($dstbtor['distributor_id'] != 0)
            {
                $rowfield = array
                (
                    'credit_limit_amount' => "credit_limit_amount-'" . $total_price . "'",
                    'balance_amount' => "balance_amount+'" . $total_price . "'",
                    'due_amount' => "due_amount-'" . $total_price . "'",
                    'status' => "'Active'",
                    'del_status' => "'0'",
                    'entry_by' => "'$user_id'",
                    'entry_date' => "'" . $db->ToDayDate() . "'"
                );
                $wherefield = array('distributor_id' => "'" . $distributor_id . "'");
                $db->data_update($tbl . 'distributor_balance', $rowfield, $wherefield);
                $db->system_event_log('', $user_id, $employee_id, '', '', $tbl . 'distributor_balance', 'Update', '');
            }
            else
            {
                $rowfield = array
                (
                    'distributor_id,' => "'" . $distributor_id . "',",
                    'credit_limit_amount,' => "credit_limit_amount+'" . $total_price . "',",
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
        }
        else
        {
            echo "There is product missing or distributor not match! Try again.";
        }
    }
    else
    {
        echo "There are is not product available! Try again.";
    }
}
else
{
    echo "Please some field is missing. Not return! Try again.";
}
?>