<?php

session_start();

require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$db = new Database();
$dbud = new Database();
$user_id = $_SESSION['user_id'];
$employee_id = $_SESSION['employee_id'];
$tbl = _DB_PREFIX;

$total_pack_price = '';
$total_quantity = '';
$total_price = '';

$maxID = "DS-" . $db->Get_CustMaxID($tbl . 'distributor_product_sale', 'sale_id', '8', '');

$count = count($_POST['id']);
for ($i = 0; $i < $count; $i++) {
    $total_price = $total_price + $_POST["total_price"][$i];
    $rowfield = array(
        'sale_id,' => "'" . $maxID . "',",
        'sale_date,' => "'" . $db->date_formate($_POST["sale_date"]) . "',",
        'zone_id,' => "'" . $_POST["zone_id"] . "',",
        'territory_id,' => "'" . $_POST["territory_id"] . "',",
        'distributor_id,' => "'" . $_POST["distributor_id"] . "',",
        'dealer_id,' => "'" . $_POST["dealer_id"] . "',",
        'crop_id,' => "'" . $_POST["crop_id"][$i] . "',",
        'product_type_id,' => "'" . $_POST["product_type_id"][$i] . "',",
        'varriety_id,' => "'" . $_POST["varriety_id"][$i] . "',",
        'pack_size,' => "'" . $_POST["pack_size"][$i] . "',",
        'purchase_price,' => "'" . $_POST["purchase_price"][$i] . "',",
        'price,' => "'" . $_POST["price"][$i] . "',",
        'quantity,' => "'" . $_POST["quantity"][$i] . "',",
        'total_price,' => "'" . $_POST["total_price"][$i] . "',",
        'status,' => "'Sale',",
        'del_status,' => "'0',",
        'entry_by,' => "'$user_id',",
        'entry_date' => "'" . $db->ToDayDate() . "'"
    );

    $db->data_insert($tbl . 'distributor_product_sale', $rowfield);
    $db->system_event_log('', $user_id, $employee_id, $maxID, '', $tbl . 'distributor_product_sale', 'Save', '');

    ///////////  START PRODUCT STOCK UPDATE ////////////////////

    $pid = $db->single_data_w($tbl . 'distributor_product_stock', "count(id) as product_id", "crop_id='" . $_POST['crop_id'][$i] . "' AND product_type_id='" . $_POST['product_type_id'][$i] . "' AND varriety_id='" . $_POST['varriety_id'][$i] . "' AND pack_size='" . $_POST['pack_size'][$i] . "' AND distributor_id='" . $_POST["distributor_id"] . "'");
    if ($pid['product_id'] != 0) {
        echo $mSQL_task = "update `$tbl" . "distributor_product_stock` set
                                `sale_quantity`=sale_quantity+'" . $_POST["quantity"][$i] . "', 
				`current_stock_qunatity`=current_stock_qunatity-'" . $_POST["quantity"][$i] . "', 
				`status`='Active', 
				`del_status`='0', 
				`entry_by`='" . $user_id . "', 
				`entry_date`='" . $db->ToDayDate() . "'
			where crop_id='" . $_POST['crop_id'][$i] . "' AND product_type_id='" . $_POST['product_type_id'][$i] . "' AND varriety_id='" . $_POST['varriety_id'][$i] . "' AND pack_size='" . $_POST['pack_size'][$i] . "' AND distributor_id='".$_POST["distributor_id"]."'
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
            'sale_quantity,' => "sale_quantity+'" . $_POST["quantity"][$i] . "',",
            'current_stock_qunatity,' => "current_stock_qunatity-'" . $_POST["quantity"][$i] . "',",
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

///////////  START DISTRIBUTOR UPDATE ////////////////////

$dstbtor = $db->single_data($tbl . "distributor_balance", "count(distributor_id) as distributor_id", "distributor_id", $_POST["distributor_id"]);
if ($dstbtor['distributor_id'] != 0) {
    $rowfield = array(
        'sale_amount' => "sale_amount+'" . $total_price . "'",
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
        'sale_amount,' => "sale_amount+'" . $total_price . "',",
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