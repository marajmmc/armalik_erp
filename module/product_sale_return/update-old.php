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

$total_pack_price = '';
$total_quantity = '';
$total_price = '';

$maxID = "PR-" . $db->Get_CustMaxID($tbl . 'product_purchase_order_challan_return', 'return_challan_id', '8', '');

$count = count($_POST['id']);
for ($i = 0; $i < $count; $i++) {
    $total_price = $total_price + $_POST["total_price"][$i];
    $rowfield = array(
        'return_challan_id,' => "'" . $maxID . "',",
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
        'return_quantity,' => "'" . $_POST["return_quantity"][$i] . "',",
        'total_price,' => "'" . $_POST["total_price"][$i] . "',",
        'warehouse_id,' => "'" . $_POST["warehouse_id"][$i] . "',",
        'status,' => "'Pending',",
        'del_status,' => "'0',",
        'entry_by,' => "'$user_id',",
        'entry_date' => "'" . $db->ToDayDate() . "'"
    );

    $db->data_insert($tbl . 'product_purchase_order_challan_return', $rowfield);
    $db->system_event_log('', $user_id, $employee_id, $maxID, '', $tbl . 'product_purchase_order_challan_return', 'Save', '');

    $updateinv = "UPDATE $tbl" . "product_purchase_order_invoice SET
                approved_quantity=approved_quantity-'" . $_POST['return_quantity'][$i] . "',
                total_price=total_price-'" . $_POST['total_price'][$i] . "'
            WHERE 
                invoice_id='" . $_POST['invoice_id'] . "' AND 
                crop_id='" . $_POST['crop_id'][$i] . "' AND 
                product_type_id='" . $_POST['product_type_id'][$i] . "' AND 
                varriety_id='" . $_POST['varriety_id'][$i] . "' AND 
                pack_size='" . $_POST['pack_size'][$i] . "' AND 
                distributor_id='" . $_POST["distributor_id"] . "'
    ";
    if ($db->open()) {
        $db->query($updateinv);
    }
    $updateCh = "UPDATE $tbl" . "product_purchase_order_challan SET
                quantity=quantity-'" . $_POST['return_quantity'][$i] . "',
                total_price=total_price-'" . $_POST['total_price'][$i] . "'
            WHERE 
                invoice_id='" . $_POST['invoice_id'] . "' AND 
                crop_id='" . $_POST['crop_id'][$i] . "' AND 
                product_type_id='" . $_POST['product_type_id'][$i] . "' AND 
                varriety_id='" . $_POST['varriety_id'][$i] . "' AND 
                pack_size='" . $_POST['pack_size'][$i] . "' AND 
                distributor_id='" . $_POST["distributor_id"] . "'
    ";
    if ($db->open()) {
        $db->query($updateCh);
    }
    $updateChR = "UPDATE $tbl" . "product_purchase_order_challan_received SET
                quantity=quantity-'" . $_POST['return_quantity'][$i] . "',
                total_price=total_price-'" . $_POST['total_price'][$i] . "'
            WHERE 
                invoice_id='" . $_POST['invoice_id'] . "' AND 
                crop_id='" . $_POST['crop_id'][$i] . "' AND 
                product_type_id='" . $_POST['product_type_id'][$i] . "' AND 
                varriety_id='" . $_POST['varriety_id'][$i] . "' AND 
                pack_size='" . $_POST['pack_size'][$i] . "' AND 
                distributor_id='" . $_POST["distributor_id"] . "'
    ";

    if ($db->open()) {
        $db->query($updateChR);
    }

    ///////////  START PRODUCT STOCK UPDATE ////////////////////

    $pid = $db->single_data_w($tbl . 'product_stock', "count(id) as product_id", "crop_id='" . $_POST['crop_id'][$i] . "' AND product_type_id='" . $_POST['product_type_id'][$i] . "' AND pack_size='" . $_POST['pack_size'][$i] . "' AND warehouse_id='" . $_POST["warehouse_id"][$i] . "'");
    if ($pid['product_id'] != 0) {
        echo $mSQL_task = "update `$tbl" . "product_stock` set
                                `return_quantity`=return_quantity+'" . $_POST["return_quantity"][$i] . "', 
				`current_stock_qunatity`=current_stock_qunatity+'" . $_POST["return_quantity"][$i] . "', 
				`status`='Active', 
				`del_status`='0', 
				`entry_by`='" . $user_id . "', 
				`entry_date`='" . $db->ToDayDate() . "'
			where crop_id='" . $_POST['crop_id'][$i] . "' AND product_type_id='" . $_POST['product_type_id'][$i] . "' AND varriety_id='" . $_POST['varriety_id'][$i] . "' AND pack_size='" . $_POST['pack_size'][$i] . "' AND warehouse_id='" . $_POST["warehouse_id"][$i] . "'
				";

        if ($db->open()) {
            $db->query($mSQL_task);
            $db->freeResult();
        }
        $db->system_event_log('', $user_id, $employee_id, $maxID, '', $tbl . 'product_stock', 'Update', '');
    } else {
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

///////////  END PRODUCT STOCK UPDATE ////////////////////
}

///////////  START DISTRIBUTOR UPDATE ////////////////////

$dstbtor = $db->single_data($tbl . "distributor_balance", "count(distributor_id) as distributor_id", "distributor_id", $_POST["distributor_id"]);
if ($dstbtor['distributor_id'] != 0) {
    $rowfield = array(
        'credit_limit_amount' => "credit_limit_amount-'" . $total_price . "'",
        'balance_amount' => "balance_amount+'" . $total_price . "'",
        'due_amount' => "due_amount-'" . $total_price . "'",
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
?>