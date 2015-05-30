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
                                    Zone
                                </th>
                                <th style="width:10%">
                                    Territory
                                </th>
                                <th style="width:10%">
                                    District
                                </th>
                            </tr>

                            <tr>
                                <td>
                                    <select id="zone_id" name="zone_id" class="span12" placeholder="Zone" onchange="load_district();load_territory_fnc();">
                                        <option value="">Select</option>
                                        <?php
                                        $sql_uesr_group = "select zone_id as fieldkey, zone_name as fieldtext from $tbl" . "zone_info WHERE status='Active' AND del_status='0'";
                                        echo $db->SelectList($sql_uesr_group);
                                        ?>
                                    </select>
                                </td>
                                <td>
                                    <select id="territory_id" name="territory_id" class="span12" placeholder="Territory" validate="Require">
                                        <option value="">Select</option>

                                    </select>
                                </td>
                                <td>
                                    <select id="district_id" name="district_id" class="span12" placeholder="" validate="Require" onchange="load_upazilla_fnc()">
                                        <option value="">Select</option>
                                        <?php
                                        //                                        $sql_uesr_group = "select zillaid as fieldkey, zillanameeng as fieldtext from $tbl" . "zilla ORDER BY zillanameeng";
                                        //                                        echo $db->SelectList($sql_uesr_group);
                                        ?>
                                    </select>
                                </td>

                            </tr>
                            <tr>
                                <th style="width:10%">
                                    Upazila
                                </th>
                                <th style="width:10%">
                                    Crop
                                </th>
                                <th style="width:10%">
                                    Product Type
                                </th>
                            </tr>
                            <tr>
                                <td>
                                    <select id="upazilla_id" name="upazilla_id" class="span12" placeholder="" validate="Require">
                                        <option value="">Select</option>

                                    </select>
                                </td>
                                <td>
                                    <select id="crop_id" name="crop_id" class="span12" placeholder="Select Crop" validate="Require" onchange="load_product_type()">
                                        <?php
                                        echo "<option value=''>Select</option>";
                                        $sql_uesr_group = "select crop_id as fieldkey, crop_name as fieldtext from $tbl" . "crop_info where status='Active'  " . $db->get_zone_access($tbl . "zone_info") . " ORDER BY $tbl" . "crop_info.order_crop";
                                        echo $db->SelectList($sql_uesr_group);
                                        ?>
                                    </select>
                                </td>
                                <td>
                                    <select id="product_type_id" name="product_type_id" class="span12" placeholder="Product Type" validate="Require">
                                        <option value="">Select</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th style="width:10%">
                                    Year
                                </th>
                                <th style="width:10%">
                                    &nbsp;
                                </th>
                                <th style="width:10%">
                                    &nbsp;
                                </th>
                            </tr>
                            <tr>
                                <th>
                                    <select id="pdo_year_id" name="pdo_year_id" class="span12" placeholder="" validate="Require">
                                        <?php
                                        echo "<option value=''>Select</option>";
                                        $sql_uesr_group = "select pdo_year_id as fieldkey, pdo_year_name as fieldtext from $tbl" . "pdo_year where status='Active' ORDER BY $tbl"."pdo_year.pdo_year_name";
                                        echo $db->SelectList($sql_uesr_group);
                                        ?>
                                    </select>
                                </th>
                                <th>

                                </th>
                                <th>
                                    <a class="btn btn-small btn-success pull-right" data-original-title="" onclick="show_report_fnc()">
                                        <i class="icon-print" data-original-title="Share"> </i> View
                                    </a>

                                </th>
                            </tr>
                        </thead>
                    </table>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        <div id="div_show_variety"></div>
        <div id="div_show_rpt"></div>
    </div>
</div>
<script>
    $(document).ready(function(){
        session_load_fnc()
    });
</script>