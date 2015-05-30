<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$tbl = _DB_PREFIX;
$editrow = $db->single_data($tbl . "distributor_credit_limit", "*", "credit_limit_id", $_POST['rowID']);
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
                            <select id="zone_id" name="zone_id" class="span5" placeholder="Zone" onchange="load_territory_fnc()" validate="Require">
                                <option value="">Select</option>
                                <?php
                                $sql_uesr_group = "select zone_id as fieldkey, zone_name as fieldtext from $tbl" . "zone_info WHERE status='Active' AND del_status='0' ".$db->get_zone_access($tbl. "zone_info")."  ";
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
                            <select id="territory_id" name="territory_id" class="span5" placeholder="Territory" onchange="load_distributor_fnc()" validate="Require">
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
                            Customer
                        </label>
                        <div class="controls">
                            <select id="distributor_id" name="distributor_id" class="span5" placeholder="Customer" validate="Require">
                                <option value="">Select</option>
                                <?php
                                $sql_uesr_group = "select distributor_id as fieldkey, CONCAT_WS(' - ', $tbl" . "distributor_info.customer_code, $tbl" . "distributor_info.distributor_name) as fieldtext from $tbl" . "distributor_info where status='Active' AND del_status='0' AND territory_id='" . $editrow['territory_id'] . "'";
                                echo $db->SelectList($sql_uesr_group, $editrow['distributor_id']);
                                ?>
                            </select>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="credit_limit">
                            Credit Limit
                        </label>
                        <div class="controls">
                            <input class="span3" type="text" name="credit_limit" id="credit_limit" value="<?php echo $editrow['credit_limit'] ?>" placeholder="Credit Limit" onkeypress="return numbersOnly(event)" />
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="check_no">
                            Cheque No
                        </label>
                        <div class="controls">
                            <input class="span9" type="text" name="check_no" id="check_no" value="<?php echo $editrow['check_no'] ?>" placeholder="Cheque No" >
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="amount">
                            Amount
                        </label>
                        <div class="controls">
                            <input class="span9" type="text" name="amount" id="amount" value="<?php echo $editrow['amount'] ?>" placeholder="Amount" onkeypress="return numbersOnly(event)" validate="Require" />
                        </div>
                        <span class="help-inline">
                            *
                        </span>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="bank_id">
                            Bank Name
                        </label>
                        <div class="controls">
                            <select id="bank_id" name="bank_id" class="span5" placeholder="Bank Name" onchange="load_branch_name()">
                                <option value="">Select</option>
                                <?php
                                $sql_uesr_group = "select bank_id as fieldkey, bank_name as fieldtext from $tbl" . "bank_info where status='Active' AND del_status='0'";
                                echo $db->SelectList($sql_uesr_group, $editrow['bank_id']);
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="bank_id">
                            Branch Name
                        </label>
                        <div class="controls">
                            <select id="branch_id" name="branch_id" class="span5" placeholder="Branch Name">
                                <option value="">Select</option>
                                <?php
                                $sql_uesr_group = "select branch_id as fieldkey, branch_name as fieldtext from $tbl" . "bank_branch_info where status='Active' AND del_status='0' AND bank_id='" . $editrow['bank_id'] . "'";
                                echo $db->SelectList($sql_uesr_group, $editrow['branch_id']);
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="address">
                            Comment
                        </label>
                        <div class="controls">
                            <textarea class="span9" name="comment" id="comment" placeholder="Comment" ><?php echo $editrow['comment'] ?></textarea>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="blood_group">
                            Status
                        </label>
                        <div class="controls controls-row">
                            <select id="status" name="status" class="span3" placeholder="Group Name">
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