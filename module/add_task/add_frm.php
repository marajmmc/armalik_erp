<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$tbl = _DB_PREFIX;
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
                            <select id="employee_id" name="employee_id" class="span5" placeholder="Employee Name">
                                <option value="">Select</option>
                                <?php
                                $sql_uesr_group = "select employee_id as fieldkey, employee_name as fieldtext from $tbl" . "employee_basic_info where status='Active' AND del_status='0'";
                                echo $db->SelectList($sql_uesr_group);
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="task_name">
                            Task Name
                        </label>
                        <div class="controls">
                            <input class="span9" type="text" name="task_name" id="task_name" placeholder="Task Name" validate="Require" />
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
                                <input type="text" name="start_date" id="start_date" class="span9" placeholder="Start Date" value="<?php echo $db->date_formate($db->ToDayDate()) ?>" />
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
                                <input type="text" name="end_date" id="end_date" class="span9" placeholder="End Date" value="<?php echo $db->date_formate($db->ToDayDate()) ?>" />
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
                            <textarea rows="3" id="task_description" name="task_description" class="input-block-level" placeholder="Task Description" ></textarea>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="gender">
                            Incomplete Notification
                        </label>
                        <div class="controls">
                            <label class="radio inline">
                                <input type="checkbox" name="incom_system" id="incom_system" value="System" checked />
                                System
                            </label>
                            <label class="radio inline">
                                <input type="checkbox" name="incom_email" id="incom_email" value="Email" checked />
                                Email
                            </label>
                            <label class="radio inline">
                                <input type="checkbox" name="incom_sms" id="incom_sms" value="SMS" checked />
                                SMS
                            </label>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="gender">
                            Complete Notification
                        </label>
                        <div class="controls">
                            <label class="radio inline">
                                <input type="checkbox" name="com_system" id="com_system" value="System" checked />
                                System
                            </label>
                            <label class="radio inline">
                                <input type="checkbox" name="com_email" id="com_email" value="Email" checked />
                                Email
                            </label>
                            <label class="radio inline">
                                <input type="checkbox" name="com_sms" id="com_sms" value="SMS" checked />
                                SMS
                            </label>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="status">
                            Status
                        </label>
                        <div class="controls">
                            <select id="status" name="status" class="span5" placeholder="Status">
                                <option value="Incomplete">Incomplete</option>
                                <option value="Complete">Complete</option>
                            </select>
                        </div>
                    </div>
                    <!--                    <div class="controls controls-row">
                                            <span class="label label label-info" style="cursor: pointer; float: right" onclick="RowIncrement()"> + Add More </span>
                                        </div>-->
                </div>
                <!--                <div class="widget-body">
                                    <div class="wrapper">
                                        <table class="table table-condensed table-striped table-bordered table-hover no-margin" id="TaskTable">
                                            <thead>
                                                <tr>
                                                    <th style="width:15%">
                                                        Status
                                                    </th>
                                                    <th style="width:80%">
                                                        Comment
                                                    </th>
                                                    <th style="width:5%">
                                                        Action
                                                    </th>
                                                </tr>
                                            </thead>
                
                                        </table>
                                    </div>
                                </div>-->
            </div>
        </div>
    </div>
</div>
<script>    
    var cal = Calendar.setup({
        onSelect: function(cal) { cal.hide() },
        fdow :0,
        minuteStep:1
    });
    cal.manageFields("calcbtn_start_date", "start_date", "%d-%m-%Y");
    cal.manageFields("calcbtn_end_date", "end_date", "%d-%m-%Y");
</script>