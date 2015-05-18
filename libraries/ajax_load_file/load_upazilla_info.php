<?php
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$tbl = _DB_PREFIX;
$db = new Database();

echo "<option value=''>Select</option>";
echo $sql_uesr_group = "select upazilla_id as fieldkey, upazilla_name as fieldtext from $tbl" . "upazilla_new where   zilla_id='".$_POST['district_id']."' ORDER BY upazilla_name";
echo $db->SelectList($sql_uesr_group);
?>