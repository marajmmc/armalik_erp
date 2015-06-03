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
    if (@$_POST[$elmIndex] == $elmIndex)
    {
        echo $_POST[$elmIndex];
        $rowfield = array(
            'zone_id,' => "'".$_POST['zone_id']."',",
            'territory_id,' => "'".$_POST['territory_id']."',",
            'zilla_id,' => "'".$_POST[$elmIndex]."',",
            'status,' => "'Active',",
            'del_status,' => "'0',",
            'entry_by,' => "'$user_id',",
            'entry_date' => "'" . $db->ToDayDate() . "'"
        );

        echo $db->data_insert($tbl . 'territory_assign_district', $rowfield);
        $db->system_event_log('', $user_id, $employee_id, '', '', $tbl . 'territory_assign_district', 'Save', '');
    }
}
?>
