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
$ttotal_price = "";
$sql = "SELECT
            $tbl" . "product_purchase_order_invoice.id,
            $tbl" . "product_purchase_order_invoice.invoice_id,
            $tbl" . "product_purchase_order_invoice.year_id,
            $tbl" . "product_purchase_order_invoice.warehouse_id,
            $tbl" . "product_purchase_order_invoice.purchase_order_id,
            $tbl" . "product_purchase_order_invoice.invoice_date,
            $tbl" . "product_purchase_order_invoice.zone_id,
            $tbl" . "product_purchase_order_invoice.territory_id,
            $tbl" . "product_purchase_order_invoice.zilla_id,
            $tbl" . "product_purchase_order_invoice.distributor_id,
            $tbl" . "product_purchase_order_invoice.crop_id,
            $tbl" . "product_purchase_order_invoice.product_type_id,
            $tbl" . "product_purchase_order_invoice.varriety_id,
            $tbl" . "product_purchase_order_invoice.pack_size,
            $tbl" . "product_purchase_order_invoice.price,
            $tbl" . "product_purchase_order_invoice.quantity,
            $tbl" . "product_purchase_order_invoice.approved_quantity,
            $tbl" . "product_purchase_order_invoice.total_price,
            $tbl" . "product_purchase_order_invoice.`status`,
            $tbl" . "product_pack_size.pack_size_name
        FROM `$tbl" . "product_purchase_order_invoice`
            left join $tbl" . "product_pack_size on $tbl" . "product_pack_size.pack_size_id=$tbl" . "product_purchase_order_invoice.pack_size
        WHERE $tbl" . "product_purchase_order_invoice.invoice_id='" . $_POST['rowID'] . "' AND 
        $tbl" . "product_purchase_order_invoice.del_status='0' AND 
        $tbl" . "product_purchase_order_invoice.approved_quantity!='0'
";
if ($db->open()) {
    $result = $db->query($sql);
    while ($row = $db->fetchAssoc($result))
    {
        $elm_id[] = $row['id'];
        $invoice_id = $row['invoice_id'];
        $purchase_order_id = $row['purchase_order_id'];
        $year_id = $row['year_id'];
        $warehouse_id = $row['warehouse_id'];
        $zilla_id = $row['zilla_id'];
        $status = $row['status'];
        $invoice_date = $row['invoice_date'];
        $zone_id = $row['zone_id'];
        $territory_id = $row['territory_id'];
        $distributor_id = $row['distributor_id'];
        $crop_id[] = $row['crop_id'];
        $product_type_id[] = $row['product_type_id'];
        $varriety_id[] = $row['varriety_id'];
        $pack_size[] = $row['pack_size'];
        $price[] = $row['price'];
        $quantity[] = $row['quantity'];
        $approved_quantity[] = $row['approved_quantity'];
        $total_price[] = $row['total_price'];
        $ttotal_price = $ttotal_price + $row['total_price'];
        $pack_size_name[] = $row['pack_size_name'];
    }
}
//echo count($elm_id);
if ($status != "Pending")
{
    ?>
    <script>
        send_status();
    </script>
    <?php
}
else
{
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
                            <label class="control-label" for="invoice_date">
                                Date of invoice
                            </label>
                            <div class="controls">
                                <div class="input-append">
                                    <input type="text" name="challan_date" id="challan_date" class="span9" placeholder="Date of invoice" value="<?php echo $db->date_formate($invoice_date) ?>"  />
                                    <span class="add-on" id="calcbtn_challan_date">
                                        <i class="icon-calendar"></i>
                                    </span>
                                    <input type='hidden' id='purchase_order_id' name='purchase_order_id' value='<?php echo $purchase_order_id; ?>'/>
                                    <input type='hidden' id='invoice_id' name='invoice_id' value='<?php echo $invoice_id; ?>'/>
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
                            <label class="control-label" for="">
                                District
                            </label>
                            <div class="controls">
                                <select id="zilla_id" name="zilla_id" class="span5" placeholder="" validate="Require" onchange="load_distributor_fnc()">
                                    <?php
                                    $db_zilla=new Database();
                                    $db_zilla->get_zone_assign_district($zilla_id,$zilla_id, $zone_id, $territory_id);
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
                            <?php
                            $color = "";
                            $amount = "";
                            $blnc = $db->single_data($tbl . "distributor_balance", "balance_amount", "distributor_id", $distributor_id);
                            $desblnc = $blnc['balance_amount'];
                            if ($desblnc == "")
                            {
                                $amount = "0.00";
                            }
                            else
                            {
                                $amount = $desblnc;
                            }
                            if ($desblnc < $ttotal_price)
                            {
                                $color = "label-important";
                            }
                            else
                            {
                                $color = "label-success";
                            }
                            ?> 
                            <span class="label label <?php echo $color ?>" style="cursor: pointer; float: right"> 
                                Credit Limit: <?php echo $amount ?>
                            </span> 
                            <span class="label label label-info" style="cursor: pointer; float: right"> 
                                Invoice No.: <?php echo $invoice_id; ?> 
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
                                    //                                    echo "<script>
                                    //                                            load_current_stock_fnc('$crop_id[$i]','$product_type_id[$i]','$varriety_id[$i]','$pack_size[$i]','$i','$elm_id[$i]')
                                    //                                            </script>";
                                    $tprice = $tprice + $price[$i];
                                    $tquantity = $tquantity + $quantity[$i];
                                    ?>
                                    <tr class='<?php echo $rowcolor; ?>' id="tr_elm_id<?php echo $i; ?>">
                                        <td>
                                            <select id='crop_id_<?php echo $i; ?>' name='crop_id[]' class='span12' placeholder='Crop' onchange='load_product_type_("<?php echo $i; ?>")'>
                                                <?php
                                                //echo "<option value=''>Select</option>";
                                                //$sql_uesr_group = "select crop_id as fieldkey, crop_name as fieldtext from $tbl" . "crop_info where status='Active' AND crop_id='$crop_id[$i]' ORDER BY $tbl" . "crop_info.order_crop";
                                                //echo $db->SelectList($sql_uesr_group, $crop_id[$i]);
                                                ?>
                                                <?php
                                                $db_crop=new Database();
                                                $db_crop->get_crop_warehouse($crop_id[$i],$crop_id[$i],$warehouse_id, $year_id);
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
                                            <select id='varriety_id_<?php echo $i; ?>' name='varriety_id[]' class='span12' placeholder='Zone' onchange='load_pack_size_fnc_("<?php echo $i; ?>")'>
                                                <?php
//                                                echo "<option value=''>Select</option>";
                                                $sql_uesr_group = "select varriety_id as fieldkey, varriety_name as fieldtext from $tbl" . "varriety_info where status='Active' AND crop_id='$crop_id[$i]' AND product_type_id='$product_type_id[$i]' AND varriety_id='$varriety_id[$i]'";
                                                echo $db->SelectList($sql_uesr_group, $varriety_id[$i]);
                                                ?>
                                            </select>
                                        </td>
                                        <td>
                                            <select  id='pack_size_<?php echo $i; ?>' name='pack_size[]' class='span12' placeholder='Zone' onchange='load_product_price_fnc_("<?php echo $i; ?>")'>\n\
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
                                            <input type='text' name='quantity[]' id='quantity_<?php echo $i; ?>' class='span12' value='<?php echo $approved_quantity[$i]; ?>' readonly="" />
                                        </td>
        <!--                                        <td>
                                            <input type='text' name='approved_quantity[]' id='approved_quantity_<?php // echo $i;                  ?>' class='span12' <?php // echo $bgclr;                       ?> value='<?php // echo $approved_quantity[$i];                  ?>' onblur='load_product_total_price_("<?php // echo $i;                  ?>"); sum_value()' />
                                        </td>-->
<!--                                        <td>
                                            <input readonly="" type='text' name='current_stock[]' id='current_stock_<?php // echo $i; ?>' class='span12' value='0' />
                                            <input type='hidden' name='pack_size_name[]' maxlength='50' id='pack_size_name_<?php // echo $i; ?>' class='span12' value='<?php // echo $pack_size_name[$i]; ?>' />
                                        </td>-->
                                        <td>
                                            <input type='text' name='total_price[]' id='total_price_<?php echo $i; ?>' class='span12' readonly="" value='<?php echo $total_price[$i]; ?>' />
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                                <tfoot>
                                <td colspan="4"></td>
                                <td>
                                    <input type='text' name='tprice[]' class='span12' value='<?php echo number_format($tprice, 2); ?>' readonly="" />
                                </td>
                                <td>
                                    <input type='text' name='tquantity[]' class='span12' value='<?php echo number_format($tquantity, 2); ?>' readonly="" />
                                </td>
    <!--                                <td>
                                    <input type='text' name='total_approve_qunty[]' id='total_approve_qunty' class='span12' value='<?php // echo $tquantity;                  ?>' readonly="" />
                                </td>-->
<!--                                <td>
                                    <input type='text' name='total_current_stock[]' id='total_current_stock' class='span12' value='' readonly="" />
                                </td>-->
                                <td>
                                    <input type='text' name='ground_total_price[]' id='ground_total_price' class='span12' value='<?php echo number_format($ttotal_price, 2); ?>' readonly="" />
                                    <input type='hidden' name='distributor_balance[]' id='distributor_balance' class='span12' value='<?php echo $amount; ?>' readonly="" />
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
<!--                                        <th style="width:10%">
                                            Stock in(kg)
                                        </th>-->
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
                                                $tbl" . "product_purchase_order_bonus.pack_size,
                                                (select Sum(($tbl" . "product_pack_size.pack_size_name*$tbl" . "product_stock.current_stock_qunatity)/1000) 
                                                FROM $tbl" . "product_stock 
                                                WHERE
                                                $tbl" . "product_stock.crop_id = $tbl" . "product_purchase_order_bonus.crop_id AND $tbl" . "product_stock.product_type_id = $tbl" . "product_purchase_order_bonus.product_type_id AND $tbl" . "product_stock.varriety_id = $tbl" . "product_purchase_order_bonus.varriety_id AND $tbl" . "product_stock.pack_size = $tbl" . "product_purchase_order_bonus.pack_size
                                                ) as current_stock_qunatity
                                            FROM
                                                $tbl" . "product_purchase_order_bonus
                                                LEFT JOIN $tbl" . "crop_info ON $tbl" . "crop_info.crop_id = $tbl" . "product_purchase_order_bonus.crop_id
                                                LEFT JOIN $tbl" . "product_type ON $tbl" . "product_type.product_type_id = $tbl" . "product_purchase_order_bonus.product_type_id
                                                LEFT JOIN $tbl" . "varriety_info ON $tbl" . "varriety_info.varriety_id = $tbl" . "product_purchase_order_bonus.varriety_id
                                                LEFT JOIN $tbl" . "product_pack_size ON $tbl" . "product_pack_size.pack_size_id = $tbl" . "product_purchase_order_bonus.pack_size
                                                LEFT JOIN $tbl" . "product_stock ON $tbl" . "product_stock.crop_id = $tbl" . "product_purchase_order_bonus.crop_id AND $tbl" . "product_stock.product_type_id = $tbl" . "product_purchase_order_bonus.product_type_id AND $tbl" . "product_stock.varriety_id = $tbl" . "product_purchase_order_bonus.varriety_id AND $tbl" . "product_stock.pack_size = $tbl" . "product_purchase_order_bonus.pack_size AND $tbl"."product_stock.warehouse_id = $tbl"."product_purchase_order_bonus.warehouse_id
                                            WHERE
                                                $tbl" . "product_purchase_order_bonus.invoice_id='" . $_POST['rowID'] . "' AND 
                                                $tbl" . "product_purchase_order_bonus.del_status='0' AND $tbl"."product_purchase_order_bonus.warehouse_id='$warehouse_id'
                                    ";
                                    if ($dbbonus->open()) {
                                        $i = 0;
                                        $resultbonus = $dbbonus->query($sqlbonus);
                                        while ($rowbonus = $dbbonus->fetchAssoc($resultbonus)) {
//                                            echo "<script>
//                                            load_current_stock_bonus_fnc('$rowbonus[crop_id]','$rowbonus[product_type_id]','$rowbonus[varriety_id]','$rowbonus[pack_size]','$i','$_POST[rowID]')
//                                            </script>";
                                            ?>
                                        <input type="hidden" id="bonus_crop_id[]" name="bonus_crop_id[]" value="<?php echo $rowbonus['crop_id'] ?>" />
                                        <input type="hidden" id="bonus_product_type_id[]" name="bonus_product_type_id[]" value="<?php echo $rowbonus['product_type_id'] ?>" />
                                        <input type="hidden" id="bonus_varriety_id[]" name="bonus_varriety_id[]" value="<?php echo $rowbonus['varriety_id'] ?>" />
                                        <input type="hidden" id="bonus_pack_size[]" name="bonus_pack_size[]" value="<?php echo $rowbonus['pack_size'] ?>" />
                                        <input type="hidden" id="bonus_pack_size_name<?php echo $i; ?>" name="bonus_pack_size_name[]" value="<?php echo $rowbonus['pack_size_name'] ?>" />
                                        <input type="hidden" id="bonus_quantity[]" name="bonus_quantity[]" value="<?php echo $rowbonus['quantity'] ?>" />
                                        <tr>
                                            <td><?php echo $rowbonus['crop_name'] ?></td>
                                            <td><?php echo $rowbonus['product_type'] ?></td>
                                            <td><?php echo $rowbonus['varriety_name'] ?></td>
                                            <td><?php echo $rowbonus['pack_size_name'] ?></td>
                                            <td><?php echo $rowbonus['quantity'] ?></td>
                                            <!--<td id="td_bonus_current_stock<?php // echo $i ?>"></td>-->
                                        </tr>
                                        <?php
                                        ++$i;
                                    }
                                }
                                ?>
                                </thead>
                            </table>
                        </div>
                    </div>


                    <div class="form-horizontal no-margin">
                        <div class="widget-body">
                            <div class="control-group">
                                <label class="control-label" for="invoice_date">
                                    Date of invoice
                                </label>
                                <div class="controls">
                                    <div class="input-append">
                                        <input type="text" name="invoice_post_date" id="invoice_post_date" class="span9" placeholder="Date" value="" validate="Require" />
                                        <span class="add-on" id="calcbtn_invoice_post_date">
                                            <i class="icon-calendar"></i>
                                        </span>

                                    </div>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">
                                    Invoice No
                                </label>
                                <div class="controls">
                                    <input type="text" id="invoice_post_no" name="invoice_post_no" class="span5" placeholder="Invoice No." validate="Require">
                                    <span class="help-inline">
                                        *
                                    </span>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">
                                    Courier Name
                                </label>
                                <div class="controls">
                                    <input type="text" id="courier_name" name="courier_name" class="span5" placeholder="Courier Name." validate="Require">
                                    <span class="help-inline">
                                        *
                                    </span>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="invoice_date">
                                    Booking Date
                                </label>
                                <div class="controls">
                                    <div class="input-append">
                                        <input type="text" name="booking_date" id="booking_date" class="span9" placeholder="Booking Date" value="" validate="Require" />
                                        <span class="add-on" id="calcbtn_booking_date">
                                            <i class="icon-calendar"></i>
                                        </span>

                                    </div>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">
                                    Courier Trac. No.
                                </label>
                                <div class="controls">
                                    <input type="text" id="courier_trac_no" name="courier_trac_no" class="span5" placeholder="Courier Trac No.." validate="Require">
                                    <span class="help-inline">
                                        *
                                    </span>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">
                                    Remarks
                                </label>
                                <div class="controls">
                                    <textarea id="remarks" name="remarks" class="span5" placeholder="Remarks" ></textarea>
                                    <span class="help-inline">

                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>



                </div>
            </div>
        </div>
    </div>
    <script>
                                                                                                                                                                
        var cal = Calendar.setup({
            onSelect: function(cal) { cal.hide() },
            fdow :0,
            minuteStep:1
        });
        cal.manageFields("calcbtn_challan_date", "challan_date", "%d-%m-%Y");
        cal.manageFields("calcbtn_invoice_post_date", "invoice_post_date", "%d-%m-%Y");
        cal.manageFields("calcbtn_booking_date", "booking_date", "%d-%m-%Y");

    </script>
    <?php
}
?>
