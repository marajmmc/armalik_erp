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
                        <label class="control-label" for="zone_name">
                            Division Name
                        </label>
                        <div class="controls">
                            <select id="division_id" name="division_id" class="span5" placeholder="Division" >
                                <?php
                                //include_once '../../libraries/ajax_load_file/load_division.php';
                                $db_division=new Database();
                                $db_division->get_division();
                                ?>
                            </select>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="zone_name">
                            Zone Name
                        </label>
                        <div class="controls">
                            <input class="span9" type="text" name="zone_name" id="zone_name" placeholder="Zone Name" validate="Require">
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
                            <textarea class="span9" name="description" id="description" placeholder="Description" ></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>