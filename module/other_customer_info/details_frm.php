<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$tbl = _DB_PREFIX;
$editrow = $db->single_data($tbl . "distributor_info", "*", "distributor_id", $_POST['rowID']);
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
                            <select id="zone_id" name="zone_id" disabled="" class="span5" placeholder="Zone" onchange="load_territory_fnc()">
                                <option value="">Select</option>
                                <?php
                                $sql_uesr_group = "select zone_id as fieldkey, zone_name as fieldtext from $tbl" . "zone_info WHERE status='Active' AND del_status='0' ".$db->get_zone_access($tbl. "zone_info")." ";
                                echo $db->SelectList($sql_uesr_group, $editrow['zone_id']);
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="territory_id">
                            Territory
                        </label>
                        <div class="controls">
                            <select id="territory_id" name="territory_id" disabled="" class="span5" placeholder="Territory">
                                <?php
                                echo "<option value=''>Select</option>";
                                $sql_uesr_group = "select territory_id as fieldkey, territory_name as fieldtext from $tbl" . "territory_info where status='Active' AND del_status='0'";
                                echo $db->SelectList($sql_uesr_group, $editrow['territory_id']);
                                ?>                                
                            </select>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="">
                            District
                        </label>
                        <div class="controls">
                            <select disabled id="zilla_id" name="zilla_id" class="span5" placeholder="" validate="Require">
                                <!--                                <option value="">Select</option>-->
                                <?php
                                $sql_uesr_group = "select zillaid as fieldkey, zillanameeng as fieldtext from $tbl" . "zilla where visible='0' AND zillaid='".$editrow['zilla_id']."'";
                                echo $db->SelectList($sql_uesr_group, $editrow['zilla_id']);
                                ?>
                            </select>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="distributor_name">
                            Customer
                        </label>
                        <div class="controls">
                            <input class="span9" type="text" name="distributor_name" id="distributor_name" disabled="" value="<?php echo $editrow['distributor_name'] ?>" placeholder="Customer Name" validate="Require">
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="customer_code">
                            Customer ID
                        </label>
                        <div class="controls">
                            <input class="span3" type="text" name="customer_code" id="customer_code" disabled="" value="<?php echo $editrow['customer_code'] ?>" placeholder="Customer ID" >
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="owner_name">
                            Owner Name
                        </label>
                        <div class="controls">
                            <input class="span9" type="text" name="owner_name" id="owner_name" disabled="" value="<?php echo $editrow['owner_name'] ?>" placeholder="Owner Name" >
                        </div>
                    </div>
                    <!--                    <div class="control-group">
                                            <label class="control-label" for="market_name">
                                                Market Name
                                            </label>
                                            <div class="controls">
                                                <input class="span9" type="text" name="market_name" id="market_name" disabled="" value="<?php // echo $editrow['market_name'] ?>" placeholder="Market Name" >
                                            </div>
                                        </div>-->
                    <div class="control-group">
                        <label class="control-label" for="address">
                            Address
                        </label>
                        <div class="controls">
                            <textarea class="span9" name="address" id="address" disabled="" placeholder="Address" ><?php echo $editrow['address'] ?></textarea>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="phone">
                            Phone
                        </label>
                        <div class="controls">
                            <input class="span3" type="text" name="phone" id="phone" disabled="" value="<?php echo $editrow['phone'] ?>" placeholder="Phone" validate="Mobile" maxlength="11" onkeypress="return numbersOnly(event)" >
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="email">
                            Email
                        </label>
                        <div class="controls">
                            <!--                            <div class="input-append">
                                                            <span class="add-on"> @</span>-->
                            <input type="text" name="email" id="email" disabled="" value="<?php echo $editrow['email'] ?>" class="span9" placeholder="Email" validate="Email" />
                            <!--</div>-->
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="due_balance">
                            Opening Balance
                        </label>
                        <div class="controls">
                            <input type="text" name="due_balance" id="due_balance" disabled="" value="<?php echo $editrow['due_balance'] ?>" class="span3" placeholder="Opening Balance" onkeypress="return numbersOnly(event)" />
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="remark">
                            Remark's
                        </label>
                        <div class="controls">
                            <textarea disabled="" class="span9" type="text" name="remark" id="remark" placeholder="Remark's"  rows="8" ><?php echo $editrow['remark'];?></textarea>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="blood_group">
                            Status
                        </label>
                        <div class="controls controls-row">
                            <select id="status" name="status" disabled="" class="span5" placeholder="Group Name">
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
                    <div class="control-group">
                        <label class="control-label" for="due_balance">
                            Agreement
                        </label>
                        <div class="controls">
                            <select disabled="" name="agreement_status" id="agreement_status" class="span5" validate="Require">
                                <option value="">Select</option>
                                <option value="Done" <?php
                                        if ($editrow['agreement_status'] == "Done") {
                                            echo "selected='selected'";
                                        }
                                ?>>Done</option>
                                <option value="Not Done" <?php
                                        if ($editrow['agreement_status'] == "Not Done") {
                                            echo "selected='selected'";
                                        }
                                ?>>Not Done</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>