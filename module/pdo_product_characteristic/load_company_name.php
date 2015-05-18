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

//echo $_POST['rowID'];
$tbl = _DB_PREFIX;
$db = new Database();
//if($_POST['product_category']=="Self")
//{
//    $cn=$db->single_data_w
//    (
//        $tbl."pdo_product_characteristic_setting",
//        "company_name, DATE_FORMAT(cultivation_period_start,'%d %M') as cultivation_period_start, DATE_FORMAT(cultivation_period_end,'%d %M') as cultivation_period_end, special_characteristics, hybrid, image_url",
//        "zone_id='".$_POST['zone_id']."' AND
//product_category='Self' AND
//crop_id='".$_POST['crop_id']."' AND
//product_type_id='".$_POST['product_type_id']."' AND
//variety_id='".$_POST['variety_id']."'
//");
//}
//else
//{
//    $cn=$db->single_data_w
//    (
//        $tbl."pdo_product_characteristic_setting",
//        "company_name, DATE_FORMAT(cultivation_period_start,'%d %M') as cultivation_period_start, DATE_FORMAT(cultivation_period_end,'%d %M') as cultivation_period_end, special_characteristics, hybrid, image_url",
//        "zone_id='".$_POST['zone_id']."' AND
//product_category='Checked Variety' AND
//crop_id='".$_POST['crop_id']."' AND
//product_type_id='".$_POST['product_type_id']."' AND
//variety_id='".$_POST['variety_id']."' AND
//prodcut_characteristic_id='".$_POST['variety_name_txt']."'
//");
//}
//
//echo $cn['company_name']."~".$cn['cultivation_period_start']."~".$cn['cultivation_period_end']."~".$cn['special_characteristics']."~".$cn['hybrid']."~".$cn['image_url'];

///////////////// new query //////////

if($_POST['product_category']=="Self")
{
    $sql="SELECT
                ait_pdo_product_characteristic_setting_zone.company_name,
                ait_pdo_product_characteristic_setting_zone.cultivation_period_start,
                ait_pdo_product_characteristic_setting_zone.cultivation_period_end,
                ait_pdo_product_characteristic_setting_zone.special_characteristics,
                ait_pdo_product_characteristic_setting_zone.hybrid,
                ait_pdo_product_characteristic_setting_zone.image_url
            FROM `ait_pdo_product_characteristic_setting_zone`
            WHERE zone_id='".$_POST['zone_id']."'
            AND product_category='Self'
            AND crop_id='".$_POST['crop_id']."'
            AND product_type_id='".$_POST['product_type_id']."'
            AND variety_id='".$_POST['variety_id']."'
            ";
    if($db->open())
    {
        $result=$db->query($sql);
        $row=$db->fetchAssoc($result);
        $str=$row['company_name']."~".$row['cultivation_period_start']."~".$row['cultivation_period_end']."~".$row['special_characteristics']."~".$row['hybrid']."~".$row['image_url'];
    }
}
else if($_POST['product_category']=="Checked Variety")
{
    if(!empty($_POST['variety_id']))
    {
        $variety="AND ait_pdo_product_characteristic_setting_zone.variety_id='".$_POST['variety_id']."'";
    }
    else if(!empty($_POST['variety_name_txt']))
    {
        $variety="AND ait_pdo_product_characteristic_setting_zone.prodcut_characteristic_id='".$_POST['variety_name_txt']."'";
    }
    else
    {
        $variety='';
    }
	 $sql="SELECT
                ait_pdo_product_characteristic_setting_zone.variety_name_txt,
                ait_pdo_product_characteristic_setting_zone.company_name,
                ait_pdo_product_characteristic_setting_zone.cultivation_period_start,
                ait_pdo_product_characteristic_setting_zone.cultivation_period_end,
                ait_pdo_product_characteristic_setting_zone.special_characteristics,
                ait_pdo_product_characteristic_setting_zone.hybrid,
                ait_pdo_product_characteristic_setting_zone.image_url
            FROM
                ait_pdo_product_characteristic_setting_zone
            WHERE ait_pdo_product_characteristic_setting_zone.zone_id='".$_POST['zone_id']."'
            AND ait_pdo_product_characteristic_setting_zone.product_category='Checked Variety'
            AND ait_pdo_product_characteristic_setting_zone.crop_id='".$_POST['crop_id']."'
            AND ait_pdo_product_characteristic_setting_zone.product_type_id='".$_POST['product_type_id']."'
            $variety
            ";
    if($db->open())
    {
        $result=$db->query($sql);
        $row=$db->fetchAssoc($result);
        $str=$row['company_name']."~".$row['cultivation_period_start']."~".$row['cultivation_period_end']."~".$row['special_characteristics']."~".$row['hybrid']."~".$row['image_url'];
    }
}
else
{
    $str="";
}
echo $str;

?>