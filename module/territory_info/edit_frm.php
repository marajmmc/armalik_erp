<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$tbl = _DB_PREFIX;
$editrow = $db->single_data($tbl . "territory_info", "*", "zone_id", $_POST['rowID']);
?>
<div class="row-fluid">
    <div class="span12">
        <div class="widget span">
            <div class="widget-header">
                <div class="title">
                    <a id="dynamicTable"><?php echo $db->Get_Auto_TaskName() ?></a>
                    <span class="mini-title">

                    </span>
                </div>
                <span class="tools">
                    <a class="btn btn-small" data-original-title="">
                        <i class="icon-plus-sign" data-original-title="Share"> </i>
                    </a>
                </span>
            </div>
            <div class="form-horizontal no-margin">
                <div class="widget-body">
                    <div class="control-group">
                        <label class="control-label" for="bank_id">
                            Zone
                        </label>
                        <div class="controls">
                            <select id="zone_id" name="zone_id" class="span5" placeholder="Zone Name">
                                <option value="">Select</option>
                                <?php
                                $sql_uesr_group = "select zone_id as fieldkey, zone_name as fieldtext from $tbl" . "zone_info where status='Active' AND del_status='0' ".$db->get_zone_access($tbl. "zone_info")." ";
                                echo $db->SelectList($sql_uesr_group, $editrow['zone_id']);
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="controls controls-row">
                        <span class="label label label-info" style="cursor: pointer; float: right" onclick="RowIncrement()"> + Add More </span>
                    </div>
                </div>
                <div class="widget-body">
                    <div class="wrapper">
                        <table class="table table-condensed table-striped table-bordered table-hover no-margin" id="TaskTable">
                            <thead>
                                <tr>
                                    <th style="width:95%">
                                        Territory
                                    </th>
                                    <th style="width:5%">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT
                                            $tbl" . "territory_info.territory_id,
                                            $tbl" . "territory_info.territory_name
                                        FROM
                                            $tbl" . "territory_info
                                        WHERE
                                            $tbl" . "territory_info.zone_id='" . $_POST['rowID'] . "'  
                                        ";
                                $i = 0;
                                if ($db->open()) {
                                    $result = $db->query($sql);
                                    while ($result_array = $db->fetchAssoc()) {
                                        if ($i % 2 == 0) {
                                            $rowcolor = "gradeC";
                                        } else {
                                            $rowcolor = "gradeA success";
                                        }
                                        echo "<tr class='$rowcolor'>
                                            <td>
                                                <input type='text' name='territory_name[]' value='$result_array[territory_name]' maxlength='50' id='territory_name_$i' class='span12'/>        
                                                <input type='hidden' id='territory_id[]' name='territory_id[]' value='$result_array[territory_id]'/>
                                                <input type='hidden' id='elmIndex[]' name='elmIndex[]' value='$i'/>
                                            </td>
                                            <td>    </td>
                                        <tr>";

                                        ++$i;
                                    }
                                }
                                ?>
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
