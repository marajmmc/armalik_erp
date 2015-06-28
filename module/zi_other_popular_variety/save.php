<?php

session_start();

require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$tbl = _DB_PREFIX;
$db = new Database();
$data = array();
$time = time();

$user_id = $_SESSION['user_id'];
$employee_id = $_SESSION['employee_id'];
$user_zone = $_SESSION['zone_id'];
$user_division_query = $db->single_data($tbl.'zone_info', 'division_id', 'zone_id', "$user_zone");
$user_division = $user_division_query['division_id'];

$zone_id = $user_zone;
$division_id = $user_division;
$territory_id = $_POST['territory_id'];
$district_id = $_POST['district_id'];
$upazilla_id = $_POST['upazilla_id'];
$crop_id = $_POST['crop_id'];
$type_id = $_POST['type_id'];
$variety_id = $_POST['variety_id'];
$farmer_name = $_POST['farmers_name'];

if(isset($_POST['row_id']))
{
    $increment = sizeof($_POST['row_id']);
    $incrementCount = $increment;
    $incrementCount = $incrementCount+1;
}
else
{
    $incrementCount = 1;
}

$other_remarks_post = $_POST['other_remarks'];
$other_picture_date_post = $_POST['other_picture_date'];
$otherFile = $_FILES["other_picture"];

for($i=0; $i<$incrementCount; $i++)
{
    if(@$otherFile['name'][$i] != "")
    {
        $ext = end(explode(".", @$otherFile['name'][$i]));
        $image_url = $time.$i . "." . $ext;
        copy(@$otherFile['tmp_name'][$i], "../../system_images/zi_others_popular/$image_url");
        $remark = $other_remarks_post[$i];
        $picture_date = date('Y-m-d', strtotime($other_picture_date_post[$i]));

        $data = array(
            'division_id,' => "'$user_division',",
            'zone_id,' => "'$user_zone',",
            'territory_id,' => "'$territory_id',",
            'district_id,' => "'$district_id',",
            'upazilla_id,' => "'$upazilla_id',",
            'crop_id,' => "'$crop_id',",
            'product_type_id,' => "'$type_id',",
            'variety_id,' => "'$variety_id',",
            'farmer_name,' => "'$farmer_name',",
            'picture_link,' => "'$image_url',",
            'remarks,' => "'$remark',",
            'picture_date,' => "'$picture_date',",
            'entry_by,' => "'$user_id',",
            'entry_date' => "'" . $db->ToDayDate() . "'"
        );

        $db->data_insert($tbl . 'zi_others_popular_variety', $data);
        $db->system_event_log('', $user_id, $employee_id, '', '', $tbl . 'zi_others_popular_variety', 'Save', '');
    }
}

?>