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
                            &nbsp;
                        </span> 
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="payment_date">
                            Payment Date
                        </label>
                        <div class="controls">
                            <div class="input-append">
                                <input disabled="" type="text" name="payment_date" id="payment_date" class="span9" placeholder="Payment Date" value="<?php echo $db->date_formate($editrow['payment_date']) ?>"  />
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
                            <select disabled="" id="zone_id" name="zone_id" class="span5" placeholder="Zone" onchange="load_territory_fnc()" validate="Require">
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
                            <select disabled="" id="distributor_id" name="distributor_id" class="span5" placeholder="Distributor" validate="Require">
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
                        <label class="control-label" for="payment_type">
                            Payment Type
                        </label>
                        <div class="controls">
                            <select disabled="" id="payment_type" name="payment_type" class="span5" placeholder="payment type" validate="Require" onchange="payment_type_fnc()">
                                <option value="">Select</option>
                                <option value="Cash" <?php
                                if ($editrow['payment_type'] == "Cash") {
                                    echo "selected='selected'";
                                }
                                ?> >Cash</option>
                                <option value="Pay Order" <?php
                                        if ($editrow['payment_type'] == "Pay Order") {
                                            echo "selected='selected'";
                                        }
                                ?> >Pay Order</option>
                                <option value="Cheque" <?php
                                        if ($editrow['payment_type'] == "Cheque") {
                                            echo "selected='selected'";
                                        }
                                ?> >Cheque</option>
                                <option value="TT" <?php
                                        if ($editrow['payment_type'] == "TT") {
                                            echo "selected='selected'";
                                        }
                                ?> >TT</option>
                                <option value="DD" <?php
                                        if ($editrow['payment_type'] == "DD") {
                                            echo "selected='selected'";
                                        }
                                ?> >DD</option>
                                <option value="Online Payment" <?php
                                        if ($editrow['payment_type'] == "Online Payment") {
                                            echo "selected='selected'";
                                        }
                                ?> >Online Payment</option>
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
                            <input disabled="" class="span3" type="text" name="amount" id="amount" value="<?php echo $editrow['amount'] ?>" placeholder="Amount" onkeypress="return numbersOnly(event)" validate="Require">
                        </div>
                        <span class="help-inline">
                            *
                        </span>
                    </div>
                    <div class="control-group" id="div_cheque_no" style="display: none;">
                        <label class="control-label" for="owner_name">
                            Payment Number
                        </label>
                        <div class="controls">
                            <input disabled="" class="span5" type="text" name="cheque_no" id="cheque_no" value="<?php echo $editrow['cheque_no'] ?>" placeholder="Payment Number" >
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="bank_id">
                            Select Bank
                        </label>
                        <div class="controls">
                            <select disabled="" id="bank_id" name="bank_id" class="span5" placeholder="Zone" >
                                <option value="">Select</option>
                                <?php
                                $sql_uesr_group = "select bank_id as fieldkey, bank_name as fieldtext from $tbl" . "bank_info WHERE status='Active' AND del_status='0'";
                                echo $db->SelectList($sql_uesr_group, $editrow['bank_id']);
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
        payment_type_fnc();
        setTimeout(function(){distributor_due_balance();},1000);
    });
</script>