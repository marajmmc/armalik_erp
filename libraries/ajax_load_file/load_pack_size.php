<?php
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$tbl = _DB_PREFIX;
$db = new Database();
if($_POST['crop_id']!=""){
    $crop_id="AND crop_id='".$_POST['crop_id']."'";
    $product_type_id = "AND product_type_id='" . $_POST['product_type_id'] . "'";
    $varriety_id="AND varriety_id='".$_POST['varriety_id']."'";
}else{
    $crop_id="AND crop_id=''";
    $product_type_id = "AND product_type_id=''";
    $varriety_id="AND varriety_id=''";
}
echo "<option value=''>Select</option>";
echo $sql_uesr_group = "select 
                            $tbl" . "product_info.pack_size as fieldkey, $tbl" . "product_pack_size.pack_size_name as fieldtext 
                        from $tbl" . "product_info 
                        LEFT JOIN $tbl" . "product_pack_size ON $tbl" . "product_pack_size.pack_size_id=$tbl" . "product_info.pack_size
                        where $tbl" . "product_info.status='Active' $crop_id $product_type_id $varriety_id
                        ORDER BY $tbl" . "product_pack_size.pack_size_name";
echo $db->SelectList($sql_uesr_group);
?>