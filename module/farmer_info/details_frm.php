<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$tbl = _DB_PREFIX;
$editrow = $db->single_data($tbl . "farmer_info", "*", "farmer_id", $_POST['rowID']);
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
                        <label class="control-label" for="zone_id">
                            Division
                        </label>
                        <div class="controls">
                            <select disabled id="division_id" name="division_id" class="span5" placeholder="Division" onchange="load_zone()" validate="Require">
                                <option value="">Select</option>
                                <?php
                                $sql_uesr_group = "select division_id as fieldkey, division_name as fieldtext from $tbl" . "division_info WHERE status='Active' AND del_status='0'";
                                echo $db->SelectList($sql_uesr_group, $editrow['division_id']);
                                ?>
                            </select>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="zone_id">
                            Zone
                        </label>
                        <div class="controls">
                            <select disabled id="zone_id" name="zone_id" class="span5" placeholder="Zone" onchange="load_territory_fnc(); load_district()" validate="Require">
                                <option value="">Select</option>
                                <?php
                                echo $sql_uesr_group = "SELECT
                                                            $tbl" . "zone_info.zone_id as fieldkey,
                                                            $tbl" . "zone_info.zone_name as fieldtext
                                                        FROM
                                                            $tbl" . "zone_user_access
                                                            LEFT JOIN $tbl" . "zone_info ON $tbl" . "zone_info.zone_id = $tbl" . "zone_user_access.zone_id
                                                        WHERE
                                                            $tbl" . "zone_user_access.del_status='0' AND $tbl" . "zone_user_access.status='Active' AND division_id='" . $editrow['division_id'] . "'
                                ";
                                echo $db->SelectList($sql_uesr_group, $editrow['zone_id']);
                                ?>
                            </select>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="territory_id">
                            Territory
                        </label>
                        <div class="controls">
                            <select disabled id="territory_id" name="territory_id" class="span5" placeholder="Territory" validate="Require">
                                <?php
                                $sql_uesr_group = "select territory_id as fieldkey, territory_name as fieldtext from $tbl" . "territory_info where status='Active' AND del_status='0' AND territory_id='".$editrow['territory_id']."'  AND zone_id='".$editrow['zone_id']."' ORDER BY territory_name";
                                echo $db->SelectList($sql_uesr_group, $editrow['territory_id']);
                                ?>
                            </select>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="distributor_id">
                            District
                        </label>
                        <div class="controls">
                            <select disabled id="district_id" name="district_id" class="span5" placeholder="Customer" validate="Require" onchange="load_upazilla_fnc()">
                                <?php
                                echo "<option value=''>Select</option>";
                                $sql_uesr_group = "SELECT
                                                        $tbl" . "zilla.zillaid as fieldkey,
                                                        $tbl" . "zilla.zillanameeng as fieldtext
                                                    FROM
                                                        $tbl" . "zone_assign_district
                                                        LEFT JOIN $tbl" . "zilla ON $tbl" . "zilla.zillaid = $tbl" . "zone_assign_district.zilla_id
                                                    WHERE
                                                        $tbl" . "zone_assign_district.del_status=0
                                                        AND $tbl" . "zilla.visible=0
                                                        AND $tbl" . "zone_assign_district.status='Active'
                                                        AND $tbl" . "zone_assign_district.zone_id='".$editrow['zone_id']."'
                                                    ";
                                echo $db->SelectList($sql_uesr_group, $editrow['district_id']);
                                ?>
                            </select>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="distributor_id">
                            Upazilla
                        </label>
                        <div class="controls">
                            <select disabled="" id="upazilla_id" name="upazilla_id" class="span5" placeholder="" validate="Require">
                                <?php
                                echo "<option value=''>Select</option>";
                                echo $sql_uesr_group = "select upazilaid as fieldkey, upazilanameeng as fieldtext from $tbl" . "upazilla where   zillaid='" . $editrow['district_id'] . "' ORDER BY upazilanameeng";
                                echo $db->SelectList($sql_uesr_group, $editrow['upazilla_id']);
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label" for="farmer_name">
                            Farmer Name
                        </label>
                        <div class="controls">
                            <input disabled="" class="span9" type="text" name="farmer_name" id="farmer_name" value="<?php echo $editrow['farmer_name'] ?>" placeholder="Farmer Name" >
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="father_name">
                            Father Name
                        </label>
                        <div class="controls">
                            <input disabled="" class="span9" type="text" name="father_name" id="father_name" value="<?php echo $editrow['father_name'] ?>" placeholder="Father Name" >
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="address">
                            Address
                        </label>
                        <div class="controls">
                            <textarea disabled="" class="span9" name="address" id="address" placeholder="Address" > <?php echo $editrow['address'] ?></textarea>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="contact_no">
                            Contact No
                        </label>
                        <div class="controls">
                            <input disabled="" class="span3" type="text" name="contact_no" id="contact_no" value="<?php echo $editrow['contact_no'] ?>" placeholder="Contact No" validate="Mobile" maxlength="11" onkeypress="return numbersOnly(event)" >
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="blood_group">
                            Status
                        </label>
                        <div class="controls controls-row">
                            <select disabled="" id="status" name="status" class="span3" placeholder="Group Name">
                                <option value="">Select</option>
                                <option value="Active" <?php
                                if ($editrow['status'] == "Active") {
                                    echo "selected='selected'";
                                }
                                ?> >Active</option>
                                <option value="In-Active" <?php
                                        if ($editrow['status'] == "In-Active") {
                                            echo "selected='selected'";
                                        }
                                ?> >In-Active</option>

                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>