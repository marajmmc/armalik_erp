<?php

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
                    <a id="dynamicTable"><?php echo $db->Get_Auto_TaskName().'ZI Task Report' ?></a>
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
                                Division
                            </th>
                            <th style="width:10%">
                                Zone
                            </th>
                            <th style="width:10%" id="territory_th_caption">
                                Territory
                            </th>
                            <th style="width:10%" id="territory_th_caption">
                                District
                            </th>
                            <th style="width:10%" id="distributor_th_caption">
                                Customer
                            </th>
                        </tr>
                        <tr>
                            <td>
                                <select id="division_id" name="division_id" class="span12" placeholder="Division" onchange="load_zone()" >
                                    <?php
                                    //include_once '../../libraries/ajax_load_file/load_division.php';
                                    $db_division=new Database();
                                    $db_division->get_division();
                                    ?>
                                </select>
                            </td>
                            <td>
                                <select id="zone_id" name="zone_id" class="span12" placeholder="Zone" onchange="load_territory_fnc()">
                                    <option value="">Select</option>
                                    <?php
                                    //$sql_uesr_group = "select zone_id as fieldkey, zone_name as fieldtext from $tbl" . "zone_info WHERE status='Active' AND del_status='0' ".$db->get_zone_access($tbl. "zone_info")."";
                                    //echo $db->SelectList($sql_uesr_group);
                                    ?>
                                </select>
                            </td>
                            <td id="territory_td_elm">
                                <select id="territory_id" name="territory_id" class="span12" placeholder="Territory" onchange="load_distributor_fnc()" >
                                    <option value="">Select</option>

                                </select>
                            </td>
                            <td id="territory_td_elm">
                                <select id="territory_id" name="territory_id" class="span12" placeholder="Territory" onchange="load_distributor_fnc()" >
                                    <option value="">Select</option>

                                </select>
                            </td>
                            <td id="distributor_td_elm">
                                <select id="distributor_id" name="distributor_id" class="span12" placeholder="Distributor" validate="Require" onchange="load_dealer_fnc()">
                                    <option value="">Select</option>

                                </select>
                            </td>
                        </tr>
                        </thead>
                    </table>

                    <table class="table table-condensed table-striped table-hover table-bordered pull-left" id="data-table">
                        <thead>
                        <tr>
                            <th style="width:50%">
                                Date From
                            </th>
                            <th style="width:50%">
                                date To
                            </th>
                        </tr>
                        <tr>
                            <td>
                                <div class="controls">
                                    <input type="text" name="start_date" id="start_date" class="span11" />
                                    <span class="add-on" id="calcbtn_start_date">
                                        <i class="icon-calendar"></i>
                                    </span>
                                </div>
                            </td>
                            <td>
                                <div class="controls">
                                    <input type="text" name="start_date" id="start_date" class="span11" />
                                    <span class="add-on" id="calcbtn_start_date">
                                        <i class="icon-calendar"></i>
                                    </span>
                                </div>
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
    $(document).ready(function(){
        session_load_fnc()
    });
</script>