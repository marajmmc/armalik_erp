<?php

session_start();

require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$db = new Database();
$user_id = $_SESSION['user_id'];
$ei_id = $_SESSION['employee_id'];
$tbl = _DB_PREFIX;
$upload_file='';
$maxID = "PC-".$db->getMaxID_six_digit($tbl . 'pdo_product_characteristic', 'prodcut_characteristic_id');

if($_POST["product_category"]=="Self")
{
    $variety_id=$_POST['variety_id'];
    $variety_name_txt="";
}
else if($_POST["product_category"]=="Checked Variety")
{
    $variety_id="";
    $variety_name_txt=$_POST['variety_name_txt'];
}
else
{
    $variety_id="";
    $variety_name_txt="";
}

$rowfield = array
(
    'prodcut_characteristic_id,' => "'$maxID',",
    'product_category,' => "'" . $_POST["product_category"] . "',",
    'crop_id,' => "'" . $_POST["crop_id"] . "',",
    'product_type_id,' => "'" . $_POST["product_type_id"] . "',",
    'variety_id,' => "'" . $variety_id . "',",
    'variety_name_txt,' => "'" . $variety_name_txt . "',",
    'zone_id,' => "'" . $_POST["zone_id"] . "',",
    'hybrid,' => "'" . $_POST["hybrid"] . "',",
    'district_id,' => "'" . $_POST["district_id"] . "',",
    'upazilla_id,' => "'" . $_POST["upazilla_id"] . "',",
    'sales_quantity,' => "'" . $_POST["sales_quantity"] . "',",
    'upload_date,' => "'" . $db->ToDayDate() . "',",
    'status,' => "'Active',",
    'del_status,' => "'0',",
    'entry_by,' => "'$user_id',",
    'entry_date' => "'" . $db->ToDayDate() . "'"
);

$db->data_insert($tbl . 'pdo_product_characteristic', $rowfield);
$db->system_event_log('', $user_id, $ei_id, $maxID, '', $tbl . 'pdo_product_characteristic', 'Save', '');
?>
