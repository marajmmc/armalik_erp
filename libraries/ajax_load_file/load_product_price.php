<?php
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$tbl = _DB_PREFIX;
$db = new Database();

$price=$db->single_data_w($tbl."product_pricing", "selling_price", "status='Active' AND crop_id='" . $_POST['crop_id'] . "' AND product_type_id='" . $_POST['product_type_id'] . "' AND varriety_id='" . $_POST['varriety_id'] . "' AND pack_size='" . $_POST['pack_size'] . "'");
echo $price['selling_price'];
?>