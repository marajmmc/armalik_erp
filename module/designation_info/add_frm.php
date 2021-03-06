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
                <div class="widget-body span6">
                    <div class="control-group">
                        <label class="control-label" for="designation_title_en">
                            Designation Name (EN)
                        </label>
                        <div class="controls">
                            <input class="span9" type="text" name="designation_title_en" id="designation_title_en" placeholder="Designation Name (EN)" validate="Require">
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
                            <input class="span9" type="text" name="designation_title_bn" id="designation_title_bn" placeholder="Designation Name (BN)" >
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>