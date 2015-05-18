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
                <div class="widget-body span6" style="border-bottom: none;">
                    <div class="control-group">
                        <span class="label label label-info">Employee Personal Information</span>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="employee_name">
                            Name
                        </label>
                        <div class="controls">
                            <input class="span9" type="text" name="employee_name" id="employee_name" placeholder="Name" validate="Require">
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="father_name">
                            Father's Name
                        </label>
                        <div class="controls">
                            <input type="text" name="father_name" id="father_name" class="span9" placeholder="Father's Name"  title=""/>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="mother_name">
                            Mother's Name
                        </label>
                        <div class="controls">
                            <input type="text" name="mother_name" id="mother_name" class="span9" placeholder="Mother's Name"  />
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="dob">
                            Date of Birth
                        </label>
                        <div class="controls">
                            <div class="input-append">
                                <input type="text" name="dob" id="dob" class="span9" placeholder="Date of birth"  />
                                <span class="add-on" id="calcbtn_dob">
                                    <i class="icon-calendar"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="gender">
                            Gender
                        </label>
                        <div class="controls">
                            <label class="radio inline">
                                <input type="radio" name="gender" id="gender" value="Male" checked>
                                Male
                            </label>
                            <label class="radio inline">
                                <input type="radio" name="gender" id="gender" value="Female">
                                Female
                            </label>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="marital_status">
                            Marital Status
                        </label>
                        <div class="controls">
                            <label class="radio inline">
                                <input type="radio" name="marital_status" id="marital_status" value="Married" checked>
                                Married
                            </label>
                            <label class="radio inline">
                                <input type="radio" name="marital_status" id="marital_status" value="Un-Married">
                                Un-Married
                            </label>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="spouse_name">
                            Spouse Name
                        </label>
                        <div class="controls">
                            <input type="text" name="spouse_name" id="spouse_name" class="span9" placeholder="Spouse Name"  />
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="nid_no">
                            NID Number
                        </label>
                        <div class="controls">
                            <input type="text" name="nid_no" id="nid_no" class="span9" placeholder="NID Number"  />
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="employee_designation">
                            Designation
                        </label>
                        <div class="controls">
                            <select id="employee_designation" name="employee_designation" class="span9" placeholder="Designation">
                                <option value="">Select</option>
                                <?php
                                $sql_uesr_group = "select designation_id as fieldkey, designation_title_en as fieldtext from $tbl" . "employee_designation";
                                echo $db->SelectList($sql_uesr_group);
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="address">
                            Present Address
                        </label>
                        <div class="controls">
                            <textarea rows="3" id="present_address" name="present_address" class="input-block-level" placeholder="Address" ></textarea>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label bottom-margin" for="image_url">
                            Profile Photo
                        </label>
                        <div class="controls">
                            <img src="../../system_images/profile.png" style="width: 77px; height: 77px;" id="blah" />
                            <input type="file" name="image_url" id="image_url" class="span9" placeholder="Date of Joining" onchange="readURL(this)" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-horizontal no-margin">
                <div class="widget-body span6" style="border-bottom: none;">
                    <div class="control-group">
                        <div class="control-group">
                            <span class="label label label-info">Employee Other's Information</span>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="basic_salary">
                                Basic Salary
                            </label>
                            <div class="controls">
                                <input type="text" name="basic_salary" id="basic_salary" class="span9" placeholder="Basic Salary" onkeypress="return numbersOnly(event)" />
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="other_allowance">
                                Other Allowance
                            </label>
                            <div class="controls">
                                <input type="text" name="other_allowance" id="other_allowance" class="span9" placeholder="Other Allowance"  onkeypress="return numbersOnly(event)"  />
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="date_of_joining">
                                Date of Joining
                            </label>
                            <div class="controls">
                                <div class="input-append">
                                    <input type="text" name="date_of_joining" id="date_of_joining" class="span9" placeholder="Date of Joining"  />
                                    <span class="add-on" id="calcbtn_date_of_joining">
                                        <i class="icon-calendar"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="increment_date">
                                Next Increment Date
                            </label>
                            <div class="controls">
                                <div class="input-append">
                                    <input type="text" name="increment_date" id="increment_date" class="span9" placeholder="Next Increment Date"  />
                                    <span class="add-on" id="calcbtn_increment_date">
                                        <i class="icon-calendar"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="blood_group">
                                Blood Group
                            </label>
                            <div class="controls controls-row">
                                <select id="blood_group" name="blood_group" class="span9" placeholder="Group Name">
                                    <option value="">Select</option>
                                    <option value="A+">A+</option>
                                    <option value="A-">A-</option>
                                    <option value="AB+">AB+</option>
                                    <option value="AB-">AB-</option>
                                    <option value="B+">B+</option>
                                    <option value="B-">B-</option>
                                    <option value="O+">O+</option>
                                    <option value="O-">O-</option>  
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="land_line_number">
                                Land Line Number
                            </label>
                            <div class="controls">
                                <input type="text" name="land_line_number" id="land_line_number" class="span9" placeholder="Land Line Number"  />
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="mobile_number">
                                Mobile Number
                            </label>
                            <div class="controls">
                                <input type="text" name="mobile_number" id="mobile_number" class="span9" placeholder="Mobile Number"  maxlength="11" validate="Mobile" />
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="contact_person">
                                Contact Person
                            </label>
                            <div class="controls">
                                <input type="text" name="contact_person" id="contact_person" class="span9" placeholder="Emergency Contact Person"  />
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="contact_number">
                                Contact Number
                            </label>
                            <div class="controls">
                                <input type="text" name="contact_number" id="contact_number" class="span9" placeholder="Emergency Contact Number" maxlength="11" validate="Mobile" />
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="address">
                                Permanent Address
                            </label>
                            <div class="controls">
                                <textarea rows="3" id="address" name="address" class="input-block-level" placeholder="Address" ></textarea>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="nid_no">
                                Employee ID No
                            </label>
                            <div class="controls">
                                <input type="text" name="employee_id_no" id="employee_id_no" class="span9" placeholder="Employee ID No"  />
                                &nbsp;<br/>
                                &nbsp;<br/>
                                &nbsp;<br/>
                                &nbsp;<br/>
                            </div>
                        </div>
                        <!--                        <div class="control-group">
                                                    <label class="control-label" for="address">
                                                        &nbsp;
                                                    </label>
                                                    <div class="controls">
                                                        &nbsp;<br/>
                                                        &nbsp;<br/>
                                                        &nbsp;<br/>
                                                        &nbsp;<br/>
                                                        &nbsp;<br/>
                                                    </div>
                                                </div>-->
                    </div>
                </div>
            </div>
            <div class="form-horizontal no-margin">
                <div class="widget-body">
                    <div class="control-group">
                        <div class="control-group">
                            <label class="control-label" for="">
                                <span class="label label label-info">Employee Official Information</span>
                            </label>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="user_unit_id">
                                Level
                            </label>
                            <div class="controls controls-row">
                                <select id="user_level" name="user_level" class="span5" onchange="load_user_type(); zone_access_fnc()" validate="Require" placeholder="User Type">
                                    <option value="">Select</option>
                                    <option value="Marketing">Marketing</option>
                                    <option value="Warehouse">Warehouse</option>
                                    <option value="Division">Division</option>
                                    <option value="Zone">Zone</option>
                                    <option value="Territory">Territory</option>
                                </select>
                                <span class="help-inline">
                                    *
                                </span>
                            </div>
                        </div>
                        <div class="control-group" id="div_division" style="display: none;">

                        </div>
                        <div class="control-group" id="div_warehouse_id" style="display: none;">
                            <label class="control-label" for="warehouse_id">
                                Select Warehouse
                            </label>
                            <div class="controls">
                                <select id="warehouse_id" name="warehouse_id" class="span5" placeholder="Warehouse">
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
                                <select id="division_id" name="division_id" class="span5" placeholder="division">
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
                                <select id="zone_id" name="zone_id" class="span5" placeholder="Zone" onchange="load_territory_fnc()">
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
                                <select id="territory_id" name="territory_id" class="span5" placeholder="Territory">
                                    <option value="">Select</option>

                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="department">
                                Department
                            </label>
                            <div class="controls controls-row">
                                <select id="department" name="department" class="span5" validate="Require" placeholder="Department">
                                    <option value="">Select</option>
                                    <option value="Admin">Admin</option>
                                    <option value="Warehouse">Warehouse</option>
                                    <option value="Accounts">Accounts</option>
                                    <option value="Marketing">Marketing</option>
                                    <option value="Potato">Potato</option>
                                    <option value="R&D">R&D</option>
                                    <option value="R&D Farm">R&D Farm</option>
                                    <option value="Lanker Char">Lanker Char</option>
                                </select>
                                <span class="help-inline">
                                    *
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <!--            <div class="widget">
                                <div class="widget-body">
                                    <div class="wrapper">
                                        <div class="control-group">
                                            <label class="control-label" for="repuser_pass">
                
                                            </label>
                                            <div class="controls">
                                                <table class="table table-condensed table-striped table-bordered table-hover no-margin" id="TaskTable">
                                                    <thead>
                                                        <tr>
                                                            <th colspan="12">
                                                                <span class="label label label-info" style="cursor: pointer; float: right;" onclick="RowIncrement()"> + Add Educational Information</span>
                                                            </th>
                                                        </tr>
                                                        <tr>
                                                            <th style="width:10%">
                                                                Name of Degree
                                                            </th>
                                                            <th style="width:5%">
                                                                Degree Obtain 
                                                            </th>
                                                            <th style="width:5%">
                                                                Passing Year
                                                            </th>
                                                            <th style="width:5%">
                                                                Action
                                                            </th>
                                                        </tr>
                                                    </thead>
                
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>-->
            </div>
        </div>
    </div>
    <script>    
        var cal = Calendar.setup({
            onSelect: function(cal) { cal.hide() },
            fdow :0,
            minuteStep:1
        });
        cal.manageFields("calcbtn_dob", "dob", "%d-%m-%Y");
        cal.manageFields("calcbtn_date_of_joining", "date_of_joining", "%d-%m-%Y");
        cal.manageFields("calcbtn_increment_date", "increment_date", "%d-%m-%Y");
        
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function  ( e ) {
                    $('#blah').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>