<?php

session_start();

require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$db = new Database();
$user_id = $_SESSION['user_id'];
$employee_id = $_SESSION['employee_id'];
$tbl = _DB_PREFIX;

$maxID = "VS-".$db->getMaxID_six_digit($tbl . 'pdo_variety_setting', 'vs_id');

$rowfield = array(
    'vs_id,' => "'".$maxID."',",
    'farmer_id,' => "'".$_POST['farmer_id']."',",
    'crop_id,' => "'".$_POST['crop_id']."',",
    'product_type_id,' => "'".$_POST['product_type_id']."',",
    'variety_id,' => "'".$_POST['variety_id']."',",
    'number_of_img,' => "'".$_POST['number_of_img']."',",
    'fruit_type,' => "'".$_POST['fruit_type']."',",
    'fruit_set,' => "'1',",
    'pdo_season_id,' => "'".$_POST['pdo_season_id']."',",
    'pdo_year_id,' => "'".$_POST['pdo_year_id']."',",
    'sowing_date,' => "'".$db->date_formate($_POST['sowing_date'])."',",
    'transplanting_date,' => "'".$db->date_formate($_POST['transplanting_date'])."',",
    'status,' => "'Active',",
    'del_status,' => "'0',",
    'entry_by,' => "'$user_id',",
    'entry_date' => "'" . $db->ToDayDate() . "'"
);

echo $db->data_insert($tbl . 'pdo_variety_setting', $rowfield);
$db->system_event_log('', $user_id, $employee_id, '', '', $tbl . 'pdo_variety_setting', 'Save', '');

$count = count($_POST["upload_date"]);

for ($i = 0; $i < $count; $i++) {

    $rowfield = array
        (
            'vs_id,' => "'" . $maxID . "',",
            'upload_date,' => "'".$db->date_formate($_POST['upload_date'][$i])."',",
            'status,' => "'Active',",
            'del_status,' => "'0',",
            'entry_by,' => "'$user_id',",
            'entry_date' => "'" . $db->ToDayDate() . "'"
        );

    echo $db->data_insert($tbl . 'pdo_variety_setting_img_date', $rowfield);
    $db->system_event_log('', $user_id, $employee_id, '', '', $tbl . 'pdo_variety_setting_img_date', 'Save', '');

}
?>
