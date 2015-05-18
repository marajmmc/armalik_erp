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
                        <label class="control-label" for="designation_title_en">
                            Distributor Name
                        </label>
                        <div class="controls">
                            <input class="span9" type="text" name="designation_title_en" id="designation_title_en" placeholder="Distributor Name" validate="Require">
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="designation_title_bn">
                            Customer Code
                        </label>
                        <div class="controls">
                            <input class="span3" type="text" name="designation_title_bn" id="designation_title_bn" placeholder="Customer Code" >
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="designation_title_bn">
                            Owner Name
                        </label>
                        <div class="controls">
                            <input class="span9" type="text" name="designation_title_bn" id="designation_title_bn" placeholder="Owner Name" >
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="designation_title_bn">
                            Market Name
                        </label>
                        <div class="controls">
                            <input class="span9" type="text" name="designation_title_bn" id="designation_title_bn" placeholder="Market Name" >
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="designation_title_bn">
                            Address
                        </label>
                        <div class="controls">
                            <textarea class="span9" name="designation_title_bn" id="designation_title_bn" placeholder="Address" ></textarea>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="employee_designation">
                            Zone
                        </label>
                        <div class="controls">
                            <select id="employee_designation" name="employee_designation" class="span5" placeholder="Zone">
                                <option value="">Select</option>
                                <?php
                                $sql_uesr_group = "select zone_id as fieldkey, zone_name_en as fieldtext from $tbl" . "zone_info";
                                echo $db->SelectList($sql_uesr_group);
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="designation_title_bn">
                            Phone
                        </label>
                        <div class="controls">
                            <input class="span3" type="text" name="designation_title_bn" id="designation_title_bn" placeholder="Phone" validate="Mobile" onkeypress="return numbersOnly(event)" >
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="date_of_joining">
                            Email
                        </label>
                        <div class="controls">
                            <div class="input-append">
                                <span class="add-on"> @</span>
                                <input type="text" name="date_of_joining" id="date_of_joining" class="span12" placeholder="Email" validate="Email" />
                            </div>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="date_of_joining">
                            Due Balance
                        </label>
                        <div class="controls">
                            <input type="text" name="date_of_joining" id="date_of_joining" class="span3" placeholder="Due Balance" validate="Email" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
