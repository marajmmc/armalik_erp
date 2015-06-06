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
$dbbonus = new Database();
$tbl = _DB_PREFIX;
$sql = "SELECT
            $tbl" . "product_purchase_order_request.id,
            $tbl" . "product_purchase_order_request.purchase_order_id,
            $tbl" . "product_purchase_order_request.purchase_order_date,
            $tbl" . "product_purchase_order_request.year_id,
            $tbl" . "product_purchase_order_request.zilla_id,
            $tbl" . "product_purchase_order_request.warehouse_id,
            $tbl" . "product_purchase_order_request.zone_id,
            $tbl" . "product_purchase_order_request.territory_id,
            $tbl" . "product_purchase_order_request.distributor_id,
            $tbl" . "product_purchase_order_request.crop_id,
            $tbl" . "product_purchase_order_request.product_type_id,
            $tbl" . "product_purchase_order_request.varriety_id,
            $tbl" . "product_purchase_order_request.pack_size,
            $tbl" . "product_purchase_order_request.price,
            $tbl" . "product_purchase_order_request.quantity,
            $tbl" . "product_purchase_order_request.total_price,
            $tbl" . "product_purchase_order_request.read_status,
            $tbl" . "product_purchase_order_request.`status`,
            $tbl" . "product_purchase_order_request.del_status,
            $tbl" . "product_purchase_order_request.entry_by,
            $tbl" . "product_purchase_order_request.entry_date
        FROM `$tbl" . "product_purchase_order_request`
        WHERE $tbl" . "product_purchase_order_request.purchase_order_id='" . $_POST['rowID'] . "' AND $tbl" . "product_purchase_order_request.del_status='0'";
if ($db->open()) {
    $result = $db->query($sql);
    while ($row = $db->fetchAssoc($result)) {
        $elm_id[] = $row['id'];
        $warehouse_id = $row['warehouse_id'];
        $year_id = $row['year_id'];
        $zilla_id = $row['zilla_id'];
        $purchase_order_id = $row['purchase_order_id'];
        $status = $row['status'];
        $purchase_order_date = $row['purchase_order_date'];
        $zone_id = $row['zone_id'];
        $territory_id = $row['territory_id'];
        $distributor_id = $row['distributor_id'];
        $crop_id[] = $row['crop_id'];
        $product_type_id[] = $row['product_type_id'];
        $varriety_id[] = $row['varriety_id'];
        $pack_size[] = $row['pack_size'];
        $price[] = $row['price'];
        $quantity[] = $row['quantity'];
        $total_price[] = $row['total_price'];
    }
}
if ($status != "Edit_Ready") {
    ?>
    <script>
        send_status();
    </script>
    <?php
} else {
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
                                    <input type="text" name="purchase_order_date" id="purchase_order_date" class="span9" placeholder="Date of PO" value="<?php echo $db->date_formate($purchase_order_date) ?>"  />
                                    <span class="add-on" id="calcbtn_purchase_order_date">
                                        <i class="icon-calendar"></i>
                                    </span>
                                    <input type='hidden' id='purchase_order_id' name='purchase_order_id' value='<?php echo $purchase_order_id; ?>'/>
                                </div>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">
                                Year
                            </label>
                            <div class="controls">
                                <select id="year_id" name="year_id" class="span5" validate="Require">
                                    <?php
                                    $db_fiscal_year=new Database();
                                    $db_fiscal_year->get_fiscal_year($year_id);
                                    ?>
                                </select>
                            <span class="help-inline">
                                *
                            </span>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="zone_id">
                                Zone
                            </label>
                            <div class="controls">
                                <select id="zone_id" name="zone_id" class="span5" placeholder="Zone" onchange="load_territory_fnc()" validate="Require">
                                    <?php
                                    $sql_uesr_group = "select zone_id as fieldkey, zone_name as fieldtext from $tbl" . "zone_info WHERE status='Active' AND del_status='0' AND zone_id='$zone_id' " . $db->get_zone_access($tbl . "zone_info") . " ";
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
                                    <?php
                                    echo $sql_uesr_group = "select territory_id as fieldkey, territory_name as fieldtext from $tbl" . "territory_info where status='Active' AND del_status='0' AND territory_id='$territory_id' ";
                                    echo $db->SelectList($sql_uesr_group);
                                    ?>
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
                                    <?php
                                    $db_zilla=new Database();
                                    $db_zilla->get_zone_assign_district($zilla_id,'', $zone_id, $territory_id);
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
                                    <?php
                                    echo $sql_uesr_group = "select distributor_id as fieldkey, CONCAT_WS(' - ', $tbl" . "distributor_info.customer_code, $tbl" . "distributor_info.distributor_name) as fieldtext from $tbl" . "distributor_info where status='Active' AND del_status='0' AND distributor_id='$distributor_id'";
                                    echo $db->SelectList($sql_uesr_group);
                                    ?>
                                </select>
                                <span class="help-inline">
                                    *
                                </span>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="zone_id">
                                Warehouse
                            </label>
                            <div class="controls">
                                <select id="warehouse_id" name="warehouse_id" class="span5" placeholder="Zone" validate="Require">
                                    <?php
//                                    echo "<option value=''>Select</option>";
                                    $sql_uesr_group = "select warehouse_id as fieldkey, warehouse_name as fieldtext from $tbl" . "warehouse_info where status='Active' AND warehouse_id='$warehouse_id'";
                                    echo $db->SelectList($sql_uesr_group, $warehouse_id);
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
                                <?php
                                $rowcount = count($crop_id);
                                for ($i = 0; $i < $rowcount; $i++)
                                {
                                    if ($i % 2 == 0)
                                    {
                                        $rowcolor = "gradeC";
                                    }
                                    else
                                    {
                                        $rowcolor = "gradeA success";
                                    }
                                    ?>
                                    <tr class='<?php echo $rowcolor; ?>' id="tr_elm_id<?php echo $i; ?>">
                                        <td>
                                            <select id='crop_id_<?php echo $i; ?>' name='crop_id[]' class='span12' placeholder='Crop' onchange='load_product_type_("<?php echo $i; ?>")' validate='Require'>
                                                <?php
                                                $db_crop=new Database();
                                                $db_crop->get_crop_warehouse($crop_id[$i],'',$warehouse_id);
                                                ?>
                                            </select>
                                            <input type='hidden' id='id[]' name='id[]' value='<?php echo $elm_id[$i]; ?>'/>
                                        </td>
                                        <td>
                                            <select id='product_type_id_<?php echo $i; ?>' name='product_type_id[]' class='span12' placeholder='Crop' onchange='load_varriety_fnc_("<?php echo $i; ?>")' validate='Require'>
                                                <?php
                                                echo "<option value=''>Select</option>";
                                                $sql_uesr_group = "select product_type_id as fieldkey, product_type as fieldtext from $tbl" . "product_type where status='Active' AND crop_id='$crop_id[$i]'";
                                                echo $db->SelectList($sql_uesr_group, $product_type_id[$i]);
                                                ?>
                                            </select>
                                        </td>
                                        <td>
                                            <select id='varriety_id_<?php echo $i; ?>' name='varriety_id[]' class='span12' placeholder='Zone' onchange='load_pack_size_fnc_("<?php echo $i; ?>")' validate='Require'>
                                                <?php
                                                echo "<option value=''>Select</option>";
                                                $sql_uesr_group = "select varriety_id as fieldkey, varriety_name as fieldtext from $tbl" . "varriety_info where status='Active' AND crop_id='$crop_id[$i]'";
                                                echo $db->SelectList($sql_uesr_group, $varriety_id[$i]);
                                                ?>
                                            </select>
                                        </td>
                                        <td>
                                            <select id='pack_size_<?php echo $i; ?>' name='pack_size[]' class='span12' placeholder='Zone' onchange='load_product_price_fnc_("<?php echo $i; ?>")' validate='Require'>
                                                <?php
                                                echo "<option value=''>Select</option>";
                                                $sql_uesr_group = "select pack_size_id as fieldkey, pack_size_name as fieldtext from $tbl" . "product_pack_size where status='Active' AND pack_size_id='$pack_size[$i]'";
                                                echo $db->SelectList($sql_uesr_group, $pack_size[$i]);
                                                ?>
                                            </select>
                                        </td>
                                        <td>
                                            <input type='text' name='price[]' maxlength='50' id='price_<?php echo $i; ?>' value="<?php echo $price[$i]; ?>" class='span12' readonly=""  validate='Require'/>
                                        </td>
                                        <td>
                                            <input type='text' name='quantity[]' maxlength='50' id='quantity_<?php echo $i; ?>' class='span12' value='<?php echo $quantity[$i]; ?>' onblur='load_product_total_price_("<?php echo $i; ?>")' validate='Require' />
                                        </td>
                                        <td>
                                            <input type='text' name='total_price[]' maxlength='50' id='total_price_<?php echo $i; ?>' class='span12' readonly="" value='<?php echo $total_price[$i]; ?>' validate='Require' />
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
                                <?php
                                 $sqlb = "SELECT
                                            ait_product_purchase_order_bonus.id,
                                            ait_product_purchase_order_bonus.purchase_order_id,
                                            ait_product_purchase_order_bonus.crop_id,
                                            ait_product_purchase_order_bonus.product_type_id,
                                            ait_product_purchase_order_bonus.varriety_id,
                                            ait_product_purchase_order_bonus.pack_size,
                                            ait_product_purchase_order_bonus.quantity
                                        FROM `ait_product_purchase_order_bonus`
                                        WHERE ait_product_purchase_order_bonus.status='Active' AND 
                                            ait_product_purchase_order_bonus.del_status='0' AND 
                                            ait_product_purchase_order_bonus.purchase_order_id='$purchase_order_id'";
                                if ($dbbonus->open())
                                {
                                    $result = $dbbonus->query($sqlb);
                                    while ($row = $dbbonus->fetchAssoc($result))
                                    {
                                        if ($i % 2 == 0)
                                        {
                                            $rowcolor = "gradeC";
                                        }
                                        else
                                        {
                                            $rowcolor = "gradeA success";
                                        }
                                        ?>
                                        <tr class='<?php echo $rowcolor; ?>' id="tr_elm_bonus_id<?php echo $i; ?>">
                                            <td>
                                                <select id='bonus_crop_id_<?php echo $i; ?>' name='bonus_crop_id[]' class='span12' placeholder='Crop' onchange='bonus_load_product_type_("<?php echo $i; ?>")'  validate='Require'>
                                                    <?php
                                                    $db_crop=new Database();
                                                    $db_crop->get_crop_warehouse($row['crop_id'],'',$warehouse_id);
                                                    ?>
                                                </select>
                                                <input type='hidden' id='bonus_id[]' name='bonus_id[]' value='<?php echo $row['id']; ?>'/>
                                            </td>
                                            <td>
                                                <select id='bonus_product_type_id_<?php echo $i; ?>' name='bonus_product_type_id[]' class='span12' placeholder='Crop' onchange='bonus_load_varriety_fnc_("<?php echo $i; ?>")' validate='Require'>
                                                    <?php
                                                    echo "<option value=''>Select</option>";
                                                    $sql_uesr_group = "select product_type_id as fieldkey, product_type as fieldtext from $tbl" . "product_type where status='Active' AND crop_id='$row[crop_id]'";
                                                    echo $db->SelectList($sql_uesr_group, $row['product_type_id']);
                                                    ?>
                                                </select>
                                            </td>
                                            <td>
                                                <select id='bonus_varriety_id_<?php echo $i; ?>' name='bonus_varriety_id[]' class='span12' placeholder='Zone' onchange='bonus_load_pack_size_fnc_("<?php echo $i; ?>")' validate='Require'>
                                                    <?php
                                                    echo "<option value=''>Select</option>";
                                                    $sql_uesr_group = "select varriety_id as fieldkey, varriety_name as fieldtext from $tbl" . "varriety_info where status='Active' AND crop_id='$row[crop_id]'";
                                                    echo $db->SelectList($sql_uesr_group, $row['varriety_id']);
                                                    ?>
                                                </select>
                                            </td>
                                            <td>
                                                <select id='bonus_pack_size_<?php echo $i; ?>' name='bonus_pack_size[]' class='span12' placeholder='Zone' onchange='bonus_load_product_price_fnc_("<?php echo $i; ?>")' validate='Require'>
                                                    <?php
                                                    echo "<option value=''>Select</option>";
                                                    $sql_uesr_group = "select pack_size_id as fieldkey, pack_size_name as fieldtext from $tbl" . "product_pack_size where status='Active' AND pack_size_id='$row[pack_size]'";
                                                    echo $db->SelectList($sql_uesr_group, $row['pack_size']);
                                                    ?>
                                                </select>
                                            </td>
                                            <td>
                                                <input type='text' name='bonus_quantity[]' maxlength='50' id='bonus_quantity_<?php echo $i; ?>' class='span12' value='<?php echo $row['quantity']; ?>' onblur='load_product_total_price_("<?php echo $i; ?>")' validate='Require' />
                                            </td>
                                            <td>
                                                <a class='btn btn-warning2' data-original-title='' onclick="del_bonus_product('<?php echo $i; ?>','<?php echo $row['id']; ?>')">
                                                    <i class='icon-white icon-trash'> </i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php
                                        ++$i;
                                    }
                                }
                                ?>

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
            cell1.innerHTML = "<select id='crop_id"+ExId+"' name='crop_id[]' class='span12' placeholder='Crop' onchange='load_product_type("+ExId+")' validate='Require'>\n\
            <?php
            echo "<option value=''>Select</option>";
            //$sql_uesr_group = "select crop_id as fieldkey, crop_name as fieldtext from $tbl" . "crop_info where status='Active' AND crop_id IN (select crop_id from $tbl" . "product_pricing where status='Active')";
            //echo $db->SelectList($sql_uesr_group);
            ?>\n\
            </select>\n\
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
            cell1.innerHTML = "<input type='text' name='price[]' maxlength='50' id='price"+ExId+"' class='span12' "+ElmReadonly+" validate='Require' />";
            cell1.style.cursor="default";
            cell1 = row.insertCell(5);
            cell1.innerHTML = "<input type='text' name='quantity[]' maxlength='50' id='quantity"+ExId+"' class='span12' value='0' onblur='load_product_total_price("+ExId+")' validate='Require' />";
            cell1.style.cursor="default";
            cell1 = row.insertCell(6);
            cell1.innerHTML = "<input type='text' name='total_price[]' maxlength='50' id='total_price"+ExId+"' class='span12' "+ElmReadonly+" value='0' validate='Require' />";
            cell1.style.cursor="default";
            cell1 = row.insertCell(7);
            cell1.innerHTML = "<a class='btn btn-warning2' data-original-title='' onclick=\"RowDecrement('TaskTable','T"+ExId+"')\">\n\
            <i class='icon-white icon-trash'> </i>";
            cell1.style.cursor="default";
            //document.getElementById("crop_id"+ExId).focus();
            load_crop_warehouse(ExId);
            ExId=ExId+1;
            //$("#TaskTable").tableDnD();
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
                                                                    
        //////////////////////////////////////// Table Row add delete function ///////////////////////////////
        var ExId=0;
        function RowIncrement_bonus() 
        {
            if($("#userLevel").val()=="Marketing"){
                var ElmReadonly="";
            }else{
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
            cell1.innerHTML = "<select id='bonus_crop_id"+ExId+"' name='bonus_crop_id[]' class='span12' placeholder='Crop' onchange='bonus_load_product_type("+ExId+")' validate='Require'>\n\
    <?php
    echo "<option value=''>Select</option>";
    $sql_uesr_group = "select crop_id as fieldkey, crop_name as fieldtext from $tbl" . "crop_info where status='Active' AND crop_id IN (select crop_id from $tbl" . "product_pricing where status='Active')";
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
            //document.getElementById("bonus_crop_id"+ExId).focus();
            load_crop_warehouse_bonus(ExId);
            ExId=ExId+1;
            //$("#TaskTable_bonus").tableDnD();
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
                                    
        $(document).ready(function(){
            setTimeout(function(){distributor_due_balance();},1000);
        });
                                        
    </script>
    <?php
}
?>