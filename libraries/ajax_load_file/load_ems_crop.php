<?php
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$tbl = _DB_PREFIX;
$db = new Database();
echo "<option value=''>Select</option>";
$sql_uesr_group = "select
crop_id as fieldkey,
crop_name as fieldtext
from $tbl" . "crop_info
where status='Active' AND del_status='0' order by order_crop";
echo $db->SelectList($sql_uesr_group);
?>