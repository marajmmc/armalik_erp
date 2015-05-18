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
$maxID = "SO-".$db->getMaxID_six_digit($tbl . 'product_special_offer', 'special_offer_id');
$rowfield = array(
    'special_offer_id,' => "'" . $maxID . "',",
    'crop_id,' => "'" . $_POST["crop_id"] . "',",
    'product_type_id,' => "'" . $_POST["product_type_id"] . "',",
    'varriety_id,' => "'" . $_POST["varriety_id"] . "',",
    'quantity,' => "'" . $_POST["quantity"] . "',",
    'offer,' => "'" . $_POST["offer"] . "',",
    'start_date,' => "'" . $db->date_formate($_POST["start_date"]) . "',",
    'end_date,' => "'" . $db->date_formate($_POST["end_date"]) . "',",
    'status,' => "'" . $_POST["status"] . "',",
    'del_status,' => "'0',",
    'entry_by,' => "'$user_id',",
    'entry_date' => "'" . $db->ToDayDate() . "'"
);

$db->data_insert($tbl . 'product_special_offer', $rowfield);
$db->system_event_log('', $user_id, $employee_id, $maxID, '', $tbl . 'product_special_offer', 'Save', '');

?>