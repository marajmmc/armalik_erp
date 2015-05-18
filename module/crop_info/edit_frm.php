<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$tbl = _DB_PREFIX;
$editrow = $db->single_data($tbl . "crop_info", "*", "crop_id", $_POST['rowID']);
$crop_id=$editrow['crop_id'];
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
                        <label class="control-label" for="crop_name">
                            Crop
                        </label>
                        <div class="controls">
                            <input class="span9" type="text" name="crop_name" id="crop_name" value="<?php echo $editrow['crop_name'] ?>" placeholder="Crop Name" validate="Require">
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="crop_name">
                            Order Crop
                        </label>
                        <div class="controls">
                            <input class="span3" type="text" name="order_crop" id="order_crop" value="<?php echo $editrow['order_crop'];?>" placeholder="Order Crop"  onblur="Existin_data(this, 'Edit', '<?php echo $crop_id;?>')" validate="Require" onkeypress="return numbersOnly(event)" />
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
                            <textarea class="span9" name="description" id="description" placeholder="Description " ><?php echo $editrow['description'] ?></textarea>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="blood_group">
                            Status
                        </label>
                        <div class="controls controls-row">
                            <select id="status" name="status" class="span3" placeholder="Group Name">
                                <option value="">Select</option>
                                <option value="Active" <?php
                                    if ($editrow['status'] == "Active") {
                                        echo "selected='selected'";
                                    }
                                    ?> >Active</option>
                                <option value="In-Active" <?php
                                        if ($editrow['status'] == "In-Active") {
                                            echo "selected='selected'";
                                        }?> >In-Active</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>