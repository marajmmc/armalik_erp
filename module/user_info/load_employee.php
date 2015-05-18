<?php

require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$tbl = _DB_PREFIX;
$db = new Database();
if ($_POST['user_level'] == "Marketing") {
    
    $user_level = "AND user_level='Marketing'";
    $zone_id = "AND zone_id=''";
    $territory_id = "AND territory_id=''";
    $warehouse_id = "AND warehouse_id=''";
    $division_id = "AND division_id=''";
    
} else if ($_POST['user_level'] == "Warehouse") {
    
    $user_level = "AND user_level='Warehouse'";
    $zone_id = "AND zone_id=''";
    $territory_id = "AND territory_id=''";
    $warehouse_id = "AND warehouse_id='" . $_POST['warehouse_id'] . "'";
    $division_id = "AND division_id=''";
    
} else if ($_POST['user_level'] == "Division") {
    
    $user_level = "AND user_level='Division'";
    $zone_id = "AND zone_id=''";
    $territory_id = "AND territory_id=''";
    $warehouse_id = "AND warehouse_id=''";
    $division_id = "AND division_id='".$_POST['division_id']."'";
    
} else if ($_POST['user_level'] == "Zone") {
    
    $user_level = "AND user_level='Zone'";
    $zone_id = "AND zone_id='" . $_POST['zone_id'] . "'";
    $territory_id = "AND territory_id=''";
    $warehouse_id = "AND warehouse_id=''";
    $division_id = "AND division_id=''";
    
} else if ($_POST['user_level'] == "Territory") {
    
    $user_level = "AND user_level='Territory'";
    $zone_id = "AND zone_id='" . $_POST['zone_id'] . "'";
    $territory_id = "AND territory_id='" . $_POST['territory_id'] . "'";
    $warehouse_id = "AND warehouse_id=''";
    $division_id = "AND division_id=''";
    
} else {
    
    $user_level = "AND user_level=''";
    $zone_id = "AND user_level=''";
    $territory_id = "AND territory_id=''";
    $warehouse_id = "AND warehouse_id=''";
    $division_id = "AND division_id=''";
    
}

echo "<option value=''>Select</option>";

if ($_POST['user_level'] == "Distributor") {
    echo $sql_uesr_group = "select distributor_id as fieldkey, CONCAT_WS(' - ', $tbl" . "distributor_info.customer_code, $tbl" . "distributor_info.distributor_name) as fieldtext from $tbl" . "distributor_info where status='Active' AND del_status='0' AND zone_id='" . $_POST['zone_id'] . "' AND territory_id='" . $_POST['territory_id'] . "'";
    echo $db->SelectList($sql_uesr_group);
} else {
    echo $sql_uesr_group = "select employee_id as fieldkey, employee_name as fieldtext from $tbl" . "employee_basic_info where status='Active' AND del_status='0' $user_level $zone_id $territory_id $warehouse_id $division_id";
    echo $db->SelectList($sql_uesr_group);
}
?>