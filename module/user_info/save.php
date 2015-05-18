<?php
session_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$db2 = new Database();
$tbl=_DB_PREFIX;
$user_id = $_SESSION['user_id'];
$employee_id = $_SESSION['employee_id'];
$user_pass = $_POST["user_pass"];
$user_name = stripQuotes(removeBadChars($_POST["user_name"]));
$user_pass = stripQuotes(removeBadChars($user_pass));
$crypt_user_pass = md5(md5($user_pass));
$MaxID = "UI-" . $db->getMaxID_six_digit($tbl . 'user_login', 'user_id');
if ($_POST['user_level'] == "Distributor") {
    $disname = $db->single_data($tbl . "distributor_info", "CONCAT_WS(' - ', $tbl" . "distributor_info.customer_code, $tbl" . "distributor_info.distributor_name) AS distributor_name", "distributor_id", $_POST['employee_id']);
    $employee_name=$disname['distributor_name'];
} else {
    $empname = $db->single_data($tbl . "employee_basic_info", "employee_name", "employee_id", $_POST['employee_id']);
    $employee_name=$empname['employee_name'];
}
$mSQL_task = "insert into $tbl" . "user_login 
    (
        user_id,
	 employee_id,
	 employee_name,
	 user_level,
	 division_id,
	 zone_id,
	 territory_id,
	 warehouse_id,
	 user_group_id,
	 user_status,
	 user_name,
	 user_pass    
    ) Values(
        '$MaxID',
        '" . $_POST['employee_id'] . "',
        '$employee_name',
        '" . $_POST['user_level'] . "',
        '" . $_POST['division_id'] . "',
        '" . $_POST['zone_id'] . "',
        '" . $_POST['territory_id'] . "',
        '" . $_POST['warehouse_id'] . "',
        '" . $_POST['user_group_id'] . "',
        'Active',
        '$user_name',
        '$crypt_user_pass'
        
    )";

if ($db2->open()) {
    $db2->query($mSQL_task);
    $db2->freeResult();
}
$db->system_event_log('', $user_id, $employee_id, $MaxID,'', $tbl . 'user_login', 'Save', '');
echo "save";
?>