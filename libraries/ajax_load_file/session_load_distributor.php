<?php

session_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$tbl = _DB_PREFIX;
$db = new Database();
$select='';
if ($_SESSION['user_level'] == "Zone") {
    $zone_id = "AND zone_id='" . $_SESSION['zone_id'] . "'";
    $territory = '';
    $distributor = '';
    $select="<option value=''>Select</option>";
} else if ($_SESSION['user_level'] == "Territory") {
    $zone_id = "AND zone_id='" . $_SESSION['zone_id'] . "'";
    $territory = "AND territory_id='" . $_SESSION['territory_id'] . "'";
    $distributor = '';
    $select="<option value=''>Select</option>";
} else if ($_SESSION['user_level'] == "Distributor") {
    $zone_id = "AND zone_id='" . $_SESSION['zone_id'] . "'";
    $territory = "AND territory_id='" . $_SESSION['territory_id'] . "'";
    $distributor = "AND distributor_id='" . $_SESSION['employee_id'] . "'";
} else {
    $select="<option value=''>Select</option>";
    $zone_id = '';
    $territory = '';
    $distributor = '';
}

echo $select;
echo $sql_uesr_group = "select distributor_id as fieldkey, distributor_name as fieldtext from $tbl" . "distributor_info where status='Active' AND del_status='0' $zone_id $territory $distributor ORDER BY distributor_name";
echo $db->SelectList($sql_uesr_group);
?>