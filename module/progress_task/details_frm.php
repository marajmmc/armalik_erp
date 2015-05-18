<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$tbl = _DB_PREFIX;
$sql = "SELECT
            $tbl" . "task_management.id,
            $tbl" . "task_management.task_plan_id,
            $tbl" . "task_management.employee_id,
            $tbl" . "task_management.task_name,
            $tbl" . "task_management.start_date,
            $tbl" . "task_management.end_date,
            $tbl" . "task_management.status
        FROM
            $tbl" . "task_management
        WHERE $tbl" . "task_management.task_plan_id='" . $_POST['rowID'] . "'
";
if ($db->open()) {
    $result = $db->query($sql);
    while ($editrow = $db->fetchAssoc($result)) {
        $task_plan_id = $editrow['task_plan_id'];
        $employee_id = $editrow['employee_id'];
        $task_name = $editrow['task_name'];
        $start_date = $editrow['start_date'];
        $end_date = $editrow['end_date'];
        $task_status = $editrow['status'];
    }
}
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
                        <label class="control-label" for="employee_id">
                            Employee Name
                        </label>
                        <div class="controls">
                            <select disabled="" id="employee_id" name="employee_id" class="span5" placeholder="Employee Name">
                                <!--<option value="">Select</option>-->
                                <?php
                                $sql_uesr_group = "select employee_id as fieldkey, employee_name as fieldtext from $tbl" . "employee_basic_info where status='Active' AND del_status='0' AND employee_id='$employee_id'";
                                echo $db->SelectList($sql_uesr_group, $employee_id);
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="task_name">
                            Task Name
                        </label>
                        <div class="controls">
                            <input disabled="" class="span9" type="text" name="task_name" id="task_name" value="<?php echo $task_name; ?>" placeholder="Task Name" validate="Require" />
                            <input class="span9" type="hidden" name="task_plan_id" id="task_plan_id" value="<?php echo $task_plan_id; ?>" />
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="start_date">
                            Start Date
                        </label>
                        <div class="controls">
                            <div class="input-append">
                                <input disabled="" type="text" name="start_date" id="start_date" class="span9" placeholder="Start Date" value="<?php echo $db->date_formate($start_date) ?>" />
                                <span class="add-on" id="calcbtn_start_date">
                                    <i class="icon-calendar"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="end_date">
                            End Date
                        </label>
                        <div class="controls">
                            <div class="input-append">
                                <input disabled="" type="text" name="end_date" id="end_date" class="span9" placeholder="End Date" value="<?php echo $db->date_formate($end_date) ?>" />
                                <span class="add-on" id="calcbtn_end_date">
                                    <i class="icon-calendar"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="status">
                            Status
                        </label>
                        <div class="controls">
                            <select disabled="" id="task_status" name="task_status" class="span5" placeholder="Status">
                                <option value="Incomplete" <?php
                                if ($task_status == "Incomplete") {
                                    echo "selected='selected'";
                                }
                                ?> >Incomplete</option>
                                <option value="Complete" <?php
                                if ($task_status == "Complete") {
                                    echo "selected='selected'";
                                }
                                ?> >Complete</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="widget-body">
                    <div class="wrapper">
                        <table class="table table-condensed table-striped table-bordered table-hover no-margin" id="TaskTable">
                            <thead>
                                <tr>
                                    <th style="width:15%">
                                        Status
                                    </th>
                                    <th style="width:60%">
                                        Comment
                                    </th>
                                    <th style="width:10%">
                                        Date
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 0;
                                $sql = "SELECT
                                            $tbl" . "task_progress.id,
                                            $tbl" . "task_progress.task_plan_id,
                                            $tbl" . "task_progress.employee_id,
                                            $tbl" . "task_progress.task_status,
                                            $tbl" . "task_progress.`comment`,
                                            $tbl" . "task_progress.`status`,
                                            $tbl" . "task_progress.del_status,
                                            $tbl" . "task_progress.entry_by,
                                            $tbl" . "task_progress.entry_date
                                        FROM `$tbl" . "task_progress`
                                        WHERE $tbl" . "task_progress.task_plan_id='" . $_POST['rowID'] . "'
                                        ";
                                if ($db->open()) {
                                    $result = $db->query($sql);
                                    while ($row = $db->fetchAssoc($result)) {

                                        if ($i % 2 == 0) {
                                            $rowcolor = "gradeC";
                                        } else {
                                            $rowcolor = "gradeA success";
                                        }
                                        ?>
                                        <tr class='<?php echo $rowcolor ?>'>
                                            <td>
                                                <?php echo $row['task_status']; ?>
                                            </td>
                                            <td>
                                                <?php echo $row['comment']; ?>        
                                            </td>
                                            <td>  <?php echo $db->date_formate($row['entry_date']) ?>  </td>
                                        <tr>
                                            <?php
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