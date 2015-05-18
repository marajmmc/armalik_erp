<?php

include_once '../../libraries/lib/inclue_system_file.php';

$db = new Database();
$db2 = new Database();
$user_pass = $_POST["user_pass"];
$user_name = stripQuotes(removeBadChars($_POST["user_name"]));
$user_pass = stripQuotes(removeBadChars($user_pass));
$crypt_user_pass = md5(md5($user_pass));

if ($_POST['user_level'] == "Distributor") {
    $disname = $db->single_data($tbl . "distributor_info", "CONCAT_WS(' - ', $tbl" . "distributor_info.customer_code, $tbl" . "distributor_info.distributor_name) AS distributor_name", "distributor_id", $_POST['employee_id']);
    $employee_name = $disname['distributor_name'];
} else {
    $empname = $db->single_data($tbl . "employee_basic_info", "employee_name", "employee_id", $_POST['employee_id']);
    $employee_name = $empname['employee_name'];
}

$mSQL_task = "update $tbl" . "user_login set
	 employee_id='" . $_POST['employee_id'] . "',
	 employee_name='" . $employee_name . "',
	 user_level='" . $_POST['user_level'] . "',
	 division_id='" . $_POST['division_id'] . "',
	 zone_id='" . $_POST['zone_id'] . "',
	 territory_id='" . $_POST['territory_id'] . "',
	 warehouse_id='" . $_POST['warehouse_id'] . "',
	 user_group_id='" . $_POST['user_group_id'] . "',
	 user_status='" . $_POST['user_status'] . "',
	 user_name='$user_name',
	 user_pass='$crypt_user_pass'  
        where user_id='" . $_POST['rowID'] . "'
    ";
if ($db2->open()) {
    $db2->query($mSQL_task);
    echo "Save";
    $db2->freeResult();
}
?>