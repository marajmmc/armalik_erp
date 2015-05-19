<?php
@session_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$tbl = _DB_PREFIX;
$db = new Database();

if($_SESSION['user_level'] == "Division")
{
    $division="AND $tbl" . "division_info.division_id='".$_SESSION['division_id']."'";
}
else if($_SESSION['user_level'] == "Zone")
{
    $division="AND $tbl" . "zone_user_access.zone_id='".$_SESSION['zone_id']."'";
}
else
{
    echo "<option value=''>Select</option>";
    $division="";
}

//$sql_uesr_group = "select division_id as fieldkey, division_name as fieldtext from $tbl" . "division_info WHERE status='Active' AND del_status='0' $division";
$sql_uesr_group="
                SELECT
                    ait_division_info.division_name as fieldtext,
                    ait_division_info.division_id as fieldkey
                FROM
                  ait_zone_user_access
                  INNER JOIN ait_division_info ON ait_division_info.division_id = ait_zone_user_access.division_id
                WHERE
                  ait_zone_user_access.status='Active' AND ait_zone_user_access.del_status=0 $division
                GROUP BY ait_division_info.division_id
";
echo $db->SelectList($sql_uesr_group);
?>