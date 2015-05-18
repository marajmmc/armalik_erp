<?php
session_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$db = new Database();
$user_id = $_SESSION['user_id'];
$employee_id = $_SESSION['employee_id'];
$tbl = _DB_PREFIX;
//$product=  explode('~', $_POST['product_id']);
$maxID = "PP-".$db->getMaxID_six_digit($tbl . 'product_pricing', 'pricing_id');
$rowfield = array(
    'pricing_id,' => "'" . $maxID . "',",
    'crop_id,' => "'" . $_POST["crop_id"] . "',",
    'product_type_id,' => "'" . $_POST["product_type_id"] . "',",
    'varriety_id,' => "'" . $_POST["varriety_id"] . "',",
    'pack_size,' => "'" . $_POST['pack_size'] . "',",
//    'cost_price,' => "'" . $_POST["cost_price"] . "',",
    'selling_price,' => "'" . $_POST["selling_price"] . "',",
    'status,' => "'Active',",
    'del_status,' => "'0',",
    'entry_by,' => "'$user_id',",
    'entry_date' => "'" . $db->ToDayDate() . "'"
);

$db->data_insert($tbl . 'product_pricing', $rowfield);
$db->system_event_log('', $user_id, $employee_id, $maxID, '', $tbl . 'product_pricing', 'Save', '');

echo $sql="UPDATE $tbl"."product_pricing SET status='In-Active' WHERE pricing_id!='$maxID' AND crop_id='" . $_POST["crop_id"] . "' AND product_type_id='".$_POST['product_type_id']."' AND varriety_id='" . $_POST["varriety_id"] . "' AND pack_size='".$_POST['pack_size']."'";
if($db->open()){
    $db->query($sql);
}

?>