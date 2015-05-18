<?php

session_start();
ob_start();
if ($_SESSION['logged'] != 'yes') {
    $_REQUEST["msg"] = "TimeoutC";
    header("location:../../index.php");
}
//echo $_SESSION['shop_name_eng']; 
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

//crop_id='" . $_POST['crop_id'] . "' AND product_type_id='" . $_POST['product_type_id'] . "' AND varriety_id='" . $_POST['varriety_id'] . "' AND warehouse_id='" . $_POST['warehouse_id'] . "' AND pack_size='" . $_POST['pack_size'] . "' 
$user_id = $_SESSION['user_id'];
$employee_id = $_SESSION['employee_id'];
$tbl = _DB_PREFIX;
$db = new Database();
$sql = "UPDATE $tbl" . "product_purchase_info SET del_status='1' WHERE id='" . $_POST['row_id'] . "'";
if ($db->open()) {
    $result = $db->query($sql);
    $result_array = $db->fetchAssoc();
}

echo $mSQL_task = "update `$tbl" . "product_stock` set
				`current_stock_qunatity`=current_stock_qunatity-'" . $_POST["purchase_quantity"] . "', 
				`status`='Active', 
				`del_status`='0', 
				`entry_by`='" . $user_id . "', 
				`entry_date`='" . $db->ToDayDate() . "'
			where crop_id='" . $_POST['crop_id'] . "' AND product_type_id='" . $_POST['product_type_id'] . "' AND varriety_id='" . $_POST['varriety_id'] . "' AND pack_size='" . $_POST['pack_size'] . "' AND warehouse_id='" . $_POST['warehouse_id'] . "'
				";

if ($db->open()) {
    $db->query($mSQL_task);
    $db->freeResult();
}

$db->system_event_log('', $user_id, $employee_id, '', '', $tbl . 'product_stock', 'Update', '');

$rowfield = array(
    'warehouse_id,' => "'" . $_POST["warehouse_id"] . "',",
    'crop_id,' => "'" . $_POST["crop_id"] . "',",
    'product_type_id,' => "'" . $_POST["product_type_id"] . "',",
    'varriety_id,' => "'" . $_POST["varriety_id"] . "',",
    'pack_size,' => "'" . $_POST["pack_size"] . "',",
    'quantity,' => "'" . $_POST["purchase_quantity"] . "',",
    'channel,' => "'Product Stock Delete',",
    'status,' => "'Active',",
    'del_status,' => "'0',",
    'entry_by,' => "'$user_id',",
    'entry_date' => "'" . $db->ToDayDate() . "'"
);

$db->data_insert($tbl . 'product_delete_info', $rowfield);
$db->system_event_log('', $user_id, $employee_id, '', '', $tbl . 'product_delete_info', 'Save', '');
?>