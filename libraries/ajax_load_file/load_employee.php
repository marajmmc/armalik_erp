<?php

require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$tbl = _DB_PREFIX;
$db = new Database();
echo "<option value=''>Select</option>";

if ($_POST['user_unit_id'] == "Distributor") {
    $sql_uesr_group = "select distributor_id as fieldkey, distributor_name as fieldtext from $tbl" . "distributor_info where status='Active' AND del_status='0' ORDER BY distributor_name";
    echo $db->SelectList($sql_uesr_group);
} else {
    $sql_uesr_group = "select employee_id as fieldkey, employee_name as fieldtext from $tbl" . "employee_basic_info where status='Active' AND del_status='0' ORDER BY employee_name";
    echo $db->SelectList($sql_uesr_group);
}
?>