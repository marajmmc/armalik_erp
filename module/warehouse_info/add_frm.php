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
                        <label class="control-label" for="warehouse_name">
                            Warehouse Name
                        </label>
                        <div class="controls">
                            <input class="span3" type="text" name="warehouse_name" id="warehouse_name" placeholder="Warehouse Name" >
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="capacity">
                            Capacity
                        </label>
                        <div class="controls">
                            <input class="span3" type="text" name="capacity" id="capacity" placeholder="Capacity" >
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="capacity_unit">
                            Capacity Unit
                        </label>
                        <div class="controls">
                            <select id="capacity_unit" name="capacity_unit" class="span3" placeholder="Capacity Unit">
                                <option value="">Select</option>
                                <option value="Metric ton">Metric ton</option>
                                <option value="Kilogram">Kilogram</option>
                                <option value="Gram">Gram</option>
                                <option value="Pound">Pound</option>
                            </select>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="address">
                            Address
                        </label>
                        <div class="controls">
                            <textarea class="span9" name="address" id="address" placeholder="Address" ></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
