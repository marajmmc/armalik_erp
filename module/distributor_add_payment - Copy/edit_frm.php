<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$tbl = _DB_PREFIX;
$editrow = $db->single_data($tbl . "distributor_add_payment", "*", "payment_id", $_POST['rowID']);
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
                    <div class="controls controls-row">
                        <span class="label label label-important" style="cursor: pointer; float: right" id="lbl_distributor_due_balance"> 

                        </span> 
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="payment_date">
                            Payment Date
                        </label>
                        <div class="controls">
                            <div class="input-append">
                                <input type="text" name="payment_date" id="payment_date" class="span9" placeholder="Date of invoice" value="<?php echo $db->date_formate($db->ToDayDate()) ?>"  />
                                <span class="add-on" id="calcbtn_payment_date">
                                    <i class="icon-calendar"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="zone_id">
                            Zone
                        </label>
                        <div class="controls">
                            <select id="zone_id" name="zone_id" class="span5" placeholder="Zone" onchange="load_territory_fnc()" validate="Require">
                                <option value="">Select</option>
                                <?php
                                $sql_uesr_group = "select zone_id as fieldkey, zone_name as fieldtext from $tbl" . "zone_info WHERE status='Active' AND del_status='0' ".$db->get_zone_access()."  ";
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
                            Distributor
                        </label>
                        <div class="controls">
                            <select id="distributor_id" name="distributor_id" class="span5" placeholder="Distributor" validate="Require">
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
                        <label class="control-label" for="payment_type">
                            Payment Type
                        </label>
                        <div class="controls">
                            <select id="payment_type" name="payment_type" class="span5" placeholder="payment type" validate="Require" onchange="payment_type_fnc()">
                                <option value="">Select</option>
                                <option value="Cash">Cash</option>
                                <option value="Pay Order">Pay Order</option>
                                <option value="Cheque">Cheque</option>
                            </select>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="dealer_name">
                            Amount
                        </label>
                        <div class="controls">
                            <input class="span3" type="text" name="amount" id="amount" placeholder="Amount" onkeypress="return numbersOnly(event)" validate="Require">
                        </div>
                        <span class="help-inline">
                            *
                        </span>
                    </div>
                    <div class="control-group" id="div_cheque_no" style="display: none;">
                        <label class="control-label" for="owner_name">
                            Cheque/Pay Order No
                        </label>
                        <div class="controls">
                            <input class="span5" type="text" name="cheque_no" id="cheque_no" placeholder="Cheque/Pay Order No" >
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="bank_id">
                            Select Bank
                        </label>
                        <div class="controls">
                            <select id="bank_id" name="bank_id" class="span5" placeholder="Zone" >
                                <option value="">Select</option>
                                <?php
                                $sql_uesr_group = "select bank_id as fieldkey, bank_name as fieldtext from $tbl" . "bank_info WHERE status='Active' AND del_status='0'";
                                echo $db->SelectList($sql_uesr_group);
                                ?>
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
        session_load_fnc();
        
        setTimeout(function(){distributor_due_balance();},1000);
    });
    
    var cal = Calendar.setup({
        onSelect: function(cal) { cal.hide() },
        fdow :0,
        minuteStep:1
    });
    cal.manageFields("calcbtn_payment_date", "payment_date", "%d-%m-%Y");
</script>