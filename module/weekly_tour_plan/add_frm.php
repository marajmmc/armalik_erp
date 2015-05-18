<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$tbl = _DB_PREFIX;
$employee_id=$_SESSION['employee_id'];
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
                            Zone
                        </label>
                        <div class="controls">
                            <select id="zone_id" name="zone_id" class="span5" placeholder="Zone" onchange="load_territory_fnc()" validate="Require">
                                <option value="">Select</option>
                                <?php
                                $sql_uesr_group = "select zone_id as fieldkey, zone_name as fieldtext from $tbl" . "zone_info WHERE status='Active' AND del_status='0' ".$db->get_zone_access($tbl. "zone_info")." ";
                                echo $db->SelectList($sql_uesr_group);
                                ?>
                            </select>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
<!--                    <div class="control-group">
                        <label class="control-label" for="employee_id">
                            Territory
                        </label>
                        <div class="controls">
                            <select id="territory_id" name="territory_id" class="span5" placeholder="Territory" onchange="load_distributor_fnc()" validate="Require" >
                                <option value="">Select</option>

                            </select>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>-->
                    <div class="control-group">
                        <label class="control-label" for="employee_id">
                            Employee Name
                        </label>
                        <div class="controls">
                            <select id="employee_id" name="employee_id" class="span5" placeholder="Employee Name" validate="Require">
                                <option value="">Select</option>
                                <?php
                                $sql_uesr_group = "select employee_id as fieldkey, employee_name as fieldtext from $tbl" . "employee_basic_info where status='Active' AND del_status='0' AND employee_id='$employee_id'";
                                echo $db->SelectList($sql_uesr_group, $employee_id);
                                ?>
                            </select>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="location">
                            Month
                        </label>
                        <div class="controls">
                            <select id="month_id" name="month_id" class="span5" placeholder="Month Name" validate="Require">
                                <option value="">Select</option>
                                <?php
                                $sql_uesr_group = "select month_id as fieldkey, month_full_name as fieldtext from $tbl" . "month_info where status='Active' AND del_status='0'";
                                echo $db->SelectList($sql_uesr_group);
                                ?>
                            </select>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="visit_purpose">
                            Week
                        </label>
                        <div class="controls">
                            <select id="week_id" name="week_id" class="span5" placeholder="Week Name" validate="Require">
                                <option value="">Select</option>
                                <option value="1st Week">1st Week</option>
                                <option value="2nd Week">2nd Week</option>
                                <option value="3rd Week">3rd Week</option>
                                <option value="4th Week">4th Week</option>
                            </select>
                            <span class="help-inline">
                                *
                            </span>
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
                                    <th style="width:10%">
                                        Location
                                    </th>
                                    <th style="width:10%">
                                        Visit Purpose
                                    </th>
                                    <th style="width:5%">
                                        Date
                                    </th>
                                    <th style="width:1%">
                                        Action
                                    </th>
                                </tr>
                            </thead>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        session_load_fnc()
    });
    
</script>