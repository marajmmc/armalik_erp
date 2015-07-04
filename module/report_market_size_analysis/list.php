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
                    <?php require_once("../../libraries/search_box/division_zone_territory_district_upzilla.php") ?>
                    <?php require_once("../../libraries/search_box/crop_type_variety_pack_size.php") ?>
                    <table class="table table-condensed table-striped table-hover table-bordered pull-left" id="data-table" style="width: 25%; float: left;">
                        <thead>
                        <tr>
                            <th style="width:10%">
                                Select Year
                            </th>
                        </tr>
                        <tr>
                            <td>
                                <select id="pdo_year_id" name="pdo_year_id" class="span12" placeholder="" validate="Require">
                                    <?php
                                    echo "<option value=''>Select</option>";
                                    $sql_uesr_group = "select pdo_year_id as fieldkey, pdo_year_name as fieldtext from $tbl" . "pdo_year where status='Active' ORDER BY $tbl"."pdo_year.pdo_year_name";
                                    echo $db->SelectList($sql_uesr_group);
                                    ?>
                                </select>
                            </td>
                        </tr>
                        </thead>
                    </table>
                    <?php require_once("../../libraries/search_box/search_button.php") ?>

                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        <div id="div_show_rpt"></div>
    </div>
</div>
<script>
    $(document).ready(function()
    {
        session_load_fnc()

        $("#product_type_id").attr('onchange', '');

        $("#variety_td_elm").hide();
        $("#variety_th_caption").hide();

        $("#pack_size_td_elm").hide();
        $("#pack_size_th_caption").hide();

    });
</script>