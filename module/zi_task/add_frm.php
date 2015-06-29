<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$tbl = _DB_PREFIX;

$user_division = $_SESSION['division_id'];
$user_zone = $_SESSION['zone_id'];
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
                            Territory
                        </label>
                        <div class="controls">
                            <select id="territory_id" name="territory_id" class="span5" onchange="load_district_by_territory()">
                                <option value="">Select</option>
                                <?php
                                $sql = "select territory_id as fieldkey, territory_name as fieldtext from $tbl" . "territory_info where zone_id='$user_zone'";
                                echo $db->SelectList($sql);
                                ?>
                            </select>
                            <input type="hidden" name="zone_id" id="zone_id" value="<?php echo $user_zone;?>" />
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">
                            District
                        </label>
                        <div class="controls">
                            <select id="district_id" name="district_id" class="span5">
                                <option value="">Select</option>
                                <?php

                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">
                            Tour Time Span
                        </label>
                        <div class="controls">
                            <select id="time_id" name="time_id" class="span5" onchange="load_distributor_by_tour_time()">
                                <option value="">Select</option>
                                <option value="1">Morning</option>
                                <option value="2">Evening</option>
                            </select>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">
                            Distributor
                        </label>
                        <div class="controls">
                            <select id="distributor_id" name="distributor_id" class="span5" onchange="load_po_and_collection()">
                                <option value="">Select</option>
                                <?php

                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">
                            Purchase Order
                        </label>
                        <div class="controls">
                            <select id="purchase_order" name="purchase_order" class="span5">
                                <option value="">Select</option>
                                <?php

                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">
                            Collection
                        </label>
                        <div class="controls">
                            <select id="collection" name="collection" class="span5">
                                <option value="">Select</option>
                                <?php

                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">
                            date
                        </label>
                        <div class="controls">
                            <input class="span5" type="text" name="entry_date" id="entry_date" value="<?php echo $db->ToDayDate();?>" placeholder="Entry date">
                            <span class="add-on" id="calcbtn_entry_date">
                                <i class="icon-calendar"></i>
                            </span>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">
                            Activities
                        </label>
                        <div class="controls">
                            <textarea name="activities" class="span6"></textarea>
                            <input type="file" name="activities_file" class="span3" />
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">
                            Problem
                        </label>
                        <div class="controls">
                            <textarea name="problem" class="span6"></textarea>
                            <input type="file" name="problem_file" class="span3" />
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
                            <textarea name="solution" class="span6" <?php if($_SESSION['user_level']=='Zone'){echo 'disabled';}?>></textarea>
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

//var cal = Calendar.setup({
//    onSelect: function(cal) { cal.hide() },
//    fdow :0,
//    minuteStep:1
//});
//cal.manageFields("calcbtn_entry_date", "entry_date", "%d-%m-%Y");

</script>