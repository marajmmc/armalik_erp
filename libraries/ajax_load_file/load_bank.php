<?php
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$tbl = _DB_PREFIX;
$db = new Database();

echo "<option value=''>Select</option>";
$sql_bank = "select bank_id as fieldkey, bank_name as fieldtext from $tbl" . "bank_info WHERE status='Active' AND del_status='0'";
echo $db->SelectList($sql_bank);
?>