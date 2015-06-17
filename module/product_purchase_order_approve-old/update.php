<?php

session_start();

require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$db = new Database();
$dbchk = new Database();
$dbud = new Database();
$user_id = $_SESSION['user_id'];
$employee_id = $_SESSION['employee_id'];
$tbl = _DB_PREFIX;
//$msg = '';
$total_pack_price = '';
$total_quantity = '';
$total_price = '';
$tpins = 0;
$tpup = 0;
$year_id = $_POST['year_id'];
$warehouse_id = $_POST['warehouse_id'];
$zone_id = $_POST['zone_id'];
$territory_id = $_POST['territory_id'];
$zilla_id = $_POST['zilla_id'];
$distributor_id = $_POST['distributor_id'];
$approved_status = $_POST['approved_status'];

if(empty($approved_status) && ($approved_status!="Approved" || $approved_status!="Reject"))
{
    echo "Approved_Status_Empty";
    die();
}

if(empty($year_id) && empty($warehouse_id) && empty($zone_id) && empty($territory_id) && empty($zilla_id) && empty($distributor_id))
{
    echo "Location_Empty";
    die();
}

$maxID = "IN-" . $db->Get_CustMaxID($tbl . 'product_purchase_order_invoice', 'invoice_id', '8', '');
$chk_inv = $dbchk->single_data($tbl . "product_purchase_order_invoice", "purchase_order_id", "purchase_order_id", $_POST['purchase_order_id']);
if ($chk_inv['purchase_order_id'] != "")
{
    echo "INVOICE_EXIST";
}
else
{
    $valid_po = TRUE;
    $count_po = count($_POST['id']);
    for ($i = 0; $i < $count_po; $i++)
    {
        $qnty = $db->get_product_stock($_POST["year_id"],$_POST["warehouse_id"], $_POST["crop_id"][$i], $_POST["product_type_id"][$i], $_POST["varriety_id"][$i], $_POST["pack_size"][$i], $_POST["approved_quantity"][$i]);
        if (!$qnty)
        {
            $valid_po = FALSE;
            break;
        }
    }

    $valid_bonus = TRUE;
    $bonus_count = count($_POST['bonus_id']);
    for ($i = 0; $i < $bonus_count; $i++)
    {
        $qnty = $db->get_product_stock($_POST["year_id"],$_POST["warehouse_id"], $_POST["bonus_crop_id"][$i], $_POST["bonus_product_type_id"][$i], $_POST["bonus_varriety_id"][$i], $_POST["bonus_pack_size"][$i], $_POST["bonus_quantity"][$i]);
        if (!$qnty)
        {
            $valid_bonus = FALSE;
            break;
        }
    }
    if ($valid_po && $valid_bonus)
    {

        if ($_POST['approved_status'] == "Reject")
        {
            $updatesql = "UPDATE $tbl" . "product_purchase_order_request SET 
                                remark='" . $_POST['remark'] . "',
                                status='" . $_POST['approved_status'] . "'
                            WHERE purchase_order_id='" . $_POST['purchase_order_id'] . "'";
            if ($dbud->open())
            {
                $result = $dbud->query($updatesql);
            }
        }
        else
        {
            $count = count($_POST['id']);
            for ($i = 0; $i < $count; $i++)
            {
                $total_price = $total_price + $_POST["total_price"][$i];
                $tpins=$_POST["price"][$i] * $_POST["approved_quantity"][$i];
                $rowfield = array
                (
                    'invoice_id,' => "'" . $maxID . "',",
                    'purchase_order_id,' => "'" . $_POST['purchase_order_id'] . "',",
                    'invoice_date,' => "'" . $db->date_formate($_POST["invoice_date"]) . "',",
                    'year_id,' => "'" . $_POST["year_id"] . "',",
                    'warehouse_id,' => "'" . $_POST["warehouse_id"] . "',",
                    'zone_id,' => "'" . $_POST["zone_id"] . "',",
                    'territory_id,' => "'" . $_POST["territory_id"] . "',",
                    'zilla_id,' => "'" . $_POST["zilla_id"] . "',",
                    'distributor_id,' => "'" . $_POST["distributor_id"] . "',",
                    'crop_id,' => "'" . $_POST["crop_id"][$i] . "',",
                    'product_type_id,' => "'" . $_POST["product_type_id"][$i] . "',",
                    'varriety_id,' => "'" . $_POST["varriety_id"][$i] . "',",
                    'pack_size,' => "'" . $_POST["pack_size"][$i] . "',",
                    'price,' => "'" . $_POST["price"][$i] . "',",
                    'quantity,' => "'" . $_POST["quantity"][$i] . "',",
                    'approved_quantity,' => "'" . $_POST["approved_quantity"][$i] . "',",
                    'total_price,' => "'" . $tpins . "',",
                    'remark,' => "'" . $_POST["remark"] . "',",
                    'status,' => "'Pending',",
                    'del_status,' => "'0',",
                    'entry_by,' => "'$user_id',",
                    'entry_date' => "'" . $db->ToDayDate() . "'"
                );

                $db->data_insert($tbl . 'product_purchase_order_invoice', $rowfield);
                $db->system_event_log('', $user_id, $employee_id, $maxID, '', $tbl . 'product_purchase_order_invoice', 'Save', '');

                $updatesql = "UPDATE $tbl" . "product_purchase_order_request SET 
                                    remark='" . $_POST['remark'] . "',
                                    status='" . $_POST['approved_status'] . "',
                                    approved_quantity='" . $_POST["approved_quantity"][$i] . "',
                                    total_price='" . $_POST["total_price"][$i] . "',
                                    invoice_id='$maxID'
                                WHERE id='" . $_POST['id'][$i] . "'";
                if ($dbud->open())
                {
                    $result = $dbud->query($updatesql);
                }

                ///////////  START PRODUCT STOCK UPDATE ////////////////////

                $pid = $db->single_data_w($tbl . 'product_stock', "count(id) as product_id, id", "year_id='$year_id' AND crop_id='" . $_POST['crop_id'][$i] . "' AND product_type_id='" . $_POST['product_type_id'][$i] . "' AND pack_size='" . $_POST['pack_size'][$i] . "' AND warehouse_id='" . $warehouse_id . "'");
                if ($pid['product_id'] != 0)
                {
                    echo $mSQL_task = "update `$tbl" . "product_stock` set
                                            `delivery_quantity`=delivery_quantity+'" . $_POST["approved_quantity"][$i] . "',
                                            `current_stock_qunatity`=current_stock_qunatity-'" . $_POST["approved_quantity"][$i] . "',
                                            `warehouse_id`='$warehouse_id',
                                            `status`='Active',
                                            `del_status`='0',
                                            `entry_by`='" . $user_id . "',
                                            `entry_date`='" . $db->ToDayDate() . "'
                                        where
                                            crop_id='" . $_POST['crop_id'][$i] . "'
                                            AND product_type_id='" . $_POST['product_type_id'][$i] . "'
                                            AND varriety_id='" . $_POST['varriety_id'][$i] . "'
                                            AND pack_size='" . $_POST['pack_size'][$i] . "'
                                            AND warehouse_id='$warehouse_id'
                                            AND year_id='$year_id'
                                        ";
                    if ($db->open())
                    {
                        $db->query($mSQL_task);
                        $db->freeResult();
                    }
                    $db->system_event_log('', $user_id, $employee_id, $maxID, '', $tbl . 'product_stock', 'Update', '');
                }
                else
                {
                    $rowfield = array
                    (
                        'year_id,' => "'" . $year_id . "',",
                        'warehouse_id,' => "'" . $warehouse_id . "',",
                        'crop_id,' => "'" . $_POST["crop_id"][$i] . "',",
                        'product_type_id,' => "'" . $_POST["product_type_id"][$i] . "',",
                        'varriety_id,' => "'" . $_POST["varriety_id"][$i] . "',",
                        'pack_size,' => "'" . $_POST["pack_size"][$i] . "',",
                        'delivery_quantity,' => "delivery_quantity+'" . $_POST["approved_quantity"][$i] . "',",
                        'current_stock_qunatity,' => "current_stock_qunatity-'" . $_POST["approved_quantity"][$i] . "',",
                        'status,' => "'Active',",
                        'del_status,' => "'0',",
                        'entry_by,' => "'$user_id',",
                        'entry_date' => "'" . $db->ToDayDate() . "'"
                    );

                    $db->data_insert($tbl . 'product_stock', $rowfield);
                    $db->system_event_log('', $user_id, $employee_id, '', '', $tbl . 'product_stock', 'Save', '');
                }

                ///////////  END PRODUCT STOCK UPDATE ////////////////////
            }


            ///////////  START DISTRIBUTOR UPDATE ////////////////////

            $dstbtor = $db->single_data_w($tbl . "distributor_balance", "count(distributor_id) as distributor_id", "distributor_id='".$_POST["distributor_id"]."' AND year_id='$year_id'");
            if ($dstbtor['distributor_id'] != 0)
            {
                $rowfield = array
                (
                    'credit_limit_amount' => "credit_limit_amount-'" . $total_price . "'",
                    'balance_amount' => "balance_amount-'" . $total_price . "'",
                    'due_amount' => "due_amount+'" . $total_price . "'",
                    'status' => "'Active'",
                    'del_status' => "'0'",
                    'entry_by' => "'$user_id'",
                    'entry_date' => "'" . $db->ToDayDate() . "'"
                );
                $wherefield = array('distributor_id' => "'" . $_POST["distributor_id"] . "'");
                $db->data_update($tbl . 'distributor_balance', $rowfield, $wherefield);
                $db->system_event_log('', $user_id, $employee_id, '', '', $tbl . 'distributor_balance', 'Update', '');
            }
            else
            {
                $rowfield = array
                (
                    'distributor_id,' => "'" . $_POST["distributor_id"] . "',",
                    'year_id,' => "'" . $year_id . "',",
                    'credit_limit_amount,' => "credit_limit_amount-'" . $total_price . "',",
                    'balance_amount,' => "balance_amount-'" . $total_price . "',",
                    'due_amount,' => "due_amount+'" . $total_price . "',",
                    'status,' => "'Active',",
                    'del_status,' => "'0',",
                    'entry_by,' => "'$user_id',",
                    'entry_date' => "'" . $db->ToDayDate() . "'"
                );

                $db->data_insert($tbl . 'distributor_balance', $rowfield);
                $db->system_event_log('', $user_id, $employee_id, '', '', $tbl . 'distributor_balance', 'Save', '');
            }

            ///////////  END DISTRIBUTOR UPDATE ////////////////////
            ///////////  START BONUS TABLE UPDATE ////////////////////

            $bonus_count = count($_POST['bonus_id']);
            for ($i = 0; $i < $bonus_count; $i++)
            {
                if ($_POST['bonus_id'][$i] != "")
                {
                    $rowfield = array
                    (
                        'invoice_id' => "'" . $maxID . "'",
                        'invoice_date' => "'" . $db->date_formate($_POST["invoice_date"]) . "'",
                        'warehouse_id' => "'" . $_POST["warehouse_id"] . "'",
                        'year_id' => "'" . $_POST["year_id"] . "'",
                        'zone_id' => "'" . $_POST["zone_id"] . "'",
                        'territory_id' => "'" . $_POST["territory_id"] . "'",
                        'zilla_id' => "'" . $_POST["zilla_id"] . "'",
                        'distributor_id' => "'" . $_POST["distributor_id"] . "'",
                        'crop_id' => "'" . $_POST["bonus_crop_id"][$i] . "'",
                        'product_type_id' => "'" . $_POST["bonus_product_type_id"][$i] . "'",
                        'varriety_id' => "'" . $_POST["bonus_varriety_id"][$i] . "'",
                        'pack_size' => "'" . $_POST["bonus_pack_size"][$i] . "'",
                        'quantity' => "'" . $_POST["bonus_quantity"][$i] . "'",
                        'status' => "'Active'",
                        'del_status' => 0,
                        'entry_by' => "'$user_id'",
                        'entry_date' => "'" . $db->ToDayDate() . "'"
                    );
                    $wherefield = array('id' => "'" . $_POST["bonus_id"][$i] . "'");
                    $db->data_update($tbl . 'product_purchase_order_bonus', $rowfield, $wherefield);
                    $db->system_event_log('', $user_id, $employee_id, $maxID, '', $tbl . 'product_purchase_order_bonus', 'Save', '');
                }
                else
                {
                    $rowfield = array
                    (
                        'invoice_id,' => "'" . $maxID . "',",
                        'purchase_order_id,' => "'" . $_POST['purchase_order_id'] . "',",
                        'invoice_date,' => "'" . $db->date_formate($_POST["invoice_date"]) . "',",
                        'year_id,' => "'" . $_POST["year_id"] . "',",
                        'warehouse_id,' => "'" . $_POST["warehouse_id"] . "',",
                        'zone_id,' => "'" . $_POST["zone_id"] . "',",
                        'territory_id,' => "'" . $_POST["territory_id"] . "',",
                        'zilla_id,' => "'" . $_POST["zilla_id"] . "',",
                        'distributor_id,' => "'" . $_POST["distributor_id"] . "',",
                        'crop_id,' => "'" . $_POST["bonus_crop_id"][$i] . "',",
                        'product_type_id,' => "'" . $_POST["bonus_product_type_id"][$i] . "',",
                        'varriety_id,' => "'" . $_POST["bonus_varriety_id"][$i] . "',",
                        'pack_size,' => "'" . $_POST["bonus_pack_size"][$i] . "',",
                        'quantity,' => "'" . $_POST["bonus_quantity"][$i] . "',",
                        'status,' => "'Active',",
                        'del_status,' => "'0',",
                        'entry_by,' => "'$user_id',",
                        'entry_date' => "'" . $db->ToDayDate() . "'"
                    );

                    $db->data_insert($tbl . 'product_purchase_order_bonus', $rowfield);
                    $db->system_event_log('', $user_id, $employee_id, $maxID, '', $tbl . 'product_purchase_order_bonus', 'Save', '');
                }
            }

            $bonus_count = count($_POST['bonus_id']);
            for ($i = 0; $i < $bonus_count; $i++)
            {
                $pid = $db->single_data_w($tbl . 'product_stock', "count(id) as product_id", "year_id='$year_id' AND crop_id='" . $_POST['bonus_crop_id'][$i] . "' AND product_type_id='" . $_POST['bonus_product_type_id'][$i] . "' AND pack_size='" . $_POST['bonus_pack_size'][$i] . "' AND warehouse_id='" . $warehouse_id . "'");
                if ($pid['product_id'] != 0)
                {
                    echo $mSQL_task = "update `$tbl" . "product_stock` set
                                            `bonus_quantity`=bonus_quantity+'" . $_POST["bonus_quantity"][$i] . "',
                                            `current_stock_qunatity`=current_stock_qunatity-'" . $_POST["bonus_quantity"][$i] . "',
                                            `status`='Active',
                                            `del_status`='0',
                                            `entry_by`='" . $user_id . "',
                                            `entry_date`='" . $db->ToDayDate() . "'
                                        where
                                            crop_id='" . $_POST['bonus_crop_id'][$i] . "'
                                            AND product_type_id='" . $_POST['bonus_product_type_id'][$i] . "'
                                            AND varriety_id='" . $_POST['bonus_varriety_id'][$i] . "'
                                            AND pack_size='" . $_POST['bonus_pack_size'][$i] . "'
                                            AND warehouse_id='$warehouse_id'
                                            AND year_id='$year_id'
                                        ";

                    if ($db->open())
                    {
                        $db->query($mSQL_task);
                        $db->freeResult();
                    }
                    $db->system_event_log('', $user_id, $employee_id, $maxID, '', $tbl . 'product_stock', 'Update', '');
                }
                else
                {
                    $rowfield = array
                    (
                        'year_id,' => "'" . $year_id . "',",
                        'warehouse_id,' => "'" . $warehouse_id . "',",
                        'crop_id,' => "'" . $_POST["bonus_crop_id"][$i] . "',",
                        'product_type_id,' => "'" . $_POST["bonus_product_type_id"][$i] . "',",
                        'varriety_id,' => "'" . $_POST["bonus_varriety_id"][$i] . "',",
                        'pack_size,' => "'" . $_POST["bonus_pack_size"][$i] . "',",
                        'bonus_quantity,' => "bonus_quantity+'" . $_POST["bonus_quantity"][$i] . "',",
                        'current_stock_qunatity,' => "current_stock_qunatity-'" . $_POST["bonus_quantity"][$i] . "',",
                        'status,' => "'Active',",
                        'del_status,' => "'0',",
                        'entry_by,' => "'$user_id',",
                        'entry_date' => "'" . $db->ToDayDate() . "'"
                    );

                    $db->data_insert($tbl . 'product_stock', $rowfield);
                    $db->system_event_log('', $user_id, $employee_id, '', '', $tbl . 'product_stock', 'Save', '');
                }
            }
        }
///////////  START BONUS TABLE UPDATE ////////////////////
        echo "VALIDATE";
    }
    else
    {
        echo "NOT_VALIDATE";
    }
}
?>