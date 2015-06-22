<?php

require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$tbl = _DB_PREFIX;
$db = new Database();

if($_POST['zilla_id']!="")
{
   $zilla_id="AND zillaid='".$_POST['zilla_id']."'";
}
else
{
    $zilla_id="";
}




echo "<option value=''>Select</option>";
//echo $sql_uesr_group = "select distributor_id as fieldkey, CONCAT_WS(' - ', customer_code, distributor_name) as fieldtext from $tbl" . "distributor_info where status='Active' AND del_status='0' AND zone_id='".$_POST['zone_id']."' $territory order by distributor_name";
echo $sql_uesr_group = "select upazilaid as fieldkey, upazilanameeng as fieldtext from $tbl" . "upazilla where visible='0' $zilla_id";
echo $db->SelectList($sql_uesr_group);
?>