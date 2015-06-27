<?php

session_start();

require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$db = new Database();
$tbl = _DB_PREFIX;

$user_id = $_SESSION['user_id'];
$employee_id = $_SESSION['employee_id'];

$division_id = $_POST['division_id'];
$zone_id = $_POST['zone_id'];
$territory_id = $_POST['territory_id'];
$district_id = $_POST['district_id'];
$upazilla_id = $_POST['upazilla_id'];
$crop_id = $_POST['crop_id'];
$type_id = $_POST['type_id'];
$variety_id = $_POST['variety_id'];
$farmers_id = $_POST['farmers_id'];
$sowing_date = $_POST['sowing_date'];
$no_of_picture = $_POST['no_of_picture'];
$interval = $_POST['interval'];

$data = array(
    'division_id,' => "'$division_id',",
    'zone_id,' => "'$zone_id',",
    'territory_id,' => "'$territory_id',",
    'district_id,' => "'$district_id',",
    'upazilla_id,' => "'$upazilla_id',",
    'crop_id,' => "'$crop_id',",
    'product_type_id,' => "'$type_id',",
    'variety_id,' => "'$variety_id',",
    'farmer_id,' => "'$farmers_id',",
    'sowing_date,' => "'$sowing_date',",
    'no_of_pictures,' => "'$no_of_picture',",
    'interval_days,' => "'$interval',",
    'entry_by,' => "'$user_id',",
    'entry_date' => "'" . $db->ToDayDate() . "'"
);

$db->data_insert($tbl . 'zi_monthly_field_visit_setup', $data);
$db->system_event_log('', $user_id, $employee_id, '', '', $tbl . 'zi_monthly_field_visit_setup', 'Save', '');
?>

<script>
    window.location.href = "list_frm.php?menuID=<?php echo $_SESSION['sm_id']; ?>&buttonID=<?php echo $_SESSION['st_id']; ?>";
</script>