<?php

session_start();

require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$db = new Database();
$user_id = $_SESSION['user_id'];
$ei_id = $_SESSION['ei_id'];
$tbl = _DB_PREFIX;

$maxID = $_POST['rowID'];
if ($_POST['pdo_type'] == "Checked Variety") {
    $pdo_type = "Checked Variety";
} else {
    $pdo_type = "Self";
}
$rowfield = array(
    'crop_id' => "'" . $_POST["crop_id"] . "'",
    'product_type_id' => "'" . $_POST["product_type_id"] . "'",
    'pdo_name' => "'" . $_POST["pdo_name"] . "'",
    'pdo_type' => "'" . $pdo_type . "'",
    'status' => "'" . $_POST["status"] . "'",
    'del_status' => "'0'",
    'entry_by' => "'$user_id'",
    'entry_date' => "'" . $db->ToDayDate() . "'"
);
$wherefield = array('pdo_id' => "'$maxID'");
$db->data_update($tbl . 'pdo_product_info', $rowfield, $wherefield);
$db->system_event_log('', $user_id, $ei_id, $maxID, '', $tbl . 'pdo_product_info', 'Save', '');
?>
