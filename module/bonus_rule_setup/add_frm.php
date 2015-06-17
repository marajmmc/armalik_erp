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
        <div class="widget span">
            <div class="widget-header">
                <div class="title">
                    <a id="dynamicTable"><?php echo $db->Get_Auto_TaskName() ?></a>
                    <span class="mini-title">

                    </span>
                </div>
                <span class="tools">
                    <a class="btn btn-small" data-original-title="">
                        <i class="icon-plus-sign" data-original-title="Share"> </i>
                    </a>
                </span>
            </div>
            <div class="form-horizontal no-margin">
                <div class="widget-body">
                    <div class="control-group">
                        <label class="control-label" for="designation_title_en">
                            Crop
                        </label>
                        <div class="controls">
                            <select id="crop_id" name="crop_id" class="span5" placeholder="Select Crop" validate="Require" onchange="load_product_type()">
                                <?php
                                $db_crop=new Database();
                                $db_crop->get_crop();
                                ?>
                            </select>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="designation_title_en">
                            Product
                        </label>
                        <div class="controls">
                            <select id="product_type_id" name="product_type_id" class="span5" placeholder="Product Type" onchange="load_varriety_fnc()" validate="Require">
                                <option value="">Select</option>
                            </select>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="designation_title_en">
                            Variety
                        </label>
                        <div class="controls">
                            <select id="varriety_id" name="varriety_id" class="span5" placeholder="Select Variety">
                                <option value="">Select</option>
                            </select>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="designation_title_bn">
                            From Qty(gm)
                        </label>
                        <div class="controls">
                            <input class="span5" type="text" name="from_quantity" id="from_quantity" value="" placeholder="From Qty(gm)" validate="Require" >
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="designation_title_bn">
                            To Qty(gm)
                        </label>
                        <div class="controls">
                            <input class="span5" type="text" name="to_quantity" id="to_quantity" value="" placeholder="To Qty(gm)" validate="Require" >
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                </div>
                <div class="widget-body">
                    <div class="control-group" id="div_pack_size">
                        <h3><u>Product Bonus Information</u></h3>

                        <table class="table table-condensed table-striped table-bordered table-hover no-margin" id="TaskTable_bonus">
                            <thead>
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
                                    Qty(pieces)
                                </th>
                            </tr>
                            </thead>
                            <tr>
                                <th>
                                    <select id="bonus_crop_id" name="bonus_crop_id" class="span12" placeholder="Crop" onchange="bonus_load_product_type()" validate="Require">
                                        <?php
                                        $db_crop=new Database();
                                        $db_crop->get_crop();
                                        ?>
                                    </select>
                                </th>
                                <th>
                                    <select id='bonus_product_type_id' name='bonus_product_type_id' class='span12' onchange='bonus_load_varriety_fnc()' validate='Require'>
                                        <option value=''>Select</option>
                                    </select>
                                </th>
                                <th>
                                    <select id='bonus_varriety_id' name='bonus_varriety_id' class='span12' onchange='bonus_load_pack_size_fnc()' validate='Require'>
                                        <option value=''>Select</option>
                                    </select>
                                </th>
                                <th>
                                    <select id='bonus_pack_size' name='bonus_pack_size' class='span12' validate='Require'>
                                        <option value=''>Select</option>
                                    </select>
                                </th>
                                <th>
                                    <input type='text' name='bonus_quantity' maxlength='50' id='bonus_quantity' class='span12' validate='Require' placeholder="Qty(pieces)" />
                                </th>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--<script>-->
<!---->
<!--    //////////////////////////////////////// Table Row add delete function ///////////////////////////////-->
<!--    var ExId=0;-->
<!--    function RowIncrement_bonus()-->
<!--    {-->
<!--        var table = document.getElementById('TaskTable_bonus');-->
<!---->
<!--        var rowCount = table.rows.length;-->
<!--        //alert(rowCount);-->
<!--        var row = table.insertRow(rowCount);-->
<!--        row.id="T"+ExId;-->
<!--        row.className="tableHover";-->
<!--        //alert(row.id);-->
<!--        var cell1 = row.insertCell(0);-->
<!--        cell1.innerHTML = "<select id='bonus_crop_id"+ExId+"' name='bonus_crop_id[]' class='span12' placeholder='Crop' onchange='bonus_load_product_type("+ExId+")' validate='Require'>\n\-->
<!--        --><?php
//        $db_crop=new Database();
//        $db_crop->get_crop();
//        ?><!--\n\-->
<!--        </select>\n\-->
<!--        <input type='hidden' id='bonus_id[]' name='bonus_id[]' value=''/>";-->
<!---->
<!--        cell1 = row.insertCell(1);-->
<!--        cell1.innerHTML = "<select id='bonus_product_type_id"+ExId+"' name='bonus_product_type_id[]' class='span12' placeholder='Zone' onchange='bonus_load_varriety_fnc("+ExId+")' validate='Require'>\n\-->
<!--        <option value=''>Select</option></select>";-->
<!--        cell1.style.cursor="default";-->
<!---->
<!--        cell1 = row.insertCell(2);-->
<!--        cell1.innerHTML = "<select id='bonus_varriety_id"+ExId+"' name='bonus_varriety_id[]' class='span12' placeholder='Zone' onchange='bonus_load_pack_size_fnc("+ExId+")' validate='Require'>\n\-->
<!--        <option value=''>Select</option></select>";-->
<!--        cell1.style.cursor="default";-->
<!---->
<!--        cell1 = row.insertCell(3);-->
<!--        cell1.innerHTML = "<select id='bonus_pack_size"+ExId+"' name='bonus_pack_size[]' class='span12' validate='Require'>\n\-->
<!--        <option value=''>Select</option></select><input type='hidden' id='bonus_pack_size_name"+ExId+"' name='bonus_pack_size_name[]' value=''/>";-->
<!--        cell1.style.cursor="default";-->
<!---->
<!--        cell1 = row.insertCell(4);-->
<!--        cell1.innerHTML = "<input type='text' name='bonus_quantity[]' maxlength='50' id='bonus_quantity"+ExId+"' class='span12' value='0' onkeypress='return numberOnly(event)' validate='Require' />";-->
<!--        cell1.style.cursor="default";-->
<!---->
<!--        cell1 = row.insertCell(5);-->
<!--        cell1.innerHTML = "<a class='btn btn-warning2' data-original-title='' onclick=\"RowDecrement_bonus('TaskTable_bonus','T"+ExId+"')\">\n\-->
<!--        <i class='icon-white icon-trash'> </i>";-->
<!--        cell1.style.cursor="default";-->
<!--        //document.getElementById("bonus_crop_id"+ExId).focus();-->
<!---->
<!--        load_crop_warehouse_bonus(ExId);-->
<!---->
<!--        ExId=ExId+1;-->
<!--        //$("#TaskTable_bonus").tableDnD();-->
<!--    }-->
<!---->
<!---->
<!--    function RowDecrement_bonus(tableID,id)-->
<!--    {-->
<!--        try {-->
<!--            var table = document.getElementById(tableID);-->
<!--            for(var i=1;i<table.rows.length;i++)-->
<!--            {-->
<!--                if(table.rows[i].id==id)-->
<!--                {-->
<!--                    table.deleteRow(i);-->
<!--                    //                showAlert('SA-00106');-->
<!--                }-->
<!--            }-->
<!--        }-->
<!--        catch(e) {-->
<!--            alert(e);-->
<!--        }-->
<!--    }-->
<!---->
<!---->
<!--    //////////////////////////////////////// Table Row add delete function ///////////////////////////////-->
<!--</script>-->