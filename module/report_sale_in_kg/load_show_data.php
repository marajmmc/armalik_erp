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

if(empty($_POST['year_id']) || empty($_POST['from_date']) || empty($_POST['to_date']))
{
    echo "<h4 style='text-align: center; color: red;'>Please select year, from date & to date...</h4>";
    die();
}

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
@$fyear = $db->DB_date_convert_year($db->date_formate($_POST['from_date']));
@$tyear = $db->DB_date_convert_year($db->date_formate($_POST['to_date']));
if ($_POST['from_date'] != "" && $_POST['to_date'] != "") {
    $between = "AND $tbl" . "product_purchase_order_invoice.invoice_date BETWEEN '" . $db->date_formate($_POST['from_date']) . "' AND '" . $db->date_formate($_POST['to_date']) . "'";
    $show_date="From Date: ".$_POST['from_date']." To Date: ".$_POST['to_date'];
    $year="AND $tbl" . "product_sale_target.year_id='".$_POST['year_id']."'";
} else {
    $between = "";
    $show_date="";
    $year="";
}
?>

<a class="btn btn-small btn-success" data-original-title="" onclick="print_rpt()" style="float: right;">
    <i class="icon-print" data-original-title="Share"> </i> Print
</a>
<div id="PrintArea" style="background-color: white;" >

    <?php

        include_once '../../libraries/print_page/Print_header.php';
    ?>
    <table class="table table-condensed table-striped table-hover table-bordered pull-left report" id="data-table">

        <thead>
            <tr>
                <td colspan="21" class="right-align-text"><?php echo $show_date;; ?></td>
            </tr>
            <tr>
                <!--                <th style="width:5%">-->
                <!--                    Inv Date-->
                <!--                </th>-->
                <!--                <th style="width:5%">-->
                <!--                    Inv No-->
                <!--                </th>-->
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
                <th style="width:5%; text-align: right">
                    Price(tk/kg)
                </th>
                <th style="width:5%; text-align: right">
                    Qty(kg)
                </th>
                <th style="width:5%; text-align: right">
                    Total Value (tk)
                </th>
            </tr>

        </thead>
        <tbody>
        <?php
        $kg = '0';
        $tkg = '0';
        $invoice_id = '';
        $invoice_date = '';
        $zone_name = '';
        $territory_name = '';
        $distributor_name = '';
        $tvalue = '';
        $sql = "SELECT
                    $tbl" . "product_purchase_order_invoice.invoice_date,
                    $tbl" . "product_purchase_order_invoice.zone_id,
                    $tbl" . "product_purchase_order_invoice.territory_id,
                    $tbl" . "product_purchase_order_invoice.pack_size,
                    $tbl" . "zone_info.zone_name,
                    $tbl" . "territory_info.territory_name,
                    CONCAT_WS(' - ', $tbl" . "distributor_info.customer_code, $tbl" . "distributor_info.distributor_name) AS distributor_name,
                    $tbl" . "crop_info.crop_name,
                    $tbl" . "product_type.product_type,
                    $tbl" . "varriety_info.varriety_name,
                    $tbl" . "product_purchase_order_invoice.quantity,
                    $tbl" . "product_pack_size.pack_size_name,
                    SUM(($tbl" . "product_pack_size.pack_size_name * $tbl" . "product_purchase_order_invoice.approved_quantity)/1000) AS quty_kg,
                    $tbl" . "product_purchase_order_invoice.invoice_id,
                    $tbl" . "product_purchase_order_invoice.id,
                    (
                    SELECT $tbl"."product_sale_target.price 
                    FROM $tbl"."product_sale_target 
                    WHERE 
                    $tbl"."product_sale_target.zone_id=$tbl"."product_purchase_order_invoice.zone_id AND 
                    $tbl"."product_sale_target.crop_id=$tbl"."product_purchase_order_invoice.crop_id AND 
                    $tbl"."product_sale_target.product_type_id=$tbl"."product_purchase_order_invoice.product_type_id AND 
                    $tbl"."product_sale_target.varriety_id=$tbl"."product_purchase_order_invoice.varriety_id
                    $year
                    GROUP BY
                    $tbl"."product_sale_target.crop_id, $tbl"."product_sale_target.product_type_id, $tbl"."product_sale_target.varriety_id
                    ) AS prince_per_kg
                FROM $tbl" . "product_purchase_order_invoice
                    LEFT JOIN $tbl" . "zone_info ON $tbl" . "zone_info.zone_id = $tbl" . "product_purchase_order_invoice.zone_id
                    LEFT JOIN $tbl" . "territory_info ON $tbl" . "territory_info.territory_id = $tbl" . "product_purchase_order_invoice.territory_id
                    LEFT JOIN $tbl" . "distributor_info ON $tbl" . "distributor_info.distributor_id = $tbl" . "product_purchase_order_invoice.distributor_id
                    LEFT JOIN $tbl" . "crop_info ON $tbl" . "crop_info.crop_id = $tbl" . "product_purchase_order_invoice.crop_id
                    LEFT JOIN $tbl" . "product_type ON $tbl" . "product_type.product_type_id = $tbl" . "product_purchase_order_invoice.product_type_id
                    LEFT JOIN $tbl" . "varriety_info ON $tbl" . "varriety_info.varriety_id = $tbl" . "product_purchase_order_invoice.varriety_id
                    LEFT JOIN $tbl" . "product_pack_size ON $tbl" . "product_pack_size.pack_size_id = $tbl" . "product_purchase_order_invoice.pack_size
                WHERE $tbl" . "product_purchase_order_invoice.del_status='0'
                        $crop_id $product_type_id $varriety_id $pack_size $zone_id $territory_id $distributor_id $between
                        ".$db->get_zone_access($tbl. "zone_info")."
                GROUP BY
                    $tbl" . "product_purchase_order_invoice.crop_id,
                    $tbl" . "product_purchase_order_invoice.product_type_id,
                    $tbl" . "product_purchase_order_invoice.varriety_id,
                    $tbl" . "product_purchase_order_invoice.distributor_id
                ORDER BY
                    $tbl" . "product_purchase_order_invoice.zone_id,
                    $tbl" . "product_purchase_order_invoice.territory_id,
                    $tbl" . "product_purchase_order_invoice.crop_id,
                    $tbl" . "product_purchase_order_invoice.product_type_id,
                    $tbl" . "product_purchase_order_invoice.varriety_id,
                    $tbl" . "product_purchase_order_invoice.pack_size
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
                $total_value=($result_array['prince_per_kg']*$result_array['quty_kg']);
                $tvalue=$tvalue+$total_value;

                $kg = $result_array['quty_kg'];
                $tkg = $tkg + $kg;
                ?>
                <tr class="<?php echo $rowcolor; ?> pointer" id="tr_id<?php echo $i; ?>" onclick="get_rowID('<?php echo $result_array["id"] ?>', '<?php echo $i; ?>')">
                    <!--                        <td>-->
                    <!--                            --><?php
                    //                            if ($invoice_date == '') {
                    //                                echo $db->date_formate($result_array['invoice_date']);
                    //                                $invoice_date = $result_array['invoice_date'];
                    //                                //$currentDate = $preDate;
                    //                            } else if ($invoice_date == $result_array['invoice_date']) {
                    //                                //exit;
                    //                                echo "&nbsp;";
                    //                            } else {
                    //                                echo $db->date_formate($result_array['invoice_date']);
                    //                                $invoice_date = $result_array['invoice_date'];
                    //                            }
                    //                            ?>
                    <!--                        </td>-->
                    <!--                        <td>-->
                    <!--                            --><?php
                    //                            if ($invoice_id == '') {
                    //                                echo $result_array['invoice_id'];
                    //                                $invoice_id = $result_array['invoice_id'];
                    //                                //$currentDate = $preDate;
                    //                            } else if ($invoice_id == $result_array['invoice_id']) {
                    //                                //exit;
                    //                                echo "&nbsp;";
                    //                            } else {
                    //                                echo $result_array['invoice_id'];
                    //                                $invoice_id = $result_array['invoice_id'];
                    //                            }
                    //                            ?>
                    <!--                        </td>-->
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
                    <td style="text-align: right;"><?php echo number_format($result_array['prince_per_kg'],2);?></td>
                    <td style="text-align: right;"><?php echo number_format($result_array['quty_kg'],2);?></td>
                    <td style="text-align: right;">
                        <?php
                        echo number_format($total_value,2);
                        ?>
                    </td>
                </tr>
                <?php
                ++$i;
            }
        }
        ?>
        <tfoot>
        <tr>
            <td colspan="7" style="text-align: right;">Total: </td>
            <td style="text-align: right;"><?php echo number_format($tkg, 2) ?></td>
            <td style="text-align: right;"><?php echo number_format($tvalue, 2) ?></td>
        </tr>
        </tfoot>
        </tbody>
    </table>
    <?php include_once '../../libraries/print_page/Print_footer.php'; ?>
</div>