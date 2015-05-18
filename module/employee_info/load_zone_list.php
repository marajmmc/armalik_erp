<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$tbl = _DB_PREFIX;
$db = new Database();

?>
<table class="table table-condensed table-striped table-bordered table-hover no-margin">
    <thead>
        <tr>
            <th style="width:40%">
                Zone
            </th>
        </tr>
    </thead>
    <tbody>
        <?php
        $sql = "SELECT
                    $tbl" . "zone_info.id,
                    $tbl" . "zone_info.zone_id,
                    $tbl" . "zone_info.zone_name,
                    $tbl" . "zone_info.`status`,
                    $tbl" . "zone_info.del_status
                FROM `$tbl" . "zone_info`
                WHERE $tbl" . "zone_info.`status`='Active' AND $tbl" . "zone_info.del_status='0'
        ";
        $i = 0;
        if ($db->open()) {
            $result = $db->query($sql);
            $tmp = '';
            while ($result_array = $db->fetchAssoc()) {
                if ($i % 2 == 0) {
                    $rowcolor = "gradeC";
                } else {
                    $rowcolor = "gradeA success";
                }
                echo "<tr class='row_hover' $rowcolor>
                <td align='left'>
                    <input type='checkbox'  name='$result_array[zone_id]' onclick='selectallTask(this)' id='$result_array[zone_id]' value='$result_array[zone_id]'  />
                    $result_array[zone_name] 
                    <input type='hidden' id='elmIndex[]' name='elmIndex[]' value='$result_array[zone_id]'/>
                </td>
        </tr>";
                ++$i;
            }
        }
        ?>
    </tbody>
</table>