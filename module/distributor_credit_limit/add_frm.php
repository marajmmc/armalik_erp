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
                        <label class="control-label" for="zone_id">
                            Zone
                        </label>
                        <div class="controls">
                            <select id="zone_id" name="zone_id" class="span5" placeholder="Zone" onchange="load_territory_fnc()" validate="Require">
                                <option value="">Select</option>
                                <?php
                                $sql_uesr_group = "select zone_id as fieldkey, zone_name as fieldtext from $tbl" . "zone_info WHERE status='Active' AND del_status='0' " . $db->get_zone_access($tbl . "zone_info") . " ";
                                echo $db->SelectList($sql_uesr_group);
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
                            <select id="distributor_id" name="distributor_id" class="span5" placeholder="Customer" validate="Require" onchange="load_credit_due_balance()">
                                <option value="">Select</option>

                            </select>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="distributor_opening_balance">
                            Opening Balance
                        </label>
                        <div class="controls">
                            <input readonly="" class="span3" type="text" name="distributor_opening_balance" id="distributor_opening_balance" placeholder="Opening Balance" onkeypress="return numbersOnly(event)" />
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="all_credit_limit">
                            Credit Limit 
                        </label>
                        <div class="controls">
                            <input readonly="" class="span3" type="text" name="all_credit_limit" id="all_credit_limit" placeholder="Credit Limit" onkeypress="return numbersOnly(event)" />
                            <input readonly="" class="span3" type="hidden" name="current_credit_limit" id="current_credit_limit" placeholder="Current Credit Limit" onkeypress="return numbersOnly(event)" />
                            <!--Due Balance--> 
                            <input readonly="" class="span3" type="hidden" name="due_balance" id="due_balance" placeholder="Due Balance" onkeypress="return numbersOnly(event)" />
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="credit_limit_status">
                            Current Credit status
                        </label>
                        <div class="controls">
                            <input readonly="" class="span3" type="text" name="credit_limit_status" id="credit_limit_status" placeholder="Current Credit status" onkeypress="return numbersOnly(event)" />
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="credit_limit">
                            Increase Credit limit
                        </label>
                        <div class="controls">
                            <input class="span3" type="text" name="credit_limit" id="credit_limit" placeholder="Increase Credit limit"  />
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="total_credit_limit">
                            Total Credit limit
                        </label>
                        <div class="controls">
                            <input readonly="" class="span3" type="text" name="total_credit_limit" id="total_credit_limit" placeholder="Total Credit limit" onkeypress="return numbersOnly(event)" />
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="check_no">
                            Cheque No
                        </label>
                        <div class="controls">
                            <input class="span9" type="text" name="check_no" id="check_no" placeholder="Cheque No" >
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="amount">
                            Amount
                        </label>
                        <div class="controls">
                            <input class="span9" type="text" name="amount" id="amount" placeholder="Amount" onkeypress="return numbersOnly(event)" validate="Require" />
                            <span class="help-inline">
                                *
                            </span>
                        </div>
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
                                echo $db->SelectList($sql_uesr_group);
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
                            </select>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="address">
                            Comment
                        </label>
                        <div class="controls">
                            <textarea class="span9" name="comment" id="comment" placeholder="Comment" ></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        session_load_fnc()
        $("#credit_limit").blur(function(){
            $("#total_credit_limit").val('');
            var all_credit_limit=parseFloat($("#all_credit_limit").val());
            var increse_credit_limit=parseFloat($("#credit_limit").val());
            var totalcredit_limit=(all_credit_limit+increse_credit_limit);
            $("#total_credit_limit").val(totalcredit_limit);
        })
    });
</script>