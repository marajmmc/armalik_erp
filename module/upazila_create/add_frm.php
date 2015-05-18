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
                        <label class="control-label" for="employee_designation">
                            District
                        </label>
                        <div class="controls">
                            <select id="zilla_id" name="zilla_id" class="span5"  validate="Require">
                                <option value="">Select</option>
                                <?php
                                $sql_uesr_group = "select zillaid as fieldkey, zillanameeng as fieldtext from $tbl" . "zilla where visible=0";
                                echo $db->SelectList($sql_uesr_group);
                                ?>
                            </select>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="designation_title_bn">
                            Upazila Name
                        </label>
                        <div class="controls">
                            <input class="span3" type="text" name="upazilla_name" id="upazilla_name" placeholder="Upazila Name"  validate="Require" onblur="Existin_data()" />
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
