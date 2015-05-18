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
//$maxID = $_POST['rowID'];
//$rowfield = array(
//    'warehouse_id' => "'" . $_POST["warehouse_id"] . "'",
//    'crop_id' => "'" . $_POST["crop_id"] . "'",
//    'product_type_id' => "'" . $_POST["product_type_id"] . "'",
//    'varriety_id' => "'" . $_POST["varriety_id"] . "'",
////    'product_type' => "'" . $_POST["product_type"] . "'",
//    'pack_size' => "'" . $_POST["pack_size"] . "'",
//    'mfg_date' => "'" . $db->date_formate($_POST["mfg_date"]) . "'",
//    'exp_date' => "'" . $db->date_formate($_POST["exp_date"]) . "'",
//    'status' => "'Active'",
//    'del_status' => "'0'",
//    'entry_by' => "'$user_id'",
//    'entry_date' => "'" . $db->ToDayDate() . "'"
//);
//$wherefield=array('id' => "'$maxID'");
//$db->data_update($tbl . 'product_info', $rowfield,$wherefield);
//$db->system_event_log('', $user_id, $employee_id, $maxID, '', $tbl . 'product_info', 'Update', '');
?>