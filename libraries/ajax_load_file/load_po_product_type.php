<?php
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$tbl = _DB_PREFIX;
$db = new Database();
if($_POST['crop_id']!="")
{
    $crop_id="AND crop_id='".$_POST['crop_id']."'";
}
else
{
    $crop_id="AND crop_id=''";
}
echo "<option value=''>Select</option>";
$sql_uesr_group = "select product_type_id as fieldkey, product_type as fieldtext from $tbl" . "product_type where product_type_id IN (select product_type_id from $tbl"."product_pricing where status='Active') AND status='Active' AND del_status='0' $crop_id";
echo $db->SelectList($sql_uesr_group);
?>