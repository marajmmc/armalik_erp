<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$user_id = $_SESSION['user_id'];
$employee_id = $_SESSION['employee_id'];
$tbl = _DB_PREFIX;
$employee_image_url = '';

if ($_POST['user_level'] == "Zone") {
    $zone_id = $_POST['zone_id'];
    $territory_id = "";
    $warehouse_id = "";
    $division_id = "";
} else if ($_POST['user_level'] == "Territory") {
    $zone_id = $_POST['zone_id'];
    $territory_id = $_POST['territory_id'];
    $warehouse_id = "";
    $division_id = "";
} else if ($_POST['user_level'] == "Warehouse") {
    $zone_id = "";
    $territory_id = "";
    $division_id = "";
    $warehouse_id = $_POST['warehouse_id'];
} else if ($_POST['user_level'] == "Division") {
    $zone_id = "";
    $territory_id = "";
    $warehouse_id = "";
    $division_id = $_POST['division_id'];
} else {
    $zone_id = "";
    $territory_id = "";
    $warehouse_id = "";
    $division_id = "";
}

$maxID = "EI-" . $db->getMaxID_six_digit($tbl . 'employee_basic_info', 'employee_id');

if (@$_FILES["image_url"]['name'] != "") {
    $ext = end(explode(".", @$_FILES["image_url"]['name']));
    $employee_image_url = $maxID . "." . $ext;
    copy(@$_FILES['image_url']['tmp_name'], "employee_photo/$employee_image_url");
}

$rowfield = array(
    'employee_id,' => "'$maxID',",
    'employee_name,' => "'" . $_POST["employee_name"] . "',",
    'father_name,' => "'" . $_POST["father_name"] . "',",
    'mother_name,' => "'" . $_POST["mother_name"] . "',",
    'dob,' => "'" . $db->date_formate($_POST["dob"]) . "',",
    'gender,' => "'" . $_POST["gender"] . "',",
    'marital_status,' => "'" . $_POST["marital_status"] . "',",
    'spouse_name,' => "'" . $_POST["spouse_name"] . "',",
    'nid_no,' => "'" . $_POST["nid_no"] . "',",
    'employee_designation,' => "'" . $_POST["employee_designation"] . "',",
    'image_url,' => "'" . $employee_image_url . "',",
    'basic_salary,' => "'" . $_POST["basic_salary"] . "',",
    'other_allowance,' => "'" . $_POST["other_allowance"] . "',",
    'date_of_joining,' => "'" . $db->date_formate($_POST["date_of_joining"]) . "',",
    'increment_date,' => "'" . $db->date_formate($_POST["increment_date"]) . "',",
    'blood_group,' => "'" . $_POST["blood_group"] . "',",
    'land_line_number,' => "'" . $_POST["land_line_number"] . "',",
    'mobile_number,' => "'" . $_POST["mobile_number"] . "',",
    'contact_person,' => "'" . $_POST["contact_person"] . "',",
    'contact_number,' => "'" . $_POST["contact_number"] . "',",
    'present_address,' => "'" . $_POST["present_address"] . "',",
    'address,' => "'" . $_POST["address"] . "',",
    'department,' => "'" . $_POST["department"] . "',",
    'user_level,' => "'" . $_POST["user_level"] . "',",
    'employee_id_no,' => "'" . $_POST["employee_id_no"] . "',",
    'division_id,' => "'" . $division_id . "',",
    'zone_id,' => "'" . $zone_id . "',",
    'territory_id,' => "'" . $territory_id . "',",
    'warehouse_id,' => "'" . $warehouse_id . "',",
    'status,' => "'Active',",
    'del_status,' => "'0',",
    'entry_by,' => "'$user_id',",
    'entry_date' => "'" . $db->ToDayDate() . "'"
);

$db->data_insert($tbl . 'employee_basic_info', $rowfield);
$db->system_event_log('', $user_id, $employee_id, $maxID, '', $tbl . 'employee_basic_info', 'Save', '');
echo "Save";


//$count = count($_POST['row_id']);
//for ($i = 0; $i < $count; $i++) {
//    $rowfield = array(
//        'employee_id,' => "'$maxID',",
//        'degree_name,' => "'" . $_POST["degree_name"][$i] . "',",
//        'degree_obtain,' => "'" . $_POST["degree_obtain"][$i] . "',",
//        'passing_year,' => "'" . $_POST["passing_year"][$i] . "',",
//        'status,' => "'Active',",
//        'del_status,' => "'0',",
//        'entry_by,' => "'$user_id',",
//        'entry_date' => "'" . $db->ToDayDate() . "'"
//    );
//
//    $db->data_insert($tbl . 'employee_education_info', $rowfield);
//}

$count = count($_POST["elmIndex"]);
for ($i = 0; $i < $count; $i++) {
    @$zone_id = $_POST["elmIndex"][$i];
    if (@$_POST[$zone_id] == $zone_id) {
        echo $zone_id;
        $rowfield = array(
            'employee_id,' => "'" . $maxID . "',",
            'zone_id,' => "'" . $zone_id . "',",
            'division_id,' => "'".$_POST['division_id']."',",
            'status,' => "'Active',",
            'del_status,' => "'0',",
            'entry_by,' => "'$user_id',",
            'entry_date' => "'" . $db->ToDayDate() . "'"
        );

        echo $db->data_insert($tbl . 'zone_user_access', $rowfield);
        $db->system_event_log('', $user_id, $employee_id, '', $MaxID, $tbl . 'zone_user_access', 'Save', '');
    }
}
?>
<script>
    window.location.href = "list_frm.php?menuID=<?php echo $_SESSION['sm_id']; ?>&buttonID=<?php echo $_SESSION['st_id']; ?>";
</script>