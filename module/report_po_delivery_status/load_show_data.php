<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$tbl = _DB_PREFIX;


if ($_POST['zone_id'] != "") {
    $zone_id = "AND ($tbl" . "product_purchase_order_invoice.zone_id='" . $_POST['zone_id'] . "' OR $tbl" . "product_purchase_order_request.zone_id='" . $_POST['zone_id'] . "' )";
} else {
    $zone_id = "";
}
if ($_POST['territory_id'] != "") {
    $territory_id = "AND ($tbl" . "product_purchase_order_invoice.territory_id='" . $_POST['territory_id'] . "' OR $tbl" . "product_purchase_order_request.territory_id='" . $_POST['territory_id'] . "')";
} else {
    $territory_id = "";
}
if ($_POST['distributor_id'] != "") {
    $distributor_id = "AND ($tbl" . "product_purchase_order_invoice.distributor_id='" . $_POST['distributor_id'] . "' OR $tbl" . "product_purchase_order_request.distributor_id='" . $_POST['distributor_id'] . "')";
} else {
    $distributor_id = "";
}
if ($_POST['from_date'] != "" && $_POST['to_date'] != "") {
    $between = "AND ($tbl" . "product_purchase_order_invoice.invoice_date BETWEEN '" . $db->date_formate($_POST['from_date']) . "' AND '" . $db->date_formate($_POST['to_date']) . "' OR $tbl" . "product_purchase_order_request.purchase_order_date BETWEEN '" . $db->date_formate($_POST['from_date']) . "' AND '" . $db->date_formate($_POST['to_date']) . "')";
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
                    Zone
                </th>
                <th style="width:5%">
                    Territory
                </th>
                <th style="width:5%">
                    Distributor
                </th>
                <th style="width:5%">
                    PO. No
                </th>
                <th style="width:5%">
                    PO Status
                </th>
                <th style="width:5%">
                    Delivery Status
                </th>
                <th style="width:5%">
                    Received Status
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
            $sql = "SELECT
                        $tbl" . "product_purchase_order_request.purchase_order_id,
                        $tbl" . "product_purchase_order_request.purchase_order_date,
                        $tbl" . "product_purchase_order_request.`status` AS po_status,
                        $tbl" . "product_purchase_order_invoice.`status` AS po_delivery_status,
                        $tbl" . "product_purchase_order_challan.`status` AS po_delivery_receive_status,
                        $tbl" . "zone_info.zone_name,
                        $tbl" . "territory_info.territory_name,
                        CONCAT_WS(' - ', $tbl" . "distributor_info.customer_code, $tbl" . "distributor_info.distributor_name) AS distributor_name
                    FROM
                        $tbl" . "product_purchase_order_request
                        LEFT JOIN $tbl" . "product_purchase_order_invoice ON $tbl" . "product_purchase_order_invoice.purchase_order_id = $tbl" . "product_purchase_order_request.purchase_order_id
                        LEFT JOIN $tbl" . "product_purchase_order_challan ON $tbl" . "product_purchase_order_challan.purchase_order_id = $tbl" . "product_purchase_order_request.purchase_order_id
                        LEFT JOIN $tbl" . "zone_info ON $tbl" . "zone_info.zone_id = $tbl" . "product_purchase_order_request.zone_id
                        LEFT JOIN $tbl" . "territory_info ON $tbl" . "territory_info.territory_id = $tbl" . "product_purchase_order_request.territory_id
                        LEFT JOIN $tbl" . "distributor_info ON $tbl" . "distributor_info.distributor_id = $tbl" . "product_purchase_order_request.distributor_id
                    WHERE 
                        ($tbl" . "product_purchase_order_invoice.del_status='0' OR 
                        $tbl" . "product_purchase_order_request.del_status='0' OR
                        $tbl" . "product_purchase_order_challan.del_status='0') 
                        $zone_id $territory_id $distributor_id $between
                    GROUP BY $tbl" . "product_purchase_order_request.purchase_order_id
                    ORDER BY $tbl" . "product_purchase_order_request.purchase_order_id
                        
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
//                    $price = $price + $result_array['price'];
//                    $qnty = $qnty + $result_array['quantity'];
//                    $aqnty = $aqnty + $result_array['approved_quantity'];
//                    $tprice = $tprice + $result_array['total_price'];
                    ?>
                    <tr class="<?php echo $rowcolor; ?> pointer" id="tr_id<?php echo $i; ?>" onclick="get_rowID('<?php echo $result_array["id"] ?>', '<?php echo $i; ?>')">
                        <td>
                            <?php
//                            if ($invoice_date == '') {
//                                echo $db->date_formate($result_array['purchase_order_date']);
//                                $invoice_date = $result_array['purchase_order_date'];
//                                //$currentDate = $preDate;
//                            } else if ($invoice_date == $result_array['purchase_order_date']) {
//                                //exit;
//                                echo "&nbsp;";
//                            } else {
//                                echo $db->date_formate($result_array['purchase_order_date']);
//                                $invoice_date = $result_array['purchase_order_date'];
//                            }
                            echo $db->date_formate($result_array['purchase_order_date']);
                            ?>
                        </td>
                        <td>
                            <?php
//                            if ($zone_name == '') {
//                                echo $result_array['zone_name'];
//                                $zone_name = $result_array['zone_name'];
//                                //$currentDate = $preDate;
//                            } else if ($zone_name == $result_array['zone_name']) {
//                                //exit;
//                                echo "&nbsp;";
//                            } else {
//                                echo $result_array['zone_name'];
//                                $zone_name = $result_array['zone_name'];
//                            }
                            echo $result_array['zone_name'];
                            ?>
                        </td>
                        <td>
                            <?php
//                            if ($territory_name == '') {
//                                echo $result_array['territory_name'];
//                                $territory_name = $result_array['territory_name'];
//                                //$currentDate = $preDate;
//                            } else if ($territory_name == $result_array['territory_name']) {
//                                //exit;
//                                echo "&nbsp;";
//                            } else {
//                                echo $result_array['territory_name'];
//                                $territory_name = $result_array['territory_name'];
//                            }
                            echo $result_array['territory_name'];
                            ?>
                        </td>
                        <td>
                            <?php
//                            if ($distributor_name == '') {
//                                echo $result_array['distributor_name'];
//                                $distributor_name = $result_array['distributor_name'];
//                                //$currentDate = $preDate;
//                            } else if ($distributor_name == $result_array['distributor_name']) {
//                                //exit;
//                                echo "&nbsp;";
//                            } else {
//                                echo $result_array['distributor_name'];
//                                $distributor_name = $result_array['distributor_name'];
//                            }
                            echo $result_array['distributor_name'];
                            ?>
                        </td>
                        <td>
                            <?php
//                            if ($purchase_order_id == '') {
//                                echo $result_array['purchase_order_id'];
//                                $purchase_order_id = $result_array['purchase_order_id'];
//                                //$currentDate = $preDate;
//                            } else if ($purchase_order_id == $result_array['purchase_order_id']) {
//                                //exit;
//                                echo "&nbsp;";
//                            } else {
//                                echo $result_array['purchase_order_id'];
//                                $purchase_order_id = $result_array['purchase_order_id'];
//                            }
                            echo $result_array['purchase_order_id'];
                            ?>
                        </td>
                        <td><?php echo $result_array['po_status']; ?>&nbsp;</td>
                        <td><?php echo $result_array['po_delivery_status']; ?>&nbsp;</td>
                        <td><?php echo $result_array['po_delivery_receive_status']; ?>&nbsp;</td>
                    </tr>
                    <?php
                    ++$i;
                }
            }
            ?>
        </tbody>
    </table>
    <?php include_once '../../libraries/print_page/Print_footer.php'; ?>
</div>