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
                    <?php require_once("../../libraries/search_box/division_zone_territory_district_customer.php") ?>
                    <?php require_once("../../libraries/search_box/crop_type_variety_pack_size.php") ?>
                    <!--                    <table class="table table-condensed table-striped table-hover table-bordered pull-left" id="data-table" style="width: 25%; float: left;">-->
                    <!--                        <thead>-->
                    <!--                        <tr>-->
                    <!--                            <th style="width:10%">-->
                    <!--                                Select Year-->
                    <!--                            </th>-->
                    <!--                        </tr>-->
                    <!--                        <tr>-->
                    <!--                            <td>-->
                    <!--                                <select id="year_id" name="year_id" class="span12" placeholder="Zone" validate="Require">-->
                    <!--                                    --><?php
                    //                                    $db_fiscal_year=new Database();
                    //                                    $db_fiscal_year->get_fiscal_year();
                    //                                    ?>
                    <!--                                </select>-->
                    <!--                            </td>-->
                    <!--                        </tr>-->
                    <!--                        </thead>-->
                    <!--                    </table>-->
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
        $("#varriety_id").attr('onchange', '');

        $("#pack_size_th_caption").hide();
        $("#pack_size_td_elm").hide();
    });

    
</script>