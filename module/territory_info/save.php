<?php

session_start();

require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$db = new Database();
$user_id = $_SESSION['user_id'];
$employee_id = $_SESSION['employee_id'];
$tbl = _DB_PREFIX;
$count = count($_POST['territory_id']);
for ($i = 0; $i < $count; $i++) {
    $maxID = "TI-" . $db->getMaxID_six_digit($tbl . 'territory_info', 'territory_id');
    $rowfield = array(
        'territory_id,' => "'$maxID',",
        'zone_id,' => "'" . $_POST["zone_id"] . "',",
        'territory_name,' => "'" . $_POST["territory_name"][$i] . "',",
        'status,' => "'Active',",
        'del_status,' => "'0',",
        'entry_by,' => "'$user_id',",
        'entry_date' => "'" . $db->ToDayDate() . "'"
    );

    echo $db->data_insert($tbl . 'territory_info', $rowfield);
    $db->system_event_log('', $user_id, $employee_id, $_POST["zone_id"], $maxID, $tbl . 'territory_info', 'Save', '');
}
?>