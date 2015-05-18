<?php

require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$tbl = _DB_PREFIX;
$db = new Database();


echo "<option value=''>Select</option>";
$sql_uesr_group = "SELECT
                        $tbl" . "distributor_product_stock.product_type_id as fieldkey,
                        $tbl" . "product_type.product_type as fieldtext
                    FROM
                        $tbl" . "distributor_product_stock
                        LEFT JOIN $tbl" . "product_type ON $tbl" . "product_type.product_type_id = $tbl" . "distributor_product_stock.product_type_id
                    WHERE
                        $tbl" . "distributor_product_stock.distributor_id='" . $_POST['distributor_id'] . "' AND $tbl" . "distributor_product_stock.crop_id='" . $_POST['crop_id'] . "'
    ";
echo $db->SelectList($sql_uesr_group);
?>