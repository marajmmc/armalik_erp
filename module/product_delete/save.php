<?php

session_start();

require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$db = new Database();
$user_id = $_SESSION['user_id'];
$employee_id = $_SESSION['employee_id'];
$tbl = _DB_PREFIX;
echo "ok";
///////////  START PRODUCT QUANTITY ////////////////////

//$maxID = "PI-" . $db->getMaxID_six_digit($tbl . 'product_info', 'product_id');
//$ppo = $db->single_data_w($tbl . "product_info", "pack_size, product_id", "crop_id='" . $_POST['crop_id'] . "' AND product_type_id='" . $_POST['product_type_id'] . "' AND varriety_id='" . $_POST['varriety_id'] . "' AND warehouse_id='" . $_POST['warehouse_id'] . "' AND pack_size='" . $_POST['pack_size'] . "' ");
//if ($ppo['pack_size'] == "") {
//    $rowfield = array(
//        'product_id,' => "'$maxID',",
//        'warehouse_id,' => "'" . $_POST["warehouse_id"] . "',",
//        'crop_id,' => "'" . $_POST["crop_id"] . "',",
//        'product_type_id,' => "'" . $_POST["product_type_id"] . "',",
//        'varriety_id,' => "'" . $_POST["varriety_id"] . "',",
////        'product_type,' => "'" . $_POST["product_type"] . "',",
//        'pack_size,' => "'" . $_POST["pack_size"] . "',",
//        'quantity,' => "'" . $_POST["quantity"] . "',",
//        'mfg_date,' => "'" . $db->date_formate($_POST["mfg_date"]) . "',",
//        'exp_date,' => "'" . $db->date_formate($_POST["exp_date"]) . "',",
//        'status,' => "'Active',",
//        'del_status,' => "'0',",
//        'entry_by,' => "'$user_id',",
//        'entry_date' => "'" . $db->ToDayDate() . "'"
//    );
//
//    $db->data_insert($tbl . 'product_info', $rowfield);
//    $db->system_event_log('', $user_id, $employee_id, $maxID, '', $tbl . 'product_info', 'Save', '');
//} else {
////    $sqlupdate="UPDATE $tbl"."product_info SET
////        warehouse_id='" . $_POST["warehouse_id"] . "',
////        crop_id='" . $_POST["crop_id"] . "',
////        product_type_id='" . $_POST["product_type_id"] . "',
////        varriety_id='" . $_POST["varriety_id"] . "',
////        product_type='" . $_POST["product_type"] . "',
////        pack_size='" . $_POST["pack_size"] . "',
////        quantity='" . $_POST["quantity"] . "',
////        mfg_date='" . $db->date_formate($_POST["mfg_date"]) . "',
////        exp_date='" .  $db->date_formate($_POST["exp_date"]) . "',
////        status='Active',
////        del_status='0',
////        status='Active',
////        entry_by='$user_id',
////        entry_date='" . $db->ToDayDate() . "'
////    WHERE product_id=''
////";
//
//    $rowfield = array(
//        'warehouse_id' => "'" . $_POST["warehouse_id"] . "'",
//        'crop_id' => "'" . $_POST["crop_id"] . "'",
//        'product_type_id' => "'" . $_POST["product_type_id"] . "'",
//        'varriety_id' => "'" . $_POST["varriety_id"] . "'",
//        'product_type' => "'" . $_POST["product_type"] . "'",
//        'pack_size' => "'" . $_POST["pack_size"] . "'",
//        'quantity' => "'" . $_POST["quantity"] . "'",
//        'mfg_date' => "'" . $db->date_formate($_POST["mfg_date"]) . "'",
//        'exp_date' => "'" . $db->date_formate($_POST["exp_date"]) . "'",
//        'status' => "'Active'",
//        'del_status' => "'0'",
//        'entry_by' => "'$user_id'",
//        'entry_date' => "'" . $db->ToDayDate() . "'"
//    );
//    $warefield = array('product_id' => "'" . $ppo['product_id'] . "'");
//    $db->data_update($tbl . 'product_info', $rowfield, $warefield);
//    $db->system_event_log('', $user_id, $employee_id, $maxID, '', $tbl . 'product_info', 'Update', '');
//}
/////////////  END PRODUCT QUANTITY ////////////////////
/////////////  START PRODUCT STOCK UPDATE ////////////////////
//
//$pid = $db->single_data_w($tbl . 'product_stock', "count(id) as product_id", "crop_id='" . $_POST['crop_id'] . "' AND product_type_id='" . $_POST['product_type_id'] . "' AND varriety_id='" . $_POST['varriety_id'] . "' AND pack_size='" . $_POST['pack_size'] . "' AND warehouse_id='" . $_POST['warehouse_id'] . "'");
//if ($pid['product_id'] != 0) {
//    echo $mSQL_task = "update `$tbl" . "product_stock` set
//                                `first_stock_quantity`=first_stock_quantity+'" . $_POST["quantity"] . "', 
//				`current_stock_qunatity`=current_stock_qunatity+'" . $_POST["quantity"] . "', 
//				`status`='Active', 
//				`del_status`='0', 
//				`entry_by`='" . $user_id . "', 
//				`entry_date`='" . $db->ToDayDate() . "'
//			where crop_id='" . $_POST['crop_id'] . "' AND product_type_id='" . $_POST['product_type_id'] . "' AND varriety_id='" . $_POST['varriety_id'] . "' AND pack_size='" . $_POST['pack_size'] . "' AND warehouse_id='" . $_POST['warehouse_id'] . "'
//				";
//
//    if ($db->open()) {
//        $db->query($mSQL_task);
//        $db->freeResult();
//    }
//    $db->system_event_log('', $user_id, $employee_id, $maxID, '', $tbl . 'product_stock', 'Update', '');
//} else {
//    $rowfield = array(
//        'warehouse_id,' => "'" . $_POST["warehouse_id"] . "',",
//        'crop_id,' => "'" . $_POST["crop_id"] . "',",
//        'product_type_id,' => "'" . $_POST["product_type_id"] . "',",
//        'varriety_id,' => "'" . $_POST["varriety_id"] . "',",
//        'pack_size,' => "'" . $_POST["pack_size"] . "',",
//        'first_stock_quantity,' => "first_stock_quantity+'" . $_POST["quantity"] . "',",
//        'current_stock_qunatity,' => "current_stock_qunatity+'" . $_POST["quantity"] . "',",
//        'status,' => "'Active',",
//        'del_status,' => "'0',",
//        'entry_by,' => "'$user_id',",
//        'entry_date' => "'" . $db->ToDayDate() . "'"
//    );
//
//    $db->data_insert($tbl . 'product_stock', $rowfield);
//    $db->system_event_log('', $user_id, $employee_id, '', '', $tbl . 'product_stock', 'Save', '');
//}
//
/////////////  END PRODUCT STOCK UPDATE ////////////////////
//
//
//$rowfield = array(
//    'product_id,' => "'$maxID',",
//    'warehouse_id,' => "'" . $_POST["warehouse_id"] . "',",
//    'crop_id,' => "'" . $_POST["crop_id"] . "',",
//    'product_type_id,' => "'" . $_POST["product_type_id"] . "',",
//    'varriety_id,' => "'" . $_POST["varriety_id"] . "',",
//    'product_type,' => "'" . $_POST["product_type"] . "',",
//    'pack_size,' => "'" . $_POST["pack_size"] . "',",
//    'quantity,' => "'" . $_POST["quantity"] . "',",
//    'mfg_date,' => "'" . $db->date_formate($_POST["mfg_date"]) . "',",
//    'exp_date,' => "'" . $db->date_formate($_POST["exp_date"]) . "',",
//    'status,' => "'Active',",
//    'del_status,' => "'0',",
//    'entry_by,' => "'$user_id',",
//    'entry_date' => "'" . $db->ToDayDate() . "'"
//);
//
//$db->data_insert($tbl . 'product_purchase_info', $rowfield);
//$db->system_event_log('', $user_id, $employee_id, $maxID, '', $tbl . 'product_purchase_info', 'Save', '');
?>