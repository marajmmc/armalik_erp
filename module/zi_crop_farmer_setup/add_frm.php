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
                    <span class="mini-title"></span>
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
                        <label class="control-label">
                            Division
                        </label>
                        <div class="controls">
                            <select id="division_id" name="division_id" class="span5" onchange="load_zone_by_division()">
                                <option value="">Select</option>
                                <?php
                                $sql = "select division_id as fieldkey, division_name as fieldtext from $tbl" . "division_info";
                                echo $db->SelectList($sql);
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">
                            Zone
                        </label>
                        <div class="controls">
                            <select id="zone_id" name="zone_id" class="span5" onchange="load_territory_by_zone()">
                                <option value="">Select</option>
                                <?php

                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">
                            Territory
                        </label>
                        <div class="controls">
                            <select id="territory_id" name="territory_id" class="span5" onchange="load_district_by_territory()">
                                <option value="">Select</option>
                                <?php

                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">
                            District
                        </label>
                        <div class="controls">
                            <select id="district_id" name="district_id" class="span5" onchange="load_upazilla_by_district()">
                                <option value="">Select</option>
                                <?php

                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">
                            Upazilla
                        </label>
                        <div class="controls">
                            <select id="upazilla_id" name="upazilla_id" class="span5">
                                <option value="">Select</option>
                                <?php

                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">
                            Crop
                        </label>
                        <div class="controls">
                            <select id="crop_id" name="crop_id" class="span5" onchange="load_type_by_crop()">
                                <option value="">Select</option>
                                <?php
                                $sql_uesr_group = "select
                                    crop_id as fieldkey,
                                    crop_name as fieldtext
                                    from $tbl" . "crop_info
                                    where status='Active' AND del_status='0' order by order_crop";
                                echo $db->SelectList($sql_uesr_group);
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">
                            Type
                        </label>
                        <div class="controls">
                            <select id="type_id" name="type_id" class="span5" onchange="load_variety_by_crop_type()">
                                <option value="">Select</option>
                                <?php

                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">
                            Variety
                        </label>
                        <div class="controls">
                            <select id="variety_id" name="variety_id" class="span5" onchange="load_farmers()">
                                <option value="">Select</option>
                                <?php

                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">
                            Farmer Name
                        </label>
                        <div class="controls">
                            <input type="text" id="farmers_name" name="farmers_name" value="" class="span5" onblur="check_farmer_existence()" />
                            <input type="hidden" name="farmer_id" id="farmer_id" value="0" />
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">
                            Address
                        </label>
                        <div class="controls">
                            <textarea name="farmers_address" class="span5"></textarea>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">
                            Contact No.
                        </label>
                        <div class="controls">
                            <input type="text" name="farmers_contact" class="span5"/>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
//    $(document).ready(function()
//    {
//
//    });

//    var cal = Calendar.setup({
//        onSelect: function(cal) { cal.hide() },
//        fdow :0,
//        minuteStep:1
//    });
//
//    cal.manageFields("entry_date", "entry_date", "%d-%m-%Y");

</script>