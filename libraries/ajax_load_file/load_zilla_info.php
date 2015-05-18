<?php
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$tbl = _DB_PREFIX;
$db = new Database();

echo "<option value=''>Select</option>";
echo $sql_uesr_group = "select zillaid as fieldkey, zillanameeng as fieldtext from $tbl" . "zilla where divid='".$_POST['division_id']."' ORDER BY zillanameeng";
echo $db->SelectList($sql_uesr_group);
?>