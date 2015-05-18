<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$tbl = _DB_PREFIX;
$editrow = $db->single_data($tbl . "farmer_info_old", "*", "farmer_id", $_POST['rowID']);
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
                            Zone
                        </label>
                        <div class="controls">
                            <select disabled="" id="zone_id" name="zone_id" class="span5" placeholder="Zone" onchange="load_territory_fnc()" validate="Require">
                                <option value="">Select</option>
                                <?php
                                $sql_uesr_group = "select zone_id as fieldkey, zone_name as fieldtext from $tbl" . "zone_info WHERE status='Active' AND del_status='0' " . $db->get_zone_access($tbl . "zone_info") . " ";
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
                            <select disabled="" id="territory_id" name="territory_id" class="span5" placeholder="Territory" onchange="load_distributor_fnc()" validate="Require">
                                <option value="">Select</option>
                                <?php
                                $sql_uesr_group = "select territory_id as fieldkey, territory_name as fieldtext from $tbl" . "territory_info where status='Active' AND del_status='0' AND zone_id='" . $editrow['zone_id'] . "' ";
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
                            Distributor
                        </label>
                        <div class="controls">
                            <select disabled="" id="distributor_id" name="distributor_id" class="span5" placeholder="Distributor" validate="Require" onchange="load_dealer_fnc()">
                                <option value="">Select</option>
                                <?php
                                $sql_uesr_group = "select distributor_id as fieldkey, CONCAT_WS(' - ', customer_code, distributor_name) as fieldtext from $tbl" . "distributor_info where status='Active' AND del_status='0' AND territory_id='" . $editrow['territory_id'] . "'";
                                echo $db->SelectList($sql_uesr_group, $editrow['distributor_id']);
                                ?>
                            </select>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="distributor_id">
                            Dealer
                        </label>
                        <div class="controls">
                            <select disabled="" id="dealer_id" name="dealer_id" class="span5" placeholder="Distributor" validate="Require">
                                <option value="">Select</option>
                                <?php
                                $sql_uesr_group = "select dealer_id as fieldkey, dealer_name as fieldtext from $tbl" . "dealer_info where status='Active' AND del_status='0' AND distributor_id='" . $editrow['distributor_id'] . "' order by dealer_name";
                                echo $db->SelectList($sql_uesr_group, $editrow['dealer_id']);
                                ?>
                            </select>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="zone_id">
                            Division
                        </label>
                        <div class="controls">
                            <select disabled="" id="division_id" name="division_id" class="span5" placeholder="Zone" onchange="load_zilla_fnc()" validate="Require">
                                <option value="">Select</option>
                                <?php
                                echo $sql_uesr_group = "select divid as fieldkey, divnameeng as fieldtext from $tbl" . "division ORDER BY divnameeng";
                                echo $db->SelectList($sql_uesr_group, $editrow['division_id']);
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
                            <select disabled="" id="district_id" name="district_id" class="span5" placeholder="Distributor" validate="Require" onchange="load_upazilla_fnc()">
                                <?php
                                echo "<option value=''>Select</option>";
                                echo $sql_uesr_group = "select zillaid as fieldkey, zillanameeng as fieldtext from $tbl" . "zilla where divid='" . $editrow['division_id'] . "' ORDER BY zillanameeng";
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
                            <select disabled="" id="upazilla_id" name="upazilla_id" class="span5" placeholder="Distributor" validate="Require">
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
                            Mobile No
                        </label>
                        <div class="controls">
                            <input disabled="" class="span3" type="text" name="contact_no" id="contact_no" value="<?php echo $editrow['contact_no'] ?>" placeholder="Mobile No" validate="Mobile" maxlength="11" onkeypress="return numbersOnly(event)" >
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