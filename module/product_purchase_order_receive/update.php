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
$dbud = new Database();
$user_id = $_SESSION['user_id'];
$employee_id = $_SESSION['employee_id'];
$warehouse_id = $_SESSION['warehouse_id'];
$tbl = _DB_PREFIX;

$total_pack_price = '';
$total_quantity = '';
$total_price = '';
$current_stock_qunatity = '';

$maxID = "CR-" . $db->Get_CustMaxID($tbl . 'product_purchase_order_challan_received', 'challan_received_id', '8', '');

$count = count($_POST['id']);
for ($i = 0; $i < $count; $i++) {
    $total_price = $total_price + $_POST["total_price"][$i];
    $rowfield = array(
        'challan_received_id,' => "'" . $maxID . "',",
        'invoice_id,' => "'" . $_POST['invoice_id'] . "',",
        'challan_id,' => "'" . $_POST['challan_id'] . "',",
        'purchase_order_id,' => "'" . $_POST['purchase_order_id'] . "',",
        'challan_received_date,' => "'" . $db->date_formate($_POST["challan_received_date"]) . "',",
        'zone_id,' => "'" . $_POST["zone_id"] . "',",
        'territory_id,' => "'" . $_POST["territory_id"] . "',",
        'distributor_id,' => "'" . $_POST["distributor_id"] . "',",
        'crop_id,' => "'" . $_POST["crop_id"][$i] . "',",
        'product_type_id,' => "'" . $_POST["product_type_id"][$i] . "',",
        'varriety_id,' => "'" . $_POST["varriety_id"][$i] . "',",
        'pack_size,' => "'" . $_POST["pack_size"][$i] . "',",
        'price,' => "'" . $_POST["price"][$i] . "',",
        'quantity,' => "'" . $_POST["quantity"][$i] . "',",
        'loss_quantity,' => "'" . $_POST["loss_quantity"][$i] . "',",
        'extra_quantity,' => "'" . $_POST["extra_quantity"][$i] . "',",
        'total_price,' => "'" . $_POST["total_price"][$i] . "',",
        'status,' => "'Received',",
        'del_status,' => "'0',",
        'entry_by,' => "'$user_id',",
        'entry_date' => "'" . $db->ToDayDate() . "'"
    );

    $db->data_insert($tbl . 'product_purchase_order_challan_received', $rowfield);
    $db->system_event_log('', $user_id, $employee_id, $maxID, '', $tbl . 'product_purchase_order_challan_received', 'Save', '');

    ///////////  START PRODUCT STOCK UPDATE ////////////////////

    $pid = $db->single_data_w($tbl . 'distributor_product_stock', "count(id) as product_id", "crop_id='" . $_POST['crop_id'][$i] . "' AND product_type_id='" . $_POST['product_type_id'][$i] . "' AND varriety_id='" . $_POST['varriety_id'][$i] . "' AND pack_size='" . $_POST['pack_size'][$i] . "' AND distributor_id='" . $_POST['distributor_id'] . "'");
    $current_stock_qunatity = ($_POST["quantity"][$i] - $_POST["loss_quantity"][$i]) + $_POST["extra_quantity"][$i];
    if ($pid['product_id'] != 0) {
        echo $mSQL_task = "update `$tbl" . "distributor_product_stock` set
                                `purchase_quantity`=purchase_quantity+'" . $_POST["quantity"][$i] . "', 
				`current_stock_qunatity`=current_stock_qunatity+'" . $current_stock_qunatity . "', 
				`loss_quantity`=loss_quantity+'" . $_POST["loss_quantity"][$i] . "', 
				`extra_quantity`=extra_quantity+'" . $_POST["extra_quantity"][$i] . "', 
				`status`='Active', 
				`del_status`='0', 
				`entry_by`='" . $user_id . "', 
				`entry_date`='" . $db->ToDayDate() . "'
			where crop_id='" . $_POST['crop_id'][$i] . "' AND product_type_id='" . $_POST['product_type_id'][$i] . "' AND varriety_id='" . $_POST['varriety_id'][$i] . "' AND pack_size='" . $_POST['pack_size'][$i] . "' AND distributor_id='" . $_POST['distributor_id'] . "'
				";

        if ($db->open()) {
            $db->query($mSQL_task);
            $db->freeResult();
        }
        $db->system_event_log('', $user_id, $employee_id, $maxID, '', $tbl . 'distributor_product_stock', 'Update', '');
    } else {
        $rowfield = array(
            'distributor_id,' => "'" . $_POST["distributor_id"] . "',",
            'crop_id,' => "'" . $_POST["crop_id"][$i] . "',",
            'product_type_id,' => "'" . $_POST["product_type_id"][$i] . "',",
            'varriety_id,' => "'" . $_POST["varriety_id"][$i] . "',",
            'pack_size,' => "'" . $_POST["pack_size"][$i] . "',",
            'purchase_quantity,' => "purchase_quantity+'" . $_POST["quantity"][$i] . "',",
            'current_stock_qunatity,' => "current_stock_qunatity+'" . $current_stock_qunatity . "',",
            'loss_quantity,' => "loss_quantity+'" . $_POST["loss_quantity"][$i] . "',",
            'extra_quantity,' => "extra_quantity+'" . $_POST["extra_quantity"][$i] . "',",
            'status,' => "'Active',",
            'del_status,' => "'0',",
            'entry_by,' => "'$user_id',",
            'entry_date' => "'" . $db->ToDayDate() . "'"
        );

        $db->data_insert($tbl . 'distributor_product_stock', $rowfield);
        $db->system_event_log('', $user_id, $employee_id, '', '', $tbl . 'distributor_product_stock', 'Save', '');
    }

///////////  END PRODUCT STOCK UPDATE ////////////////////
}

$updatesql = "UPDATE $tbl" . "product_purchase_order_challan SET 
                    status='Received'
                WHERE challan_id='" . $_POST['challan_id'] . "'";
if ($dbud->open()) {
    $result = $dbud->query($updatesql);
}

$countbonus = count($_POST['bonus_crop_id']);
for ($i = 0; $i < $countbonus; $i++) {
    $pid = $db->single_data_w($tbl . 'distributor_product_stock', "count(id) as product_id", "crop_id='" . $_POST['bonus_crop_id'][$i] . "' AND product_type_id='" . $_POST['bonus_product_type_id'][$i] . "' AND varriety_id='" . $_POST['bonus_varriety_id'][$i] . "' AND pack_size='" . $_POST['bonus_pack_size'][$i] . "' AND distributor_id='" . $_POST['distributor_id'] . "'");
    if ($pid['product_id'] != 0) {
        echo $mSQL_task = "update `$tbl" . "distributor_product_stock` set
                                `bonus_quantity`=bonus_quantity+'" . $_POST["bonus_quantity"][$i] . "', 
				`current_stock_qunatity`=current_stock_qunatity+'" . $_POST["bonus_quantity"][$i] . "', 
				`status`='Active', 
				`del_status`='0', 
				`entry_by`='" . $user_id . "', 
				`entry_date`='" . $db->ToDayDate() . "'
			where crop_id='" . $_POST['bonus_crop_id'][$i] . "' AND product_type_id='" . $_POST['bonus_product_type_id'][$i] . "' AND varriety_id='" . $_POST['bonus_varriety_id'][$i] . "' AND pack_size='" . $_POST['bonus_pack_size'][$i] . "' AND distributor_id='" . $_POST['distributor_id'] . "'
				";

        if ($db->open()) {
            $db->query($mSQL_task);
            $db->freeResult();
        }
        $db->system_event_log('', $user_id, $employee_id, $maxID, '', $tbl . 'distributor_product_stock', 'Update', '');
    } else {
        $rowfield = array(
            'distributor_id,' => "'" . $_POST["distributor_id"] . "',",
            'crop_id,' => "'" . $_POST["bonus_crop_id"][$i] . "',",
            'product_type_id,' => "'" . $_POST["bonus_product_type_id"][$i] . "',",
            'varriety_id,' => "'" . $_POST["bonus_varriety_id"][$i] . "',",
            'pack_size,' => "'" . $_POST["bonus_pack_size"][$i] . "',",
            'bonus_quantity,' => "bonus_quantity+'" . $_POST["bonus_quantity"][$i] . "',",
            'current_stock_qunatity,' => "current_stock_qunatity+'" . $_POST["bonus_quantity"][$i] . "',",
            'status,' => "'Active',",
            'del_status,' => "'0',",
            'entry_by,' => "'$user_id',",
            'entry_date' => "'" . $db->ToDayDate() . "'"
        );

        $db->data_insert($tbl . 'distributor_product_stock', $rowfield);
        $db->system_event_log('', $user_id, $employee_id, '', '', $tbl . 'distributor_product_stock', 'Save', '');
    }
}
?>