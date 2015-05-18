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
                            <select id="zone_id" name="zone_id" class="span5" placeholder="Zone" onchange="load_territory_fnc()">
                                <option value="">Select</option>
                                <?php
                                echo $sql_uesr_group = "select zone_id as fieldkey, zone_name as fieldtext from $tbl" . "zone_info WHERE status='Active' AND del_status='0' " . $db->get_zone_access($tbl . "zone_info") . " ";
                                echo $db->SelectList($sql_uesr_group);
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="control-group">
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
                        <label class="control-label" for="distributor_name">
                            Distributor
                        </label>
                        <div class="controls">
                            <input class="span9" type="text" name="distributor_name" id="distributor_name" placeholder="Distributor Name" validate="Require">
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
                            <input class="span3" type="text" name="customer_code" id="customer_code" placeholder="Customer ID" >
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="owner_name">
                            Owner Name
                        </label>
                        <div class="controls">
                            <input class="span9" type="text" name="owner_name" id="owner_name" placeholder="Owner Name" >
                        </div>
                    </div>
                    <!--                    <div class="control-group">
                                            <label class="control-label" for="market_name">
                                                Market Name
                                            </label>
                                            <div class="controls">
                                                <input class="span9" type="text" name="market_name" id="market_name" placeholder="Market Name" >
                                            </div>
                                        </div>-->
                    <div class="control-group">
                        <label class="control-label" for="address">
                            Address
                        </label>
                        <div class="controls">
                            <textarea class="span9" name="address" id="address" placeholder="Address" ></textarea>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="phone">
                            Phone
                        </label>
                        <div class="controls">
                            <input class="span3" type="text" name="phone" id="phone" placeholder="Phone" validate="Mobile" maxlength="11" onkeypress="return numbersOnly(event)" >
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="email">
                            Email
                        </label>
                        <div class="controls">
                            <div class="input-append">
                                <span class="add-on"> @</span>
                                <input type="text" name="email" id="email" class="span12" placeholder="Email" validate="Email" />
                            </div>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="due_balance">
                            Opening Balance
                        </label>
                        <div class="controls">
                            <input type="text" name="due_balance" id="due_balance" class="span3" placeholder="Opening Balance" />
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="remark">
                            Remark's
                        </label>
                        <div class="controls">
                            <textarea class="span9" type="text" name="remark" id="remark" placeholder="Remark's" rows="8" ></textarea>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="due_balance">
                            Agreement
                        </label>
                        <div class="controls">
                            <select name="agreement_status" id="agreement_status" class="span5" validate="Require">
                                <option value="">Select</option>
                                <option value="Done">Done</option>
                                <option value="Not Done">Not Done</option>
                            </select>
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
    });
</script>