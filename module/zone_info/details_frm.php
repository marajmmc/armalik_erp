<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$tbl = _DB_PREFIX;
$editrow = $db->single_data($tbl . "zone_info", "*", "zone_id", $_POST['rowID']);
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
                <div class="widget-body span6">
                    <div class="control-group">
                        <label class="control-label" for="zone_name_en">
                            Zone Name
                        </label>
                        <div class="controls">
                            <input class="span9" type="text" disabled="" name="zone_name_en" id="zone_name_en" value="<?php echo $editrow['zone_name'] ?>" placeholder="Zone Name (EN)" validate="Require">
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="description">
                            Description
                        </label>
                        <div class="controls">
                            <textarea class="span9" name="description" id="description" disabled="" placeholder="Description" ><?php echo $editrow['description'] ?></textarea>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="blood_group">
                            Status
                        </label>
                        <div class="controls controls-row">
                            <select id="status" name="status" disabled="" class="span9" placeholder="Group Name">
                                <option value="">No Select</option>
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