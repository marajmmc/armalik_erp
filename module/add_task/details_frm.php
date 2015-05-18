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
            $tbl" . "task_management.task_description,
            $tbl" . "task_management.incomplete_status,
            $tbl" . "task_management.complete_status,
            $tbl" . "task_management.status
        FROM
            $tbl" . "task_management
        WHERE $tbl" . "task_management.task_plan_id='" . $_POST['rowID'] . "'
";
if ($db->open()) {
    $result = $db->query($sql);
    while ($editrow = $db->fetchAssoc($result)) {
        $id[] = $editrow['id'];
        $task_plan_id = $editrow['task_plan_id'];
        $employee_id = $editrow['employee_id'];
        $task_name = $editrow['task_name'];
        $start_date = $editrow['start_date'];
        $end_date = $editrow['end_date'];
        $task_description = $editrow['task_description'];
        $incomplete_status = $editrow['incomplete_status'];
        $complete_status = $editrow['complete_status'];
        $status = $editrow['status'];
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
                        <label class="control-label" for="task_description">
                            Task Description
                        </label>
                        <div class="controls">
                            <textarea disabled="" rows="3" id="task_description" name="task_description" class="input-block-level" placeholder="Task Description" ><?php echo $task_description; ?></textarea>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="gender">
                            Incomplete Notification
                            <?php
                            $incom = explode('~', $incomplete_status);
                            ?>
                        </label>
                        <div class="controls">
                            <label class="radio inline">
                                <input disabled="" type="checkbox" name="incom_system" id="incom_system" <?php
                            if ($incom[0] == "System") {
                                echo "checked='checked'";
                            }
                            ?> value="System" />
                                System
                            </label>
                            <label class="radio inline">
                                <input disabled="" type="checkbox" name="incom_email" id="incom_email" value="Email" <?php
                                       if ($incom[1] == "Email") {
                                           echo "checked='checked'";
                                       }
                            ?> />
                                Email
                            </label>
                            <label class="radio inline">
                                <input disabled="" type="checkbox" name="incom_sms" id="incom_sms" value="SMS" <?php
                                       if ($incom[2] == "SMS") {
                                           echo "checked='checked'";
                                       }
                            ?> />
                                SMS
                            </label>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="gender">
                            Complete Notification
                            <?php
                            $com = explode('~', $complete_status);
                            ?>
                        </label>
                        <div class="controls">
                            <label class="radio inline">
                                <input disabled="" type="checkbox" name="com_system" id="com_system" value="System"  <?php
                            if ($com[0] == "System") {
                                echo "checked='checked'";
                            }
                            ?>  />
                                System
                            </label>
                            <label class="radio inline">
                                <input disabled="" type="checkbox" name="com_email" id="com_email" value="Email" <?php
                                       if ($com[1] == "Email") {
                                           echo "checked='checked'";
                                       }
                            ?> />
                                Email
                            </label>
                            <label class="radio inline">
                                <input disabled="" type="checkbox" name="com_sms" id="com_sms" value="SMS" <?php
                                       if ($com[2] == "SMS") {
                                           echo "checked='checked'";
                                       }
                            ?> />
                                SMS
                            </label>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="status">
                            Status
                        </label>
                        <div class="controls">
                            <select disabled="" id="status" name="status" class="span5" placeholder="Status">
                                <option value="Incomplete" <?php
                                       if ($status == "Incomplete") {
                                           echo "selected='selected'";
                                       }
                            ?> >Incomplete</option>
                                <option value="Complete" <?php
                                        if ($status == "Complete") {
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
