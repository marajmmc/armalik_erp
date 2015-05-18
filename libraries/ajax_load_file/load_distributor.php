<?php

require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$tbl = _DB_PREFIX;
$db = new Database();

if($_POST['territory_id']!="")
{
   $territory="AND territory_id='".$_POST['territory_id']."'";
}
else
{
    $territory="";
}

echo "<option value=''>Select</option>";
echo $sql_uesr_group = "select distributor_id as fieldkey, CONCAT_WS(' - ', customer_code, distributor_name) as fieldtext from $tbl" . "distributor_info where status='Active' AND del_status='0' AND zone_id='".$_POST['zone_id']."' $territory order by distributor_name";
echo $db->SelectList($sql_uesr_group);
?>