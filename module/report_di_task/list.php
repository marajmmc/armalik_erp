<?php

ob_start();
session_start();
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
                    <a id="dynamicTable"><?php echo $db->Get_Auto_TaskName(); ?></a>
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

                    <table class="table table-condensed table-striped table-hover table-bordered pull-left" id="data-table"  style="border-right:0px; width: 30%; float: left;">
                        <thead>
                            <tr>
                                <th style="width:5%">
                                    Criteria
                                </th>
                            </tr>
                            <tr>
                                <td>
                                    <div class="input-append">
                                        <select name="criteria" class="span12">
                                            <option value="">Select</option>
                                            <option value="1">Activities</option>
                                            <option value="2">Problem</option>
                                        </select>
                                    </div>
                                </td>
                            </tr>
                        </thead>
                    </table>

                    <?php require_once("../../libraries/search_box/from_to_date.php") ?>
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
        //session_load_fnc()
    });
</script>