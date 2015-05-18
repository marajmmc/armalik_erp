<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();

?>
<div class="row-fluid">
    <div class="span12">
        <div class="widget">
            <div class="widget-header">
                <div class="title">
                    <a id="dynamicTable"><?php echo $db->Get_Auto_TaskName()?></a>
                    <span class="mini-title">
                        
                    </span>
                </div>
                <span class="tools">
                    <a class="btn btn-small" data-original-title="">
                        <i class="icon-plus-sign" data-original-title="Share"> </i>
                    </a>
                </span>
            </div>
            <div class="widget-body">
                <div class="control-group">
                    <div class="controls span6">
                        <label class="control-label" for="sm_name">
                            Module Name
                        </label>
                        <input class="span12" type="text" name="sm_name" id="sm_name" placeholder="Module Name" validate="Require">
                    </div>
                    <div class="controls span6">
                        <label class="control-label" for="sm_icon">
                            Module Icon
                        </label>
                        <input class="span12" type="file" name="sm_icon" id="sm_icon" placeholder="Module Icon" validate="Picture">
                    </div>
                    <div class="controls controls-row">
                        <span class="label label label-info" style="cursor: pointer; float: right" onclick="RowIncrement()"> + Add More </span>
                    </div>
                </div>
            </div>
            <div class="widget-body">
                <div class="wrapper">
                    <table class="table table-condensed table-striped table-bordered table-hover no-margin" id="TaskTable">
                        <thead>
                            <tr>
                                <th style="width:10%">
                                    Task Name
                                </th>
                                <th style="width:5%">
                                    New
                                </th>
                                <th style="width:5%">
                                    Save
                                </th>
                                <th style="width:5%">
                                    Edit
                                </th>
                                <th style="width:5%">
                                    View
                                </th>
                                <th style="width:5%">
                                    Del
                                </th>
                                <th style="width:5%">
                                    Rpt
                                </th>
                                <th style="width:10%">
                                    Task Icon
                                </th>
                                <th style="width:10%">
                                    Directory Name
                                </th>
                                <th style="width:5%">
                                    Action
                                </th>
                            </tr>
                        </thead>

                    </table>
                </div>
            </div>
        </div>
    </div>

</div>