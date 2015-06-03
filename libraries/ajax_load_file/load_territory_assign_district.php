<?php

require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$tbl = _DB_PREFIX;
$db = new Database();

if($_POST['zone_id']!="" && $_POST['territory_id']!="")
{
   $territory="AND $tbl" . "territory_assign_district.zone_id='".$_POST['zone_id']."' AND $tbl" . "territory_assign_district.territory_id='".$_POST['territory_id']."' ";
}
else
{
    $territory="";
}

//if($_SESSION['user_level']=="Zone")
//{
//    $zone_id="AND $tbl" . "zone_assign_district.zone_id='".$_SESSION['zone_id']."'";
//}
//else
//{
//    $zone_id="AND $tbl" . "zone_assign_district.zone_id='".$_POST['zone_id']."'";
//}

echo "<option value=''>Select</option>";
$sql_uesr_group = "SELECT
                        $tbl" . "zilla.zillaid as fieldkey,
                        $tbl" . "zilla.zillanameeng as fieldtext
                    FROM
                        $tbl" . "territory_assign_district
                        LEFT JOIN $tbl" . "zilla ON $tbl" . "zilla.zillaid = $tbl" . "territory_assign_district.zilla_id
                    WHERE
                        $tbl" . "territory_assign_district.del_status=0
                        AND $tbl" . "zilla.visible=0
                        AND $tbl" . "territory_assign_district.status='Active'
                        $territory
";
echo $db->SelectList($sql_uesr_group);
?>