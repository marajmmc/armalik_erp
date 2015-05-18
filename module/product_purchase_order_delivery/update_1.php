<?php

session_start();

require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$db = new Database();
$dbud = new Database();
$dbbn = new Database();
$user_id = $_SESSION['user_id'];
$employee_id = $_SESSION['employee_id'];
$warehouse_id = $_SESSION['warehouse_id'];
$tbl = _DB_PREFIX;

$total_pack_price = '';
$total_quantity = '';
$total_price = '';

$maxID = "PC-" . $db->Get_CustMaxID($tbl . 'product_purchase_order_challan', 'challan_id', '8', '');

$count = count($_POST['id']);
for ($i = 0; $i < $count; $i++) {
    $total_price = $total_price + $_POST["total_price"][$i];
    $rowfield = array(
        'challan_id,' => "'" . $maxID . "',",
        'warehouse_id,' => "'" . $warehouse_id . "',",
        'purchase_order_id,' => "'" . $_POST['purchase_order_id'] . "',",
        'invoice_id,' => "'" . $_POST['invoice_id'] . "',",
        'challan_date,' => "'" . $db->date_formate($_POST["challan_date"]) . "',",
        'zone_id,' => "'" . $_POST["zone_id"] . "',",
        'territory_id,' => "'" . $_POST["territory_id"] . "',",
        'distributor_id,' => "'" . $_POST["distributor_id"] . "',",
        'crop_id,' => "'" . $_POST["crop_id"][$i] . "',",
        'product_type_id,' => "'" . $_POST["product_type_id"][$i] . "',",
        'varriety_id,' => "'" . $_POST["varriety_id"][$i] . "',",
        'pack_size,' => "'" . $_POST["pack_size"][$i] . "',",
        'price,' => "'" . $_POST["price"][$i] . "',",
        'quantity,' => "'" . $_POST["quantity"][$i] . "',",
        'total_price,' => "'" . $_POST["total_price"][$i] . "',",
        'status,' => "'Pending',",
        'del_status,' => "'0',",
        'entry_by,' => "'$user_id',",
        'entry_date' => "'" . $db->ToDayDate() . "'"
    );

    $db->data_insert($tbl . 'product_purchase_order_challan', $rowfield);
    $db->system_event_log('', $user_id, $employee_id, $maxID, '', $tbl . 'product_purchase_order_challan', 'Save', '');

    ///////////  START PRODUCT STOCK UPDATE ////////////////////

    $pid = $db->single_data_w($tbl . 'product_stock', "count(id) as product_id", "crop_id='" . $_POST['crop_id'][$i] . "' AND product_type_id='" . $_POST['product_type_id'][$i] . "' AND pack_size='" . $_POST['pack_size'][$i] . "' AND warehouse_id='" . $warehouse_id . "'");
    if ($pid['product_id'] != 0) {
        echo $mSQL_task = "update `$tbl" . "product_stock` set
                                `delivery_quantity`=delivery_quantity+'" . $_POST["quantity"][$i] . "', 
				`current_stock_qunatity`=current_stock_qunatity-'" . $_POST["quantity"][$i] . "', 
				`warehouse_id`='$warehouse_id', 
				`status`='Active', 
				`del_status`='0', 
				`entry_by`='" . $user_id . "', 
				`entry_date`='" . $db->ToDayDate() . "'
			where crop_id='" . $_POST['crop_id'][$i] . "' AND product_type_id='" . $_POST['product_type_id'][$i] . "' AND varriety_id='" . $_POST['varriety_id'][$i] . "' AND pack_size='" . $_POST['pack_size'][$i] . "' AND warehouse_id='$warehouse_id'
				";

        if ($db->open()) {
            $db->query($mSQL_task);
            $db->freeResult();
        }
        $db->system_event_log('', $user_id, $employee_id, $maxID, '', $tbl . 'product_stock', 'Update', '');
    } else {
        $rowfield = array(
            'warehouse_id,' => "'" . $warehouse_id . "',",
            'crop_id,' => "'" . $_POST["crop_id"][$i] . "',",
            'product_type_id,' => "'" . $_POST["product_type_id"][$i] . "',",
            'varriety_id,' => "'" . $_POST["varriety_id"][$i] . "',",
            'pack_size,' => "'" . $_POST["pack_size"][$i] . "',",
            'delivery_quantity,' => "delivery_quantity+'" . $_POST["quantity"][$i] . "',",
            'current_stock_qunatity,' => "current_stock_qunatity-'" . $_POST["quantity"][$i] . "',",
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

$updatesql = "UPDATE $tbl" . "product_purchase_order_invoice SET 
                    warehouse_id='$warehouse_id',
                    status='Delivery'
                WHERE invoice_id='" . $_POST['invoice_id'] . "'";
if ($dbud->open()) {
    $result = $dbud->query($updatesql);
}
$updatebonus = "UPDATE $tbl" . "product_purchase_order_bonus SET 
                    warehouse_id='$warehouse_id',
                    challan_id='$maxID'
                WHERE invoice_id='" . $_POST['invoice_id'] . "'";
if ($dbbn->open()) {
    $result = $dbbn->query($updatebonus);
}
///////////  START DISTRIBUTOR UPDATE ////////////////////

$dstbtor = $db->single_data($tbl . "distributor_balance", "count(distributor_id) as distributor_id", "distributor_id", $_POST["distributor_id"]);
if ($dstbtor['distributor_id'] != 0) {
    $rowfield = array(
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
} else {
    $rowfield = array(
        'distributor_id,' => "'" . $_POST["distributor_id"] . "',",
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

$bonus_count = count($_POST['bonus_crop_id']);
for ($i = 0; $i < $bonus_count; $i++) {
    $pid = $db->single_data_w($tbl . 'product_stock', "count(id) as product_id", "crop_id='" . $_POST['bonus_crop_id'][$i] . "' AND product_type_id='" . $_POST['bonus_product_type_id'][$i] . "' AND pack_size='" . $_POST['bonus_pack_size'][$i] . "' AND warehouse_id='" . $warehouse_id . "'");
    if ($pid['product_id'] != 0) {
        echo $mSQL_task = "update `$tbl" . "product_stock` set
                                `bonus_quantity`=bonus_quantity+'" . $_POST["bonus_quantity"][$i] . "', 
				`current_stock_qunatity`=current_stock_qunatity-'" . $_POST["bonus_quantity"][$i] . "', 
				`status`='Active', 
				`del_status`='0', 
				`entry_by`='" . $user_id . "', 
				`entry_date`='" . $db->ToDayDate() . "'
			where crop_id='" . $_POST['bonus_crop_id'][$i] . "' AND product_type_id='" . $_POST['bonus_product_type_id'][$i] . "' AND varriety_id='" . $_POST['bonus_varriety_id'][$i] . "' AND pack_size='" . $_POST['bonus_pack_size'][$i] . "' AND warehouse_id='$warehouse_id'
				";

        if ($db->open()) {
            $db->query($mSQL_task);
            $db->freeResult();
        }
        $db->system_event_log('', $user_id, $employee_id, $maxID, '', $tbl . 'product_stock', 'Update', '');
    } else {
        $rowfield = array(
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
?>