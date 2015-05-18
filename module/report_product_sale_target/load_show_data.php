<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$tbl = _DB_PREFIX;
//if ($_POST['session_id'] != "") {
//    $session_id = "AND $tbl" . "product_purchase_order_invoice.session_id='" . $_POST['session_id'] . "'";
//} else {
//    $session_id = "";
//}
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
//if ($_POST['pack_size'] != "") {
//    $pack_size = "AND $tbl" . "product_purchase_order_invoice.pack_size='" . $_POST['pack_size'] . "'";
//} else {
//    $pack_size = "";
//}
if ($_POST['zone_id'] != "") {
    $zone_id = "AND $tbl" . "product_sale_target.zone_id='" . $_POST['zone_id'] . "'";
} else {
    $zone_id = "";
}
if ($_POST['territory_id'] != "") {
    $territory_id = "AND $tbl" . "product_sale_target.territory_id='" . $_POST['territory_id'] . "'";
} else {
    $territory_id = "";
}
if ($_POST['distributor_id'] != "") {
    $distributor = "AND $tbl" . "product_sale_target.distributor_id='" . $_POST['distributor_id'] . "'";
} else {
    $distributor = "";
}
//if ($_POST['from_date'] != "" && $_POST['to_date'] != "") {
//    $between = "AND $tbl" . "product_purchase_order_invoice.invoice_date BETWEEN '" . $db->date_formate($_POST['from_date']) . "' AND '" . $db->date_formate($_POST['to_date']) . "'";
//} else {
//    $between = "";
//}
$fdate = $_POST['from_date'] . "-01-01";
//$tdate=$_POST['to_date'] . "-01-01";
//$getyear = $db->get_increment_DMY($fdate, $tdate, "year");
//$totalyear = explode('~', $getyear);
//$countyear = count($totalyear);
?>

<a class="btn btn-small btn-success" data-original-title="" onclick="print_rpt()" style="float: right;">
    <i class="icon-print" data-original-title="Share"> </i> Print
</a>
<div id="PrintArea" style="background-color: white;" >
    <?php include_once '../../libraries/print_page/Print_header.php'; ?>
    <div style="text-align: center;">
        <b>
            <u>
                <?php
                echo "Sales Target June: " . $_POST['from_date'] . " - May: ";
                echo $_POST['from_date'] + 1;
                ?>
            </u>
        </b>
    </div>

    <table class="table table-condensed table-striped table-hover table-bordered pull-left report" id="data-table">

        <thead>
            <tr>
                <th style="width:5%">
                    Zone
                </th>
                <th style="width:5%">
                    Territory
                </th>
                <th style="width:5%">
                    Distributor
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
                <th style="width:5%; text-align: right;">
                    Price(TK/Kg)
                </th>
                <th style="width:5%; text-align: right;">
                    Target(Kg)
                </th>
                <th style="width:5%; text-align: right;">
                    Target Value(TK)
                </th>
                <th style="width:5%; text-align: right;">
                    Achieve(%)
                </th>
                <?php
//                for ($yi = 0; $yi < $countyear; $yi++) {
//                    echo "<th style='width:2%; text-align: center;' title='$totalyear[$yi] :: Target Qty(kg) | Achievement Qty(kg)'>$totalyear[$yi] <br /> Tar | Ach (kg)</th>";
//                }
                ?>
            </tr>
        </thead>
        <tbody>
            <?php
            $price = '0';
            $qnty = '0';
            $tprice = '0';
            $persentance = '0';
            $invoice_id = '';
            $invoice_date = '';
            $zone_name = '';
            $territory_name = '';
            $distributor_name = '';
            $sql = "SELECT
                        $tbl" . "product_sale_target.zone_id,
                        $tbl" . "product_sale_target.territory_id,
                        $tbl" . "product_sale_target.distributor_id,
                        $tbl" . "product_sale_target.start_date,
                        $tbl" . "product_sale_target.crop_id,
                        $tbl" . "product_sale_target.product_type_id,
                        $tbl" . "product_sale_target.varriety_id,
                        SUM($tbl" . "product_sale_target.price) AS price,
                        SUM($tbl" . "product_sale_target.quantity) AS quantity,
                        SUM($tbl" . "product_sale_target.value) AS value,
                        $tbl" . "zone_info.zone_name,
                        $tbl" . "territory_info.territory_name,
                        CONCAT_WS(' - ', $tbl" . "distributor_info.customer_code, $tbl" . "distributor_info.distributor_name) AS distributor_name,
                        $tbl" . "crop_info.crop_name,
                        $tbl" . "product_type.product_type,
                        $tbl" . "varriety_info.varriety_name,
                        (SELECT 
                            SUM($tbl" . "product_purchase_order_challan.total_price) 
                        FROM $tbl" . "product_purchase_order_challan 
                        WHERE 
                            $tbl" . "product_purchase_order_challan.zone_id=$tbl" . "product_sale_target.zone_id AND
                            $tbl" . "product_purchase_order_challan.territory_id=$tbl" . "product_sale_target.territory_id AND
                            $tbl" . "product_purchase_order_challan.distributor_id=$tbl" . "product_sale_target.distributor_id AND
                            $tbl" . "product_purchase_order_challan.crop_id=$tbl" . "product_sale_target.crop_id AND
                            $tbl" . "product_purchase_order_challan.product_type_id=$tbl" . "product_sale_target.product_type_id AND
                            $tbl" . "product_purchase_order_challan.varriety_id=$tbl" . "product_sale_target.varriety_id AND
                            $tbl" . "product_purchase_order_challan.`status`='Received' AND
                            $tbl" . "product_purchase_order_challan.del_status='0' AND
                            $tbl" . "product_purchase_order_challan.challan_date BETWEEN '2014-06-01' AND '2015-05-31'
                        GROUP BY 
                            $tbl" . "product_purchase_order_challan.crop_id, 
                            $tbl" . "product_purchase_order_challan.product_type_id, 
                            $tbl" . "product_purchase_order_challan.varriety_id, 
                            $tbl" . "product_purchase_order_challan.distributor_id
                        ) AS sale_target_persentance
                    FROM
                        $tbl" . "product_sale_target
                        LEFT JOIN $tbl" . "zone_info ON $tbl" . "zone_info.zone_id = $tbl" . "product_sale_target.zone_id
                        LEFT JOIN $tbl" . "territory_info ON $tbl" . "territory_info.territory_id = $tbl" . "product_sale_target.territory_id
                        LEFT JOIN $tbl" . "distributor_info ON $tbl" . "distributor_info.distributor_id = $tbl" . "product_sale_target.distributor_id
                        LEFT JOIN $tbl" . "crop_info ON $tbl" . "crop_info.crop_id = $tbl" . "product_sale_target.crop_id
                        LEFT JOIN $tbl" . "product_type ON $tbl" . "product_type.product_type_id = $tbl" . "product_sale_target.product_type_id
                        LEFT JOIN $tbl" . "varriety_info ON $tbl" . "varriety_info.varriety_id = $tbl" . "product_sale_target.varriety_id
                    WHERE $tbl" . "product_sale_target.del_status='0' AND 
                        $tbl" . "product_sale_target.`status`='Active' AND 
                        $tbl" . "product_sale_target.channel='Distributor' AND
                        $tbl" . "product_sale_target.start_date = '$fdate'
                        $zone_id $territory_id $distributor
                    GROUP BY 
                        $tbl" . "product_sale_target.crop_id,
                        $tbl" . "product_sale_target.product_type_id,
                        $tbl" . "product_sale_target.varriety_id,
                        $tbl" . "product_sale_target.distributor_id,
                        $tbl" . "product_sale_target.start_date
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
                    $distributor_id = $result_array['distributor_id'];
                    $crop_id = $result_array['crop_id'];
                    $product_type_id = $result_array['product_type_id'];
                    $varriety_id = $result_array['varriety_id'];
                    $start_date = $result_array['start_date'];

                    $price = $price + $result_array['price'];
                    $qnty = $qnty + $result_array['quantity'];
                    $tprice = $tprice + $result_array['value'];
                    $persentance = (($result_array['sale_target_persentance'] / $result_array['value']) * 100);
                    ?>
                    <tr class="<?php echo $rowcolor; ?> pointer" id="tr_id<?php echo $i; ?>" onclick="get_rowID('<?php echo $result_array["id"] ?>', '<?php echo $i; ?>')">
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
                        <td style="width:5%; text-align: right;"><?php echo $result_array['price']; ?></td>
                        <td style="width:5%; text-align: right;"><?php echo $result_array['quantity']; ?></td>
                        <td style="width:5%; text-align: right;"><?php echo $result_array['value']; ?></td>
                        <td style="width:5%; text-align: right;"><?php echo $persentance; ?></td>
                        <?php
//                        $dbst = new Database();
//                        $dbsq = new Database();
//                        for ($yi = 0; $yi < $countyear; $yi++) {
//                            $fyidate = $totalyear[$yi] . "-01-01";
//                            $tyidate = $totalyear[$yi] . "-12-31";
//                            echo "<td style='text-align:center;' title='$totalyear[$yi] :: Target Qty(kg) | Achievement Qty(kg)'>";
//                            $sqlst = "SELECT
//                                        SUM($tbl" . "product_sale_target.quantity) AS quantity
//                                    FROM
//                                        $tbl" . "product_sale_target
//                                    WHERE $tbl" . "product_sale_target.del_status='0' AND 
//                                        $tbl" . "product_sale_target.`status`='Active' AND 
//                                        $tbl" . "product_sale_target.distributor_id='$distributor_id' AND
//                                        $tbl" . "product_sale_target.crop_id='$crop_id' AND
//                                        $tbl" . "product_sale_target.product_type_id='$product_type_id' AND
//                                        $tbl" . "product_sale_target.varriety_id='$varriety_id' AND
//                                        $tbl" . "product_sale_target.start_date BETWEEN '$fyidate' AND '$tyidate'
//                                    GROUP BY 
//                                        $tbl" . "product_sale_target.crop_id,
//                                        $tbl" . "product_sale_target.product_type_id,
//                                        $tbl" . "product_sale_target.varriety_id,
//                                        $tbl" . "product_sale_target.distributor_id,
//                                        $tbl" . "product_sale_target.start_date";
//                            if ($dbst->open()) {
//                                $resultst = $dbst->query($sqlst);
//                                $rowst = $dbst->fetchAssoc($resultst);
//                                if ($rowst['quantity'] == "") {
//                                    echo "0 | ";
//                                } else {
//                                    echo $rowst['quantity'] . " | ";
//                                }
//                            }
//
//                            $sqlsq = "SELECT
//                                        $tbl" . "product_purchase_order_challan.quantity AS sale_quantity,
//                                        $tbl" . "product_pack_size.pack_size_name,
//                                        $tbl" . "product_purchase_order_challan.challan_date
//                                    FROM
//                                        $tbl" . "product_purchase_order_challan
//                                        LEFT JOIN $tbl" . "product_pack_size ON $tbl" . "product_pack_size.pack_size_id=$tbl" . "product_purchase_order_challan.pack_size
//                                    WHERE $tbl" . "product_purchase_order_challan.`status`='Received' AND 
//                                        $tbl" . "product_purchase_order_challan.del_status='0' AND 
//                                        $tbl" . "product_purchase_order_challan.distributor_id='$distributor_id' AND
//                                        $tbl" . "product_purchase_order_challan.crop_id='$crop_id' AND
//                                        $tbl" . "product_purchase_order_challan.product_type_id='$product_type_id' AND
//                                        $tbl" . "product_purchase_order_challan.varriety_id='$varriety_id' AND
//                                        $tbl" . "product_purchase_order_challan.challan_date BETWEEN '$fyidate' AND '$tyidate'
//                                    GROUP BY
//                                        $tbl" . "product_purchase_order_challan.zone_id,
//                                        $tbl" . "product_purchase_order_challan.territory_id,
//                                        $tbl" . "product_purchase_order_challan.distributor_id,
//                                        $tbl" . "product_purchase_order_challan.crop_id,
//                                        $tbl" . "product_purchase_order_challan.product_type_id,
//                                        $tbl" . "product_purchase_order_challan.varriety_id";
//                            if ($dbsq->open()) {
//                                $resultsq = $dbsq->query($sqlsq);
//                                $rowsq = $dbsq->fetchAssoc($resultsq);
//                                $salequty = (($rowsq['pack_size_name'] * $rowsq['sale_quantity']) / 1000);
//                                if ($rowsq['sale_quantity'] == "") {
//                                    echo "0";
//                                } else {
//                                    echo $salequty;
//                                }
//                            }
//                            echo "</td>";
//                        }
                        ?>
                    </tr>
                    <?php
                    ++$i;
                }
            }
            ?>
        <tfoot>
            <tr>
                <td colspan="6" style="text-align: right;">Total: </td>
                <td style="text-align: right;"><?php echo number_format($price, 2) ?></td>
                <td style="text-align: right;"><?php echo number_format($qnty, 2) ?></td>
                <td style="text-align: right;"><?php echo number_format($tprice, 2) ?></td>
                <td style="text-align: right;">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="15" style="text-align: right;">In word: <?php echo $db->number_convert_inword($tprice) ?> Only</td>
            </tr>
        </tfoot>
        </tbody>
    </table>
    <?php include_once '../../libraries/print_page/Print_footer.php'; ?>
</div>