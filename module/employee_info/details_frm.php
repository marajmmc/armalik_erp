<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$dbzoneacs = new Database();
$tbl = _DB_PREFIX;
$editrow = $db->single_data($tbl . "employee_basic_info", "*", "employee_id", $_POST['rowID']);
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
                        <label class="control-label" for="user_name">
                            Name
                        </label>
                        <div class="controls">
                            <input class="span9" disabled="" type="text" name="employee_name" id="employee_name" value="<?php echo $editrow['employee_name']; ?>" placeholder="Name" validate="Require">
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="user_pass">
                            Father's Name
                        </label>
                        <div class="controls">
                            <input type="text" disabled="" name="father_name" id="father_name" value="<?php echo $editrow['father_name']; ?>" class="span9" placeholder="Father's Name"  title=""/>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="repuser_pass">
                            Mother's Name
                        </label>
                        <div class="controls">
                            <input type="text" disabled="" name="mother_name" id="mother_name" value="<?php echo $editrow['mother_name']; ?>" class="span9" placeholder="Mother's Name"  />
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="repuser_pass">
                            Date of Birth
                        </label>
                        <div class="controls">
                            <div class="input-append">
                                <input type="text" disabled="" name="dob" id="dob" value="<?php echo $db->date_formate($editrow['dob']); ?>" class="span9" placeholder="Date of birth"  />
                                <span class="add-on" id="calcbtn_dob">
                                    <i class="icon-calendar"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="repuser_pass">
                            Gender
                        </label>
                        <div class="controls">
                            <input type="text" disabled="" value="<?php echo $editrow['gender']; ?>" class="span9" />
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="repuser_pass">
                            Marital Status
                        </label>
                        <div class="controls">
                            <input type="text" disabled="" value="<?php echo $editrow['marital_status']; ?>" class="span9" />
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="repuser_pass">
                            Spouse Name
                        </label>
                        <div class="controls">
                            <input type="text" disabled="" name="spouse_name" id="spouse_name" value="<?php echo $editrow['spouse_name']; ?>" class="span9" placeholder="Spouse Name" />
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="repuser_pass">
                            NID Number
                        </label>
                        <div class="controls">
                            <input type="text" disabled="" name="nid_no" id="nid_no" class="span9" placeholder="NID Number"  value="<?php echo $editrow['nid_no']; ?>" />
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="repuser_pass">
                            Designation
                        </label>
                        <div class="controls">
                            <select id="employee_designation" disabled="" name="employee_designation" class="span9" placeholder="Designation">
                                <option value="">Select</option>
                                <?php
                                $sql_uesr_group = "select designation_id as fieldkey, designation_title_en as fieldtext from $tbl" . "employee_designation";
                                echo $db->SelectList($sql_uesr_group, $editrow['employee_designation']);
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="address">
                            Permanent Address
                        </label>
                        <div class="controls">
                            <textarea disabled="" rows="3" id="address" name="address" class="input-block-level" placeholder="Permanent Address" ><?php echo $editrow['present_address']; ?></textarea>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label bottom-margin" for="repuser_pass">
                            Profile Photo
                        </label>
                        <div class="controls">
                            <?php if ($editrow['image_url'] != "") { ?>
                                <img src="../../module/employee_info/employee_photo/<?php echo $editrow['image_url']; ?>" style="width: 105px; height: 107px;" id="blah" />
                            <?php } else { ?>
                                <img src="../../system_images/profile.png" style="width: 105px; height: 107px;" id="blah" />
                            <?php } ?>
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
                            <label class="control-label" for="repuser_pass">
                                Basic Salary
                            </label>
                            <div class="controls">
                                <input type="text" disabled="" name="basic_salary" id="basic_salary" value="<?php echo $editrow['basic_salary']; ?>" class="span9" placeholder="Basic Salary"  />
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="repuser_pass">
                                Other Allowance
                            </label>
                            <div class="controls">
                                <input type="text" disabled="" name="other_allowance" id="other_allowance" value="<?php echo $editrow['other_allowance']; ?>" class="span9" placeholder="Other Allowance"  />
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="repuser_pass">
                                Date of Joining
                            </label>
                            <div class="controls">
                                <div class="input-append">
                                    <input type="text" disabled="" name="date_of_joining" id="date_of_joining" value="<?php echo $db->date_formate($editrow['date_of_joining']); ?>" class="span9" placeholder="Date of Joining"  />
                                    <span class="add-on" id="calcbtn_date_of_joining">
                                        <i class="icon-calendar"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="repuser_pass">
                                Next Increment Date
                            </label>
                            <div class="controls">
                                <div class="input-append">
                                    <input type="text" disabled="" name="increment_date" id="increment_date" value="<?php echo $db->date_formate($editrow['increment_date']); ?>" class="span9" placeholder="Next Increment Date"  />
                                    <span class="add-on" id="calcbtn_increment_date">
                                        <i class="icon-calendar"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="user_group_id">
                                Blood Group
                            </label>
                            <div class="controls controls-row">
                                <input type="text" disabled="" value="<?php echo $editrow['blood_group']; ?>" class="span9" />
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="repuser_pass">
                                Land Line Number
                            </label>
                            <div class="controls">
                                <input type="text" disabled="" name="land_line_number" id="land_line_number" value="<?php echo $editrow['land_line_number']; ?>" class="span9" placeholder="Land Line Number"  />
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="repuser_pass">
                                Mobile Number
                            </label>
                            <div class="controls">
                                <input type="text" disabled="" name="mobile_number" id="mobile_number" value="<?php echo $editrow['mobile_number']; ?>" class="span9" placeholder="Mobile Number"  />
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="repuser_pass">
                                Contact Person
                            </label>
                            <div class="controls">
                                <input type="text" disabled="" name="contact_person" id="contact_person" value="<?php echo $editrow['contact_person']; ?>" class="span9" placeholder="Emergency Contact Person"  />
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="repuser_pass">
                                Contact Number
                            </label>
                            <div class="controls">
                                <input type="text" disabled="" name="contact_number" id="contact_number" value="<?php echo $editrow['contact_number']; ?>" class="span9" placeholder="Emergency Contact Number"  />
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="repuser_pass">
                                Address
                            </label>
                            <div class="controls">
                                <textarea disabled="" rows="3" disabled="" id="address" name="address" class="input-block-level" placeholder="Address" ><?php echo $editrow['address']; ?></textarea>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="nid_no">
                                Employee ID No
                            </label>
                            <div class="controls">
                                <input disabled="" type="text" name="employee_id_no" id="employee_id_no" class="span9" placeholder="Employee ID No"  value="<?php echo $editrow['employee_id_no']; ?>" />
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
                            <label class="control-label" for="user_level">
                                Level
                            </label>
                            <div class="controls controls-row">
                                <select id="user_level" name="user_level" disabled="" class="span5" onchange="load_user_type()" validate="Require" placeholder="User Level">
                                    <option value="">Select</option>
                                    <option value="Marketing" <?php
                            if ($editrow['user_level'] == "Marketing") {
                                echo "selected='selected'";
                            }
                            ?> > Marketing </option>
                                    <option value="Warehouse" <?php
                                            if ($editrow['user_level'] == "Warehouse") {
                                                echo "selected='selected'";
                                            }
                            ?> > Warehouse </option>
                                    <option value="Division" <?php
                                            if ($editrow['user_level'] == "Division") {
                                                echo "selected='selected'";
                                            }
                            ?> > Division </option>
                                    <option value="Zone" <?php
                                            if ($editrow['user_level'] == "Zone") {
                                                echo "selected='selected'";
                                            }
                            ?> > Zone </option>
                                    <option value="Territory" <?php
                                            if ($editrow['user_level'] == "Territory") {
                                                echo "selected='selected'";
                                            }
                            ?> > Territory </option>
                                </select>
                                <span class="help-inline">
                                    *
                                </span>
                            </div>
                        </div>
                        <div class="control-group" id="div_division" style="display: none;">
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
                                            $access = $dbzoneacs->single_data_w($tbl . "zone_user_access", "zone_id", "employee_id='$_POST[rowID]' AND zone_id='$result_array[zone_id]'");
                                            if ($result_array['zone_id'] == $access['zone_id']) {
                                                $acs_check = "checked='checked'";
                                            } else {
                                                $acs_check = "";
                                            }
                                            echo "<tr class='row_hover' $rowcolor>
                                            <td align='left'>
                                                <input disabled='' type='checkbox' $acs_check  name='$result_array[zone_id]' onclick='selectallTask(this)' id='$result_array[zone_id]' value='$result_array[zone_id]'  />
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
                        </div>
                        <div class="control-group" id="div_warehouse_id" style="display: none;">
                            <label class="control-label" for="warehouse_id">
                                Select Warehouse
                            </label>
                            <div class="controls">
                                <select id="warehouse_id" name="warehouse_id" disabled="" class="span5" placeholder="Warehouse">
                                    <option value="">Select</option>
                                    <?php
                                    $sql_uesr_group = "select warehouse_id as fieldkey, warehouse_name as fieldtext from $tbl" . "warehouse_info WHERE status='Active' AND del_status='0'";
                                    echo $db->SelectList($sql_uesr_group, $editrow['warehouse_id']);
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="control-group" id="div_division_id" style="display: none;">
                            <label class="control-label" for="division_id">
                                Division
                            </label>
                            <div class="controls">
                                <select disabled="" id="division_id" name="division_id" class="span5" placeholder="division">
                                    <option value="">Select</option>
                                    <?php
                                    $sql_uesr_group = "select division_id as fieldkey, division_name as fieldtext from $tbl" . "division_info WHERE status='Active' AND del_status='0'";
                                    echo $db->SelectList($sql_uesr_group, $editrow['division_id']);
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="control-group" id="div_zone_id" style="display: none;">
                            <label class="control-label" for="zone_id">
                                Zone
                            </label>
                            <div class="controls">
                                <select id="zone_id" name="zone_id" disabled="" class="span5" placeholder="Zone" onchange="load_territory_fnc()">
                                    <option value="">Select</option>
                                    <?php
                                    $sql_uesr_group = "select zone_id as fieldkey, zone_name as fieldtext from $tbl" . "zone_info WHERE status='Active' AND del_status='0'";
                                    echo $db->SelectList($sql_uesr_group, $editrow['zone_id']);
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="control-group" id="div_territory_id" style="display: none;">
                            <label class="control-label" for="territory_id">
                                Territory
                            </label>
                            <div class="controls">
                                <select id="territory_id" name="territory_id" disabled="" class="span5" placeholder="Territory">
                                    <?php
                                    echo "<option value=''>Select</option>";
                                    echo $sql_uesr_group = "select territory_id as fieldkey, territory_name as fieldtext from $tbl" . "territory_info where status='Active' AND del_status='0' AND zone_id='" . $editrow['zone_id'] . "'";
                                    echo $db->SelectList($sql_uesr_group, $editrow['territory_id']);
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="department">
                                Department
                            </label>
                            <div class="controls controls-row">
                                <select disabled="" id="department" name="department" class="span5" onchange="load_user_type()" validate="Require" placeholder="User Type">
                                    <option value="">Select</option>
                                    <option value="Admin" <?php
                                    if ($editrow['department'] == "Admin") {
                                        echo "selected='selected'";
                                    }
                                    ?>>Admin</option>
                                    <option value="Warehouse" <?php
                                            if ($editrow['department'] == "Warehouse") {
                                                echo "selected='selected'";
                                            }
                                    ?>>Warehouse</option>
                                    <option value="Accounts" <?php
                                            if ($editrow['department'] == "Accounts") {
                                                echo "selected='selected'";
                                            }
                                    ?>>Accounts</option>
                                    <option value="Marketing" <?php
                                            if ($editrow['department'] == "Marketing") {
                                                echo "selected='selected'";
                                            }
                                    ?>>Marketing</option>
                                    <option value="Potato" <?php
                                            if ($editrow['department'] == "Potato") {
                                                echo "selected='selected'";
                                            }
                                    ?>>Potato</option>
                                    <option value="R&D" <?php
                                            if ($editrow['department'] == "R&D") {
                                                echo "selected='selected'";
                                            }
                                    ?>>R&D</option>
                                    <option value="R&D Farm" <?php
                                            if ($editrow['department'] == "R&D Farm") {
                                                echo "selected='selected'";
                                            }
                                    ?>>R&D Farm</option>
                                    <option value="Lanker Char" <?php
                                            if ($editrow['department'] == "Lanker Char") {
                                                echo "selected='selected'";
                                            }
                                    ?>>Lanker Char</option>
                                </select>
                                <span class="help-inline">
                                    *
                                </span>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="status">
                                Status
                            </label>
                            <div class="controls controls-row">
                                <select disabled="" id="status" name="status" class="span5" validate="Require" placeholder="User Type">
                                    <option value="">Select</option>
                                    <option value="Active" <?php
                                            if ($editrow['status'] == "Active") {
                                                echo "selected='selected'";
                                            }
                                    ?>>Active</option>
                                    <option value="In-Active" <?php
                                            if ($editrow['status'] == "In-Active") {
                                                echo "selected='selected'";
                                            }
                                    ?>>In-Active</option>

                                </select>
                                <span class="help-inline">
                                    *
                                </span>
                            </div>
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
                                                            <span class="label label label-info">Educational Information</span>
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
                                                    </tr>
                                                </thead>
                                                <tbody>
            <?php
//                                        $sql = "SELECT
//                                                    $tbl" . "employee_education_info.row_id,
//                                                    $tbl" . "employee_education_info.employee_id,
//                                                    $tbl" . "employee_education_info.degree_name,
//                                                    $tbl" . "employee_education_info.degree_obtain,
//                                                    $tbl" . "employee_education_info.passing_year
//                                                FROM $tbl" . "employee_education_info
//                                                WHERE
//                                                    $tbl" . "employee_education_info.employee_id='$editrow[employee_id]' AND
//                                                    $tbl" . "employee_education_info.`status`='Active' AND
//                                                    $tbl" . "employee_education_info.del_status='0'
//                                        ";
//                                        if ($db->open()) {
//                                            $i = 0;
//                                            $result = $db->query($sql);
//                                            while ($row = $db->fetchAssoc($result)) {
//                                                if ($i % 2 == 0) {
//                                                    $rowcolor = "gradeC";
//                                                } else {
//                                                    $rowcolor = "gradeA success";
//                                                }
//
//                                                echo "<tr class='$rowcolor'>
//                                                    <td>
//                                                    $row[degree_name]
//                                                    </td>
//                                                    <td>
//                                                    $row[degree_obtain]
//                                                    </td>
//                                                    <td>
//                                                    $row[passing_year]
//                                                    </td>                                                    
//                                                </tr>";
//                                            }
//                                        }
            ?>
                                                </tbody>
            
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
    $(document).ready(function(){
        load_user_type();
    });
</script>
