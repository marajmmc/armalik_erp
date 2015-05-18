<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$tbl = _DB_PREFIX;
if ($_SESSION['warehouse_id'] == "") {
    $warehouse = "";
} else {
    $warehouse = "AND warehouse_id='" . $_SESSION['warehouse_id'] . "'";
}
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
                                    Warehouse
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
                                    Pack Size(gm)
                                </th>
                                <th style="width:2%">
                                    &nbsp;
                                </th>
                            </tr>
                            <tr>
                                <td>
                                    <select id="warehouse_id" name="warehouse_id" class="span12" placeholder="Warehouse" >
                                        <option value="">Select</option>
                                        <?php
                                        $sql_uesr_group = "select warehouse_id as fieldkey, warehouse_name as fieldtext from $tbl" . "warehouse_info WHERE status='Active' AND del_status='0'";
                                        echo $db->SelectList($sql_uesr_group);
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
                                    <select id="varriety_id" name="varriety_id" class="span12" placeholder="Select Variety" onchange="load_pack_size_fnc()">
                                        <option value="">Select</option>
                                    </select>
                                </td>
                                <td>
                                    <select id='pack_size' name='pack_size' class='span12' placeholder='Select Pack Size(gm)'>
                                        <option value=''>Select</option>
                                    </select>
                                </td>
                                <td style="text-align: right">
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
