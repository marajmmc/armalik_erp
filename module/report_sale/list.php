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
                                    Zone
                                </th>
                                <th style="width:10%">
                                    Territory
                                </th>
                                <th style="width:10%">
                                    Distributor
                                </th>
                                <th style="width:10%">
                                    F. Date
                                </th>
                                <th style="width:10%">
                                    T. Date
                                </th>
                            </tr>
                            <tr>
                                <td>
                                    <select id="zone_id" name="zone_id" class="span12" placeholder="Zone" onchange="load_territory_fnc()">
                                        <option value="">Select</option>
                                        <?php
                                        $sql_uesr_group = "select zone_id as fieldkey, zone_name as fieldtext from $tbl" . "zone_info WHERE status='Active' AND del_status='0' ".$db->get_zone_access($tbl. "zone_info")."";
                                        echo $db->SelectList($sql_uesr_group);
                                        ?>
                                    </select>
                                </td>
                                <td>
                                    <select id="territory_id" name="territory_id" class="span12" placeholder="Territory" onchange="load_distributor_fnc()" >
                                        <option value="">Select</option>

                                    </select>
                                </td>
                                <td>
                                    <select id="distributor_id" name="distributor_id" class="span12" placeholder="Distributor" validate="Require" onchange="load_dealer_fnc()">
                                        <option value="">Select</option>

                                    </select>
                                </td>
                                <td>
                                    <div class="input-append">
                                        <input type="text" name="from_date" id="from_date" class="span9" placeholder="From Date" value="<?php // echo $db->date_formate($db->ToDayDate())  ?>"  />
                                        <span class="add-on" id="calcbtn_from_date">
                                            <i class="icon-calendar"></i>
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <div class="input-append">
                                        <input type="text" name="to_date" id="to_date" class="span9" placeholder="From Date" value="<?php // echo $db->date_formate($db->ToDayDate())  ?>"  />
                                        <span class="add-on" id="calcbtn_to_date">
                                            <i class="icon-calendar"></i>
                                        </span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
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
                                <th style="width:10%">
                                    &nbsp;
                                </th>
                            </tr>
                            <tr>
                                <td>
                                    <select id="crop_id" name="crop_id" class="span12" placeholder="Select Crop" validate="Require" onchange="load_product_type()">
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
                                <td>
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

<script>
    $(document).ready(function(){
        session_load_fnc()
    });
    
    var cal = Calendar.setup({
        onSelect: function(cal) { cal.hide() },
        fdow :0,
        minuteStep:1
    });
    cal.manageFields("calcbtn_from_date", "from_date", "%d-%m-%Y");
    cal.manageFields("calcbtn_to_date", "to_date", "%d-%m-%Y");
    
</script>