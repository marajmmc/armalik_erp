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
                        <label class="control-label" for="user_name">
                            User Name
                        </label>
                        <div class="controls">
                            <input class="span5" type="text" name="user_name" id="user_name" placeholder="User Name" validate="Require" onblur="Existin_data(this)">
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="user_pass">
                            Password
                        </label>
                        <div class="controls">
                            <input type="password" name="user_pass" id="user_pass" class="span5" placeholder="User Password"  validate="Require" autocomplete="off" title="Password must be 6 digit And minimum one Uppercase Word, one Lowercase word and one numeric."/>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="repuser_pass">
                            Repeat Password
                        </label>
                        <div class="controls">
                            <input type="password" name="repuser_pass" id="repuser_pass" class="span5" placeholder="Retype Password"  validate="Require" />
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="user_group_id">
                            Group Name
                        </label>
                        <div class="controls controls-row">
                            <select id="user_group_id" name="user_group_id" class="span5" validate="Require" placeholder="Group Name">
                                <option value="">Select</option>
                                <?php
                                $sql_uesr_group = "select ug_id as fieldkey, ug_name as fieldtext from $tbl" . "user_group group by ug_name";
                                echo $db->SelectList($sql_uesr_group);
                                ?>
                            </select>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="user_unit_id">
                            Level
                        </label>
                        <div class="controls controls-row">
                            <select id="user_level" name="user_level" class="span5" onchange="load_user_type();load_employee_fnc()" validate="Require" placeholder="User Type">
                                <option value="">Select</option>
                                <option value="Marketing">Marketing</option>
                                <option value="Warehouse">Warehouse</option>
                                <option value="Division">Division</option>
                                <option value="Zone">Zone</option>
                                <option value="Territory">Territory</option>
                                <option value="Distributor">Distributor</option>
                            </select>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group" id="div_warehouse_id" style="display: none;">
                        <label class="control-label" for="warehouse_id">
                            Select Warehouse
                        </label>
                        <div class="controls">
                            <select id="warehouse_id" name="warehouse_id" class="span5" placeholder="Warehouse" onchange="load_employee_fnc()">
                                <option value="">Select</option>
                                <?php
                                $sql_uesr_group = "select warehouse_id as fieldkey, warehouse_name as fieldtext from $tbl" . "warehouse_info WHERE status='Active' AND del_status='0'";
                                echo $db->SelectList($sql_uesr_group);
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="control-group" id="div_division_id" style="display: none;">
                        <label class="control-label" for="division_id">
                            Division
                        </label>
                        <div class="controls">
                            <select id="division_id" name="division_id" class="span5" placeholder="division" onchange="load_employee_fnc()">
                                <option value="">Select</option>
                                <?php
                                $sql_uesr_group = "select division_id as fieldkey, division_name as fieldtext from $tbl" . "division_info WHERE status='Active' AND del_status='0'";
                                echo $db->SelectList($sql_uesr_group);
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="control-group" id="div_zone_id" style="display: none;">
                        <label class="control-label" for="zone_id">
                            Zone
                        </label>
                        <div class="controls">
                            <select id="zone_id" name="zone_id" class="span5" placeholder="Zone" onchange="load_territory_fnc();load_employee_fnc()">
                                <option value="">Select</option>
                                <?php
                                $sql_uesr_group = "select zone_id as fieldkey, zone_name as fieldtext from $tbl" . "zone_info WHERE status='Active' AND del_status='0'";
                                echo $db->SelectList($sql_uesr_group);
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="control-group" id="div_territory_id" style="display: none;">
                        <label class="control-label" for="territory_id">
                            Territory
                        </label>
                        <div class="controls">
                            <select id="territory_id" name="territory_id" class="span5" placeholder="Territory"  onchange="load_distributor_fnc();load_employee_fnc()">
                                <option value="">Select</option>

                            </select>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="employee_id">
                            Name
                        </label>
                        <div class="controls controls-row">
                            <select id="employee_id" name="employee_id" class="span5" validate="Require" placeholder="Employee Name">
                                <option value="">Select</option>
                            </select>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
