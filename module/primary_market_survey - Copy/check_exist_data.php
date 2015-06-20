<?php
session_start();
ob_start();
if ($_SESSION['logged'] != 'yes') {
    $_REQUEST["msg"] = "TimeoutC";
    header("location:../../index.php");
}
//echo $_SESSION['shop_name_eng']; 
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");


$tbl = _DB_PREFIX;
$db = new Database();

$zone_id=$_POST['zone_id'];
$territory_id=$_POST['territory_id'];
$district_id=$_POST['district_id'];
$upazilla_id=$_POST['upazilla_id'];
$pdo_year_id=$_POST['pdo_year_id'];
$crop_id=$_POST['crop_master_id'];
$product_type_id=$_POST['product_master_type_id'];

$exist_data=$db->single_data_w($tbl.'primary_market_survey', 'market_survey_group_id', "pdo_year_id='".$pdo_year_id."' AND zone_id='".$zone_id."' AND territory_id='".$territory_id."' AND district_id='".$district_id."' AND upazilla_id='".$upazilla_id."' AND crop_id='".$crop_id."' AND product_type_id='".$product_type_id."'");
if(!empty($exist_data['market_survey_group_id']))
{
    echo "Exist Data";
}

?>