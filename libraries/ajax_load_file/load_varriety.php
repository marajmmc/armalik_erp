<?php
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$tbl = _DB_PREFIX;
$db = new Database();

if ($_POST['crop_id'] != "")
{
    $crop_id = "AND crop_id='" . $_POST['crop_id'] . "'";
}
else
{
    $crop_id = "AND crop_id=''";
}

if ($_POST['product_type_id'] != "")
{
    $product_type_id = "AND product_type_id='" . $_POST['product_type_id'] . "'";
}
else
{
    $product_type_id = "AND product_type_id=''";
}

//if ($_POST['channel'] != "")
//{
//    $ch = "AND product_type_id='" . $_POST['product_type_id'] . "'";
//}
//else
//{
//    $product_type_id = "AND product_type_id=''";
//}
echo "<option value=''>Select</option>";
$sql_uesr_group = "select varriety_id as fieldkey, varriety_name as fieldtext from $tbl" . "varriety_info where status='Active' AND del_status='0' $crop_id $product_type_id ORDER BY varriety_name";
echo $db->SelectList($sql_uesr_group);
?>