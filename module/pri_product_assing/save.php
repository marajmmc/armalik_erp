<?php

session_start();

require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$db = new Database();
$user_id = $_SESSION['user_id'];
$employee_id = $_SESSION['employee_id'];
$tbl = _DB_PREFIX;

//$maxID = "UL-".$db->getMaxID_six_digit($tbl . 'pdo_photo_upload', 'upload_id');


$count = count($_POST["elmIndex"]);
for ($i = 0; $i < $count; $i++) {
    @$elmIndex = $_POST["elmIndex"][$i];
    if (@$_POST[$elmIndex] == $elmIndex) {

        $rowfield = array(
            'employee_id,' => "'" . $_POST['employee_id'] . "',",
            'crop_id,' => "'".$_POST['crop_id']."',",
            'product_type_id,' => "'".$_POST['product_type_id']."',",
            'variety_id,' => "'".$_POST[$elmIndex]."',",
            'status,' => "'Active',",
            'del_status,' => "'0',",
            'entry_by,' => "'$user_id',",
            'entry_date' => "'" . $db->ToDayDate() . "'"
        );

        echo $db->data_insert($tbl . 'assign_variety_pri', $rowfield);
        $db->system_event_log('', $user_id, $employee_id, '', '', $tbl . 'ait_assign_variety_pri', 'Save', '');
    }
}
?>
