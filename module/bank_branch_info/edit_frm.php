<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$tbl = _DB_PREFIX;
$editrow = $db->single_data($tbl . "bank_branch_info", "*", "bank_id", $_POST['rowID']);
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
                            Bank Name
                        </label>
                        <div class="controls">
                            <select id="bank_id" name="bank_id" class="span5" placeholder="Bank Name">
                                <option value="">Select</option>
                                <?php
                                $sql_uesr_group = "select bank_id as fieldkey, bank_name as fieldtext from $tbl" . "bank_info where status='Active' AND del_status='0'";
                                echo $db->SelectList($sql_uesr_group, $editrow['bank_id']);
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
                                        Branch Name
                                    </th>
                                    <th style="width:5%">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT
                                            $tbl" . "bank_branch_info.branch_id,
                                            $tbl" . "bank_branch_info.branch_name
                                        FROM
                                            $tbl" . "bank_branch_info
                                        WHERE
                                            $tbl" . "bank_branch_info.bank_id='" . $_POST['rowID'] . "'  
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
                                                <input type='text' name='branch_name[]' value='$result_array[branch_name]' maxlength='50' id='branch_name_$i' class='span12'/>        
                                                <input type='hidden' id='branch_id[]' name='branch_id[]' value='$result_array[branch_id]'/>
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
