<?php
session_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$tbl = _DB_PREFIX;
$db = new Database();
$crop_id=$_POST['crop_id'];
$product_type_id=$_POST['product_type_id'];
$varriety_id=$_POST['varriety_id'];
$pack_size=$_POST['pack_size'];
$distributor_id=$_POST['distributor_id'];

$prices = $db->single_data_w($tbl . "distributor_product_stock", "current_stock_qunatity", "crop_id='$crop_id' AND product_type_id='$product_type_id' AND varriety_id='$varriety_id' AND pack_size='$pack_size' AND distributor_id='$distributor_id'");
echo $crnt_stock= $prices['current_stock_qunatity'];

?>