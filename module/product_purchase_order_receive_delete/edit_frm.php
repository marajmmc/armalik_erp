<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$tbl = _DB_PREFIX;
$ttotal_price = "";
$sql = "SELECT
            $tbl" . "product_purchase_order_challan.id,
            $tbl" . "product_purchase_order_challan.challan_id,
            $tbl" . "product_purchase_order_challan.invoice_id,
            $tbl" . "product_purchase_order_challan.purchase_order_id,
            $tbl" . "product_purchase_order_challan.challan_date,
            $tbl" . "product_purchase_order_challan.zone_id,
            $tbl" . "product_purchase_order_challan.territory_id,
            $tbl" . "product_purchase_order_challan.distributor_id,
            $tbl" . "product_purchase_order_challan.crop_id,
            $tbl" . "product_purchase_order_challan.product_type_id,
            $tbl" . "product_purchase_order_challan.varriety_id,
            $tbl" . "product_purchase_order_challan.pack_size,
            $tbl" . "product_purchase_order_challan.price,
            $tbl" . "product_purchase_order_challan.quantity,
            $tbl" . "product_purchase_order_challan.total_price,
            $tbl" . "product_purchase_order_challan.`status`
        FROM `$tbl" . "product_purchase_order_challan`
        WHERE $tbl" . "product_purchase_order_challan.challan_id='" . $_POST['rowID'] . "' AND $tbl" . "product_purchase_order_challan.del_status='0'
";
if ($db->open()) {
    $result = $db->query($sql);
    while ($row = $db->fetchAssoc($result)) {
        $elm_id[] = $row['id'];
        $invoice_id = $row['invoice_id'];
        $challan_id = $row['challan_id'];
        $purchase_order_id = $row['purchase_order_id'];
        $status = $row['status'];
        $challan_date = $row['challan_date'];
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
        $ttotal_price = $ttotal_price + $row['total_price'];
    }
}
if ($status == "Received") {
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
                            <label class="control-label" for="challan_received_date">
                                Received Date
                            </label>
                            <div class="controls">
                                <div class="input-append">
                                    <input type="text" name="challan_received_date" id="challan_received_date" class="span9" placeholder="Date of challan" value="<?php echo $db->date_formate($challan_date) ?>"  />
                                    <span class="add-on" id="calcbtn_challan_received_date">
                                        <i class="icon-calendar"></i>
                                    </span>
                                    <input type='hidden' id='invoice_id' name='invoice_id' value='<?php echo $invoice_id; ?>'/>
                                    <input type='hidden' id='purchase_order_id' name='purchase_order_id' value='<?php echo $purchase_order_id; ?>'/>
                                    <input type='hidden' id='challan_id' name='challan_id' value='<?php echo $challan_id; ?>'/>
                                </div>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="zone_id">
                                Zone
                            </label>
                            <div class="controls">
                                <select id="zone_id" name="zone_id" class="span5" placeholder="Zone" onchange="load_territory_fnc()" validate="Require">
                                    <?php
                                    $sql_uesr_group = "select zone_id as fieldkey, zone_name as fieldtext from $tbl" . "zone_info WHERE status='Active' AND del_status='0' AND zone_id='$zone_id' ".$db->get_zone_access($tbl. "zone_info")." ";
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
                                    <?php
                                    $sql_uesr_group = "select territory_id as fieldkey, territory_name as fieldtext from $tbl" . "territory_info where status='Active' AND del_status='0' AND territory_id='$territory_id' ";
                                    echo $db->SelectList($sql_uesr_group);
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
                                    $sql_uesr_group = "select distributor_id as fieldkey, CONCAT_WS(' - ', $tbl" . "distributor_info.customer_code, $tbl" . "distributor_info.distributor_name) as fieldtext from $tbl" . "distributor_info where status='Active' AND del_status='0' AND distributor_id='$distributor_id'";
                                    echo $db->SelectList($sql_uesr_group);
                                    ?>
                                </select>
                                <span class="help-inline">
                                    *
                                </span>
                            </div>
                        </div>
                        <div class="controls controls-row">
                            <?php
                            $color = "";
                            $amount = "";
                            $blnc = $db->single_data($tbl . "distributor_balance", "balance_amount", "distributor_id", $distributor_id);
                            $desblnc = $blnc['balance_amount'];
                            if ($desblnc == "") {
                                $amount = "0.00";
                            } else {
                                $amount = $desblnc;
                            }
                            if ($desblnc < $ttotal_price) {
                                $color = "label-important";
                            } else {
                                $color = "label-success";
                            }
                            ?> 
                            <span class="label label <?php echo $color ?>" style="cursor: pointer; float: right"> 
                                Credit Limit: <?php echo $amount ?>
                            </span> 
                            <span class="label label label-info" style="cursor: pointer; float: right"> 
                                Challan No.: <?php echo $challan_id; ?> 
                            </span>
                        </div>
                    </div>
                    <div class="widget-body">
                        <div class="wrapper">
                            <table class="table table-condensed table-striped table-bordered table-hover no-margin" id="TaskTable">
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
                                        <th style="width:5%">
                                            Pack Size(gm)
                                        </th>
                                        <th style="width:10%">
                                            Price/Pack
                                        </th>
                                        <th style="width:10%">
                                            Qty(pieces)
                                        </th>
                                        <th style="width:10%">
                                            Loss Qty(pieces)
                                        </th>
                                        <th style="width:10%">
                                            Extra Qty(pieces)
                                        </th>
                                        <th style="width:10%">
                                            Total Value
                                        </th>
                                    </tr>
                                </thead>
                                <?php
                                $tprice = "";
                                $tquantity = "";
                                $z = "0";
                                $rowcount = count($crop_id);
                                for ($i = 0; $i < $rowcount; $i++) {
                                    $z++;
                                    if ($z % 2 == 0) {
                                        $rowcolor = "gradeC";
                                    } else {
                                        $rowcolor = "gradeA success";
                                    }
                                    echo "<script>
                                            load_current_stock_fnc('$crop_id[$i]','$product_type_id[$i]','$varriety_id[$i]','$pack_size[$i]','$i')
                                            </script>";
                                    $tprice = $tprice + $price[$i];
                                    $tquantity = $tquantity + $quantity[$i];
                                    ?>
                                    <tr class='<?php echo $rowcolor; ?>' id="tr_elm_id<?php echo $i; ?>">
                                        <td>
                                            <select id='crop_id_<?php echo $i; ?>' name='crop_id[]' class='span12' placeholder='Crop' onchange='load_product_type_("<?php echo $i; ?>")' validate="Reqiure">
                                                <?php
//                                                echo "<option value=''>Select</option>";
                                                $sql_uesr_group = "select crop_id as fieldkey, crop_name as fieldtext from $tbl" . "crop_info where status='Active' AND crop_id='$crop_id[$i]' ORDER BY $tbl" . "crop_info.order_crop";
                                                echo $db->SelectList($sql_uesr_group, $crop_id[$i]);
                                                ?>
                                            </select>
                                            <input type='hidden' id='id[]' name='id[]' value='<?php echo $elm_id[$i]; ?>'/>
                                        </td>
                                        <td>
                                            <select id='product_type_id_<?php echo $i; ?>' name='product_type_id[]' class='span12' placeholder='Crop' onchange='load_varriety_fnc_("<?php echo $i; ?>")'>
                                                <?php
//                                                echo "<option value=''>Select</option>";
                                                $sql_uesr_group = "select product_type_id as fieldkey, product_type as fieldtext from $tbl" . "product_type where status='Active' AND crop_id='$crop_id[$i]' AND product_type_id='$product_type_id[$i]'";
                                                echo $db->SelectList($sql_uesr_group, $product_type_id[$i]);
                                                ?>
                                            </select>
                                        </td>
                                        <td>
                                            <select id='varriety_id_<?php echo $i; ?>' name='varriety_id[]' class='span12' placeholder='Zone' onchange='load_pack_size_fnc_("<?php echo $i; ?>")' validate="Reqiure">
                                                <?php
//                                                echo "<option value=''>Select</option>";
                                                $sql_uesr_group = "select varriety_id as fieldkey, varriety_name as fieldtext from $tbl" . "varriety_info where status='Active' AND crop_id='$crop_id[$i]' AND product_type_id='$product_type_id[$i]' AND varriety_id='$varriety_id[$i]'";
                                                echo $db->SelectList($sql_uesr_group, $varriety_id[$i]);
                                                ?>
                                            </select>
                                        </td>
                                        <td>
                                            <select  id='pack_size_<?php echo $i; ?>' name='pack_size[]' class='span12' placeholder='Zone' onchange='load_product_price_fnc_("<?php echo $i; ?>")' validate="Reqiure">
                                                <?php
//                                                echo "<option value=''>Select</option>";
                                                $sql_uesr_group = "select pack_size_id as fieldkey, pack_size_name as fieldtext from $tbl" . "product_pack_size where status='Active' AND pack_size_id='$pack_size[$i]'";
                                                echo $db->SelectList($sql_uesr_group, $pack_size[$i]);
                                                ?>
                                            </select>
                                        </td>
                                        <td>
                                            <input type='text' name='price[]' id='price_<?php echo $i; ?>' value="<?php echo $price[$i]; ?>" class='span12' readonly="" />
                                        </td>
                                        <td>
                                            <input type='text' name='quantity[]' id='quantity_<?php echo $i; ?>' class='span12' value='<?php echo $quantity[$i]; ?>' readonly="" />
                                        </td>
                                        <td>
                                            <input type='text' name='loss_quantity[]' id='loss_quantity_<?php echo $i; ?>' class='span12' value='0' onblur="sum_value()" />
                                        </td>
                                        <td>
                                            <input type='text' name='extra_quantity[]' id='extra_quantity_<?php echo $i; ?>' class='span12' value='0' onblur="sum_value_extra()" />
                                        </td>
                                        <td>
                                            <input type='text' name='total_price[]' id='total_price_<?php echo $i; ?>' class='span12' readonly="" value='<?php echo $total_price[$i]; ?>' />
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                                <tfoot>
                                <td colspan="4"><div style="text-align: right">Total</div></td>
                                <td>
                                    <input type='text' name='tprice[]' class='span12' value='<?php echo number_format($tprice, 2); ?>' readonly="" />
                                </td>
                                <td>
                                    <input type='text' name='tquantity[]' class='span12' value='<?php echo number_format($tquantity, 2); ?>' readonly="" />
                                </td>
                                <td>
                                    <input type='text' name='total_loss_quantity[]' id='total_loss_quantity' class='span12' value='' readonly="" />
                                </td>
                                <td>
                                    <input type='text' name='total_extra_quantity[]' id='total_extra_quantity' class='span12' value='' readonly="" />
                                </td>
                                <td>
                                    <input type='text' name='ground_total_price[]' id='ground_total_price' class='span12' value='<?php echo number_format($ttotal_price, 2); ?>' readonly="" />
                                </td>
                                </tfoot>
                            </table>
                            <br />
                            <span class="label label label-info"> 
                                Bonus Information
                            </span>
                            <table class="table table-condensed table-striped table-bordered table-hover no-margin" id="TaskTable">
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
                                    <?php
                                     $sqlbonus = "SELECT
                                                $tbl" . "product_purchase_order_bonus.invoice_id,
                                                $tbl" . "product_purchase_order_bonus.purchase_order_id,
                                                $tbl" . "crop_info.crop_name,
                                                $tbl" . "product_type.product_type,
                                                $tbl" . "varriety_info.varriety_name,
                                                $tbl" . "product_pack_size.pack_size_name,
                                                $tbl" . "product_purchase_order_bonus.id,
                                                $tbl" . "product_purchase_order_bonus.quantity,
                                                $tbl" . "product_purchase_order_bonus.crop_id,
                                                $tbl" . "product_purchase_order_bonus.product_type_id,
                                                $tbl" . "product_purchase_order_bonus.varriety_id,
                                                $tbl" . "product_purchase_order_bonus.pack_size
                                            FROM
                                                $tbl" . "product_purchase_order_bonus
                                                LEFT JOIN $tbl" . "crop_info ON $tbl" . "crop_info.crop_id = $tbl" . "product_purchase_order_bonus.crop_id
                                                LEFT JOIN $tbl" . "product_type ON $tbl" . "product_type.product_type_id = $tbl" . "product_purchase_order_bonus.product_type_id
                                                LEFT JOIN $tbl" . "varriety_info ON $tbl" . "varriety_info.varriety_id = $tbl" . "product_purchase_order_bonus.varriety_id
                                                LEFT JOIN $tbl" . "product_pack_size ON $tbl" . "product_pack_size.pack_size_id = $tbl" . "product_purchase_order_bonus.pack_size
                                            WHERE
                                                $tbl" . "product_purchase_order_bonus.invoice_id='" . $invoice_id . "' AND $tbl" . "product_purchase_order_bonus.del_status='0'
                                    ";
                                    if ($db->open()) {
                                        $resultbonus = $db->query($sqlbonus);
                                        while ($rowbonus = $db->fetchAssoc($resultbonus)) {
                                            if ($rowbonus['crop_name'] == "") {
                                                echo "<tr><td colspan='12' style='text-align: center; color red;'><h3>Bonus Not Available</h3></td></tr>";
                                            } else {
                                                ?>
                                            <input type="hidden" id="bonus_crop_id[]" name="bonus_crop_id[]" value="<?php echo $rowbonus['crop_id'] ?>" />
                                            <input type="hidden" id="bonus_product_type_id[]" name="bonus_product_type_id[]" value="<?php echo $rowbonus['product_type_id'] ?>" />
                                            <input type="hidden" id="bonus_varriety_id[]" name="bonus_varriety_id[]" value="<?php echo $rowbonus['varriety_id'] ?>" />
                                            <input type="hidden" id="bonus_pack_size[]" name="bonus_pack_size[]" value="<?php echo $rowbonus['pack_size'] ?>" />
                                            <input type="hidden" id="bonus_quantity[]" name="bonus_quantity[]" value="<?php echo $rowbonus['quantity'] ?>" />
                                            <tr>
                                                <td><?php echo $rowbonus['crop_name'] ?></td>
                                                <td><?php echo $rowbonus['product_type'] ?></td>
                                                <td><?php echo $rowbonus['varriety_name'] ?></td>
                                                <td><?php echo $rowbonus['pack_size_name'] ?></td>
                                                <td><?php echo $rowbonus['quantity'] ?></td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                }
                                ?>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
                                                
        //    $(document).ready(function(){
        //        setTimeout(function(){sum_value()},1000);            
        //    });
                                                
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
                cell1.innerHTML = "<input type='text' name='price[]' id='price"+ExId+"' class='span12' "+ElmReadonly+" />";
                cell1.style.cursor="default";
                cell1 = row.insertCell(4);
                cell1.innerHTML = "<input type='text' name='quantity[]' id='quantity"+ExId+"' class='span12' value='0' onblur='load_product_total_price("+ExId+")' />";
                cell1.style.cursor="default";
                cell1 = row.insertCell(5);
                cell1.innerHTML = "<input type='text' name='total_price[]' id='total_price"+ExId+"' class='span12' "+ElmReadonly+" value='0' />";
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
        cal.manageFields("calcbtn_challan_received_date", "challan_received_date", "%d-%m-%Y");
                                                                                                                    
    </script>
    <?php
}
?>