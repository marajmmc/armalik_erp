<?php
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$tbl = _DB_PREFIX;
$db = new Database();

echo "<option value=''>Select</option>";
$sql = "select upazilaid as fieldkey, upazilanameeng as fieldtext from $tbl" . "upazilla where zillaid='".$_POST['district_id']."' ORDER BY upazilanameeng";
echo $db->SelectList($sql);
?>