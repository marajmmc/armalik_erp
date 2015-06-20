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
                            Start Date
                        </label>
                        <div class="controls">
                            <input class="span5" type="text" name="start_date" id="start_date" placeholder="Start Date" validate="Require">
                            <span class="help-inline">*</span>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">
                            End Date
                        </label>
                        <div class="controls">
                            <input class="span5" type="text" name="end_date" id="end_date" placeholder="End Date" validate="Require">
                            <span class="help-inline">*</span>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">
                            Zone
                        </label>
                        <div class="controls">
                            <select id="zone" name="zone" class="span5">
                                <option value="">Select</option>
                                <?php
                                //$sql = "select zone_id as fieldkey, zone_name_en as fieldtext from $tbl" . "zone_info";
                                //echo $db->SelectList($sql);
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">
                            District
                        </label>
                        <div class="controls">
                            <select id="district" name="district" class="span5">
                                <option value="">Select</option>
                                <?php

                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">
                            Zone In-charge
                        </label>
                        <div class="controls">
                            <select id="zone_in_charge" name="zone_in_charge" class="span5">
                                <option value="">Select</option>
                                <?php

                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">
                            Distributor
                        </label>
                        <div class="controls">
                            <select id="distributor" name="distributor" class="span5">
                                <option value="">Select</option>
                                <?php

                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">
                            Product
                        </label>
                        <div class="controls">
                            <select id="product" name="product" class="span5">
                                <option value="">Select</option>
                                <?php

                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">
                            Distributor Others
                        </label>
                        <div class="controls">
                            <textarea name="distributor_others" class="span6"></textarea>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">
                            date
                        </label>
                        <div class="controls">
                            <input class="span5" type="text" name="entry_date" id="entry_date" value="<?php echo $db->date_formate($db->ToDayDate()) ?>" placeholder="Entry date" validate="Require">
                            <span class="help-inline">*</span>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">
                            Activities
                        </label>
                        <div class="controls">
                            <textarea name="activities" class="span6"></textarea>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">
                            Problem
                        </label>
                        <div class="controls">
                            <textarea name="problem" class="span6"></textarea>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">
                            Recommendation
                        </label>
                        <div class="controls">
                            <textarea name="recommendation" class="span6"></textarea>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">
                            Solution
                        </label>
                        <div class="controls">
                            <textarea name="solution" class="span6"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
    });

    var cal = Calendar.setup({
        onSelect: function(cal) { cal.hide() },
        fdow :0,
        minuteStep:1
    });
    cal.manageFields("start_date", "start_date", "%d-%m-%Y");
    cal.manageFields("end_date", "end_date", "%d-%m-%Y");

</script>