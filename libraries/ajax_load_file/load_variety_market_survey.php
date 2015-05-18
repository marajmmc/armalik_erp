<?php
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$tbl = _DB_PREFIX;
$db = new Database();
if($_POST['crop_id']!=""){
    $crop_id="AND crop_id='".$_POST['crop_id']."'";
}else{
    $crop_id="AND crop_id=''";
}
if($_POST['product_type_id']!=""){
    $product_type_id="AND product_type_id='".$_POST['product_type_id']."'";
}else{
    $product_type_id="AND product_type_id=''";
}
//if($_POST['hybrid']!=""){
//    $hybrid="AND hybrid='".$_POST['hybrid']."'";
//}else{
//    $hybrid="AND hybrid=''";
//}
//varriety_name
echo "<option value=''>Select</option>";
$sql_uesr_group = "
select
varriety_id as fieldkey,
CONCAT_WS(' - ', varriety_name,
CASE
		WHEN type=0 THEN 'ARM'
		WHEN type=1 THEN 'Check Variety'
		WHEN type=2 THEN 'Upcoming'
END, hybrid) as fieldtext
from $tbl" . "varriety_info
where
status='Active'
AND del_status='0'
AND (type=0 OR type=1)
$crop_id
$product_type_id
ORDER BY order_variety";
echo $db->SelectList($sql_uesr_group);
?>