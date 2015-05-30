<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$tbl = _DB_PREFIX;
$sql = "SELECT
            $tbl" . "product_purchase_order_request.id,
            $tbl" . "product_purchase_order_request.purchase_order_id,
            $tbl" . "product_purchase_order_request.purchase_order_date,
            $tbl" . "product_purchase_order_request.zone_id,
            $tbl" . "product_purchase_order_request.territory_id,
            $tbl" . "product_purchase_order_request.distributor_id,
            $tbl" . "product_purchase_order_request.crop_id,
            $tbl" . "product_purchase_order_request.product_type_id,
            $tbl" . "product_purchase_order_request.varriety_id,
            $tbl" . "product_purchase_order_request.pack_size,
            $tbl" . "product_purchase_order_request.price,
            $tbl" . "product_purchase_order_request.quantity,
            $tbl" . "product_purchase_order_request.approved_quantity,
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
        $elm_id = $row['id'];
        $status = $row['status'];
        $purchase_order_id = $row['purchase_order_id'];
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
        $approved_quantity[] = $row['approved_quantity'];
        $total_price[] = $row['total_price'];
    }
}
if ($status != "Approved") {
    ?>
    <script>
        send_status_approved();
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
                                    <input disabled="" type="text" name="purchase_order_date" id="purchase_order_date" class="span9" placeholder="Date of PO" value="<?php echo $db->date_formate($purchase_order_date) ?>"  />
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
                                Customer
                            </label>
                            <div class="controls">
                                <select disabled="" id="distributor_id" name="distributor_id" class="span5" placeholder="Customer" validate="Require">
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
                                        <th style="width:10%">
                                             Variety
                                        </th>
                                        <th style="width:5%">
                                            Pack Size(gm)
                                        </th>
                                        <th style="width:10%">
                                            Price / Pack
                                        </th>
                                        <th style="width:10%">
                                            Qty(pieces)
                                        </th>
                                        <th style="width:10%">
                                            Ap. Qty(pieces)
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
                                                $sql_uesr_group = "select crop_id as fieldkey, crop_name as fieldtext from $tbl" . "crop_info where status='Active' ORDER BY $tbl" . "crop_info.order_crop";
                                                echo $db->SelectList($sql_uesr_group, $crop_id[$i]);
                                                ?>
                                            </select>
                                            <input type='hidden' id='id[]' name='id[]' value='<?php echo $purchase_order_id[$i]; ?>'/>
                                        </td>
                                        <td>
                                            <select disabled="" id='product_type_id_<?php echo $i; ?>' name='product_type_id[]' class='span12' placeholder='Crop' onchange='load_varriety_fnc_("<?php echo $i; ?>")'>
                                                <?php
                                                echo "<option value=''>Select</option>";
                                                $sql_uesr_group = "select product_type_id as fieldkey, product_type as fieldtext from $tbl" . "product_type where status='Active' AND crop_id='$crop_id[$i]'";
                                                echo $db->SelectList($sql_uesr_group, $product_type_id[$i]);
                                                ?>
                                            </select>
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
                                            <input disabled="" type='text' name='price[]' maxlength='50' id='price_<?php echo $i; ?>' value="<?php echo $price[$i]; ?>" class='span12' readonly="" />
                                        </td>
                                        <td>
                                            <input disabled="" type='text' name='quantity[]' maxlength='50' id='quantity_<?php echo $i; ?>' class='span12' value='<?php echo $quantity[$i]; ?>' onblur='load_product_total_price_("<?php echo $i; ?>")' />
                                        </td>
                                        <td>
                                            <input disabled="" type='text' name='approved_quantity[]' maxlength='50' id='approved_quantity_<?php echo $i; ?>' class='span12' value='<?php echo $approved_quantity[$i]; ?>'  />
                                        </td>
                                        <td>
                                            <input disabled="" type='text' name='total_price[]' maxlength='50' id='total_price_<?php echo $i; ?>' class='span12' readonly="" value='<?php echo $total_price[$i]; ?>' />
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}
?>