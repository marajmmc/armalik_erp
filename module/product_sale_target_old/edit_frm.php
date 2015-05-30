<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$tbl = _DB_PREFIX;
$total_quantity="";
$sql = "SELECT
            $tbl" . "product_sale_target.id,
            $tbl" . "product_sale_target.sale_target_id,
            $tbl" . "product_sale_target.zone_id,
            $tbl" . "product_sale_target.territory_id,
            $tbl" . "product_sale_target.distributor_id,
            $tbl" . "product_sale_target.start_date,
            $tbl" . "product_sale_target.end_date,
            $tbl" . "product_sale_target.crop_id,
            $tbl" . "product_sale_target.product_type_id,
            $tbl" . "product_sale_target.varriety_id,
            $tbl" . "product_sale_target.quantity,
            $tbl" . "product_sale_target.`status`,
            $tbl" . "product_sale_target.del_status,
            $tbl" . "product_sale_target.entry_by,
            $tbl" . "product_sale_target.entry_date
        FROM
            $tbl" . "product_sale_target
        WHERE $tbl" . "product_sale_target.sale_target_id='" . $_POST['rowID'] . "' AND $tbl" . "product_sale_target.del_status='0'";
if ($db->open()) {
    $result = $db->query($sql);
    while ($row = $db->fetchAssoc($result)) {
        $elm_id[] = $row['id'];
        $sale_target_id = $row['sale_target_id'];
        $zone_id = $row['zone_id'];
        $territory_id = $row['territory_id'];
        $distributor_id = $row['distributor_id'];
        $start_date = $row['start_date'];
//        $end_date = $row['end_date'];
        $crop_id[] = $row['crop_id'];
        $product_type_id[] = $row['product_type_id'];
        $varriety_id[] = $row['varriety_id'];
        $quantity[] = $row['quantity'];
        $total_quantity=$total_quantity+$row['quantity'];
    }
}
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
                        <label class="control-label" for="zone_id">
                            Zone
                        </label>
                        <div class="controls">
                            <select id="zone_id" name="zone_id" class="span5" placeholder="Zone" onchange="load_territory_fnc()" validate="Require">
                                <option value="">Select</option>
                                <?php
                                $sql_uesr_group = "select zone_id as fieldkey, zone_name as fieldtext from $tbl" . "zone_info WHERE status='Active' AND del_status='0'";
                                echo $db->SelectList($sql_uesr_group, $zone_id);
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
                                <?php
                                $sql_uesr_group = "select territory_id as fieldkey, territory_name as fieldtext from $tbl" . "territory_info where status='Active' AND del_status='0' AND zone_id='$zone_id' ";
                                echo $db->SelectList($sql_uesr_group, $territory_id);
                                ?>
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
                            <select id="distributor_id" name="distributor_id" class="span5" placeholder="Customer" validate="Require">
                                <option value="">Select</option>
                                <?php
                                $sql_uesr_group = "select distributor_id as fieldkey, CONCAT_WS(' - ', $tbl" . "distributor_info.customer_code, $tbl" . "distributor_info.distributor_name) as fieldtext from $tbl" . "distributor_info where status='Active' AND del_status='0' AND territory_id='$territory_id'";
                                echo $db->SelectList($sql_uesr_group, $distributor_id);
                                ?>
                            </select>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="start_date">
                            Year Start Date
                        </label>
                        <div class="controls">
                            <div class="input-append">
                                <input type="text" name="start_date" id="start_date" class="span9" placeholder="Start Date" value="<?php echo $db->DB_date_convert_year($start_date) ?>" />
                                <span class="add-on" id="calcbtn_start_date">
                                    <i class="icon-calendar"></i>
                                </span>
                            </div>
                        </div>
                    </div>
<!--                    <div class="control-group">
                        <label class="control-label" for="end_date">
                            Year End Date
                        </label>
                        <div class="controls">
                            <div class="input-append">
                                <input type="text" name="end_date" id="end_date" class="span9" placeholder="End Date" value="<?php // echo $db->date_formate($end_date) ?>" />
                                <span class="add-on" id="calcbtn_end_date">
                                    <i class="icon-calendar"></i>
                                </span>
                            </div>
                        </div>
                    </div>-->
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
                                        Product Type
                                    </th>
                                    <th style="width:15%">
                                        Variety
                                    </th>
                                    <th style="width:15%">
                                        Qty(kg)
                                    </th>
                                    <th style="width:5%">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <?php
                            $rowcount = count($elm_id);
                            for ($i = 0; $i < $rowcount; $i++) {
                                if ($i % 2 == 0) {
                                    $rowcolor = "gradeC";
                                } else {
                                    $rowcolor = "gradeA success";
                                }
                                ?>
                                <tr class='<?php echo $rowcolor; ?>' id="tr_elm_id<?php echo $i; ?>">
                                    <td>
                                        <select id='crop_id_<?php echo $i; ?>' name='crop_id[]' class='span12' placeholder='Crop' onchange='load_product_type_("<?php echo $i; ?>")'>
                                            <?php
                                            echo "<option value=''>Select</option>";
                                            $sql_uesr_group = "select crop_id as fieldkey, crop_name as fieldtext from $tbl" . "crop_info where status='Active'";
                                            echo $db->SelectList($sql_uesr_group, $crop_id[$i]);
                                            ?>
                                        </select>
                                        <input type='hidden' id='id[]' name='id[]' value='<?php echo $elm_id[$i]; ?>'/>
                                    </td>
                                    <td>
                                        <select id='product_type_id_<?php echo $i; ?>' name='product_type_id[]' class='span12' placeholder='Product type' onchange='load_varriety_fnc_("<?php echo $i; ?>")'>
                                            <?php
                                            echo "<option value=''>Select</option>";
                                            $sql_uesr_group = "select product_type_id as fieldkey, product_type as fieldtext from $tbl" . "product_type where status='Active' AND del_status='0' AND crop_id='".$crop_id[$i]."'";
                                            echo $db->SelectList($sql_uesr_group, $product_type_id[$i]);
                                            ?>
                                        </select>
                                    </td>
                                    <td>
                                        <select id='varriety_id_<?php echo $i; ?>' name='varriety_id[]' class='span12' placeholder='Zone' >
                                            <?php
                                            echo "<option value=''>Select</option>";
                                            $sql_uesr_group = "select varriety_id as fieldkey, varriety_name as fieldtext from $tbl" . "varriety_info where status='Active' AND crop_id='$crop_id[$i]' AND product_type_id='$product_type_id[$i]'";
                                            echo $db->SelectList($sql_uesr_group, $varriety_id[$i]);
                                            ?>
                                        </select>
                                    </td>
                                    <td>
                                        <input type='text' name='quantity[]' maxlength='50' id='quantity<?php echo $i; ?>' class='span12' value='<?php echo $quantity[$i]; ?>' onblur='sum_value()' />
                                        <input type='hidden' name='sale_target_id' maxlength='50' id='sale_target_id' class='span12' value='<?php echo $sale_target_id; ?>' />
                                    </td>
                                    <td>
                                        <a class='btn btn-warning2' data-original-title='' onclick="del_product('<?php echo $i; ?>','<?php echo $elm_id[$i]; ?>')">
                                            <i class='icon-white icon-trash'> </i>
                                        </a>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </table>
                        <table class="table table-condensed table-striped table-bordered table-hover no-margin">
                            <tfoot>
                                <tr>
                                    <th style="width:15%">&nbsp;</th>
                                    <th style="width:15%">&nbsp;</th>
                                    <th style="width:15%" >
                                        <label style="text-align: right;">
                                            Yearly Target
                                        </label>
                                    </th>
                                    <th style="width:15%">
                                        <input readonly="" class="span" type="text" name="total_quantity" id="total_quantity" placeholder="Total Qty" value="<?php echo $total_quantity?>" />
                                    </th>
                                    <th style="width:5%">&nbsp;</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function(){
        //        session_load_fnc()
    });
            
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
        cell1.innerHTML = "<select id='crop_id"+ExId+"' name='crop_id[]' class='span12' placeholder='Crop' onchange='load_product_type("+ExId+")'>\n\
<?php
echo "<option value=''>Select</option>";
$sql_uesr_group = "select crop_id as fieldkey, crop_name as fieldtext from $tbl" . "crop_info where status='Active'";
echo $db->SelectList($sql_uesr_group);
?>\n\
</select>\n\
    <input type='hidden' id='id[]' name='id[]' value=''/>";
                    
            cell1 = row.insertCell(1);
            cell1.innerHTML = "<select id='product_type_id"+ExId+"' name='product_type_id[]' class='span12' placeholder='Zone' onchange='load_varriety_fnc("+ExId+")'>\n\
    <option value=''>Select</option></select>";
            cell1.style.cursor="default";
            
            cell1 = row.insertCell(2);
            cell1.innerHTML = "<select id='varriety_id"+ExId+"' name='varriety_id[]' class='span12' placeholder='Zone'>\n\
    <option value=''>Select</option></select>";
            cell1.style.cursor="default";
        
            cell1 = row.insertCell(3);
            cell1.innerHTML = "<input type='text' name='quantity[]' maxlength='50' id='quantity"+ExId+"' class='span12' value='0' onblur='sum_value()' />";
            cell1.style.cursor="default";
        
            cell1 = row.insertCell(4);
            cell1.innerHTML = "<a class='btn btn-warning2' data-original-title='' onclick=\"RowDecrement('TaskTable','T"+ExId+"');sum_value()\">\n\
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
    cal.manageFields("calcbtn_start_date", "start_date", "%Y");
//    cal.manageFields("calcbtn_end_date", "end_date", "%d-%m-%Y");
</script>
