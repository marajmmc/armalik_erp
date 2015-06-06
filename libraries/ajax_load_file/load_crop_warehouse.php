<?php

require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$tbl = _DB_PREFIX;
$db = new Database();

echo "<option value=''>Select</option>";
echo $sql_uesr_group = "SELECT
    $tbl" . "crop_info.crop_id AS fieldkey,
    $tbl" . "crop_info.crop_name AS fieldtext
FROM
    $tbl" . "product_info
    LEFT JOIN $tbl" . "crop_info ON $tbl" . "crop_info.crop_id = $tbl" . "product_info.crop_id
WHERE 
        $tbl" . "product_info.warehouse_id='".$_POST['warehouse_id']."'
        AND $tbl" . "crop_info.`status`='Active' AND $tbl" . "product_info.crop_id IN (SELECT $tbl" . "product_pricing.crop_id FROM $tbl" . "product_pricing WHERE $tbl" . "product_pricing.`status`='Active')
GROUP BY $tbl" . "crop_info.crop_id
ORDER BY $tbl" . "crop_info.order_crop";
echo $db->SelectList($sql_uesr_group);
?>