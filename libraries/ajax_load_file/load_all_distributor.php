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

if(strlen($_POST['zone_id'])>0)
{
   $zone_id="AND zone_id='".$_POST['zone_id']."'";
}
else
{
    $zone_id="";
}



echo "<option value=''>Select</option>";
//echo $sql_uesr_group = "select distributor_id as fieldkey, CONCAT_WS(' - ', customer_code, distributor_name) as fieldtext from $tbl" . "distributor_info where status='Active' AND del_status='0' AND zone_id='".$_POST['zone_id']."' $territory order by distributor_name";
$sql1 = "select distributor_id as fieldkey, distributor_name as fieldtext from $tbl" . "distributor_info where status='Active' AND del_status='0' $zone_id $territory $zilla_id order by distributor_name";
$arr1 = $db->return_result_array($sql1);

$sql2 = "select distributor_id as fieldkey, distributor_name as fieldtext from $tbl" . "other_distributor_info where status='Active' AND del_status='0' $zone_id $territory $zilla_id order by distributor_name";
$arr2 = $db->return_result_array($sql2);
$arr = array_merge($arr1, $arr2);

$selectData = "";
foreach($arr as $customer)
{
    $selectData.="<option value='$customer[fieldkey]'>$customer[fieldtext]</option>";
}
echo $selectData;
?>