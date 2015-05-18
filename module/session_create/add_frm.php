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
                        <label class="control-label" for="session_name">
                            Season Name
                        </label>
                        <div class="controls">
                            <input class="span9" type="text" name="session_name" id="session_name" placeholder="Session Name" validate="Require">
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="designation_title_bn">
                            Season Period
                        </label>
                        <div class="controls">
                            <div class="input-append">
                                <input type="text" name="from_date" id="from_date" class="span9" placeholder="From Date" readonly="" />
                                <span class="add-on" id="calcbtn_from_date">
                                    <i class="icon-calendar"></i>
                                </span>
                            </div>
                            <div class="input-append">
                                <input type="text" name="to_date" id="to_date" class="span9" placeholder="To Date" readonly="" />
                                <span class="add-on" id="calcbtn_to_date">
                                    <i class="icon-calendar"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="crop_id">
                            Crop
                        </label>
                        <div class="controls">
                            <select id="crop_id" name="crop_id" class="span5" placeholder="Crop" validate="Require" onchange="load_product_type()">
                                <?php
                                include_once '../../libraries/ajax_load_file/load_crop.php';
                                ?>
                            </select>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="product_type_id">
                            Select Product Type
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
                        <label class="control-label" for="varriety_id">
                            Select Variety
                        </label>
                        <div class="controls">
                            <select id="varriety_id" name="varriety_id" class="span5" placeholder="Select Variety" validate="Require">
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
                                    <th style="width:10%">
                                        Select Status 
                                    </th>
                                    <th style="width:10%">
                                        Select Color
                                    </th>
                                    <th style="width:10%">
                                        From Date
                                    </th>
                                    <th style="width:10%">
                                        To Date
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
    
    //////////////////////////////////////// Table Row add delete function ///////////////////////////////
    var ExId=0;
    function RowIncrement() 
    {
        var table = document.getElementById('TaskTable');

        var rowCount = table.rows.length;
        //alert(rowCount);
        var row = table.insertRow(rowCount);
        row.id="T"+ExId;
        row.className="tableHover";
        //alert(row.id);
        var cell1 = row.insertCell(0);
        cell1.innerHTML = "<select id='product_status"+ExId+"' name='product_status[]' class='span12' placeholder='Select Status' validate='Require'>\n\
        <option value=''>Select</option>\n\
        <option value='Before Start'>Before Start</option>\n\
        <option value='Peak'>Peak</option>\n\
        <option value='Off Peak'>Off Peak</option>\n\
        <option value='Finish'>Finish</option>\n\
</select>\n\
    <input type='hidden' id='id[]' name='id[]' value=''/>";
            
            cell1 = row.insertCell(1);
            cell1.innerHTML = "<select id='session_color"+ExId+"' name='session_color[]' class='span12' placeholder='Select Issue' validate='Require'>\n\
                                        <option value=''>Select</option>\n\
                                    <option value='Yellow'>Yellow</option>\n\
                                    <option value='Green'>Green</option>\n\
                                    <option value='Purple'>Purple</option>\n\
                                    <option value='Red'>Red</option>\n\
                                    <option value='Magenta'>Magenta</option>\n\
                                    <option value='Blue'>Blue</option>\n\
                                    <option value='Pink'>Pink</option>\n\
                                </select>";
            cell1.style.cursor="default";
        
            cell1 = row.insertCell(2);
            cell1.innerHTML = "<div class='input-append'>\n\
        <input type='text' name='session_from_date[]' id='session_from_date"+ExId+"' class='span12' placeholder='From Date' readonly='' />\n\
<span class='add-on' id='calcbtn_session_from_date"+ExId+"'>\n\
<i class='icon-calendar'></i>\n\
</span></div>";
        cell1.style.cursor="default";
        
        cell1 = row.insertCell(3);
        cell1.innerHTML = "<div class='input-append'>\n\
        <input type='text' name='session_to_date[]' id='session_to_date"+ExId+"' class='span12' placeholder='To Date' readonly=''/>\n\
<span class='add-on' id='calcbtn_session_to_date"+ExId+"'>\n\
<i class='icon-calendar'></i>\n\
</span></div>";
        cell1.style.cursor="default";
        
        cell1 = row.insertCell(4);
        cell1.innerHTML = "<a class='btn btn-warning2' data-original-title='' onclick=\"RowDecrement('TaskTable','T"+ExId+"')\">\n\
    <i class='icon-white icon-trash'> </i>";
            cell1.style.cursor="default";
            document.getElementById("product_status"+ExId).focus();
            
            var cal = Calendar.setup({
                onSelect: function(cal) { cal.hide() },
                fdow :0,
                minuteStep:1
            });
            cal.manageFields("calcbtn_session_from_date"+ExId, "session_from_date"+ExId, "%d-%m-%Y");
            cal.manageFields("calcbtn_session_to_date"+ExId, "session_to_date"+ExId, "%d-%m-%Y");
            
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
    cal.manageFields("calcbtn_from_date", "from_date", "%d-%m-%Y");
    cal.manageFields("calcbtn_to_date", "to_date", "%d-%m-%Y");
    
</script>