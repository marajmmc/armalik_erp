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
echo "<option value=''>Select</option>";
//echo $sql_uesr_group = "
//select
//prodcut_characteristic_id as fieldkey,
//variety_name_txt as fieldtext
//from $tbl" . "pdo_product_characteristic_setting
//where status='Active'  AND
//zone_id='".$_POST['zone_id']."' AND
//product_category='Checked Variety' AND
//crop_id='".$_POST['crop_id']."' AND
//product_type_id='".$_POST['product_type_id']."'
//";
//echo $db->SelectList($sql_uesr_group);

$sql_uesr_group="SELECT
            ait_pdo_product_characteristic_setting.prodcut_characteristic_id as fieldkey,
            ait_pdo_product_characteristic_setting.variety_name_txt as fieldtext
        FROM
            ait_pdo_product_characteristic_setting_zone
        INNER JOIN ait_pdo_product_characteristic_setting ON ait_pdo_product_characteristic_setting.prodcut_characteristic_id = ait_pdo_product_characteristic_setting_zone.prodcut_characteristic_id AND ait_pdo_product_characteristic_setting.product_category = ait_pdo_product_characteristic_setting_zone.product_category AND ait_pdo_product_characteristic_setting.crop_id = ait_pdo_product_characteristic_setting_zone.crop_id AND ait_pdo_product_characteristic_setting.product_type_id = ait_pdo_product_characteristic_setting_zone.product_type_id
        WHERE ait_pdo_product_characteristic_setting_zone.zone_id='".$_POST['zone_id']."'
        AND ait_pdo_product_characteristic_setting_zone.product_category='Checked Variety'
        AND ait_pdo_product_characteristic_setting_zone.crop_id='".$_POST['crop_id']."'
        AND ait_pdo_product_characteristic_setting_zone.product_type_id='".$_POST['product_type_id']."'
            ";
echo $db->SelectList($sql_uesr_group);

?>