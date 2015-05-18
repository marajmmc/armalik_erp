<?php

session_start();

require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$db = new Database();
$user_id = $_SESSION['user_id'];
$ei_id = $_SESSION['ei_id'];
$tbl = _DB_PREFIX;

$maxID = "SI-" . $db->getMaxID_six_digit($tbl . 'session_info', 'session_id');
$count = count($_POST['id']);
for ($i = 0; $i < $count; $i++) {

    $rowfield = array(
        'session_id,' => "'$maxID',",
        'session_name,' => "'" . $_POST["session_name"] . "',",
        'from_date,' => "'" . $db->date_formate($_POST["from_date"]) . "',",
        'to_date,' => "'" . $db->date_formate($_POST["to_date"]) . "',",
        'crop_id,' => "'" . $_POST["crop_id"] . "',",
        'product_type_id,' => "'" . $_POST["product_type_id"] . "',",
        'varriety_id,' => "'" . $_POST["varriety_id"] . "',",
        'product_status,' => "'" . $_POST["product_status"][$i] . "',",
        'session_color,' => "'" . $_POST["session_color"][$i] . "',",
        'session_from_date,' => "'" . $db->date_formate($_POST["session_from_date"][$i]) . "',",
        'session_to_date,' => "'" . $db->date_formate($_POST["session_to_date"][$i]) . "',",
        'status,' => "'Active',",
        'del_status,' => "'0',",
        'entry_by,' => "'$user_id',",
        'entry_date' => "'" . $db->ToDayDate() . "'"
    );

    $db->data_insert($tbl . 'session_info', $rowfield);
    $db->system_event_log('', $user_id, $ei_id, $maxID, '', $tbl . 'session_info', 'Save', '');
}
?>