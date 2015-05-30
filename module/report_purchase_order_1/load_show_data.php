<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$tbl = _DB_PREFIX;

if ($_POST['crop_id'] != "") {
    $crop_id = "AND $tbl" . "product_purchase_order_invoice.crop_id='" . $_POST['crop_id'] . "'";
} else {
    $crop_id = "";
}
if ($_POST['product_type_id'] != "") {
    $product_type_id = "AND $tbl" . "product_purchase_order_invoice.product_type_id='" . $_POST['product_type_id'] . "'";
} else {
    $product_type_id = "";
}
if ($_POST['varriety_id'] != "") {
    $varriety_id = "AND $tbl" . "product_purchase_order_invoice.varriety_id='" . $_POST['varriety_id'] . "'";
} else {
    $varriety_id = "";
}
if ($_POST['pack_size'] != "") {
    $pack_size = "AND $tbl" . "product_purchase_order_invoice.pack_size='" . $_POST['pack_size'] . "'";
} else {
    $pack_size = "";
}
if ($_POST['zone_id'] != "") {
    $zone_id = "AND $tbl" . "product_purchase_order_invoice.zone_id='" . $_POST['zone_id'] . "'";
} else {
    $zone_id = "";
}
if ($_POST['territory_id'] != "") {
    $territory_id = "AND $tbl" . "product_purchase_order_invoice.territory_id='" . $_POST['territory_id'] . "'";
} else {
    $territory_id = "";
}
if ($_POST['distributor_id'] != "") {
    $distributor_id = "AND $tbl" . "product_purchase_order_invoice.distributor_id='" . $_POST['distributor_id'] . "'";
} else {
    $distributor_id = "";
}
if ($_POST['from_date'] != "" && $_POST['to_date'] != "") {
    $between = "AND $tbl" . "product_purchase_order_invoice.invoice_date BETWEEN '" . $db->date_formate($_POST['from_date']) . "' AND '" . $db->date_formate($_POST['to_date']) . "'";
} else {
    $between = "";
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
                    Date
                </th>
                <th style="width:5%">
                    PO. No
                </th>
                <th style="width:5%">
                    Zone
                </th>
                <th style="width:5%">
                    Territory
                </th>
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
                    Order Qty(pieces)
                </th>
                <th style="width:5%; text-align: right;">
                    Price/Pack
                </th>
                <th style="width:5%; text-align: right;">
                    Qty(pieces)
                </th>
                <th style="width:5%; text-align: right;">
                    Total Value
                </th>
            </tr>
        </thead>
        <tbody>
            <?php
            $price = '0';
            $qnty = '0';
            $aqnty = '0';
            $tprice = '0';
            $purchase_order_id = '';
            $invoice_date = '';
            $zone_name = '';
            $territory_name = '';
            $distributor_name = '';
            echo $sql = "SELECT
                        $tbl" . "product_purchase_order_invoice.id,
                        $tbl" . "product_purchase_order_invoice.purchase_order_id,
                        $tbl" . "product_purchase_order_invoice.invoice_date,
                        $tbl" . "product_purchase_order_invoice.price,
                        $tbl" . "product_purchase_order_invoice.quantity,
                        $tbl" . "product_purchase_order_invoice.approved_quantity,
                        $tbl" . "product_purchase_order_invoice.total_price,
                        $tbl" . "zone_info.zone_name,
                        $tbl" . "territory_info.territory_name,
                        CONCAT_WS(' - ', $tbl" . "distributor_info.customer_code, $tbl" . "distributor_info.distributor_name) AS distributor_name,
                        $tbl" . "crop_info.crop_name,
                        $tbl" . "product_type.product_type,
                        $tbl" . "varriety_info.varriety_name,
                        $tbl" . "product_pack_size.pack_size_name
                    FROM
                        $tbl" . "product_purchase_order_invoice
                        LEFT JOIN $tbl" . "zone_info ON $tbl" . "zone_info.zone_id = $tbl" . "product_purchase_order_invoice.zone_id
                        LEFT JOIN $tbl" . "territory_info ON $tbl" . "territory_info.territory_id = $tbl" . "product_purchase_order_invoice.territory_id
                        LEFT JOIN $tbl" . "distributor_info ON $tbl" . "distributor_info.distributor_id = $tbl" . "product_purchase_order_invoice.distributor_id
                        LEFT JOIN $tbl" . "crop_info ON $tbl" . "crop_info.crop_id = $tbl" . "product_purchase_order_invoice.crop_id
                        LEFT JOIN $tbl" . "product_type ON $tbl" . "product_type.product_type_id = $tbl" . "product_purchase_order_invoice.product_type_id
                        LEFT JOIN $tbl" . "varriety_info ON $tbl" . "varriety_info.varriety_id = $tbl" . "product_purchase_order_invoice.varriety_id
                        LEFT JOIN $tbl" . "product_pack_size ON $tbl" . "product_pack_size.pack_size_id = $tbl" . "product_purchase_order_invoice.pack_size
                    WHERE
                        $tbl" . "product_purchase_order_invoice.del_status='0'
                        $crop_id $product_type_id $varriety_id $pack_size $zone_id $territory_id $distributor_id $between
                             " . $db->get_zone_access($tbl . "product_purchase_order_invoice") . "
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
                    $price = $price + $result_array['price'];
                    $qnty = $qnty + $result_array['quantity'];
                    $aqnty = $aqnty + $result_array['approved_quantity'];
                    $tprice = $tprice + $result_array['total_price'];
                    ?>
                    <tr class="<?php echo $rowcolor; ?> pointer" id="tr_id<?php echo $i; ?>" onclick="get_rowID('<?php echo $result_array["id"] ?>', '<?php echo $i; ?>')">
                        <td>
                            <?php
                            if ($invoice_date == '') {
                                echo $db->date_formate($result_array['invoice_date']);
                                $invoice_date = $result_array['invoice_date'];
                                //$currentDate = $preDate;
                            } else if ($invoice_date == $result_array['invoice_date']) {
                                //exit;
                                echo "&nbsp;";
                            } else {
                                echo $db->date_formate($result_array['invoice_date']);
                                $invoice_date = $result_array['invoice_date'];
                            }
                            ?>
                        </td>
                        <td>
                            <?php
                            if ($purchase_order_id == '') {
                                echo $result_array['purchase_order_id'];
                                $purchase_order_id = $result_array['purchase_order_id'];
                                //$currentDate = $preDate;
                            } else if ($purchase_order_id == $result_array['purchase_order_id']) {
                                //exit;
                                echo "&nbsp;";
                            } else {
                                echo $result_array['purchase_order_id'];
                                $purchase_order_id = $result_array['purchase_order_id'];
                            }
                            ?>
                        </td>
                        <td>
                            <?php
                            if ($zone_name == '') {
                                echo $result_array['zone_name'];
                                $zone_name = $result_array['zone_name'];
                                //$currentDate = $preDate;
                            } else if ($zone_name == $result_array['zone_name']) {
                                //exit;
                                echo "&nbsp;";
                            } else {
                                echo $result_array['zone_name'];
                                $zone_name = $result_array['zone_name'];
                            }
                            ?>
                        </td>
                        <td>
                            <?php
                            if ($territory_name == '') {
                                echo $result_array['territory_name'];
                                $territory_name = $result_array['territory_name'];
                                //$currentDate = $preDate;
                            } else if ($territory_name == $result_array['territory_name']) {
                                //exit;
                                echo "&nbsp;";
                            } else {
                                echo $result_array['territory_name'];
                                $territory_name = $result_array['territory_name'];
                            }
                            ?>
                        </td>
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
                        <td><?php echo $result_array['crop_name']; ?></td>
                        <td><?php echo $result_array['product_type']; ?></td>
                        <td><?php echo $result_array['varriety_name']; ?></td>
                        <td><?php echo $result_array['pack_size_name']; ?></td>
                        <td style="text-align: right;"><?php echo $result_array['quantity']; ?></td>
                        <td style="text-align: right;"><?php echo $result_array['price']; ?></td>
                        <td style="text-align: right;"><?php echo $result_array['approved_quantity']; ?></td>
                        <td style="text-align: right;"><?php echo $result_array['total_price']; ?></td>
                    </tr>
                    <?php
                    ++$i;
                }
            }
            ?>
            <?php
//             $sqlb = "SELECT
//                            $tbl" . "product_purchase_order_bonus.invoice_id,
//                            $tbl" . "zone_info.zone_name,
//                            $tbl" . "territory_info.territory_name,
//                            $tbl" . "distributor_info.distributor_name,
//                            $tbl" . "crop_info.crop_name,
//                            $tbl" . "product_type.product_type,
//                            $tbl" . "varriety_info.varriety_name,
//                            $tbl" . "product_pack_size.pack_size_name,
//                            $tbl" . "product_purchase_order_bonus.quantity
//                        FROM
//                            $tbl" . "product_purchase_order_bonus
//                            LEFT JOIN $tbl" . "zone_info ON $tbl" . "zone_info.zone_id = $tbl" . "product_purchase_order_bonus.zone_id
//                            LEFT JOIN $tbl" . "territory_info ON $tbl" . "territory_info.territory_id = $tbl" . "product_purchase_order_bonus.territory_id
//                            LEFT JOIN $tbl" . "distributor_info ON $tbl" . "distributor_info.distributor_id = $tbl" . "product_purchase_order_bonus.distributor_id
//                            LEFT JOIN $tbl" . "crop_info ON $tbl" . "crop_info.crop_id = $tbl" . "product_purchase_order_bonus.crop_id
//                            LEFT JOIN $tbl" . "product_type ON $tbl" . "product_type.product_type_id = $tbl" . "product_purchase_order_bonus.product_type_id
//                            LEFT JOIN $tbl" . "varriety_info ON $tbl" . "varriety_info.varriety_id = $tbl" . "product_purchase_order_bonus.varriety_id
//                            LEFT JOIN $tbl" . "product_pack_size ON $tbl" . "product_pack_size.pack_size_id = $tbl" . "product_purchase_order_bonus.pack_size
//                        WHERE $tbl" . "product_purchase_order_bonus.del_status='0'
//                        $crop_id $product_type_id $varriety_id $pack_size $zone_id $territory_id $distributor_id $between
//                             " . $db->get_zone_access($tbl . "product_purchase_order_bonus") . "
//                    ";
//            if ($db->open()) {
//                $resultb = $db->query($sqlb);
//                while ($rowb = $db->fetchAssoc($resultb)) {
//                    $bqnty = $bqnty + $rowb['quantity'];
                    ?>
<!--                    <tr class="<?php echo $rowcolor; ?> pointer" id="tr_id<?php echo $i; ?>" onclick="get_rowID('<?php echo $rowb["id"] ?>', '<?php echo $i; ?>')">
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>
                            <?php
                            if ($zone_name == '') {
                                echo $rowb['zone_name'];
                                $zone_name = $rowb['zone_name'];
                                //$currentDate = $preDate;
                            } else if ($zone_name == $rowb['zone_name']) {
                                //exit;
                                echo "&nbsp;";
                            } else {
                                echo $rowb['zone_name'];
                                $zone_name = $rowb['zone_name'];
                            }
                            ?>
                        </td>
                        <td>
                            <?php
                            if ($territory_name == '') {
                                echo $rowb['territory_name'];
                                $territory_name = $rowb['territory_name'];
                                //$currentDate = $preDate;
                            } else if ($territory_name == $rowb['territory_name']) {
                                //exit;
                                echo "&nbsp;";
                            } else {
                                echo $rowb['territory_name'];
                                $territory_name = $rowb['territory_name'];
                            }
                            ?>
                        </td>
                        <td>&nbsp;</td>
                        <td><?php echo $rowb['crop_name']; ?></td>
                        <td><?php echo $rowb['product_type']; ?></td>
                        <td><?php echo $rowb['varriety_name']; ?></td>
                        <td><?php echo $rowb['pack_size_name']; ?></td>
                        <td style="text-align: right;">0</td>
                        <td style="text-align: right;">0</td>
                        <td style="text-align: right;"><?php echo $rowb['quantity']; ?></td>
                        <td style="text-align: right;">0</td>
                    </tr>-->
                    <?php
//                    ++$i;
//                }
//            }
            ?>
        <tfoot>
            <tr>
                <td colspan="9" style="text-align: right;">Total: </td>
                <td style="text-align: right;"><?php echo number_format($qnty, 2) ?></td>
                <td style="text-align: right;"><?php echo number_format($price, 2); ?></td>
                <td style="text-align: right;"><?php echo number_format($aqnty, 2) ?></td>
                <td style="text-align: right;"><?php echo number_format($tprice, 2) ?></td>
            </tr>
            <tr>
                <td colspan="15" style="text-align: right;">In word: <?php echo $db->number_convert_inword($tprice) ?> Only</td>
            </tr>
        </tfoot>
        </tbody>
    </table>
    <?php include_once '../../libraries/print_page/Print_footer.php'; ?>
</div>