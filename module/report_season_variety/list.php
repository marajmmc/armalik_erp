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
        <div class="widget">
            <div class="widget-header">
                <div class="title">
                    <a id="dynamicTable"><?php echo $db->Get_Auto_TaskName() ?></a>
                    <span class="mini-title">

                    </span>
                </div>
                <span class="tools">
                    <a class="btn btn-small" data-original-title="">
                        <i class="icon-list-alt" data-original-title="Share"> </i>
                    </a>
                </span>

            </div>
            <div class="widget-body">
                <div id="dt_example" class="example_alt_pagination">
                    
                    <table class="table table-condensed table-striped table-hover table-bordered pull-left" id="data-table">
                        <thead>
                            <tr>
                                <th style="width:10%">
                                    Season
                                </th>
                                <th style="width:10%">
                                    Crop
                                </th>
                                <th style="width:10%">
                                    Product Type
                                </th>
                                <th style="width:10%">
                                    Variety
                                </th>
                                <th style="width:10%">
                                    Status
                                </th>
                            </tr>
                            <tr>
                                <td>
                                    <select id="session_id" name="session_id" class="span12" placeholder="Crop" validate="Require" >
                                        <option value="">Select</option>
                                        <?php
                                        $sql_season = "select session_id as fieldkey, session_name as fieldtext from $tbl" . "session_info where status='Active' GROUP BY session_id";
                                        echo $db->SelectList($sql_season);
                                        ?>
                                    </select>
                                </td>
                                <td>
                                    <select id="crop_id" name="crop_id" class="span12" placeholder="Crop" validate="Require" onchange="load_product_type()">
                                        <?php
                                        include_once '../../libraries/ajax_load_file/load_crop.php';
                                        ?>
                                    </select>
                                </td>
                                <td>
                                    <select id="product_type_id" name="product_type_id" class="span12" placeholder="Product Type" onchange="load_varriety_fnc()" validate="Require">
                                        <option value="">Select</option>
                                    </select>
                                </td>
                                <td>
                                    <select id="varriety_id" name="varriety_id" class="span12" placeholder="Select Variety" validate="Require">
                                        <option value="">Select</option>
                                    </select>
                                </td>
                                <td>
                                    <select id='product_status' name='product_status' class='span12' placeholder='Select Status' validate='Require'>
                                        <option value=''>Select</option>
                                        <option value='Before Start'>Before Start</option>
                                        <option value='Peak'>Peak</option>
                                        <option value='Off Peak'>Off Peak</option>
                                        <option value='Finish'>Finish</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="12" style="text-align: right;">
                                    <a class="btn btn-small btn-success" data-original-title="" onclick="show_report_fnc()">
                                        <i class="icon-print" data-original-title="Share"> </i> View
                                    </a>
                                </td>
                            </tr>
                        </thead>
                    </table>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        <div id="div_show_rpt"></div>
    </div>
</div>

