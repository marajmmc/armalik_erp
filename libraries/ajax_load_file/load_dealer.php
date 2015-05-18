<?php

require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$tbl = _DB_PREFIX;
$db = new Database();

echo "<option value=''>Select</option>";
echo $sql_uesr_group = "select dealer_id as fieldkey, dealer_name as fieldtext from $tbl" . "dealer_info where status='Active' AND del_status='0' AND distributor_id='".$_POST['distributor_id']."' order by dealer_name";
echo $db->SelectList($sql_uesr_group);
?>