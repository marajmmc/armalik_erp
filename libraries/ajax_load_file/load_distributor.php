<?php

require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$tbl = _DB_PREFIX;
$db = new Database();

if($_POST['territory_id']!="")
{
   $territory="AND territory_id='".$_POST['territory_id']."'";
}
else
{
    $territory="";
}
if($_POST['zilla_id']!="")
{
   $zilla_id="AND zilla_id='".$_POST['zilla_id']."'";
}
else
{
    $zilla_id="";
}

echo "<option value=''>Select</option>";
//echo $sql_uesr_group = "select distributor_id as fieldkey, CONCAT_WS(' - ', customer_code, distributor_name) as fieldtext from $tbl" . "distributor_info where status='Active' AND del_status='0' AND zone_id='".$_POST['zone_id']."' $territory order by distributor_name";
$sql_uesr_group = "select distributor_id as fieldkey, distributor_name as fieldtext from $tbl" . "distributor_info where status='Active' AND del_status='0' AND zone_id='".$_POST['zone_id']."' $territory $zilla_id order by distributor_name";
echo $db->SelectList($sql_uesr_group);
?>