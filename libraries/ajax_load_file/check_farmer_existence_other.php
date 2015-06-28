<?php
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$tbl = _DB_PREFIX;
$db = new Database();

if ($_POST['division_id'] != "")
{
    $division_id = "AND division_id='" . $_POST['division_id'] . "'";
}
else
{
    $division_id = "";
}

if ($_POST['zone_id'] != "")
{
    $zone_id = "AND zone_id='" . $_POST['zone_id'] . "'";
}
else
{
    $zone_id = "";
}

if ($_POST['territory_id'] != "")
{
    $territory_id = "AND territory_id='" . $_POST['territory_id'] . "'";
}
else
{
    $territory_id = "";
}

if ($_POST['district_id'] != "")
{
    $district_id = "AND district_id='" . $_POST['district_id'] . "'";
}
else
{
    $district_id = "";
}

if ($_POST['upazilla_id'] != "")
{
    $upazilla_id = "AND upazilla_id='" . $_POST['upazilla_id'] . "'";
}
else
{
    $upazilla_id = "";
}

if ($_POST['crop_id'] != "")
{
    $crop_id = "AND crop_id='" . $_POST['crop_id'] . "'";
}
else
{
    $crop_id = "";
}

if ($_POST['product_type_id'] != "")
{
    $product_type_id = "AND product_type_id='" . $_POST['product_type_id'] . "'";
}
else
{
    $product_type_id = "";
}

if ($_POST['variety_id'] != "")
{
    $variety_id = "AND variety_id='" . $_POST['variety_id'] . "'";
}
else
{
    $variety_id = "";
}

if ($_POST['farmers_name'] != "")
{
    $name = $_POST['farmers_name'];
    $farmers_name = "AND farmer_name='".$name."'";
}
else
{
    $farmers_name = "";
}

if ($_POST['farmer_id'] > 0)
{
    $id = $_POST['farmer_id'];
    $farmers_id = "AND id !=$id";
}
else
{
    $farmers_id = "";
}

$sql = "select id from $tbl" . "zi_others_popular_variety where del_status='0' $farmers_id $farmers_name $division_id $zone_id $territory_id $district_id $upazilla_id $crop_id $product_type_id $variety_id";
$result = $db->return_result_array($sql);

if(sizeof($result)>0 && $result[0]['id']>0)
{
    echo 1;
}
else
{
    echo 0;
}

?>