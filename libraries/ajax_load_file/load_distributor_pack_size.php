<?php

require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$tbl = _DB_PREFIX;
$db = new Database();
if ($_POST['crop_id'] != "") {
    $crop_id = "AND crop_id='" . $_POST['crop_id'] . "'";
    $varriety_id = "AND varriety_id='" . $_POST['varriety_id'] . "'";
} else {
    $crop_id = "AND crop_id=''";
    $varriety_id = "AND varriety_id=''";
}
echo "<option value=''>Select</option>";
echo $sql_uesr_group = "SELECT
                            $tbl" . "distributor_product_stock.pack_size as fieldkey,
                            $tbl" . "product_pack_size.pack_size_name as fieldtext
                        FROM
                            $tbl" . "distributor_product_stock
                            LEFT JOIN $tbl" . "product_pack_size ON $tbl" . "product_pack_size.pack_size_id = $tbl" . "distributor_product_stock.pack_size
                        WHERE $tbl" . "distributor_product_stock.distributor_id='" . $_POST['distributor_id'] . "' AND
                            $tbl" . "distributor_product_stock.crop_id='" . $_POST['crop_id'] . "' AND
                            $tbl" . "distributor_product_stock.product_type_id='" . $_POST['product_type_id'] . "' AND
                            $tbl" . "distributor_product_stock.varriety_id='" . $_POST['varriety_id'] . "'
                        ";

echo $db->SelectList($sql_uesr_group);
?>