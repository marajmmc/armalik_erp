<?php
session_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$db = new Database();
$user_id = $_SESSION['user_id'];
$ei_id = $_SESSION['employee_id'];
$tbl = _DB_PREFIX;
$current_stock_qunatity=($_POST["current_stock_qunatity"]+$_POST['access_quantity'])-$_POST["damage_quantity"];
$pid = $db->single_data_w($tbl . 'product_stock', "count(id) as product_id", "crop_id='".$_POST['crop_id']."' AND product_type_id='".$_POST['product_type_id']."' AND varriety_id='".$_POST['varriety_id']."' AND pack_size='".$_POST['pack_size']."' AND warehouse_id='" . $_POST['warehouse_id'] . "'");
if ($pid['product_id'] != 0) {
    echo "update";
    echo $mSQL_task = "update `$tbl" . "product_stock` set
                                `inventory_quantity`=inventory_quantity+'" . $current_stock_qunatity . "', 
				`current_stock_qunatity`='$current_stock_qunatity', 
				`damage_quantity`=damage_quantity+'" . $_POST["damage_quantity"] . "', 
				`access_quantity`=access_quantity+'" . $_POST["access_quantity"] . "', 
				`status`='Active', 
				`del_status`='0', 
				`entry_by`='" . $user_id . "', 
				`entry_date`='" . $db->ToDayDate() . "'
			where crop_id='".$_POST['crop_id']."' AND product_type_id='".$_POST['product_type_id']."' AND varriety_id='".$_POST['varriety_id']."' AND pack_size='".$_POST['pack_size']."' AND warehouse_id='" . $_POST['warehouse_id'] . "'
				";

    if ($db->open()) {
        $db->query($mSQL_task);
        $db->freeResult();
    }
    $db->system_event_log('', $user_id, $ei_id, '', '', $tbl . 'product_stock', 'Update', '');
} else {
    echo "insert";
    $rowfield = array(
        'warehouse_id,' => "'" . $_POST["warehouse_id"] . "',",
        'crop_id,' => "'" . $_POST["crop_id"] . "',",
        'product_type_id,' => "'" . $_POST["product_type_id"] . "',",
        'varriety_id,' => "'" . $_POST["varriety_id"] . "',",
        'pack_size,' => "'" . $_POST["pack_size"] . "',",
        'inventory_quantity,' => "inventory_quantity+'" . $current_stock_qunatity . "',",
        'current_stock_qunatity,' => "'" . $current_stock_qunatity . "',",
        'damage_quantity,' => "damage_quantity+'" . $_POST["damage_quantity"] . "',",
        'access_quantity,' => "access_quantity+'" . $_POST["access_quantity"] . "',",
        'status,' => "'Active',",
        'del_status,' => "'0',",
        'entry_by,' => "'$user_id',",
        'entry_date' => "'" . $db->ToDayDate() . "'"
    );

    $db->data_insert($tbl . 'product_stock', $rowfield);
    $db->system_event_log('', $user_id, $ei_id, '', '', $tbl . 'product_stock', 'Save', '');
}

$MaxID = "IT-" . $db->getMaxID_six_digit($tbl . 'product_inventory', 'inventory_id');
$rowfield = array
(
    'inventory_id,' => "'" . $MaxID . "',",
    'inventory_date,' => "'" . $db->date_formate($_POST["inventory_date"]) . "',",
    'warehouse_id,' => "'" . $_POST["warehouse_id"] . "',",
    'year_id,' => "'" . $_POST["year_id"] . "',",
    'crop_id,' => "'" . $_POST["crop_id"] . "',",
    'product_type_id,' => "'" . $_POST["product_type_id"] . "',",
    'varriety_id,' => "'" . $_POST["varriety_id"] . "',",
    'pack_size,' => "'" . $_POST["pack_size"] . "',",
    'current_stock_qunatity,' => "'" . $_POST["current_stock_qunatity"] . "',",
    'damage_quantity,' => "'" . $_POST["damage_quantity"] . "',",
    'access_quantity,' => "'" . $_POST["access_quantity"] . "',",
    'remark,' => "'" . $_POST["remark"] . "',",
    'status,' => "'Active',",
    'del_status,' => "'0',",
    'entry_by,' => "'$user_id',",
    'entry_date' => "'" . $db->ToDayDate() . "'"
);

$db->data_insert($tbl . 'product_inventory', $rowfield);
$db->system_event_log('', $user_id, $ei_id, '', '', $tbl . 'product_inventory', 'Save', '');
?>