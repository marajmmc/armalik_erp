<?php

session_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$tbl = _DB_PREFIX;
$db = new Database();

//$zone_id = "AND zone_id='" . $_SESSION['zone_id'] . "'";
if ($_POST['division_id']) {
    $division_id = "AND division_id='" . $_POST['division_id'] . "'";
} else {
    $division_id = '';
}

echo "<option value=''>Select</option>";
$sql_uesr_group = "SELECT
                            $tbl" . "zone_info.zone_id as fieldkey,
                            $tbl" . "zone_info.zone_name as fieldtext
                        FROM
                            $tbl" . "zone_user_access
                            LEFT JOIN $tbl" . "zone_info ON $tbl" . "zone_info.zone_id = $tbl" . "zone_user_access.zone_id
                        WHERE
                            $tbl" . "zone_user_access.del_status='0' AND $tbl" . "zone_user_access.status='Active' $division_id
";
echo $db->SelectList($sql_uesr_group);
?>