<?php

session_start();

require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$db = new Database();
$user_id = $_SESSION['user_id'];
$ei_id = $_SESSION['employee_id'];
$tbl = _DB_PREFIX;
$maxID=$_POST['row_id'];
$rowfield = array(
    'crop_img_upload_id,' => "'$maxID',",
    'description,' => "'" . $_POST["comment"] . "',",
    'status,' => "'Active',",
    'del_status,' => "'0',",
    'entry_by,' => "'$user_id',",
    'entry_date' => "'" . $db->ToDayDate() . "'"
);

$db->data_insert($tbl . 'pdo_photo_remark', $rowfield);
$db->system_event_log('', $user_id, $ei_id, $maxID, '', $tbl . 'pdo_photo_remark', 'Save', '');
?>
