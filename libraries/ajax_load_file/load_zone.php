<?php
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$tbl = _DB_PREFIX;
$db = new Database();

if ($_POST['division_id'] != "")
{
    $division_id = "AND division_id='" . $_POST['division_id'] . "'";
}
else
{
    $division_id = "AND division_id=''";
}


echo "<option value=''>Select</option>";
$sql = "select zone_id as fieldkey, zone_name as fieldtext from $tbl" . "zone_info where status='Active' AND del_status='0' $division_id";
echo $db->SelectList($sql);
?>