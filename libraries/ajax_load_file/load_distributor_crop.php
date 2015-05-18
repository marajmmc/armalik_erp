<?php

require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$tbl = _DB_PREFIX;
$db = new Database();
echo "<option value=''>Select</option>";
$sql_uesr_group = "SELECT
                        $tbl" . "crop_info.crop_id as fieldkey,
                        $tbl" . "crop_info.crop_name as fieldtext
                    FROM
                        $tbl" . "distributor_product_stock
                        LEFT JOIN $tbl" . "crop_info ON $tbl" . "crop_info.crop_id = $tbl" . "distributor_product_stock.crop_id
                    WHERE
                        $tbl" . "distributor_product_stock.distributor_id='" . $_POST['distributor_id'] . "'
                    ORDER BY $tbl" . "crop_info.crop_name
                ";
echo $db->SelectList($sql_uesr_group);
?>