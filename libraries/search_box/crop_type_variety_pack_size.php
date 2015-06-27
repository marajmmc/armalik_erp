<?php
@session_start();
@ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$tbl = _DB_PREFIX;
?>

<table class="table table-condensed table-striped table-hover table-bordered pull-left crop_type_variety" id="data-table" >
    <thead>
    <tr>
        <th style="width:10%">
            Crop
        </th>
        <th style="width:10%">
            Product Type
        </th>
        <th style="width:10%" id="variety_th_caption">
            Variety
        </th>
        <th style="width:10%" id="pack_size_th_caption">
            Pack Size(gm)
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
        <td id="variety_td_elm">
            <select id="varriety_id" name="varriety_id" class="span12" placeholder="Select Variety" onchange="load_pack_size_fnc()">
                <option value="">Select</option>
            </select>
        </td>
        <td id="pack_size_td_elm">
            <select id='pack_size' name='pack_size' class='span12' placeholder='Select Pack Size(gm)'>
                <option value=''>Select</option>
            </select>
        </td>
    </tr>
    </thead>
</table>

<script>

    function load_varriety_fnc()
    {
        $("#varriety_id").html('');
        $.post("../../libraries/ajax_load_file/load_varriety.php",{
            crop_id:$("#crop_id").val(),
            product_type_id:$("#product_type_id").val()
        }, function(result){
            if (result){
                $("#varriety_id").append(result);
            }
        });
    }

    function load_product_type()
    {

        $("#product_type_id").html('');
        $.post("../../libraries/ajax_load_file/load_product_type.php", {
            crop_id: $("#crop_id").val()
        }, function(result){
            if(result){
                $("#product_type_id").append(result);
            }
        })
    }

    function load_pack_size_fnc()
    {
        $("#pack_size").html('');
        $.post("../../libraries/ajax_load_file/load_pack_size.php",{
            crop_id:$("#crop_id").val(),
            product_type_id:$("#product_type_id").val(),
            varriety_id:$("#varriety_id").val()
        }, function(result){
            if (result){
                $("#pack_size").append(result);
            }
        });
    }
</script>