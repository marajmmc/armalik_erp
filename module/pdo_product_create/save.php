<?php
session_start();

require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$db = new Database();
$user_id = $_SESSION['user_id'];
$ei_id = $_SESSION['ei_id'];
$tbl = _DB_PREFIX;

$pdo_type='';

$maxID = "PD-".$db->getMaxID_six_digit($tbl . 'pdo_product_info', 'pdo_id');
if($_POST['pdo_type']=="Checked Variety"){
    $pdo_type="Checked Variety";
}else{
    $pdo_type="Self";
}

$rowfield = array(
    'pdo_id,' => "'$maxID',",
    'crop_id,' => "'" . $_POST["crop_id"] . "',",
    'product_type_id,' => "'" . $_POST["product_type_id"] . "',",
    'pdo_name,' => "'" . $_POST["pdo_name"] . "',",
    'pdo_type,' => "'" . $pdo_type . "',",
    'status,' => "'Active',",
    'del_status,' => "'0',",
    'entry_by,' => "'$user_id',",
    'entry_date' => "'" . $db->ToDayDate() . "'"
);

$db->data_insert($tbl . 'pdo_product_info', $rowfield);
$db->system_event_log('', $user_id, $ei_id, $maxID, '', $tbl . 'pdo_product_info', 'Save', '');
?>
