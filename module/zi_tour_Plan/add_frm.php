<?php

ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$tbl = _DB_PREFIX;
session_start();

//echo $_SESSION['sm_id'];

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
                <table class="table table-condensed table-striped table-hover table-bordered pull-left" id="data-table">
                    <thead>
                        <tr>
                            <th style="width:10%" id="territory_th_caption">
                                Year
                            </th>
                            <th style="width:10%" id="territory_th_caption">
                                From Month
                            </th>
                            <th style="width:10%" id="distributor_th_caption">
                                To Month
                            </th>
                        </tr>
                    </thead>
                    <tr>
                        <td>
                            <div class="controls">
                                <select id="year" name="year" class="span12" validate="Require">
                                    <option value="">Select</option>
                                    <?php
                                    $sql = "select year_id as fieldkey, year_name as fieldtext from $tbl" . "year";
                                    echo $db->SelectList($sql);
                                    ?>
                                </select>
                            </div>
                        </td>
                        <td>
                            <div class="controls">
                                <select id="from_month" name="from_month" class="span12" validate="Require">
                                    <option value="">Select</option>
                                    <?php
                                    $monthArray = $db->get_month_array();
                                    foreach($monthArray as $val=>$month)
                                    {
                                    ?>
                                        <option value="<?php echo $val;?>"><?php echo $month;?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </td>
                        <td>
                            <div class="controls">
                                <select id="to_month" name="to_month" class="span12" validate="Require">
                                    <option value="">Select</option>
                                    <?php
                                    $monthArray = $db->get_month_array();
                                    foreach($monthArray as $val=>$month)
                                    {
                                        ?>
                                        <option value="<?php echo $val;?>"><?php echo $month;?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </td>
                    </tr>
                </table>

                <div id="dt_example" class="example_alt_pagination">
                    <table class="table table-condensed table-striped table-hover table-bordered pull-left" id="data-table">
                        <thead>
                            <tr>
                                <th style="width:1%" id="territory_th_caption">

                                </th>
                                <th style="width:1%" id="territory_th_caption">

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
                        </thead>

                        <?php
                        $week = $db->get_week_days();
                        foreach($week as $val=>$day)
                        {
                        ?>
                            <tr>
                                <td>
                                    <label class="label label-info text-center"><?php echo $day;?></label>
                                </td>
                                <td>
                                    <label class="label label-warning text-center">Morning</label>
                                </td>
                                <td class="territory_td_elm">
                                    <select name="territory_id[<?php echo $val;?>][1]" class="span12 territory_id" placeholder="Territory" onchange="" >
                                        <option value="">Select</option>

                                    </select>
                                </td>
                                <td class="district_td_elm">
                                    <select name="district_id[<?php echo $val;?>][1]" class="span12 district_id" placeholder="District" onchange="" >
                                        <option value="">Select</option>

                                    </select>
                                </td>
                                <td class="distributor_td_elm">
                                    <select name="distributor_id[<?php echo $val;?>][1][]" class="span12 distributor_id" multiple="multiple" placeholder="Distributor" validate="Require" onchange="load_dealer_fnc()">
                                        <option value="">Select</option>

                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td></td>
                                <td>
                                    <label class="label label-success text-center">Afternoon</label>
                                </td>
                                <td class="territory_td_elm">
                                    <select name="territory_id[<?php echo $val;?>][2]" class="span12 territory_id" placeholder="Territory" onchange="" >
                                        <option value="">Select</option>

                                    </select>
                                </td>
                                <td class="district_td_elm">
                                    <select name="district_id[<?php echo $val;?>][2]" class="span12 district_id" placeholder="District" onchange="" >
                                        <option value="">Select</option>

                                    </select>
                                </td>
                                <td class="distributor_td_elm">
                                    <select name="distributor_id[<?php echo $val;?>][2][]" class="span12 distributor_id" multiple="multiple" placeholder="Distributor" validate="Require" onchange="load_dealer_fnc()">
                                        <option value="">Select</option>

                                    </select>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </table>

                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        <div id="div_show_rpt"></div>
    </div>
</div>

<script>
    $(document).ready(function(){
        session_load_fnc();
        turn_off_trigger();

        $(document).on("change",".territory_id",function()
        {
            if($(this).val().length>0)
            {
                $(this).parents().next(".district_td_elm").find(".district_id").html('');
                var location = $(this).parents().next(".district_td_elm").find(".district_id");

                $.post("../../libraries/ajax_load_file/load_territory_assign_district.php",
                {
                    zone_id:"", territory_id: $(this).val()
                },
                function(result)
                {
                    if(result)
                    {
                        location.append(result);
                    }
                });
            }
            else
            {
                $(this).parents().next(".district_td_elm").find(".district_id").html('');
                $(this).parents().next(".district_td_elm").next(".distributor_td_elm").find(".distributor_id").html('');
            }
        });

        $(document).on("change",".district_id",function()
        {
            if($(this).val()>0)
            {
                $(this).parents().next(".distributor_td_elm").find(".distributor_id").html('');

                var territory_id = $(this).parents().prev(".territory_td_elm").find(".territory_id").val();

                var location = $(this).parents().next(".distributor_td_elm").find(".distributor_id");

                $.post("../../libraries/ajax_load_file/load_distributor.php",
                {
                    zone_id:"", territory_id: territory_id, zilla_id: $(this).val()
                },
                function(result)
                {
                    if(result)
                    {
                        location.append(result);
                    }
                });
            }
            else
            {
                $(this).parents().next(".distributor_td_elm").find(".distributor_id").html('');
            }
        });
    });

</script>