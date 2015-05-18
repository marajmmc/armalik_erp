<?php

session_start();

require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$db = new Database();
$dbto = new Database();
$user_id = $_SESSION['user_id'];
$ei_id = $_SESSION['employee_id'];
$tbl = _DB_PREFIX;


$pid = $db->single_data_w($tbl . 'product_stock', "count(id) as product_id", "crop_id='" . $_POST['crop_id'] . "' AND product_type_id='" . $_POST['product_type_id'] . "' AND varriety_id='" . $_POST['varriety_id'] . "' AND pack_size='" . $_POST['pack_size'] . "' AND warehouse_id='" . $_POST['to_warehouse_id'] . "'");
if ($pid['product_id'] > 0)
{
    echo $mSQL_task = "update `$tbl" . "product_stock` set
				`current_stock_qunatity`=current_stock_qunatity+'" . $_POST["quantity"] . "',
				`transert_receive_quantity`=transert_receive_quantity+'" . $_POST["quantity"] . "',
				`status`='Active',
				`del_status`='0',
				`entry_by`='" . $user_id . "',
				`entry_date`='" . $db->ToDayDate() . "'
			where crop_id='" . $_POST['crop_id'] . "' AND product_type_id='" . $_POST['product_type_id'] . "' AND varriety_id='" . $_POST['varriety_id'] . "' AND pack_size='" . $_POST['pack_size'] . "' AND warehouse_id='" . $_POST['to_warehouse_id'] . "'
				";

    if ($db->open())
    {
        $db->query($mSQL_task);
        $db->freeResult();
    }
    echo $mSQL_taskto = "update `$tbl" . "product_stock` set
				`current_stock_qunatity`=current_stock_qunatity-'" . $_POST["quantity"] . "',
                                `transfer_quantity`=transfer_quantity+'" . $_POST["quantity"] . "',
				`status`='Active',
				`del_status`='0',
				`entry_by`='" . $user_id . "',
				`entry_date`='" . $db->ToDayDate() . "'
			where crop_id='" . $_POST['crop_id'] . "' AND product_type_id='" . $_POST['product_type_id'] . "' AND varriety_id='" . $_POST['varriety_id'] . "' AND pack_size='" . $_POST['pack_size'] . "' AND warehouse_id='" . $_POST['from_warehouse_id'] . "'
				";

    if ($dbto->open())
    {
        $dbto->query($mSQL_taskto);
        $dbto->freeResult();
    }
    $db->system_event_log('', $user_id, $ei_id, '', '', $tbl . 'product_stock', 'Update', '');
}
else
{
    $rowfield = array(
        'warehouse_id,' => "'" . $_POST["to_warehouse_id"] . "',",
        'crop_id,' => "'" . $_POST["crop_id"] . "',",
        'product_type_id,' => "'" . $_POST["product_type_id"] . "',",
        'varriety_id,' => "'" . $_POST["varriety_id"] . "',",
        'pack_size,' => "'" . $_POST["pack_size"] . "',",
        'current_stock_qunatity,' => "current_stock_qunatity+'" . $_POST["quantity"] . "',",
        'transert_receive_quantity,' => "transert_receive_quantity+'" . $_POST["quantity"] . "',",
        'status,' => "'Active',",
        'del_status,' => "'0',",
        'entry_by,' => "'$user_id',",
        'entry_date' => "'" . $db->ToDayDate() . "'"
    );

    $db->data_insert($tbl . 'product_stock', $rowfield);
    $db->system_event_log('', $user_id, $ei_id, '', '', $tbl . 'product_stock', 'Save', '');

    echo $mSQL_taskto = "update `$tbl" . "product_stock` set
				`current_stock_qunatity`=current_stock_qunatity-'" . $_POST["quantity"] . "',
				`transfer_quantity`=transfer_quantity+'" . $_POST["quantity"] . "',
				`status`='Active',
				`del_status`='0',
				`entry_by`='" . $user_id . "',
				`entry_date`='" . $db->ToDayDate() . "'
			where crop_id='" . $_POST['crop_id'] . "' AND product_type_id='" . $_POST['product_type_id'] . "' AND varriety_id='" . $_POST['varriety_id'] . "' AND pack_size='" . $_POST['pack_size'] . "' AND warehouse_id='" . $_POST['from_warehouse_id'] . "'
				";

    if ($dbto->open())
    {
        $dbto->query($mSQL_taskto);
        $dbto->freeResult();
    }
    $db->system_event_log('', $user_id, $ei_id, '', '', $tbl . 'product_stock', 'Update', '');
}

$MaxID = "PT-" . $db->getMaxID_six_digit($tbl . 'product_transfer', 'transfer_id');
$rowfield = array(
    'transfer_id,' => "'" . $MaxID . "',",
    'transfer_date,' => "'" . $db->date_formate($_POST["transfer_date"]) . "',",
    'from_warehouse_id,' => "'" . $_POST["from_warehouse_id"] . "',",
    'to_warehouse_id,' => "'" . $_POST["to_warehouse_id"] . "',",
    'crop_id,' => "'" . $_POST["crop_id"] . "',",
    'product_type_id,' => "'" . $_POST["product_type_id"] . "',",
    'varriety_id,' => "'" . $_POST["varriety_id"] . "',",
    'pack_size,' => "'" . $_POST["pack_size"] . "',",
    'quantity,' => "'" . $_POST["quantity"] . "',",
    'status,' => "'Active',",
    'del_status,' => "'0',",
    'entry_by,' => "'$user_id',",
    'entry_date' => "'" . $db->ToDayDate() . "'"
);

$db->data_insert($tbl . 'product_transfer', $rowfield);
$db->system_event_log('', $user_id, $ei_id, '', '', $tbl . 'product_transfer', 'Save', '');

$pinfo = $db->single_data_w($tbl . 'product_info', "count(id) as productid", "crop_id='" . $_POST['crop_id'] . "' AND product_type_id='" . $_POST['product_type_id'] . "' AND pack_size='" . $_POST['pack_size'] . "' AND warehouse_id='" . $_POST['to_warehouse_id'] . "'");
if($pinfo['productid']<1)
{
    $maxID = "PI-" . $db->getMaxID_six_digit($tbl . 'product_info', 'product_id');
    $rowfield = array(
        'product_id,' => "'$maxID',",
        'warehouse_id,' => "'" . $_POST["to_warehouse_id"] . "',",
        'crop_id,' => "'" . $_POST["crop_id"] . "',",
        'product_type_id,' => "'" . $_POST["product_type_id"] . "',",
        'varriety_id,' => "'" . $_POST["varriety_id"] . "',",
        'pack_size,' => "'" . $_POST["pack_size"] . "',",
        'quantity,' => "'" . $_POST["quantity"] . "',",
        'status,' => "'Active',",
        'del_status,' => "'0',",
        'entry_by,' => "'$user_id',",
        'entry_date' => "'" . $db->ToDayDate() . "'"
    );

    $db->data_insert($tbl . 'product_info', $rowfield);
    $db->system_event_log('', $user_id, $ei_id, $maxID, '', $tbl . 'product_info', 'Save', '');
}


//$pp = $db->single_data_w($tbl . 'product_pricing', "selling_price, id, count(id) as ppid", "crop_id='" . $_POST['crop_id'] . "' AND product_type_id='" . $_POST['product_type_id'] . "' AND pack_size='" . $_POST['pack_size'] . "' AND status='Active'");
//
//if($pp['ppid']>0)
//{
//    echo $pp['id'];
//    $rowfield = array(
//        'crop_id' => "'" . $_POST["crop_id"] . "'",
//        'product_type_id' => "'" . $_POST["product_type_id"] . "'",
//        'varriety_id' => "'" . $_POST["varriety_id"] . "'",
//        'pack_size' => "'" . $_POST['pack_size'] . "'",
//        'selling_price' => "'" . $pp["selling_price"] . "'",
//        'entry_by' => "'$user_id'",
//        'entry_date' => "'" . $db->ToDayDate() . "'"
//    );
//    $wherefield=array('id' => "'".$pp['id']."'");
//    $db->data_update($tbl . 'product_pricing', $rowfield, $wherefield);
//    $db->system_event_log('', $user_id, $ei_id, $pp['id'], '', $tbl . 'product_pricing', 'Save', '');
//}
//else
//{
//    $maxID = "PP-".$db->getMaxID_six_digit($tbl . 'product_pricing', 'pricing_id');
//    $rowfield = array(
//        'pricing_id,' => "'" . $maxID . "',",
//        'crop_id,' => "'" . $_POST["crop_id"] . "',",
//        'product_type_id,' => "'" . $_POST["product_type_id"] . "',",
//        'varriety_id,' => "'" . $_POST["varriety_id"] . "',",
//        'pack_size,' => "'" . $_POST['pack_size'] . "',",
//        'selling_price,' => "'" . $pp["selling_price"] . "',",
//        'status,' => "'Active',",
//        'del_status,' => "'0',",
//        'entry_by,' => "'$user_id',",
//        'entry_date' => "'" . $db->ToDayDate() . "'"
//    );
//
//    $db->data_insert($tbl . 'product_pricing', $rowfield);
//    $db->system_event_log('', $user_id, $ei_id, $maxID, '', $tbl . 'product_pricing', 'Save', '');
//
//    echo $sql="UPDATE $tbl"."product_pricing SET status='In-Active' WHERE pricing_id!='$maxID' AND crop_id='" . $_POST["crop_id"] . "' AND product_type_id='".$_POST['product_type_id']."' AND varriety_id='" . $_POST["varriety_id"] . "' AND pack_size='".$_POST['pack_size']."'";
//    if($db->open()){
//        $db->query($sql);
//    }
//}


?>