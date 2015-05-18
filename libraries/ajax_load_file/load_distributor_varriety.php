<?php

require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$tbl = _DB_PREFIX;
$db = new Database();

echo "<option value=''>Select</option>";
$sql_uesr_group = "SELECT
                        $tbl" . "distributor_product_stock.varriety_id as fieldkey,
                        $tbl" . "varriety_info.varriety_name as fieldtext
                    FROM
                        $tbl" . "distributor_product_stock
                        LEFT JOIN $tbl" . "varriety_info ON $tbl" . "varriety_info.varriety_id = $tbl" . "distributor_product_stock.varriety_id
                    WHERE
                        $tbl" . "distributor_product_stock.distributor_id='" . $_POST['distributor_id'] . "' AND 
                        $tbl" . "distributor_product_stock.crop_id='" . $_POST['crop_id'] . "' AND
                        $tbl" . "distributor_product_stock.product_type_id='" . $_POST['product_type_id'] . "'
                    ORDER BY $tbl" . "varriety_info.varriety_name
    ";
echo $db->SelectList($sql_uesr_group);
?>