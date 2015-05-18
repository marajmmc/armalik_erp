<?php
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$tbl = _DB_PREFIX;
$db = new Database();
if($_POST['crop_id']!=""){
    $crop_id="AND crop_id='".$_POST['crop_id']."'";
    $varriety_id="AND varriety_id='".$_POST['varriety_id']."'";
}else{
    $crop_id="AND crop_id=''";
    $varriety_id="AND varriety_id=''";
}
echo "<option value=''>Select</option>";
echo $sql_uesr_group = "select 
                            product_id as fieldkey, 
                            pack_size as fieldtext 
                        from $tbl" . "product_info 
                        where status='Active' $crop_id $varriety_id
                            AND product_id IN (select product_id from $tbl"."product_pricing where status='Active')
                        ORDER BY pack_size";
echo $db->SelectList($sql_uesr_group);
?>