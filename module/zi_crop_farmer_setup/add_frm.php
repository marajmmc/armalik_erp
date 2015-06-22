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