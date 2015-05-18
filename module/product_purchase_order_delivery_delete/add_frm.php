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
                        <label class="control-label" for="purchase_order_date">
                            Received Date
                        </label>
                        <div class="controls">
                            <div class="input-append">
                                <input type="text" name="purchase_order_date" id="purchase_order_date" class="span9" placeholder="Date of PO" value="<?php echo $db->date_formate($db->ToDayDate())?>"  />
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
                                $sql_uesr_group = "select zone_id as fieldkey, zone_name as fieldtext from $tbl" . "zone_info WHERE status='Active' AND del_status='0' ".$db->get_zone_access($tbl. "zone_info")." ";
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
                            <select id="territory_id" name="territory_id" class="span5" placeholder="Territory" onchange="load_distributor_fnc()" validate="Require">
                                <option value="">Select</option>

                            </select>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="distributor_id">
                            Distributor
                        </label>
                        <div class="controls">
                            <select id="distributor_id" name="distributor_id" class="span5" placeholder="Distributor" validate="Require">
                                <option value="">Select</option>

                            </select>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="controls controls-row">
                        <span class="label label label-info" style="cursor: pointer; float: right" onclick="RowIncrement()"> + Add More </span>
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
                                        Variety
                                    </th>
                                    <th style="width:15%">
                                        Pack Size(gm)
                                    </th>
                                    <th style="width:15%">
                                        Price / Pack
                                    </th>
                                    <th style="width:15%">
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function(){
        session_load_fnc()
    });
    
    //////////////////////////////////////// Table Row add delete function ///////////////////////////////
    var ExId=0;
    function RowIncrement() 
    {
        if($("#userLevel").val()=="Marketing"){
            var ElmReadonly="";
        }else{
            var ElmReadonly="readonly='readonly'";
        }
        var table = document.getElementById('TaskTable');

        var rowCount = table.rows.length;
        //alert(rowCount);
        var row = table.insertRow(rowCount);
        row.id="T"+ExId;
        row.className="tableHover";
        //alert(row.id);
        var cell1 = row.insertCell(0);
        cell1.innerHTML = "<select id='crop_id"+ExId+"' name='crop_id[]' class='span12' placeholder='Crop' onchange='load_varriety_fnc("+ExId+")'>\n\
<?php
echo "<option value=''>Select</option>";
$sql_uesr_group = "select crop_id as fieldkey, crop_name as fieldtext from $tbl" . "crop_info where status='Active'";
echo $db->SelectList($sql_uesr_group);
?>\n\
</select>\n\
    <input type='hidden' id='id[]' name='id[]' value=''/>";
            
            cell1 = row.insertCell(1);
            cell1.innerHTML = "<select id='varriety_id"+ExId+"' name='varriety_id[]' class='span12' placeholder='Zone' onchange='load_pack_size_fnc("+ExId+")'>\n\
    <option value=''>Select</option></select>";
        cell1.style.cursor="default";
        
            cell1 = row.insertCell(2);
            cell1.innerHTML = "<select id='pack_size"+ExId+"' name='pack_size[]' class='span12' placeholder='Zone' onchange='load_product_price_fnc("+ExId+")'>\n\
    <option value=''>Select</option></select>";
        cell1.style.cursor="default";
        
        cell1 = row.insertCell(3);
        cell1.innerHTML = "<input type='text' name='price[]' maxlength='50' id='price"+ExId+"' class='span12' "+ElmReadonly+" />";
        cell1.style.cursor="default";
        cell1 = row.insertCell(4);
        cell1.innerHTML = "<input type='text' name='quantity[]' maxlength='50' id='quantity"+ExId+"' class='span12' value='0' onblur='load_product_total_price("+ExId+")' />";
        cell1.style.cursor="default";
        cell1 = row.insertCell(5);
        cell1.innerHTML = "<input type='text' name='total_price[]' maxlength='50' id='total_price"+ExId+"' class='span12' "+ElmReadonly+" value='0' />";
        cell1.style.cursor="default";
        cell1 = row.insertCell(6);
        cell1.innerHTML = "<a class='btn btn-warning2' data-original-title='' onclick=\"RowDecrement('TaskTable','T"+ExId+"')\">\n\
    <i class='icon-white icon-trash'> </i>";
            cell1.style.cursor="default";
            document.getElementById("crop_id"+ExId).focus();
            ExId=ExId+1;
            $("#TaskTable").tableDnD();
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