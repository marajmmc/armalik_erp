<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$tbl = _DB_PREFIX;

if ($_POST['crop_id'] != "") {
    $crop_id = "AND apporcr.crop_id='" . $_POST['crop_id'] . "'";
} else {
    $crop_id = "";
}
if ($_POST['product_type_id'] != "") {
    $product_type_id = "AND apporcr.product_type_id='" . $_POST['product_type_id'] . "'";
} else {
    $product_type_id = "";
}
if ($_POST['varriety_id'] != "") {
    $varriety_id = "AND apporcr.varriety_id='" . $_POST['varriety_id'] . "'";
} else {
    $varriety_id = "";
}
if ($_POST['pack_size'] != "") {
    $pack_size = "AND apporcr.pack_size='" . $_POST['pack_size'] . "'";
} else {
    $pack_size = "";
}
if ($_POST['division_id'] != "")
{
    $division_id = "AND $tbl"."division_info.division_id='" . $_POST['division_id'] . "'";
}
else
{
    $division_id = "";
}
if ($_POST['zone_id'] != "") {
    $zone_id = "AND apporcr.zone_id='" . $_POST['zone_id'] . "'";
} else {
    $zone_id = "";
}
if ($_POST['territory_id'] != "") {
    $territory_id = "AND apporcr.territory_id='" . $_POST['territory_id'] . "'";
} else {
    $territory_id = "";
}
if ($_POST['distributor_id'] != "") {
    $distributor_id = "AND apporcr.distributor_id='" . $_POST['distributor_id'] . "'";
} else {
    $distributor_id = "";
}
if ($_POST['from_date'] != "" && $_POST['to_date'] != "") {
    $between = "AND apporcr.challan_date BETWEEN '" . $db->date_formate($_POST['from_date']) . "' AND '" . $db->date_formate($_POST['to_date']) . "'";
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
                    Division
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
                    Date
                </th>
                <th style="width:5%">
                    Inv No
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
                    Bonus Qty(pieces)
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
            $bonus_qnty = '0';
            $aqnty = '0';
            $tprice = '0';
            $invoice_id = '';
            $invoice_date = '';
            $division_name = '';
            $zone_name = '';
            $territory_name = '';
            $distributor_name = '';
            $sql = "SELECT
                        apporcr.id,
                        apporcr.invoice_id,
                        apporcr.challan_date,
                        apporcr.price,
                        apporcr.return_quantity,
                        apporcr.total_price,
                        $tbl"."zone_info.zone_name,
                        $tbl"."territory_info.territory_name,
                        CONCAT_WS(' - ', $tbl"."distributor_info.customer_code, $tbl"."distributor_info.distributor_name) AS distributor_name_width_code,
                        $tbl"."distributor_info.distributor_name,
                        $tbl"."crop_info.crop_name,
                        $tbl"."product_type.product_type,
                        $tbl"."varriety_info.varriety_name,
                        $tbl"."product_pack_size.pack_size_name,
                        (
                        SELECT appob.quantity FROM $tbl"."product_purchase_order_bonus as appob
                        WHERE
                            appob.invoice_id = apporcr.invoice_id
                            AND appob.warehouse_id = apporcr.warehouse_id
                            AND appob.zone_id = apporcr.zone_id
                            AND appob.territory_id = apporcr.territory_id
                            AND appob.distributor_id = apporcr.distributor_id
                            AND appob.crop_id = apporcr.crop_id
                            AND appob.product_type_id = apporcr.product_type_id
                            AND appob.varriety_id = apporcr.varriety_id
                            AND appob.pack_size = apporcr.pack_size
                        ) as bonus_qantity,
                        $tbl"."division_info.division_name
                    FROM
                        $tbl"."product_purchase_order_challan_return as apporcr
                        LEFT JOIN $tbl"."zone_info ON $tbl"."zone_info.zone_id = apporcr.zone_id
                        LEFT JOIN $tbl"."territory_info ON $tbl"."territory_info.territory_id = apporcr.territory_id
                        LEFT JOIN $tbl"."distributor_info ON $tbl"."distributor_info.distributor_id = apporcr.distributor_id
                        LEFT JOIN $tbl"."crop_info ON $tbl"."crop_info.crop_id = apporcr.crop_id
                        LEFT JOIN $tbl"."product_type ON $tbl"."product_type.product_type_id = apporcr.product_type_id
                        LEFT JOIN $tbl"."varriety_info ON $tbl"."varriety_info.varriety_id = apporcr.varriety_id
                        LEFT JOIN $tbl"."product_pack_size ON $tbl"."product_pack_size.pack_size_id = apporcr.pack_size
                        INNER JOIN $tbl"."zone_user_access ON $tbl"."zone_user_access.zone_id = $tbl"."zone_info.zone_id
                        INNER JOIN $tbl"."division_info ON $tbl"."division_info.division_id = $tbl"."zone_user_access.division_id
                    WHERE
                        apporcr.del_status='0'
                        $crop_id $product_type_id $varriety_id $pack_size
                        $division_id $zone_id $territory_id $distributor_id $between
                        ".$db->get_zone_access("apporcr")."
                    ORDER BY
                            $tbl"."division_info.division_name,
                            $tbl"."zone_info.zone_name,
                            $tbl"."territory_info.territory_name,
                            $tbl"."distributor_info.distributor_name,
                            apporcr.challan_date,
                            apporcr.invoice_id
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
                    $qnty = $qnty + $result_array['return_quantity'];
                    $bonus_qnty = $bonus_qnty + $result_array['bonus_qantity'];
                    $tprice = $tprice + $result_array['total_price'];
                    ?>
                    <tr class="<?php echo $rowcolor; ?> pointer" id="tr_id<?php echo $i; ?>" onclick="get_rowID('<?php echo $result_array["id"] ?>', '<?php echo $i; ?>')">
                        <td>
                            <?php
                            if ($division_name == '') {
                                echo $result_array['division_name'];
                                $division_name = $result_array['division_name'];
                                //$currentDate = $preDate;
                            } else if ($division_name == $result_array['division_name']) {
                                //exit;
                                echo "&nbsp;";
                            } else {
                                echo $result_array['division_name'];
                                $division_name = $result_array['division_name'];
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
                            if ($invoice_date == '') {
                                echo $db->date_formate($result_array['challan_date']);
                                $invoice_date = $result_array['challan_date'];
                                //$currentDate = $preDate;
                            } else if ($invoice_date == $result_array['challan_date']) {
                                //exit;
                                echo "&nbsp;";
                            } else {
                                echo $db->date_formate($result_array['challan_date']);
                                $invoice_date = $result_array['challan_date'];
                            }
                            ?>
                        </td>
                        <td>
                            <?php
                            if ($invoice_id == '') {
                                echo $result_array['invoice_id'];
                                $invoice_id = $result_array['invoice_id'];
                                //$currentDate = $preDate;
                            } else if ($invoice_id == $result_array['invoice_id']) {
                                //exit;
                                echo "&nbsp;";
                            } else {
                                echo $result_array['invoice_id'];
                                $invoice_id = $result_array['invoice_id'];
                            }
                            ?>
                        </td>
                        <td><?php echo $result_array['crop_name']; ?></td>
                        <td><?php echo $result_array['product_type']; ?></td>
                        <td><?php echo $result_array['varriety_name']; ?></td>
                        <td><?php echo $result_array['pack_size_name']; ?></td>
                        <td style="text-align: right;"><?php echo $result_array['price']; ?></td>
                        <td style="text-align: right;"><?php echo $result_array['return_quantity']; ?></td>
                        <td style="text-align: right;"><?php echo $result_array['bonus_qantity']; ?></td>
                        <td style="text-align: right;"><?php echo $result_array['total_price']; ?></td>
                    </tr>
                    <?php
                    ++$i;
                }
            }
            ?>
        <tfoot>
            <tr>
                <td colspan="10" style="text-align: right;">Total: </td>
                <td style="text-align: right;"><?php echo number_format($price, 2) ?></td>
                <td style="text-align: right;"><?php echo number_format($qnty, 2) ?></td>
                <td style="text-align: right;"><?php echo number_format($bonus_qnty, 2) ?></td>
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