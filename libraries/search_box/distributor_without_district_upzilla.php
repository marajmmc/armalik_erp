<?php
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$tbl = _DB_PREFIX;
?>

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
        <td id="distributor_td_elm">
            <select id="distributor_id" name="distributor_id" class="span12" placeholder="Distributor" validate="Require" onchange="load_dealer_fnc()">
                <option value="">Select</option>

            </select>
        </td>
    </tr>
    </thead>
</table>

<script>

    //////////  start zone, territory, distributor load function //////////////

    function load_territory_fnc(){
        $("#territory_id").html('');
        $.post("../../libraries/ajax_load_file/load_territory.php",{
            zone_id:$("#zone_id").val()
        }, function(result){
            if (result){
                $("#territory_id").append(result);
            }
        });
    }


    function load_distributor_fnc(){
        $("#distributor_id").html('');
        $.post("../../libraries/ajax_load_file/load_distributor.php",{
            zone_id:$("#zone_id").val(),
            territory_id:$("#territory_id").val()
        }, function(result){
            if (result){
                $("#distributor_id").append(result);
            }
        });
    }

    function session_load_fnc(){
        if($("#userLevel").val()=="Zone")
        {
            session_load_zone();
            session_load_territory();
        }
        else if($("#userLevel").val()=="Territory")
        {
            session_load_zone();
            session_load_territory();
            session_load_distributor();
        }
        else if($("#userLevel").val()=="Distributor")
        {
            session_load_zone();
            session_load_territory();
            session_load_distributor();
        }
        else if($("#userLevel").val()=="Division")
        {
            load_zone()
        }
        else
        {

        }
    }

    function session_load_zone(){
        $("#zone_id").html('');
        $.post("../../libraries/ajax_load_file/session_load_zone.php",function(result){
            if (result){
                $("#zone_id").append(result);
            }
        });
    }
    function session_load_territory(){
        $("#territory_id").html('');
        $.post("../../libraries/ajax_load_file/session_load_territory.php", function(result){
            if (result){
                $("#territory_id").append(result);
            }
        });
    }
    function session_load_distributor(){
        $("#distributor_id").html('');
        $.post("../../libraries/ajax_load_file/session_load_distributor.php", function(result){
            if (result){
                $("#distributor_id").append(result);
            }
        });
    }

    //////////  end zone, territory, distributor load function //////////////

    function print_rpt(){
        URL="../../libraries/print_page/Print_a4_Eng.php?selLayer=PrintArea";
        day = new Date();
        id = day.getTime();
        eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=yes,scrollbars=yes ,location=0,statusbar=0 ,menubar=yes,resizable=1,width=880,height=600,left = 20,top = 50');");
    }

    function load_zone()
    {
        $("#zone_id").html('');
        $.post("../../libraries/ajax_load_file/load_zone_info_user_access.php",{division_id: $("#division_id").val()}, function(result){
            if (result){
                $("#zone_id").append(result);
            }
        });
    }
</script>