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

$crop_id=$_POST['crop_id'];
$product_type_id=$_POST['product_type_id'];
$variety_id=$_POST['varriety_id'];
$pack_size=$_POST['pack_size'];
$warehouse_id=$_POST['warehouse_id'];
$row_id=$_POST['row_id'];


if(empty($crop_id) && empty($product_type_id) && empty($variety_id) && empty($pack_size) && empty($warehouse_id))
{
    echo "Please check warehouse, crop, product type, variety, pack size.";
    die();
}

$db_ppi=new Database();
$ppi=$db_ppi->single_data_w($tbl. "product_purchase_info", "id, quantity", "id='".$row_id."'");
$purchase_quantity=$ppi['quantity'];
if(empty($ppi['id']))
{
    echo "This head office product purchase id is empty.";
    die();
}

//$db_pi=new Database();
//$pi=$db_pi->single_data_w($tbl. "product_info", "id, quantity", "crop_id='" . $crop_id . "' AND product_type_id='" . $product_type_id . "' AND varriety_id='" . $variety_id . "' AND pack_size='" . $pack_size . "' AND warehouse_id='" . $warehouse_id . "'");
//if(empty($pi['id']) && ($pi['quantity']<$purchase_quantity))
//{
//    echo "This product info id is empty or product quantity over.";
//    die();
//}

$db_ps=new Database();
$ps=$db_ps->single_data_w($tbl. "product_stock", "id", "crop_id='" . $crop_id . "' AND product_type_id='" . $product_type_id . "' AND varriety_id='" . $variety_id . "' AND pack_size='" . $pack_size . "' AND warehouse_id='" . $warehouse_id . "'");
if(empty($ps['id']) && ($ps['current_stock_qunatity']<$purchase_quantity))
{
    echo "This product stock id is empty or product quantity over.";
    die();
}

$sql = "UPDATE $tbl" . "product_purchase_info SET del_status='1' WHERE id='" . $row_id . "'";
if ($db->open())
{
    $result = $db->query($sql);
    $result_array = $db->fetchAssoc();
}

//$sql = "UPDATE $tbl" . "product_info SET
//            quantity=quantity-'".$purchase_quantity."'
//        WHERE
//            crop_id='" . $crop_id . "' AND product_type_id='" . $product_type_id . "' AND varriety_id='" . $variety_id . "' AND pack_size='" . $pack_size . "' AND warehouse_id='" . $warehouse_id . "'
//        ";
//if ($db->open())
//{
//    $result = $db->query($sql);
//    $result_array = $db->fetchAssoc();
//}

$mSQL_task = "update `$tbl" . "product_stock` set
				`current_stock_qunatity`=current_stock_qunatity-'" . $purchase_quantity . "',
				`status`='Active', 
				`del_status`='0', 
				`entry_by`='" . $user_id . "', 
				`entry_date`='" . $db->ToDayDate() . "'
            where crop_id='" . $crop_id . "' AND product_type_id='" . $product_type_id . "' AND varriety_id='" . $variety_id . "' AND pack_size='" . $pack_size . "' AND warehouse_id='" . $warehouse_id . "'
            ";

if ($db->open())
{
    $db->query($mSQL_task);
    $db->freeResult();
}

$db->system_event_log('', $user_id, $employee_id, '', '', $tbl . 'product_stock', 'Update', '');

$rowfield = array
(
    'warehouse_id,' => "'" . $warehouse_id . "',",
    'crop_id,' => "'" . $crop_id . "',",
    'product_type_id,' => "'" . $product_type_id . "',",
    'varriety_id,' => "'" . $variety_id . "',",
    'pack_size,' => "'" . $pack_size . "',",
    'quantity,' => "'" . $purchase_quantity . "',",
    'channel,' => "'Product Stock Delete',",
    'status,' => "'Active',",
    'del_status,' => "'0',",
    'entry_by,' => "'$user_id',",
    'entry_date' => "'" . $db->ToDayDate() . "'"
);

$db->data_insert($tbl . 'product_delete_info', $rowfield);
$db->system_event_log('', $user_id, $employee_id, '', '', $tbl . 'product_delete_info', 'Save', '');
?>