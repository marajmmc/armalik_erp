<?php

session_start();

require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$db = new Database();
$user_id = $_SESSION['user_id'];
$ei_id = $_SESSION['employee_id'];
$tbl = _DB_PREFIX;

$crop_id=$_POST["crop_id"];
$product_type_id=$_POST["product_type_id"];
$variety_id=$_POST["varriety_id"];
$from_post_quantity=$_POST["from_quantity"];
$to_post_quantity=$_POST["to_quantity"];

if(empty($crop_id) && empty($product_type_id) && empty($variety_id) && $from_post_quantity<=0 && $to_post_quantity<=0)
{
    echo "Please check crop, type, variety & form, to quantity";
    die();
}

$db_bonus=new Database();
$bonus=$db_bonus->single_data_w($tbl."bonus_role_setup", "bonus_rule_id, from_quantity, to_quantity", "crop_id='$crop_id' AND product_type_id='$product_type_id' AND varriety_id='$variety_id' AND status='Active' AND del_status=0");
$from_quantity=$bonus['from_quantity'];
$to_quantity=$bonus['to_quantity'];
if(($from_quantity<=$from_post_quantity && $to_quantity>=$from_post_quantity) || ($from_quantity<=$to_post_quantity && $to_quantity>=$to_post_quantity))
{
    echo "Exist form & to quantity";
    die();
}

$maxID = "BS-".$db->getMaxID_six_digit($tbl . 'bonus_role_setup', 'bonus_rule_id');
$rowfield = array
(
    'bonus_rule_id,' => "'" . $maxID . "',",
    'crop_id,' => "'" . $_POST["crop_id"] . "',",
    'product_type_id,' => "'" . $_POST["product_type_id"] . "',",
    'varriety_id,' => "'" . $_POST["varriety_id"] . "',",
    'from_quantity,' => "'" . $_POST["from_quantity"] . "',",
    'to_quantity,' => "'" . $_POST["to_quantity"] . "',",
    'status,' => "'Active',",
    'del_status,' => "'0',",
    'entry_by,' => "'$user_id',",
    'entry_date' => "'" . $db->ToDayDate() . "'"
);

$db->data_insert($tbl . 'bonus_role_setup', $rowfield);
$db->system_event_log('', $user_id, $ei_id, '', '', $tbl . 'bonus_role_setup', 'Save', '');

$rowfield = array
(
    'bonus_rule_id,' => "'" . $maxID . "',",
    'crop_id,' => "'" . $_POST["bonus_crop_id"] . "',",
    'product_type_id,' => "'" . $_POST["bonus_product_type_id"] . "',",
    'varriety_id,' => "'" . $_POST["bonus_varriety_id"] . "',",
    'pack_size,' => "'" . $_POST["bonus_pack_size"] . "',",
    'bonus_quantity,' => "'" . $_POST["bonus_quantity"] . "',",
    'status,' => "'Active',",
    'del_status,' => "'0',",
    'entry_by,' => "'$user_id',",
    'entry_date' => "'" . $db->ToDayDate() . "'"
);

$db->data_insert($tbl . 'bonus_role_setup_details', $rowfield);
$db->system_event_log('', $user_id, $ei_id, '', '', $tbl . 'bonus_role_setup_details', 'Save', '');

?>