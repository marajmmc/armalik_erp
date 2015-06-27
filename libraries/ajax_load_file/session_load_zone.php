<?php

session_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$tbl = _DB_PREFIX;
$db = new Database();

//$zone_id = "AND zone_id='" . $_SESSION['zone_id'] . "'";
if ($_SESSION['user_level'] == "Zone")
{
    $zone_id = "AND zone_id='" . $_SESSION['zone_id'] . "'";
}
else if ($_SESSION['user_level'] == "Territory")
{
    $zone_id = "AND zone_id='" . $_SESSION['zone_id'] . "'";
}
else if ($_SESSION['user_level'] == "Distributor")
{
    $zone_id = "AND zone_id='" . $_SESSION['zone_id'] . "'";
}
else if ($_SESSION['user_level'] == "Division")
{
    echo "<option value=''>Select</option>";
    $zone_id = "AND division_id='" . $_SESSION['division_id'] . "'";
}
else
{
    $zone_id = '';
}


echo $sql_uesr_group = "select zone_id as fieldkey, zone_name as fieldtext from $tbl" . "zone_info where status='Active' AND del_status='0' $zone_id ORDER BY zone_name";
echo $db->SelectList($sql_uesr_group);
?>