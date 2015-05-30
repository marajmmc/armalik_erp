<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$tbl = _DB_PREFIX;

if ($_POST['crop_id'] != "") {
    $crop_id = "AND $tbl" . "distributor_product_sale.crop_id='" . $_POST['crop_id'] . "'";
} else {
    $crop_id = "";
}
if ($_POST['product_type_id'] != "") {
    $product_type_id = "AND $tbl" . "distributor_product_sale.product_type_id='" . $_POST['product_type_id'] . "'";
} else {
    $product_type_id = "";
}
if ($_POST['varriety_id'] != "") {
    $varriety_id = "AND $tbl" . "distributor_product_sale.varriety_id='" . $_POST['varriety_id'] . "'";
} else {
    $varriety_id = "";
}
if ($_POST['pack_size'] != "") {
    $pack_size = "AND $tbl" . "distributor_product_sale.pack_size='" . $_POST['pack_size'] . "'";
} else {
    $pack_size = "";
}
if ($_POST['zone_id'] != "") {
    $zone_id = "AND $tbl" . "distributor_product_sale.zone_id='" . $_POST['zone_id'] . "'";
} else {
    $zone_id = "";
}
if ($_POST['territory_id'] != "") {
    $territory_id = "AND $tbl" . "distributor_product_sale.territory_id='" . $_POST['territory_id'] . "'";
} else {
    $territory_id = "";
}
if ($_POST['distributor_id'] != "") {
    $distributor_id = "AND $tbl" . "distributor_product_sale.distributor_id='" . $_POST['distributor_id'] . "'";
} else {
    $distributor_id = "";
}
if ($_POST['from_date'] != "" && $_POST['to_date'] != "") {
    $between = "AND $tbl" . "distributor_product_sale.sale_date BETWEEN '" . $db->date_formate($_POST['from_date']) . "' AND '" . $db->date_formate($_POST['to_date']) . "'";
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
                    Sale Inv No
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
                    Dealer
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
            $sale_id = '';
            $sale_date = '';
            $zone_name = '';
            $territory_name = '';
            $distributor_name = '';
            $dealer_name = '';
            $sql = "SELECT
                        $tbl" . "distributor_product_sale.id,
                        $tbl" . "distributor_product_sale.sale_id,
                        $tbl" . "distributor_product_sale.sale_date,
                        $tbl" . "zone_info.zone_name,
                        $tbl" . "territory_info.territory_name,
                        CONCAT_WS(' - ', $tbl" . "distributor_info.customer_code, $tbl" . "distributor_info.distributor_name) AS distributor_name,
                        $tbl" . "dealer_info.dealer_name,
                        $tbl" . "crop_info.crop_name,
                        $tbl" . "product_type.product_type,
                        $tbl" . "varriety_info.varriety_name,
                        $tbl" . "product_pack_size.pack_size_name,
                        $tbl" . "distributor_product_sale.purchase_price,
                        $tbl" . "distributor_product_sale.price,
                        $tbl" . "distributor_product_sale.quantity,
                        $tbl" . "distributor_product_sale.total_price
                    FROM
                        $tbl" . "distributor_product_sale
                        LEFT JOIN $tbl" . "zone_info ON $tbl" . "zone_info.zone_id = $tbl" . "distributor_product_sale.zone_id
                        LEFT JOIN $tbl" . "territory_info ON $tbl" . "territory_info.territory_id = $tbl" . "distributor_product_sale.territory_id
                        LEFT JOIN $tbl" . "distributor_info ON $tbl" . "distributor_info.distributor_id = $tbl" . "distributor_product_sale.distributor_id
                        LEFT JOIN $tbl" . "dealer_info ON $tbl" . "dealer_info.dealer_id = $tbl" . "distributor_product_sale.dealer_id
                        LEFT JOIN $tbl" . "crop_info ON $tbl" . "crop_info.crop_id = $tbl" . "distributor_product_sale.crop_id
                        LEFT JOIN $tbl" . "product_type ON $tbl" . "product_type.product_type_id = $tbl" . "distributor_product_sale.product_type_id
                        LEFT JOIN $tbl" . "varriety_info ON $tbl" . "varriety_info.varriety_id = $tbl" . "distributor_product_sale.varriety_id
                        LEFT JOIN $tbl" . "product_pack_size ON $tbl" . "product_pack_size.pack_size_id = $tbl" . "distributor_product_sale.pack_size
                    WHERE
                        $tbl" . "distributor_product_sale.del_status='0'
                        $crop_id $product_type_id $varriety_id $pack_size $zone_id $territory_id $distributor_id $between
                            ".$db->get_zone_access($tbl. "distributor_product_sale")."
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
                    $tprice = $tprice + $result_array['total_price'];
                    ?>
                    <tr class="<?php echo $rowcolor; ?> pointer" id="tr_id<?php echo $i; ?>" onclick="get_rowID('<?php echo $result_array["id"] ?>', '<?php echo $i; ?>')">
                        <td>
                            <?php
                            if ($sale_date == '') {
                                echo $db->date_formate($result_array['sale_date']);
                                $sale_date = $result_array['sale_date'];
                                //$currentDate = $preDate;
                            } else if ($sale_date == $result_array['sale_date']) {
                                //exit;
                                echo "&nbsp;";
                            } else {
                                echo $db->date_formate($result_array['sale_date']);
                                $sale_date = $result_array['sale_date'];
                            }
                            ?>
                        </td>
                        <td>
                            <?php
                            if ($sale_id == '') {
                                echo $result_array['sale_id'];
                                $sale_id = $result_array['sale_id'];
                                //$currentDate = $preDate;
                            } else if ($sale_id == $result_array['sale_id']) {
                                //exit;
                                echo "&nbsp;";
                            } else {
                                echo $result_array['sale_id'];
                                $sale_id = $result_array['sale_id'];
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
                        <td>
                            <?php
                            if ($dealer_name == '') {
                                echo $result_array['dealer_name'];
                                $dealer_name = $result_array['dealer_name'];
                                //$currentDate = $preDate;
                            } else if ($dealer_name == $result_array['dealer_name']) {
                                //exit;
                                echo "&nbsp;";
                            } else {
                                echo $result_array['dealer_name'];
                                $dealer_name = $result_array['dealer_name'];
                            }
                            ?>
                        </td>
                        <td><?php echo $result_array['crop_name']; ?></td>
                        <td><?php echo $result_array['product_type']; ?></td>
                        <td><?php echo $result_array['varriety_name']; ?></td>
                        <td><?php echo $result_array['pack_size_name']; ?></td>
                        <td style="text-align: right;">
                            <?php echo $result_array['price']; ?>
                        </td>
                        <td style="text-align: right;">
                            <?php echo $result_array['quantity']; ?>
                        </td>
                        <td style="text-align: right;"><?php echo $result_array['total_price']; ?></td>
                    </tr>
                    <?php
                    ++$i;
                }
            }
            ?>
        <tfoot>
            <tr>
                <td colspan="11" style="text-align: right;">Total: </td>
<!--                <td style="text-align: right;">
                    <?php // echo number_format($price, 2);?>
                </td>-->
                <td style="text-align: right;">
                    <?php echo number_format($qnty, 2) ?>
                </td>
                <td style="text-align: right;">
                    <?php echo number_format($tprice, 2) ?>
                </td>
            </tr>
            <tr>
                <td colspan="15" style="text-align: right;">In word: <?php echo $db->number_convert_inword($tprice) ?> Only</td>
            </tr>
        </tfoot>
        </tbody>
    </table>
    <?php include_once '../../libraries/print_page/Print_footer.php'; ?>
</div>