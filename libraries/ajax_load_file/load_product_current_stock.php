<?php

require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$tbl = _DB_PREFIX;
$db = new Database();

$price = $db->single_data_w($tbl . "product_stock", "sum(current_stock_qunatity) as current_stock_qunatity", "crop_id='".$_POST['crop_id']."' AND product_type_id='".$_POST['product_type_id']."' AND varriety_id='".$_POST['varriety_id']."' AND pack_size='".$_POST['pack_size']."' AND warehouse_id='" . $_POST['warehouse_id'] . "'");
echo $price['current_stock_qunatity'];
?>