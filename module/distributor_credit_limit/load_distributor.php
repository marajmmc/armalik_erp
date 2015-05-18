<?php

require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$tbl = _DB_PREFIX;
$db = new Database();

echo "<option value=''>Select</option>";
//echo $sql_uesr_group = "select 
//    distributor_id as fieldkey, 
//    distributor_name as fieldtext 
//    FROM $tbl" . "distributor_info 
//    WHERE status='Active' AND 
//    del_status='0' AND 
//    zone_id='".$_POST['zone_id']."' AND 
//    territory_id='".$_POST['territory_id']."' AND
//    distributor_id NOT IN (SELECT distributor_id FROM $tbl" . "distributor_credit_limit)
//";
echo $sql_uesr_group = "select 
    distributor_id as fieldkey, 
    distributor_name as fieldtext 
    FROM $tbl" . "distributor_info 
    WHERE status='Active' AND 
    del_status='0' AND
    zone_id='".$_POST['zone_id']."' AND 
    territory_id='".$_POST['territory_id']."'
";
echo $db->SelectList($sql_uesr_group);
?>