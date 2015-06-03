<?php
session_start();
ob_start();
if ($_SESSION['logged'] != 'yes') {
    $_REQUEST["msg"] = "TimeoutC";
    header("location:../../index.php");
}
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
                        <label class="control-label" for="purchase_order_date">
                            Date of PO
                        </label>
                        <div class="controls">
                            <div class="input-append">
                                <input  type="text" name="purchase_order_date" id="purchase_order_date" class="span9" placeholder="Date of PO" value="<?php echo $db->date_formate($db->ToDayDate()) ?>"  />
                                <span class="add-on" id="calcbtn_purchase_order_date">
                                    <i class="icon-calendar"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="zone_id">
                            Zone
                        </label>
                        <div class="controls">
                            <select id="zone_id" name="zone_id" class="span5" placeholder="Zone" onchange="load_territory_fnc()" validate="Require">
                                <option value="">Select</option>
                                <?php
                                $sql_uesr_group = "select zone_id as fieldkey, zone_name as fieldtext from $tbl" . "zone_info WHERE status='Active' AND del_status='0' " . $db->get_zone_access($tbl . "zone_info") . " ";
                                echo $db->SelectList($sql_uesr_group);
                                ?>
                            </select>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="territory_id">
                            Territory
                        </label>
                        <div class="controls">
                            <select id="territory_id" name="territory_id" class="span5" placeholder="Territory" onchange="load_district_fnc()" validate="Require">
                                <option value="">Select</option>

                            </select>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="">
                            District
                        </label>
                        <div class="controls">
                            <select id="zilla_id" name="zilla_id" class="span5" placeholder="" validate="Require" onchange="load_distributor_fnc()">
                                <option value="">Select</option>

                            </select>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="distributor_id">
                            Customer
                        </label>
                        <div class="controls">
                            <select id="distributor_id" name="distributor_id" class="span5" placeholder="Customer" validate="Require" onchange="distributor_due_balance()">
                                <option value="">Select</option>

                            </select>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="distributor_id">
                            Warehouse
                        </label>
                        <div class="controls">
                            <select id="warehouse_id" name="warehouse_id" class="span5" placeholder="Warehouse" validate="Require" onchange="RowDecrement_All('TaskTable')">
                                <?php
                                echo "<option value=''>Select</option>";
                                $sql_uesr_group = "select warehouse_id as fieldkey, warehouse_name as fieldtext from $tbl" . "warehouse_info where status='Active'";
                                echo $db->SelectList($sql_uesr_group);
                                ?>
                            </select>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="controls controls-row">
                        <span class="label label label-info" style="cursor: pointer; float: right" onclick="RowIncrement()"> + Add More </span>
                        <span class="label label label-important" style="cursor: pointer; float: right" id="lbl_distributor_due_balance"> 
                            &nbsp;
                        </span> 
                    </div>
                </div>
                <div class="widget-body">
                    <div class="wrapper">
                        <table class="table table-condensed table-striped table-bordered table-hover no-margin" id="TaskTable">
                            <thead>
                                <tr>
                                    <th style="width:15%">
                                        Crop 
                                    </th>
                                    <th style="width:15%">
                                        Product Type
                                    </th>
                                    <th style="width:15%">
                                        Variety
                                    </th>
                                    <th style="width:10%">
                                        Pack Size(gm)
                                    </th>
                                    <th style="width:10%">
                                        Price / Pack
                                    </th>
                                    <th style="width:10%">
                                        Qty(pieces)
                                    </th>
                                    <th style="width:15%">
                                        Total Value
                                    </th>
                                    <th style="width:5%">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                        </table>
                        <input type='hidden' name='total_value' maxlength='50' id='total_value' class='span12'/>
                        <input type='hidden' name='txt_distributor_due_balance' maxlength='50' id='txt_distributor_due_balance' class='span12' />
                        <h3><u>Product Bonus Information</u></h3>
                        <div class="controls controls-row">
                            <span class="label label label-info" style="cursor: pointer; float: right" onclick="RowIncrement_bonus()"> + Add More </span>
                        </div>
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
                                    <th style="width:5%">
                                        Action
                                    </th>
                                </tr>
                            </thead>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function()
    {
        session_load_fnc()
    });
    
    //////////////////////////////////////// Table Row add delete function ///////////////////////////////
    var ExId=0;
    function RowIncrement() 
    {
        
        if($("#warehouse_id").val()=="")
        {
            reset();
            alertify.set({
                delay: 3000
            });
            alertify.error("Please select warehouse.");
            return false;
        }
        else
        {
            if($("#userLevel").val()=="Marketing")
            {
                var ElmReadonly="";
            }
            else
            {
                var ElmReadonly="readonly='readonly'";
            }
            var table = document.getElementById('TaskTable');

            var rowCount = table.rows.length;
            //alert(rowCount);
            var row = table.insertRow(rowCount);
            row.id="T"+ExId;
            row.className="tableHover";
            //        alert(row.id);
            var cell1 = row.insertCell(0);
            cell1.innerHTML = "<select id='crop_id"+ExId+"' name='crop_id[]' class='span12' placeholder='Crop' onchange='load_product_type("+ExId+")' validate='Require'>\n\
                <option value=''>Select</option></select>\n\
                <input type='hidden' id='id[]' name='id[]' value=''/>";

                cell1 = row.insertCell(1);
                cell1.innerHTML = "<select id='product_type_id"+ExId+"' name='product_type_id[]' class='span12' placeholder='Zone' onchange='load_varriety_fnc("+ExId+")' validate='Require'>\n\
                <option value=''>Select</option></select>";
                cell1.style.cursor="default";

                cell1 = row.insertCell(2);
                cell1.innerHTML = "<select id='varriety_id"+ExId+"' name='varriety_id[]' class='span12' placeholder='Zone' onchange='load_pack_size_fnc("+ExId+")' validate='Require'>\n\
                <option value=''>Select</option></select>";
                cell1.style.cursor="default";
        
                cell1 = row.insertCell(3);
                cell1.innerHTML = "<select id='pack_size"+ExId+"' name='pack_size[]' class='span12' placeholder='Zone' onchange='load_product_price_fnc("+ExId+")' validate='Require'>\n\
                <option value=''>Select</option></select>";
                cell1.style.cursor="default";
        
                cell1 = row.insertCell(4);
                cell1.innerHTML = "<input type='text' name='price[]' maxlength='50' id='price"+ExId+"' class='span12' "+ElmReadonly+"  validate='Require' />";
                cell1.style.cursor="default";

                cell1 = row.insertCell(5);
                cell1.innerHTML = "<input type='text' name='quantity[]' maxlength='50' id='quantity"+ExId+"' class='span12' value='0' onblur='load_product_total_price("+ExId+"); sum_value()' validate='Require' />";
                cell1.style.cursor="default";

                cell1 = row.insertCell(6);
                cell1.innerHTML = "<input type='text' name='total_price[]' maxlength='50' id='total_price"+ExId+"' class='span12' "+ElmReadonly+" value='0' validate='Require' />";
                cell1.style.cursor="default";

                cell1 = row.insertCell(7);
                cell1.innerHTML = "<a class='btn btn-warning2' data-original-title='' onclick=\"RowDecrement('TaskTable','T"+ExId+"')\">\n\
                <i class='icon-white icon-trash'> </i>";
                cell1.style.cursor="default";
                document.getElementById("crop_id"+ExId).focus();
                load_crop_warehouse(ExId);
                ExId=ExId+1;
                $("#TaskTable").tableDnD();
            }
        }

        //////////////////////////////////////// Table Row add delete function ///////////////////////////////
</script>
<script>
                                                                
    //////////////////////////////////////// Table Row add delete function ///////////////////////////////
    var ExId=0;
    function RowIncrement_bonus() 
    {
        if($("#warehouse_id").val()=="")
        {
            reset();
            alertify.set({
                delay: 3000
            });
            alertify.error("Please select warehouse.");
            return false;
        }
        else
        {
            if($("#userLevel").val()=="Marketing")
            {
                var ElmReadonly="";
            }
            else
            {
                var ElmReadonly="readonly='readonly'";
            }
            var table = document.getElementById('TaskTable_bonus');

            var rowCount = table.rows.length;
            //alert(rowCount);
            var row = table.insertRow(rowCount);
            row.id="T"+ExId;
            row.className="tableHover";
            //alert(row.id);
            var cell1 = row.insertCell(0);
            cell1.innerHTML = "<select id='bonus_crop_id["+ExId+"]' name='bonus_crop_id[]' class='span12' placeholder='Crop' onchange='bonus_load_product_type("+ExId+")' validate='Require'>\n\
            <?php
            echo "<option value=''>Select</option>";
            $sql_uesr_group = "select crop_id as fieldkey, crop_name as fieldtext from $tbl" . "crop_info where status='Active' AND crop_id IN (select crop_id from $tbl" . "product_pricing where status='Active')  ORDER BY $tbl" . "crop_info.order_crop";
            echo $db->SelectList($sql_uesr_group);
            ?>\n\
            </select>\n\
            <input type='hidden' id='bonus_id[]' name='bonus_id[]' value=''/>";

            cell1 = row.insertCell(1);
            cell1.innerHTML = "<select id='bonus_product_type_id"+ExId+"' name='bonus_product_type_id[]' class='span12' placeholder='Zone' onchange='bonus_load_varriety_fnc("+ExId+")' validate='Require'>\n\
            <option value=''>Select</option></select>";
            cell1.style.cursor="default";

            cell1 = row.insertCell(2);
            cell1.innerHTML = "<select id='bonus_varriety_id"+ExId+"' name='bonus_varriety_id[]' class='span12' placeholder='Zone' onchange='bonus_load_pack_size_fnc("+ExId+")' validate='Require'>\n\
            <option value=''>Select</option></select>";
            cell1.style.cursor="default";

            cell1 = row.insertCell(3);
            cell1.innerHTML = "<select id='bonus_pack_size"+ExId+"' name='bonus_pack_size[]' class='span12' placeholder='Zone' onchange='load_bonus_current_stock_fnc("+ExId+"); load_pack_size_name("+ExId+")' validate='Require'>\n\
            <option value=''>Select</option></select><input type='hidden' id='bonus_pack_size_name"+ExId+"' name='bonus_pack_size_name[]' value=''/>";
            cell1.style.cursor="default";

            cell1 = row.insertCell(4);
            cell1.innerHTML = "<input type='text' name='bonus_quantity[]' maxlength='50' id='bonus_quantity"+ExId+"' class='span12' value='0' onkeypress='return numberOnly(event)' validate='Require' />";
            cell1.style.cursor="default";

            cell1 = row.insertCell(5);
            cell1.innerHTML = "<a class='btn btn-warning2' data-original-title='' onclick=\"RowDecrement_bonus('TaskTable_bonus','T"+ExId+"')\">\n\
            <i class='icon-white icon-trash'> </i>";
            cell1.style.cursor="default";
            document.getElementById("bonus_crop_id"+ExId).focus();

            load_crop_warehouse_bonus(ExId);

            ExId=ExId+1;
            $("#TaskTable_bonus").tableDnD();
        }
    }


    function RowDecrement_All()
    {
        $("#TaskTable .tableHover").remove();
        $("#TaskTable_bonus .tableHover").remove();
    }

    function RowDecrement(tableID,id)
    {
        try {
            var table = document.getElementById(tableID);
            for(var i=1;i<table.rows.length;i++)
            {
                if(table.rows[i].id==id)
                {
                    table.deleteRow(i);
                    //                showAlert('SA-00106');
                }
            }
        }
        catch(e) {
            alert(e);
        }
    }


        function RowDecrement_bonus(tableID,id) 
        {
            try {
                var table = document.getElementById(tableID);
                for(var i=1;i<table.rows.length;i++)
                {                   
                    if(table.rows[i].id==id)
                    {
                        table.deleteRow(i);
                        //                showAlert('SA-00106');
                    }
                }
            }
            catch(e) {
                alert(e);
            }
        }
        

        //////////////////////////////////////// Table Row add delete function ///////////////////////////////
</script>
<script>
        
        var cal = Calendar.setup({
            onSelect: function(cal) { cal.hide() },
            fdow :0,
            minuteStep:1
        });
        cal.manageFields("calcbtn_purchase_order_date", "purchase_order_date", "%d-%m-%Y");
    
</script>