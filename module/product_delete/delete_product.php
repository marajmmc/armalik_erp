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

$user_id = $_SESSION['user_id'];
$employee_id = $_SESSION['employee_id'];
$tbl = _DB_PREFIX;
$db = new Database();

$row_id=$_POST['row_id'];



$db_ppi=new Database();
$ppi=$db_ppi->single_data_w($tbl. "product_purchase_info", "*", "id='".$row_id."'");

if(empty($ppi['id']))
{
    echo "This head office product purchase id is empty.";
    die();
}
$purchase_quantity=$ppi['quantity'];
$crop_id=$ppi['crop_id'];
$product_type_id=$ppi['product_type_id'];
$variety_id=$ppi['varriety_id'];
$pack_size=$ppi['pack_size'];
$warehouse_id=$ppi['warehouse_id'];

$db_ps=new Database();
$ps=$db_ps->single_data_w($tbl. "product_stock", "id", "crop_id='" . $crop_id . "' AND product_type_id='" . $product_type_id . "' AND varriety_id='" . $variety_id . "' AND pack_size='" . $pack_size . "' AND warehouse_id='" . $warehouse_id . "'");
if(empty($ps['id']))
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
echo "Successfully Delete.";
?>