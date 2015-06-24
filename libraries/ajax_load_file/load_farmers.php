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

if($_POST['district_id']!="")
{
   $district_id="AND district_id='".$_POST['district_id']."'";
}
else
{
    $district_id="";
}

if($_POST['upazilla_id']!="")
{
   $upazilla_id = "AND upazilla_id='".$_POST['upazilla_id']."'";
}
else
{
    $upazilla_id = "";
}

if($_POST['crop_id']!="")
{
   $crop_id = "AND crop_id='".$_POST['crop_id']."'";
}
else
{
    $crop_id = "";
}

if($_POST['type_id']!="")
{
   $type_id = "AND product_type_id='".$_POST['type_id']."'";
}
else
{
    $type_id = "";
}

if($_POST['variety_id']!="")
{
   $variety_id = "AND variety_id='".$_POST['variety_id']."'";
}
else
{
    $variety_id = "";
}

echo "<option value=''>Select</option>";

$sql = "select id as fieldkey, farmers_name as fieldtext from $tbl" . "zi_crop_farmer_setup where del_status='0' $territory $district_id $upazilla_id $crop_id $type_id $variety_id";
echo $db->SelectList($sql);
?>