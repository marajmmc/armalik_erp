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

for($i=1; $i<=$total; $i++)
{
    if(@$_FILES["picture_link_".$i]['name'] != "")
    {
        $ext = end(explode(".", @$_FILES["picture_link_".$i]['name']));
        $image_url = $time.$i . "." . $ext;
        copy(@$_FILES['picture_link_'.$i]['tmp_name'], "../../system_images/zi_field_visit/$image_url");

        $remark = $_POST['remarks_'.$i];
        $picture_date = date('Y-m-d', strtotime($_POST['picture_date_'.$i]));

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
            'entry_by,' => "'$user_id',",
            'entry_date' => "'" . $db->ToDayDate() . "'"
        );

        $db->data_insert($tbl . 'zi_monthly_field_visit_pictures', $data);
        $db->system_event_log('', $user_id, $employee_id, '', '', $tbl . 'zi_monthly_field_visit_pictures', 'Save', '');
    }
}

?>

<script>
    window.location.href = "list_frm.php?menuID=<?php echo $_SESSION['sm_id']; ?>&buttonID=<?php echo $_SESSION['st_id']; ?>";
</script>
