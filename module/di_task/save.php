<?php

session_start();

require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$db = new Database();
$tbl = _DB_PREFIX;
$user_id = $_SESSION['user_id'];
$employee_id = $_SESSION['employee_id'];

$start_date = date('Y-m-d', strtotime($_POST['start_date']));
$end_date = date('Y-m-d', strtotime($_POST['end_date']));
$user_division = $_SESSION['division_id'];
$zone_id = $_POST['zone_id'];
$territory_id = $_POST['territory_id'];
$district_id = $_POST['district_id'];
$distributor_id = $_POST['distributor_id'];
$crop_id = $_POST['crop_id'];
$distributor_others = $_POST['distributor_others'];

$entry_date = $_POST['entry_date'];
$activities = $_POST['activities'];
$problem = $_POST['problem'];
$recommendation = $_POST['recommendation'];
$time = time();

if(@$_FILES["activities_file"]['name'] != "")
{
    $ext = end(explode(".", @$_FILES["activities_file"]['name']));
    $activities_image_url = $time . "." . $ext;
    copy(@$_FILES['activities_file']['tmp_name'], "../../system_images/zi_task/$activities_image_url");
}
else
{
    $activities_image_url = '';
}

if(@$_FILES["problem_file"]['name'] != "")
{
    $ext = end(explode(".", @$_FILES["problem_file"]['name']));
    $problem_image_url = $time+1 . "." . $ext;
    copy(@$_FILES['problem_file']['tmp_name'], "../../system_images/zi_task/$problem_image_url");
}
else
{
    $problem_image_url = '';
}

$data = array(
    'start_date,' => "'$start_date',",
    'end_date,' => "'$end_date',",
    'division_id,' => "'$user_division',",
    'zone_id,' => "'$zone_id',",
    'territory_id,' => "'$territory_id',",
    'district_id,' => "'$district_id',",
    'zone_in_charge,' => "'',",
    'distributor_id,' => "'$distributor_id',",
    'crop_id,' => "'$crop_id',",
    'distributor_others,' => "'$distributor_others',",
    'task_entry_date,' => "'" . $entry_date . "',",
    'activities,' => "'$activities',",
    'activities_image,' => "'$activities_image_url',",
    'problem,' => "'$problem',",
    'problem_image,' => "'$problem_image_url',",
    'recommendation,' => "'$recommendation',",
    'entry_by,' => "'$user_id',",
    'entry_date' => "'" . $db->ToDayDate() . "'"
);

$db->data_insert($tbl . 'di_task', $data);
$db->system_event_log('', $user_id, $employee_id, '', '', $tbl . 'di_task', 'Save', '');
?>

<script>
    window.location.href = "list_frm.php?menuID=<?php echo $_SESSION['sm_id']; ?>&buttonID=<?php echo $_SESSION['st_id']; ?>";
</script>