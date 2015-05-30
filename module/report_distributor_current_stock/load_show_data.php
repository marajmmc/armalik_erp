<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$tbl = _DB_PREFIX;

if ($_POST['crop_id'] != "") {
    $crop_id = "AND $tbl" . "distributor_product_stock.crop_id='" . $_POST['crop_id'] . "'";
} else {
    $crop_id = "";
}
if ($_POST['product_type_id'] != "") {
    $product_type_id = "AND $tbl" . "distributor_product_stock.product_type_id='" . $_POST['product_type_id'] . "'";
} else {
    $product_type_id = "";
}
if ($_POST['varriety_id'] != "") {
    $varriety_id = "AND $tbl" . "distributor_product_stock.varriety_id='" . $_POST['varriety_id'] . "'";
} else {
    $varriety_id = "";
}
if ($_POST['pack_size'] != "") {
    $pack_size = "AND $tbl" . "distributor_product_stock.pack_size='" . $_POST['pack_size'] . "'";
} else {
    $pack_size = "";
}
if ($_POST['zone_id'] != "") {
    $zone_id = "AND $tbl" . "distributor_info.zone_id='" . $_POST['zone_id'] . "'";
} else {
    $zone_id = "";
}
if ($_POST['territory_id'] != "") {
    $territory_id = "AND $tbl" . "distributor_info.territory_id='" . $_POST['territory_id'] . "'";
} else {
    $territory_id = "";
}
if ($_POST['distributor_id'] != "") {
    $distributor_id = "AND $tbl" . "distributor_product_stock.distributor_id='" . $_POST['distributor_id'] . "'";
} else {
    $distributor_id = "";
}
?>

<a class="btn btn-small btn-success" data-original-title="" onclick="print_rpt()" style="float: right;">
    <i class="icon-print" data-original-title="Share"> </i> Print
</a>
<div id="PrintArea" style="background-color: white;" >
    <?php include_once '../../libraries/print_page/Print_header.php'; ?>
    <table class="table table-condensed table-striped table-hover table-bordered pull-left report" id="data-table">

        <thead>
            <tr>
                <th style="width:5%">
                    Customer
                </th>
                <th style="width:5%">
                    Crop
                </th>
                <th style="width:5%">
                    Product Type
                </th>
                <th style="width:5%">
                    Variety
                </th>
                <th style="width:5%">
                    Pack Size(gm)
                </th>
                <th style="width:5%; text-align: right">
                    Purchase Qty(pieces)
                </th>
<!--                <th style="width:5%; text-align: right">
                    Issue Qty(pieces)
                </th>-->
                
                <th style="width:5%; text-align: right">
                    Sales Qty(pieces)
                </th>
                <th style="width:5%; text-align: right">
                    Loss Qty(pieces)
                </th>
                <th style="width:5%; text-align: right">
                    Short Qty(pieces)
                </th>
                <th style="width:5%; text-align: right">
                    Bonus Qty(pieces)
                </th>
                <th style="width:5%; text-align: right">
                    Current Stock
                </th>
            </tr>
        </thead>
        <tbody>
            <?php
            $PQ = '0';
            $INQ = '0';
            $CQ = '0';
            $SQ = '0';
            $LQ = '0';
            $DQ = '0';
            $BQ = '0';
            $distributor_name = '';
            $crop_name = '';
            $product_type = '';
            $sql = "SELECT
                        $tbl" . "distributor_product_stock.id,
                        CONCAT_WS(' - ', $tbl" . "distributor_info.customer_code, $tbl" . "distributor_info.distributor_name) AS distributor_name,
                        $tbl" . "zone_info.zone_name,
                        $tbl" . "territory_info.territory_name,
                        $tbl" . "crop_info.crop_name,
                        $tbl" . "product_type.product_type,
                        $tbl" . "varriety_info.varriety_name,
                        $tbl" . "product_pack_size.pack_size_name,
                        $tbl" . "distributor_product_stock.purchase_quantity,
                        $tbl" . "distributor_product_stock.inventory_quantity,
                        $tbl" . "distributor_product_stock.current_stock_qunatity,
                        $tbl" . "distributor_product_stock.sale_quantity,
                        $tbl" . "distributor_product_stock.loss_quantity,
                        $tbl" . "distributor_product_stock.damage_quantity,
                        $tbl" . "product_purchase_order_bonus.quantity
                    FROM
                        $tbl" . "distributor_product_stock
                        LEFT JOIN $tbl" . "distributor_info ON $tbl" . "distributor_info.distributor_id = $tbl" . "distributor_product_stock.distributor_id
                        LEFT JOIN $tbl" . "zone_info ON $tbl" . "zone_info.zone_id = $tbl" . "distributor_info.zone_id
                        LEFT JOIN $tbl" . "territory_info ON $tbl" . "territory_info.territory_id = $tbl" . "distributor_info.territory_id
                        LEFT JOIN $tbl" . "crop_info ON $tbl" . "crop_info.crop_id = $tbl" . "distributor_product_stock.crop_id
                        LEFT JOIN $tbl" . "product_type ON $tbl" . "product_type.product_type_id = $tbl" . "distributor_product_stock.product_type_id
                        LEFT JOIN $tbl" . "varriety_info ON $tbl" . "varriety_info.varriety_id = $tbl" . "distributor_product_stock.varriety_id
                        LEFT JOIN $tbl" . "product_pack_size ON $tbl" . "product_pack_size.pack_size_id = $tbl" . "distributor_product_stock.pack_size
                        LEFT JOIN $tbl" . "product_purchase_order_bonus ON $tbl" . "product_purchase_order_bonus.zone_id = $tbl" . "distributor_info.zone_id AND $tbl" . "product_purchase_order_bonus.territory_id = $tbl" . "distributor_info.territory_id AND $tbl" . "product_purchase_order_bonus.distributor_id = $tbl" . "distributor_product_stock.distributor_id AND $tbl" . "product_purchase_order_bonus.crop_id = $tbl" . "distributor_product_stock.crop_id AND $tbl" . "product_purchase_order_bonus.product_type_id = $tbl" . "distributor_product_stock.product_type_id AND $tbl" . "product_purchase_order_bonus.varriety_id = $tbl" . "distributor_product_stock.varriety_id AND $tbl" . "product_purchase_order_bonus.pack_size = $tbl" . "distributor_product_stock.pack_size
                    WHERE
                        $tbl" . "distributor_product_stock.del_status='0'
                        $crop_id $product_type_id $varriety_id $pack_size $zone_id $territory_id $distributor_id
                            ".$db->get_zone_access($tbl. "distributor_info")."
            ";
            if ($db->open()) {
                $result = $db->query($sql);
                $i = 1;
                while ($result_array = $db->fetchAssoc()) {
                    if ($i % 2 == 0) {
                        $rowcolor = "gradeC";
                    } else {
                        $rowcolor = "gradeA success";
                    }
                    $PQ = $PQ + $result_array['purchase_quantity'];
                    $INQ = $INQ + $result_array['inventory_quantity'];
                    $CQ = $CQ + $result_array['current_stock_qunatity'];
                    $SQ = $SQ + $result_array['sale_quantity'];
                    $LQ = $LQ + $result_array['loss_quantity'];
                    $DQ = $DQ + $result_array['damage_quantity'];
                    $BQ = $BQ + $result_array['quantity'];
                    ?>
                    <tr class="<?php echo $rowcolor; ?> pointer" id="tr_id<?php echo $i; ?>" onclick="get_rowID('<?php echo $result_array["id"] ?>', '<?php echo $i; ?>')">
                        <td>
                            <?php
                            if ($distributor_name == '') {
                                echo $result_array['distributor_name'];
                                $distributor_name = $result_array['distributor_name'];
                                //$currentDate = $preDate;
                            } else if ($distributor_name == $result_array['distributor_name']) {
                                //exit;
                                echo "&nbsp;";
                            } else {
                                echo $result_array['distributor_name'];
                                $distributor_name = $result_array['distributor_name'];
                            }
                            ?>
                        </td>
                        <td>
                            <?php
                            if ($crop_name == '') {
                                echo $result_array['crop_name'];
                                $crop_name = $result_array['crop_name'];
                                //$currentDate = $preDate;
                            } else if ($crop_name == $result_array['crop_name']) {
                                //exit;
                                echo "&nbsp;";
                            } else {
                                echo $result_array['crop_name'];
                                $crop_name = $result_array['crop_name'];
                            }
                            ?>
                        </td>
                        <td>
                            <?php
                            if ($product_type == '') {
                                echo $result_array['product_type'];
                                $product_type = $result_array['product_type'];
                                //$currentDate = $preDate;
                            } else if ($product_type == $result_array['product_type']) {
                                //exit;
                                echo "&nbsp;";
                            } else {
                                echo $result_array['product_type'];
                                $product_type = $result_array['product_type'];
                            }
                            ?>
                        </td>
                        <td><?php echo $result_array['varriety_name']; ?></td>
                        <td><?php echo $result_array['pack_size_name']; ?></td>
                        <td style="text-align: right;"><?php echo $result_array['purchase_quantity']; ?></td>
                        <!--<td style="text-align: right;"><?php // echo $result_array['inventory_quantity']; ?></td>-->
                        <td style="text-align: right;"><?php echo $result_array['sale_quantity']; ?></td>
                        <td style="text-align: right;"><?php echo $result_array['loss_quantity']; ?></td>
                        <td style="text-align: right;"><?php echo $result_array['damage_quantity']; ?></td>
                        <td style="text-align: right;"><?php if($result_array['quantity']==""){echo "0";}else{echo $result_array['quantity'];}?></td>
                        <td style="text-align: right;"><?php echo $result_array['current_stock_qunatity']; ?></td>
                    </tr>
                    <?php
                    ++$i;
                }
            }
            ?>
        <tfoot>
            <tr>
                <td colspan="5" style="text-align: right;">Total: </td>
                <td style="text-align: right;"><?php echo number_format($PQ, 2) ?></td>
                <!--<td style="text-align: right;"><?php // echo number_format($INQ, 2) ?></td>-->
                
                <td style="text-align: right;"><?php echo number_format($SQ, 2) ?></td>
                <td style="text-align: right;"><?php echo number_format($LQ, 2) ?></td>
                <td style="text-align: right;"><?php echo number_format($DQ, 2) ?></td>
                <td style="text-align: right;"><?php echo number_format($BQ, 2) ?></td>
                <td style="text-align: right;"><?php echo number_format($CQ, 2); ?></td>
            </tr>
        </tfoot>
        </tbody>
    </table>
    <?php include_once '../../libraries/print_page/Print_footer.php'; ?>
</div>