<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$tbl = _DB_PREFIX;
$editrow = $db->single_data($tbl . "employee_designation", "*", "designation_id", $_POST['rowID']);
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
                        <label class="control-label" for="designation_title_en">
                            Designation Name (EN)
                        </label>
                        <div class="controls">
                            <input class="span9" type="text" disabled="" name="designation_title_en" id="designation_title_en" value="<?php echo $editrow['designation_title_en'] ?>" placeholder="Designation Name (EN)" validate="Require">
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="designation_title_bn">
                            Designation Name (BN)
                        </label>
                        <div class="controls">
                            <input class="span9" disabled="" type="text" name="designation_title_bn" id="designation_title_bn" value="<?php echo $editrow['designation_title_bn'] ?>" placeholder="Designation Name (BN)" >
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="blood_group">
                            Status
                        </label>
                        <div class="controls controls-row">
                            <select id="blood_group" disabled="" name="blood_group" class="span9" placeholder="Group Name">
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