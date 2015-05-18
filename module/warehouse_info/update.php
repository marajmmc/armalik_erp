<?php
session_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$db = new Database();
$user_id = $_SESSION['user_id'];
$employee_id = $_SESSION['employee_id'];
$tbl = _DB_PREFIX;

$MaxID = $_POST['rowID'];
$rowfield = array(
    'warehouse_name' => "'" . $_POST["warehouse_name"] . "'",
    'capacity' => "'" . $_POST["capacity"] . "'",
    'capacity_unit' => "'" . $_POST["capacity_unit"] . "'",
    'address' => "'" . $_POST["address"] . "'",
    'status' => "'" . $_POST["status"] . "'",
    'del_status' => "'0'",
    'entry_by' => "'$user_id'",
    'entry_date' => "'" . $db->ToDayDate() . "'"
);
$wherefield=array('warehouse_id' => "'$MaxID'");
$db->data_update($tbl . 'warehouse_info', $rowfield,$wherefield);
$db->system_event_log('', $user_id, $employee_id, $MaxID,'', $tbl . 'warehouse_info', 'Update', '');
?>