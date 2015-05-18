<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$tbl = _DB_PREFIX;
$total_purchase_price='';
$total_pprice='';
$total_quantity='';
$total_total_price='';
$sql = "SELECT
            $tbl" . "distributor_product_sale.id,
            $tbl" . "distributor_product_sale.sale_id,
            $tbl" . "distributor_product_sale.sale_date,
            $tbl" . "distributor_product_sale.zone_id,
            $tbl" . "distributor_product_sale.territory_id,
            $tbl" . "distributor_product_sale.distributor_id,
            $tbl" . "distributor_product_sale.dealer_id,
            $tbl" . "distributor_product_sale.crop_id,
            $tbl" . "distributor_product_sale.varriety_id,
            $tbl" . "distributor_product_sale.pack_size,
            $tbl" . "distributor_product_sale.purchase_price,
            $tbl" . "distributor_product_sale.price,
            $tbl" . "distributor_product_sale.quantity,
            $tbl" . "distributor_product_sale.total_price,
            $tbl" . "distributor_product_sale.`status`,
            $tbl" . "distributor_product_sale.del_status,
            $tbl" . "distributor_product_sale.entry_by,
            $tbl" . "distributor_product_sale.entry_date
        FROM
            $tbl" . "distributor_product_sale
        WHERE $tbl" . "distributor_product_sale.sale_id='" . $_POST['rowID'] . "' AND $tbl" . "distributor_product_sale.del_status='0'";
if ($db->open()) {
    $result = $db->query($sql);
    while ($row = $db->fetchAssoc($result)) {
        $elm_id = $row['id'];
        $sale_id = $row['sale_id'];
        $sale_date = $row['sale_date'];
        $zone_id = $row['zone_id'];
        $territory_id = $row['territory_id'];
        $distributor_id = $row['distributor_id'];
        $dealer_id = $row['dealer_id'];
        $crop_id[] = $row['crop_id'];
        $varriety_id[] = $row['varriety_id'];
        $pack_size[] = $row['pack_size'];
        $purchase_price[] = $row['purchase_price'];
        $price[] = $row['price'];
        $quantity[] = $row['quantity'];
        $total_price[] = $row['total_price'];
        $total_purchase_price = $total_purchase_price + $row['purchase_price'];
        $total_pprice = $total_pprice + $row['price'];
        $total_quantity = $total_quantity + $row['quantity'];
        $total_total_price = $total_total_price + $row['total_price'];
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
                        <label class="control-label" for="sale_date">
                            Sale Date
                        </label>
                        <div class="controls">
                            <div class="input-append">
                                <input disabled="" type="text" name="sale_date" id="sale_date" class="span9" placeholder="Sale Date" value="<?php echo $db->date_formate($sale_date) ?>"  />
                                <span class="add-on" id="calcbtn_sale_date">
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
                            <select disabled="" id="zone_id" name="zone_id" class="span5" placeholder="Zone" onchange="load_territory_fnc()" validate="Require">
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
                            <select disabled="" id="territory_id" name="territory_id" class="span5" placeholder="Territory" onchange="load_distributor_fnc()" validate="Require">
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
                        <label class="control-label" for="distributor_id">
                            Distributor
                        </label>
                        <div class="controls">
                            <select disabled="" id="distributor_id" name="distributor_id" class="span5" placeholder="Distributor" validate="Require">
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
                        <label class="control-label" for="distributor_id">
                            Dealer
                        </label>
                        <div class="controls">
                            <select disabled="" id="dealer_id" name="dealer_id" class="span5" placeholder="Distributor" validate="Require">
                                <?php
                                $sql_uesr_group = "select dealer_id as fieldkey, dealer_name as fieldtext from $tbl" . "dealer_info where status='Active' AND del_status='0' AND dealer_id='" . $dealer_id . "'";
                                echo $db->SelectList($sql_uesr_group);
                                ?>
                            </select>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
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
                                        Variety
                                    </th>
                                    <th style="width:5%">
                                        Pack Size(gm)
                                    </th>
                                    <th style="width:10%">
                                        P. Price
                                    </th>
                                    <th style="width:10%">
                                        S. Price
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
                            $rowcount = count($crop_id);
                            for ($i = 0; $i < $rowcount; $i++) {
                                if ($i % 2 == 0) {
                                    $rowcolor = "gradeC";
                                } else {
                                    $rowcolor = "gradeA success";
                                }
                                ?>
                                <tr class='<?php echo $rowcolor; ?>' id="tr_elm_id<?php echo $i; ?>">
                                    <td>
                                        <select disabled="" id='crop_id_<?php echo $i; ?>' name='crop_id[]' class='span12' placeholder='Crop' onchange='load_varriety_fnc_("<?php echo $i; ?>")'>
                                            <?php
                                            echo "<option value=''>Select</option>";
                                            $sql_uesr_group = "select crop_id as fieldkey, crop_name as fieldtext from $tbl" . "crop_info where status='Active'";
                                            echo $db->SelectList($sql_uesr_group, $crop_id[$i]);
                                            ?>
                                        </select>
                                        <input type='hidden' id='id[]' name='id[]' value='<?php echo $purchase_order_id[$i]; ?>'/>
                                    </td>
                                    <td>
                                        <select disabled="" id='varriety_id_<?php echo $i; ?>' name='varriety_id[]' class='span12' placeholder='Zone' onchange='load_pack_size_fnc_("<?php echo $i; ?>")'>
                                            <?php
                                            echo "<option value=''>Select</option>";
                                            $sql_uesr_group = "select varriety_id as fieldkey, varriety_name as fieldtext from $tbl" . "varriety_info where status='Active' AND crop_id='$crop_id[$i]'";
                                            echo $db->SelectList($sql_uesr_group, $varriety_id[$i]);
                                            ?>
                                        </select>
                                    </td>
                                    <td>
                                        <select disabled="" id='pack_size_<?php echo $i; ?>' name='pack_size[]' class='span12' placeholder='Zone' onchange='load_product_price_fnc_("<?php echo $i; ?>")'>\n\
                                            <?php
                                            echo "<option value=''>Select</option>";
                                            $sql_uesr_group = "select pack_size_id as fieldkey, pack_size_name as fieldtext from $tbl" . "product_pack_size where status='Active' AND pack_size_id='$pack_size[$i]'";
                                            echo $db->SelectList($sql_uesr_group, $pack_size[$i]);
                                            ?>
                                        </select>
                                    </td>
                                    <td>
                                        <input disabled="" type='text' name='purchase_price[]' maxlength='50' id='purchase_price_<?php echo $i; ?>' value="<?php echo $purchase_price[$i]; ?>" class='span12' readonly="" />
                                    </td>
                                    <td>
                                        <input disabled="" type='text' name='price[]' maxlength='50' id='price_<?php echo $i; ?>' value="<?php echo $price[$i]; ?>" class='span12' readonly="" />
                                    </td>
                                    <td>
                                        <input disabled="" type='text' name='quantity[]' maxlength='50' id='quantity_<?php echo $i; ?>' class='span12' value='<?php echo $quantity[$i]; ?>' onblur='load_product_total_price_("<?php echo $i; ?>")' />
                                    </td>
                                    <td>
                                        <input disabled="" type='text' name='total_price[]' maxlength='50' id='total_price_<?php echo $i; ?>' class='span12' readonly="" value='<?php echo $total_price[$i]; ?>' />
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
<!--                        </table>
                        <table class="table table-condensed table-striped table-bordered table-hover no-margin" id="TaskTable">
                            <tbody>-->
                                <tr>

                                    <th style="text-align: right" colspan="5"> Total</th>
<!--                                    <th style="width:10%">
                                        <input readonly="" type='text' name='ground_price' id='ground_price' value="<?php // echo $total_purchase_price; ?>" class='span12' />
                                    </th>
                                    <th style="width:10%">
                                        <input readonly="" type='text' name='ground_total_quantity' id='ground_total_quantity' value="<?php // echo $total_pprice; ?>" class='span12' />
                                    </th>-->
                                    <th>
                                        <input readonly="" type='text' name='ground_total_stock' id='ground_total_stock' value="<?php echo $total_quantity; ?>" class='span12' />
                                    </th>
                                    <th>
                                        <input readonly="" type='text' name='ground_total_price' id='ground_total_price' value="<?php echo $total_total_price; ?>" class='span12' />
                                    </th>
                                </tr>
                            <!--</tbody>-->    
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>