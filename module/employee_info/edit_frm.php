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
                        <label class="control-label" for="employee_name">
                            Name
                        </label>
                        <div class="controls">
                            <input class="span9" type="text" name="employee_name" id="employee_name" value="<?php echo $editrow['employee_name']; ?>" placeholder="Name" validate="Require">
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
                            <input type="text" name="father_name" id="father_name" value="<?php echo $editrow['father_name']; ?>" class="span9" placeholder="Father's Name"  title=""/>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="mother_name">
                            Mother's Name
                        </label>
                        <div class="controls">
                            <input type="text" name="mother_name" id="mother_name" value="<?php echo $editrow['mother_name']; ?>" class="span9" placeholder="Mother's Name"  />
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="dob">
                            Date of Birth
                        </label>
                        <div class="controls">
                            <div class="input-append">
                                <input type="text" name="dob" id="dob" value="<?php echo $db->date_formate($editrow['dob']); ?>" class="span9" placeholder="Date of birth"  />
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
                                <input type="radio" name="gender" id="gender" value="Male" <?php
if ($editrow['gender'] == "Male") {
    echo "checked='checked'";
}
?> />
                                Male
                            </label>
                            <label class="radio inline">
                                <input type="radio" name="gender" id="gender" value="Female" <?php
                                       if ($editrow['gender'] == "Female") {
                                           echo "checked='checked'";
                                       }
?> />
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
                                <input type="radio" name="marital_status" id="marital_status" value="Married" <?php
                                       if ($editrow['marital_status'] == "Married") {
                                           echo "checked='checked'";
                                       }
?> />
                                Married
                            </label>
                            <label class="radio inline">
                                <input type="radio" name="marital_status" id="marital_status" value="Un-Married" <?php
                                       if ($editrow['marital_status'] == "Un-Married") {
                                           echo "checked='checked'";
                                       }
?> />
                                Un-Married
                            </label>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="spouse_name">
                            Spouse Name
                        </label>
                        <div class="controls">
                            <input type="text" name="spouse_name" id="spouse_name" value="<?php echo $editrow['spouse_name']; ?>" class="span9" placeholder="Spouse Name" />
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="nid_no">
                            NID Number
                        </label>
                        <div class="controls">
                            <input type="text" name="nid_no" id="nid_no" class="span9" placeholder="NID Number"  value="<?php echo $editrow['nid_no']; ?>" />
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
                                echo $db->SelectList($sql_uesr_group, $editrow['employee_designation']);
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="address">
                            Present Address
                        </label>
                        <div class="controls">
                            <textarea rows="3" id="present_address" name="present_address" class="input-block-level" placeholder="Present Address" ><?php echo $editrow['present_address']; ?></textarea>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label bottom-margin" for="image_url">
                            Profile Photo
                        </label>
                        <div class="controls">
                            <?php if ($editrow['image_url'] != "") { ?>
                                <img src="../../module/employee_info/employee_photo/<?php echo $editrow['image_url']; ?>" style="width: 77px; height: 77px;" id="blah" />
                            <?php } else { ?>
                                <img src="../../system_images/profile.png" style="width: 77px; height: 77px;" id="blah" />
                            <?php } ?>
                            <input type="file" name="image_url" id="image_url" class="span9" placeholder="Date of Joining" onchange="readURL(this)" />
                            <input type="hidden" name="image_url_tmp" id="image_url_tmp" class="span9" value="<?php echo $editrow['image_url'] ?>" />
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
                                <input type="text" name="basic_salary" id="basic_salary" value="<?php echo $editrow['basic_salary']; ?>" class="span9" placeholder="Basic Salary"  onkeypress="return numbersOnly(event)"  />
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="other_allowance">
                                Other Allowance
                            </label>
                            <div class="controls">
                                <input type="text" name="other_allowance" id="other_allowance" value="<?php echo $editrow['other_allowance']; ?>" class="span9" placeholder="Other Allowance"  onkeypress="return numbersOnly(event)"  />
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="date_of_joining">
                                Date of Joining
                            </label>
                            <div class="controls">
                                <div class="input-append">
                                    <input type="text" name="date_of_joining" id="date_of_joining" value="<?php echo $db->date_formate($editrow['date_of_joining']); ?>" class="span9" placeholder="Date of Joining"  />
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
                                    <input type="text" name="increment_date" id="increment_date" value="<?php echo $db->date_formate($editrow['increment_date']); ?>" class="span9" placeholder="Next Increment Date"  />
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
                                    <option value="A+" <?php
                            if ($editrow['blood_group'] == "A+") {
                                echo "selected='selected'";
                            }
                            ?> >A+</option>
                                    <option value="A-" <?php
                                            if ($editrow['blood_group'] == "A-") {
                                                echo "selected='selected'";
                                            }
                            ?> >A-</option>
                                    <option value="AB+" <?php
                                            if ($editrow['blood_group'] == "AB+") {
                                                echo "selected='selected'";
                                            }
                            ?> >AB+</option>
                                    <option value="AB-" <?php
                                            if ($editrow['blood_group'] == "AB-") {
                                                echo "selected='selected'";
                                            }
                            ?> >AB-</option>
                                    <option value="B+" <?php
                                            if ($editrow['blood_group'] == "B+") {
                                                echo "selected='selected'";
                                            }
                            ?> >B+</option>
                                    <option value="B-" <?php
                                            if ($editrow['blood_group'] == "B-") {
                                                echo "selected='selected'";
                                            }
                            ?> >B-</option>
                                    <option value="O+" <?php
                                            if ($editrow['blood_group'] == "O+") {
                                                echo "selected='selected'";
                                            }
                            ?> >O+</option>
                                    <option value="O-" <?php
                                            if ($editrow['blood_group'] == "O-") {
                                                echo "selected='selected'";
                                            }
                            ?> >O-</option>  
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="land_line_number">
                                Land Line Number
                            </label>
                            <div class="controls">
                                <input type="text" name="land_line_number" id="land_line_number" value="<?php echo $editrow['land_line_number']; ?>" class="span9" placeholder="Land Line Number"  />
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="mobile_number">
                                Mobile Number
                            </label>
                            <div class="controls">
                                <input type="text" name="mobile_number" id="mobile_number" value="<?php echo $editrow['mobile_number']; ?>" class="span9" placeholder="Mobile Number"  maxlength="11" validate="Mobile" />
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="contact_person">
                                Contact Person
                            </label>
                            <div class="controls">
                                <input type="text" name="contact_person" id="contact_person" value="<?php echo $editrow['contact_person']; ?>" class="span9" placeholder="Emergency Contact Person"  />
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="contact_number">
                                Contact Number
                            </label>
                            <div class="controls">
                                <input type="text" name="contact_number" id="contact_number" value="<?php echo $editrow['contact_number']; ?>" class="span9" placeholder="Emergency Contact Number"  maxlength="11" validate="Mobile" />
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="address">
                                Permanent Address
                            </label>
                            <div class="controls">
                                <textarea rows="3" id="address" name="address" class="input-block-level" placeholder="Permanent Address" ><?php echo $editrow['address']; ?></textarea>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="nid_no">
                                Employee ID No
                            </label>
                            <div class="controls">
                                <input type="text" name="employee_id_no" id="employee_id_no" class="span9" placeholder="Employee ID No"  value="<?php echo $editrow['employee_id_no']; ?>" />
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
                                <select id="user_level" name="user_level" class="span5" onchange="load_user_type(); zone_access_fnc()" validate="Require" placeholder="User Level">
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
                                                <input type='checkbox' $acs_check  name='$result_array[zone_id]' onclick='selectallTask(this)' id='$result_array[zone_id]' value='$result_array[zone_id]'  />
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
                        <div class="control-group" id="div_division_id" style="display: none;">
                            <label class="control-label" for="division_id">
                                Division
                            </label>
                            <div class="controls">
                                <select id="division_id" name="division_id" class="span5" placeholder="division">
                                    <option value="">Select</option>
                                    <?php
                                    $sql_uesr_group = "select division_id as fieldkey, division_name as fieldtext from $tbl" . "division_info WHERE status='Active' AND del_status='0'";
                                    echo $db->SelectList($sql_uesr_group, $editrow['division_id']);
                                    ?>
                                </select>
                            </div>
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
                                    echo $db->SelectList($sql_uesr_group, $editrow['warehouse_id']);
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
                                <select id="territory_id" name="territory_id" class="span5" placeholder="Territory">
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
                                <select id="department" name="department" class="span5" validate="Require" placeholder="User Type">
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
                                <select id="status" name="status" class="span5" validate="Require" placeholder="User Type">
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
//                                                    <input type='text' id='degree_name_$i' name='degree_name[]' value='$row[degree_name]' class='span12' placeholder='Degree Name'/>
//                                                    <input type='hidden' id='row_id$i' name='row_id[]' value='$row[employee_id]' class='span12' />
//                                                    </td>
//                                                    <td>
//                                                    <input type='text' id='degree_obtain_$i' name='degree_obtain[]' value='$row[degree_obtain]' class='span12' placeholder='Degree Obtain'/>
//                                                    </td>
//                                                    <td>
//                                                    <input type='text' id='passing_year_$i' name='passing_year[]' value='$row[passing_year]' class='span12' placeholder='Passing Year'/>
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