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
                    <?php require_once("../../libraries/search_box/division_zone_territory_district_upzilla.php") ?>
                    <?php require_once("../../libraries/search_box/crop_type_variety_pack_size.php") ?>
                    <?php require_once("../../libraries/search_box/search_button.php") ?>

                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        <div id="div_show_rpt"></div>
    </div>
</div>

<script>
    $(document).ready(function(){
        session_load_fnc()

        $("#zone_id").attr('onchange', '');

        $("#territory_td_elm").hide();
        $("#territory_th_caption").hide();

        $("#zilla_td_elm").hide();
        $("#zilla_th_caption").hide();

        $("#upazilla_td_elm").hide();
        $("#upazilla_th_caption").hide();

        $("#varriety_id").attr('onchange', '');

        $("#pack_size_th_caption").hide();
        $("#pack_size_td_elm").hide();
    });
</script>