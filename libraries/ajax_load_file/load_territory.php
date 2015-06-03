<?php
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$tbl = _DB_PREFIX;
$db = new Database();
if($_POST['zone_id']!=""){
    $zone_id="AND zone_id='".$_POST['zone_id']."'";
}else{
    $zone_id="AND zone_id=''";
}
echo "<option value=''>Select</option>";
$sql_uesr_group = "select
                        territory_id as fieldkey,
                        territory_name as fieldtext
                    from $tbl" . "territory_info
                    where status='Active' AND del_status='0' AND territory_name!='' $zone_id
                    ORDER BY territory_name";
echo $db->SelectList($sql_uesr_group);
?>