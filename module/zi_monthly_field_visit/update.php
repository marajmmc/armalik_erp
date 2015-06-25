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
$farmer_id = $_POST['farmer_id'];
$id = $_POST['id'];
$total = $_POST['total'];




//
//for($i=1; $i<=$total; $i++)
//{
//    if(@$_FILES["picture_link_".$i]['name'] != "")
//    {
//        $ext = end(explode(".", @$_FILES["picture_link_".$i]['name']));
//        $image_url = $time.$i . "." . $ext;
//        copy(@$_FILES['picture_link_'.$i]['tmp_name'], "../../system_images/zi_field_visit/$image_url");
//
//        $remark = $_POST['remarks_'.$i];
//        $picture_date = date('Y-m-d', strtotime($_POST['picture_date_'.$i]));
//
//        $data = array(
//            'division_id,' => "'$user_division',",
//            'zone_id,' => "'$user_zone',",
//            'territory_id,' => "'$territory_id',",
//            'district_id,' => "'$district_id',",
//            'upazilla_id,' => "'$upazilla_id',",
//            'crop_id,' => "'$crop_id',",
//            'product_type_id,' => "'$type_id',",
//            'variety_id,' => "'$variety_id',",
//            'farmer_id,' => "'$farmer_id',",
//            'setup_id,' => "'$id',",
//            'picture_link,' => "'$image_url',",
//            'remarks,' => "'$remark',",
//            'picture_date,' => "'$picture_date',",
//            'picture_number,' => "'$i',",
//            'entry_by,' => "'$user_id',",
//            'entry_date' => "'" . $db->ToDayDate() . "'"
//        );
//
//        $db->data_insert($tbl . 'zi_monthly_field_visit_pictures', $data);
//        $db->system_event_log('', $user_id, $employee_id, '', '', $tbl . 'zi_monthly_field_visit_pictures', 'Save', '');
//    }
//}


$increment = $_POST['row_id'];
if(isset($increment))
{
    $incrementCount = $increment;
    $incrementCount = $incrementCount+1;
}
else
{
    $incrementCount = 1;
}

$other_picture_post = $_POST['other_picture'];
$other_remarks_post = $_POST['other_remarks'];
$other_picture_date_post = $_POST['other_picture_date'];
$otherFile = $_FILES["other_picture"];

for($i=1; $i<$incrementCount; $i++)
{
    if(@$otherFile[$i]['name'] != "")
    {
        $ext = end(explode(".", @$otherFile[$i]['name']));
        $image_url = $time.$i . "." . $ext;
        copy(@$otherFile[$i]['tmp_name'], "../../system_images/zi_field_visit/$image_url");
        $remark = $other_remarks_post[$i];
        $picture_date = $other_picture_date_post[$i];

        $data = array(
            'division_id,' => "'$user_division',",
            'zone_id,' => "'$user_zone',",
            'territory_id,' => "'$territory_id',",
            'district_id,' => "'$district_id',",
            'upazilla_id,' => "'$upazilla_id',",
            'crop_id,' => "'$crop_id',",
            'product_type_id,' => "'$type_id',",
            'variety_id,' => "'$variety_id',",
            'farmer_id,' => "'$farmer_id',",
            'setup_id,' => "'$id',",
            'picture_link,' => "'$image_url',",
            'remarks,' => "'$remark',",
            'picture_date,' => "'$picture_date',",
            'picture_number,' => "'$i',",
            'popular_status,' => "'1',",
            'entry_by,' => "'$user_id',",
            'entry_date' => "'" . $db->ToDayDate() . "'"
        );
    }
}

//print_r($_POST);
//$imgCount = sizeof($picture_linkPost);
//echo $imgCount;
exit;

//foreach($picturePost as $field=>$picture)
//{
//    $data['division_id,'] = "'$division_id',";
//    $data['zone_id,'] = "'$zone_id',";
//    $data['territory_id,'] = "'$territory_id',";
//    $data['district_id,'] = "'$district_id',";
//    $data['upazilla_id,'] = "'$upazilla_id',";
//    $data['crop_id,'] = "'$crop_id',";
//    $data['product_type_id,'] = "'$type_id',";
//    $data['variety_id,'] = "'$variety_id',";
//    $data['farmer_id,'] = "'$farmer_id',";
//    $data['setup_id,'] = "'$id',";
//
//    foreach($picture as $number=>$value)
//    {
//        if($field=='picture_link')
//        {
//            if(@$_FILES["picture[picture_link][$number]"]['name'] != "")
//            {
//                $ext = end(explode(".", @$_FILES["picture[picture_link][$number]"]['name']));
//                $image_url = $time.$number . "." . $ext;
//                copy(@$_FILES['picture[picture_link]["'.$number.'"]']['tmp_name'], "../../system_images/zi_field_visit/$image_url");
//
//                $data['picture_link,'] = "'$image_url',";
//            }
//            else
//            {
//                $data['picture_link,'] = "'no_image.jpg',";
//            }
//        }
//        else
//        {
//            $data["$field,"] = "'$value',";
//        }
//
//        $data['entry_by,'] = "'$user_id',";
//        $data['entry_date'] = "'" . $db->ToDayDate() . "'";
//
//        $db->data_insert($tbl . 'zi_monthly_field_visit_pictures', $data);
//        $db->system_event_log('', $user_id, $employee_id, '', '', $tbl . 'zi_monthly_field_visit_pictures', 'Save', '');
//    }
//
//}

//$maxID = $_POST['rowID'];

//$whereField = array('id' => "'$maxID'");
//$db->data_update($tbl . 'zi_crop_farmer_setup', $data, $whereField);
//$db->system_event_log('', $user_id, $employee_id, $maxID, '', $tbl . 'zi_crop_farmer_setup', 'Update', '');

?>
