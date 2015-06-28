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

$incrementCount = sizeof($_POST['row_id']);

$other_remarks_post = $_POST['other_remarks'];
$other_picture_date_post = $_POST['other_picture_date'];
$otherFile = $_FILES["other_picture"];
$editPost = $_POST["edit_id"];

//$delSQL = "DELETE FROM $tbl"."zi_others_popular_variety WHERE `division_id`='$user_division' AND `zone_id`='$user_zone' AND `territory_id`='$territory_id' AND `district_id`=$district_id AND `upazilla_id`=$upazilla_id AND `crop_id`='$crop_id' AND `product_type_id`='$type_id' AND `variety_id`='$variety_id' AND `farmer_name`='$farmer_name'";
//
//if ($db->open())
//{
//    $db->query($delSQL);
//    $db->freeResult();
//}

$whereFieldUpdate = array('division_id' => "'$user_division'", 'zone_id' => "'$user_zone'", 'territory_id' => "'$territory_id'", 'district_id' => "'$district_id'", 'upazilla_id' => "'$upazilla_id'", 'crop_id' => "'$crop_id'", 'product_type_id' => "'$type_id'", 'variety_id' => "'$variety_id'", 'farmer_name' => "'$farmer_name'");
$update_data = array('del_status' => "'1'");
$db->data_update($tbl . 'zi_others_popular_variety', $update_data, $whereFieldUpdate);

for($i=0; $i<$incrementCount; $i++)
{
    if(@$otherFile['name'][$i] != "")
    {
        $ext = end(explode(".", @$otherFile['name'][$i]));
        $image_url = $time.$i . "." . $ext;
        copy(@$otherFile['tmp_name'][$i], "../../system_images/zi_others_popular/$image_url");
        $remark = $other_remarks_post[$i];
        $picture_date = date('Y-m-d', strtotime($other_picture_date_post[$i]));

        if($editPost[$i]>0)
        {
            $data = array(
                'division_id' => "'$user_division'",
                'zone_id' => "'$user_zone'",
                'territory_id' => "'$territory_id'",
                'district_id' => "'$district_id'",
                'upazilla_id' => "'$upazilla_id'",
                'crop_id' => "'$crop_id'",
                'product_type_id' => "'$type_id'",
                'variety_id' => "'$variety_id'",
                'farmer_name' => "'$farmer_name'",
                'picture_link' => "'$image_url'",
                'remarks' => "'$remark'",
                'picture_date' => "'$picture_date'",
                'del_status' => "'0'",
                'entry_by' => "'$user_id'",
                'entry_date' => "'" . $db->ToDayDate() . "'"
            );

            $maxID = $editPost[$i];
            $whereField = array('id' => "'$maxID'");

            $db->data_update($tbl . 'zi_others_popular_variety', $data, $whereField);
            $db->system_event_log('', $user_id, $employee_id, $maxID, '', $tbl . 'zi_others_popular_variety', 'Update', '');
        }
        else
        {
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
    else
    {
        $remark = $other_remarks_post[$i];
        $picture_date = date('Y-m-d', strtotime($other_picture_date_post[$i]));

        if($editPost[$i]>0)
        {
            $data = array(
                'division_id' => "'$user_division'",
                'zone_id' => "'$user_zone'",
                'territory_id' => "'$territory_id'",
                'district_id' => "'$district_id'",
                'upazilla_id' => "'$upazilla_id'",
                'crop_id' => "'$crop_id'",
                'product_type_id' => "'$type_id'",
                'variety_id' => "'$variety_id'",
                'farmer_name' => "'$farmer_name'",
                'remarks' => "'$remark'",
                'picture_date' => "'$picture_date'",
                'del_status' => "'0'",
                'entry_by' => "'$user_id'",
                'entry_date' => "'" . $db->ToDayDate() . "'"
            );

            $maxID = $editPost[$i];
            $whereField = array('id' => "'$maxID'");

            $db->data_update($tbl . 'zi_others_popular_variety', $data, $whereField);
            $db->system_event_log('', $user_id, $employee_id, $maxID, '', $tbl . 'zi_others_popular_variety', 'Update', '');
        }
    }
}

?>
<script>
    window.location.href = "list_frm.php?menuID=<?php echo $_SESSION['sm_id']; ?>&buttonID=<?php echo $_SESSION['st_id']; ?>";
</script>