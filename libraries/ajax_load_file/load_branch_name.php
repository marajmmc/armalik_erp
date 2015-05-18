<?php

require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$tbl = _DB_PREFIX;
$db = new Database();
echo "<option value=''>Select</option>";
echo $sql_uesr_group = "select branch_id as fieldkey, branch_name as fieldtext from $tbl" . "bank_branch_info where status='Active' AND del_status='0' AND bank_id='" . $_POST['bank_id'] . "' order by branch_name";
echo $db->SelectList($sql_uesr_group);
?>