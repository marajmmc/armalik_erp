<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$tbl = _DB_PREFIX;
$editrow = $db->single_data($tbl . "warehouse_info", "*", "warehouse_id", $_POST['rowID']);
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
                        <label class="control-label" for="warehouse_name">
                            Warehouse Name
                        </label>
                        <div class="controls">
                            <input class="span3" disabled="" type="text" name="warehouse_name" id="warehouse_name" value="<?php echo $editrow['warehouse_name'] ?>" placeholder="Warehouse Name" >
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="capacity">
                            Capacity
                        </label>
                        <div class="controls">
                            <input class="span3" disabled="" type="text" name="capacity" id="capacity" placeholder="Capacity" value="<?php echo $editrow['capacity'] ?>" >
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="capacity_unit">
                            Capacity Unit
                        </label>
                        <div class="controls">
                            <select id="capacity_unit" name="capacity_unit" disabled="" class="span3" placeholder="Capacity Unit">
                                <option value="">Select</option>
                                <option value="Metric ton" <?php
if ($editrow['capacity_unit'] == "Metric ton") {
    echo "selected='selected'";
}
?> >Metric ton</option>
                                <option value="Kilogram" <?php
                                        if ($editrow['capacity_unit'] == "Kilogram") {
                                            echo "selected='selected'";
                                        }
?> >Kilogram</option>
                                <option value="Gram" <?php
                                        if ($editrow['capacity_unit'] == "Gram") {
                                            echo "selected='selected'";
                                        }
?> >Gram</option>
                                <option value="Pound" <?php
                                        if ($editrow['capacity_unit'] == "Pound") {
                                            echo "selected='selected'";
                                        }
?> >Pound</option>
                            </select>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="address">
                            Address
                        </label>
                        <div class="controls">
                            <textarea class="span9" disabled="" name="address" id="address" placeholder="Address" ><?php echo $editrow['address'] ?></textarea>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="blood_group">
                            Status
                        </label>
                        <div class="controls controls-row">
                            <select id="status" name="status" disabled="" class="span5" placeholder="Group Name">
                                <option value="">Select</option>
                                <option value="Active" <?php
                                        if ($editrow['status'] == "Active") {
                                            echo "selected='selected'";
                                        }
?> >Active</option>
                                <option value="In-Active" <?php
                                        if ($editrow['status'] == "In-Active") {
                                            echo "selected='selected'";
                                        }
?> >In-Active</option>

                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>