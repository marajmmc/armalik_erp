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
                                    Year
                                </th>
                                <th style="width:10%">
                                    Season
                                </th>
                                <th style="width:10%">
                                    Division
                                </th>
                                <th style="width:10%">
                                    Zone
                                </th>
                                <th style="width:10%">
                                    Territory
                                </th>
                            </tr>
                            <tr>
                                <td>
                                    <select id="pdo_year_id" name="pdo_year_id" class="span12" placeholder="" >
                                        <?php
                                        echo "<option value=''>Select</option>";
                                        $sql_uesr_group = "select pdo_year_id as fieldkey, pdo_year_name as fieldtext from $tbl" . "pdo_year where status='Active' ORDER BY $tbl"."pdo_year.pdo_year_name";
                                        echo $db->SelectList($sql_uesr_group);
                                        ?>
                                    </select>
                                </td>
                                <td>
                                    <select id="pdo_season_id" name="pdo_season_id" class="span12" placeholder="" >
                                        <?php
                                        echo "<option value=''>Select</option>";
                                        $sql_uesr_group = "select pdo_season_id as fieldkey, pdo_season_name as fieldtext from $tbl" . "pdo_season_info where status='Active' ORDER BY $tbl"."pdo_season_info.pdo_season_name";
                                        echo $db->SelectList($sql_uesr_group);
                                        ?>
                                    </select>
                                </td>
                                <td>
                                    <select id="division_id" name="division_id" class="span12   " placeholder="Division" onchange="load_zone()" validate="Require">
                                        <option value="">Select</option>
                                        <?php
                                        $session_zone='';
                                        if ($_SESSION['user_level'] == "Zone")
                                        {
                                            $session_zone = "AND ait_zone_user_access.zone_id='" . $_SESSION['zone_id'] . "'";
                                        }
                                        $sql_division="SELECT
                                                            ait_zone_user_access.division_id as fieldkey,
                                                            ait_division_info.division_name as fieldtext
                                                        FROM
                                                            ait_zone_user_access
                                                            LEFT JOIN ait_division_info ON ait_division_info.division_id = ait_zone_user_access.division_id
                                                        WHERE ait_division_info.del_status=0 $session_zone
                                                        GROUP BY ait_zone_user_access.division_id
                                                            ";
                                        echo $db->SelectList($sql_division);
                                        ?>
                                    </select>
                                </td>
                                <td>
                                    <select id="zone_id" name="zone_id" class="span12" placeholder="Select Variety" validate="Require" onchange="load_territory_fnc(); load_district();">
                                        <option value="">Select</option>
                                        <?php
                                        /*
                                        $sql_uesr_group = "SELECT
                                                                    $tbl" . "zone_info.zone_id as fieldkey,
                                                                    $tbl" . "zone_info.zone_name as fieldtext
                                                                FROM
                                                                    $tbl" . "zone_user_access
                                                                    LEFT JOIN $tbl" . "zone_info ON $tbl" . "zone_info.zone_id = $tbl" . "zone_user_access.zone_id
                                                                WHERE
                                                                    $tbl" . "zone_user_access.del_status='0' AND $tbl" . "zone_user_access.status='Active'
                                                                ";
                                        echo $db->SelectList($sql_uesr_group);
                                        */
                                        ?>
                                    </select>
                                </td>
                                <td>
                                    <select id="territory_id" name="territory_id" class="span12" placeholder="Territory" validate="Require">
                                        <option value="">Select</option>

                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>District</th>
                                <th>Upazila</th>
                                <th>Crop</th>
                                <th>Product Type</th>
                                <th>&nbsp;</th>
                            </tr>
                            <tr>
                                <td>
                                    <select id="district_id" name="district_id" class="span12" placeholder="Distributor" validate="Require" onchange="load_upazilla_fnc()">
                                        <option value="">Select</option>

                                    </select>
                                </td>
                                <td>
                                    <select id="upazilla_id" name="upazilla_id" class="span12" placeholder="Distributor" validate="Require">
                                        <option value="">Select</option>

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
                                    <select id="product_type_id" name="product_type_id" class="span12" placeholder="Product Type" >
                                        <option value="">Select</option>
                                    </select>
                                </td>
                                <td style="text-align: right;">
                                    <a class="btn btn-small btn-success" data-original-title="" onclick="load_variety_view()">
                                        <i class="icon-book" data-original-title="Share"> </i> Load Variety
                                    </a>
                                </td>
                            </tr>

                        </thead>
                    </table>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        <div id="div_show_variety"></div>
        <br />
        <div id="div_show_rpt"></div>
    </div>
</div>

<script>
    $(document).ready(function(){
        session_load_fnc()
    });
</script>