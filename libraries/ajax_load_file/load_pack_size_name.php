<?php

require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$tbl = _DB_PREFIX;
$db = new Database();
$pack_size = $_POST['pack_size_id'];

$ps=$db->single_data_w($tbl . "product_pack_size", "pack_size_name", "pack_size_id='$pack_size' AND del_status='0' AND status='Active'");
echo $ps['pack_size_name'];
?>