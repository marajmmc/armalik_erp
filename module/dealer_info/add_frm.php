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
                                echo $sql_uesr_group = "select zone_id as fieldkey, zone_name as fieldtext from $tbl" . "zone_info WHERE status='Active' AND del_status='0' ".$db->get_zone_access($tbl. "zone_info")." ";
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
                            Distributor
                        </label>
                        <div class="controls">
                            <select id="distributor_id" name="distributor_id" class="span5" placeholder="Distributor" validate="Require">
                                <option value="">Select</option>

                            </select>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="dealer_name">
                            Dealer Name
                        </label>
                        <div class="controls">
                            <input class="span3" type="text" name="dealer_name" id="dealer_name" placeholder="Dealer Name" >
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
                    <div class="control-group">
                        <label class="control-label" for="market_name">
                            Market Name
                        </label>
                        <div class="controls">
                            <input class="span9" type="text" name="market_name" id="market_name" placeholder="Market Name" >
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="address">
                            Address
                        </label>
                        <div class="controls">
                            <textarea class="span9" name="address" id="address" placeholder="Address" ></textarea>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="phone_no">
                            Phone
                        </label>
                        <div class="controls">
                            <input class="span3" type="text" name="phone_no" id="phone_no" placeholder="Phone" validate="Mobile" maxlength="11" onkeypress="return numbersOnly(event)" >
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