<?php

session_start();

require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$db = new Database();
$user_id = $_SESSION['user_id'];
$employee_id = $_SESSION['employee_id'];
$tbl = _DB_PREFIX;

$maxID = $_POST['rowID'];
$rowfield = array(
    'crop_id' => "'" . $_POST["crop_id"] . "'",
    'product_type_id' => "'" . $_POST["product_type_id"] . "'",
    'varriety_id' => "'" . $_POST["varriety_id"] . "'",
    'quantity' => "'" . $_POST["quantity"] . "'",
    'offer' => "'" . $_POST["offer"] . "'",
    'start_date' => "'" . $db->date_formate($_POST["start_date"]) . "'",
    'end_date' => "'" . $db->date_formate($_POST["end_date"]) . "'",
    'status' => "'" . $_POST["status"] . "'",
    'del_status' => "'0'",
    'entry_by' => "'$user_id'",
    'entry_date' => "'" . $db->ToDayDate() . "'"
);
$wherefield=array('special_offer_id' => "'$maxID'");
$db->data_update($tbl . 'product_special_offer', $rowfield, $wherefield);
$db->system_event_log('', $user_id, $employee_id, $maxID, '', $tbl . 'product_special_offer', 'Update', '');
?>