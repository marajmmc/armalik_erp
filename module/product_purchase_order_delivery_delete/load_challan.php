<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$tbl = _DB_PREFIX;
$sqli = "SELECT
            $tbl" . "product_purchase_order_invoice.invoice_id,
            $tbl" . "product_purchase_order_invoice.invoice_date,
            $tbl" . "product_purchase_order_invoice.purchase_order_id,
            $tbl" . "distributor_info.distributor_name,
            $tbl" . "product_purchase_order_challan.challan_id,
            $tbl" . "employee_basic_info.employee_id_no
        FROM
            $tbl" . "product_purchase_order_invoice
            LEFT JOIN $tbl" . "distributor_info ON $tbl" . "distributor_info.distributor_id = $tbl" . "product_purchase_order_invoice.distributor_id
            LEFT JOIN $tbl" . "product_purchase_order_challan ON $tbl" . "product_purchase_order_challan.invoice_id = $tbl" . "product_purchase_order_invoice.invoice_id AND $tbl" . "product_purchase_order_challan.purchase_order_id = $tbl" . "product_purchase_order_invoice.purchase_order_id
            LEFT JOIN $tbl" . "product_purchase_order_request ON $tbl" . "product_purchase_order_request.invoice_id = $tbl" . "product_purchase_order_invoice.invoice_id
            LEFT JOIN $tbl" . "user_login ON $tbl" . "user_login.user_id = $tbl" . "product_purchase_order_request.entry_by
            LEFT JOIN $tbl" . "employee_basic_info ON $tbl" . "employee_basic_info.employee_id = $tbl" . "user_login.employee_id
        WHERE $tbl" . "product_purchase_order_invoice.invoice_id='" . $_POST['rowID'] . "' AND $tbl" . "product_purchase_order_invoice.approved_quantity!='0'
        GROUP BY $tbl" . "product_purchase_order_invoice.invoice_id";
if ($db->open()) {
    $resulti = $db->query($sqli);
    $rowi = $db->fetchAssoc($resulti);
}
if ($rowi['invoice_id'] == "") {
    echo "<div style='text-align: center; color: red;'><b>Invoice No Not Found! Please Try Again.</b></div>";
} else {
    ?>

    <a class="btn btn-small btn-success" data-original-title="" onclick="print_rpt()" style="float: right;">
        <i class="icon-print" data-original-title="Share"> </i> Print
    </a>
    <div id="PrintArea" style="background-color: white;" >
        <div style="text-align: center;">
            <h3>A.R. Malik & Company (Pvt) Ltd.</h3>
            House No.: 312 (4th Floor),<br />
            Road No.: 10, Block: D,<br />
            Bashundhara R/A,<br />
            Dhaka - 1229, Bangladesh,<br />
            E-mail: armalik@armalikgroup.com.bd<br />
            <b><u>Invoice Print</u></b>
        </div>

        <div style="text-align: center;">
            <b>Customer: <?php echo $rowi['distributor_name'] ?></b>
        </div>
        <label style="float: right; font-size: 11px;">Print Date: <?php echo $db->date_formate($db->ToDayDate()) ?></label>
        Invoice Date: <?php echo $db->date_formate($rowi['invoice_date']) ?><br />
        Invoice No: <?php echo $rowi['invoice_id'] ?><br />
        Ref. No: <?php echo $rowi['purchase_order_id'] ?><br />
        Challan No: <?php echo $rowi['challan_id'] ?><br />
        Employee ID: <?php echo $rowi['employee_id_no'] ?>
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
                $bqnty = '0';
                $tprice = '0';
                $purchase_order_id = '';
                $invoice_date = '';
                $zone_name = '';
                $territory_name = '';
                $distributor_name = '';
                $sql = "SELECT
                        $tbl" . "product_purchase_order_invoice.id,
                        $tbl" . "product_purchase_order_invoice.purchase_order_id,
                        $tbl" . "product_purchase_order_invoice.invoice_date,
                        $tbl" . "product_purchase_order_invoice.price,
                        $tbl" . "product_purchase_order_invoice.quantity,
                        $tbl" . "product_purchase_order_invoice.approved_quantity,
                        $tbl" . "product_purchase_order_invoice.total_price,
                        $tbl" . "zone_info.zone_name,
                        $tbl" . "territory_info.territory_name,
                        $tbl" . "distributor_info.distributor_name,
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
                        $tbl" . "product_purchase_order_invoice.del_status='0' AND
                        $tbl" . "product_purchase_order_invoice.invoice_id='" . $_POST['rowID'] . "' AND $tbl" . "product_purchase_order_invoice.approved_quantity!='0'
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
                            <td><?php echo $result_array['crop_name']; ?></td>
                            <td><?php echo $result_array['product_type']; ?></td>
                            <td><?php echo $result_array['varriety_name']; ?></td>
                            <td><?php echo $result_array['pack_size_name']; ?></td>
                            <td style="text-align: right;"><?php echo $result_array['price']; ?></td>
                            <td style="text-align: right;"><?php echo $result_array['approved_quantity']; ?></td>
                            <td style="text-align: right;">0</td>
                            <td style="text-align: right;"><?php echo $result_array['total_price']; ?></td>
                        </tr>
                        <?php
                        ++$i;
                    }
                }
                ?>
                <?php
                $sqlb = "SELECT
                            $tbl" . "product_purchase_order_bonus.invoice_id,
                            $tbl" . "zone_info.zone_name,
                            $tbl" . "territory_info.territory_name,
                            $tbl" . "distributor_info.distributor_name,
                            $tbl" . "crop_info.crop_name,
                            $tbl" . "product_type.product_type,
                            $tbl" . "varriety_info.varriety_name,
                            $tbl" . "product_pack_size.pack_size_name,
                            $tbl" . "product_purchase_order_bonus.quantity
                        FROM
                            $tbl" . "product_purchase_order_bonus
                            LEFT JOIN $tbl" . "zone_info ON $tbl" . "zone_info.zone_id = $tbl" . "product_purchase_order_bonus.zone_id
                            LEFT JOIN $tbl" . "territory_info ON $tbl" . "territory_info.territory_id = $tbl" . "product_purchase_order_bonus.territory_id
                            LEFT JOIN $tbl" . "distributor_info ON $tbl" . "distributor_info.distributor_id = $tbl" . "product_purchase_order_bonus.distributor_id
                            LEFT JOIN $tbl" . "crop_info ON $tbl" . "crop_info.crop_id = $tbl" . "product_purchase_order_bonus.crop_id
                            LEFT JOIN $tbl" . "product_type ON $tbl" . "product_type.product_type_id = $tbl" . "product_purchase_order_bonus.product_type_id
                            LEFT JOIN $tbl" . "varriety_info ON $tbl" . "varriety_info.varriety_id = $tbl" . "product_purchase_order_bonus.varriety_id
                            LEFT JOIN $tbl" . "product_pack_size ON $tbl" . "product_pack_size.pack_size_id = $tbl" . "product_purchase_order_bonus.pack_size
                        WHERE $tbl" . "product_purchase_order_bonus.invoice_id='" . $_POST['rowID'] . "'
                    ";
                if ($db->open()) {
                    $resultb = $db->query($sqlb);
                    while ($rowb = $db->fetchAssoc($resultb)) {
                        $bqnty = $bqnty + $rowb['quantity'];
                        ?>
                        <tr class="<?php echo $rowcolor; ?> pointer" id="tr_id<?php echo $i; ?>" onclick="get_rowID('<?php echo $rowb["id"] ?>', '<?php echo $i; ?>')">
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
                            <td><?php echo $rowb['crop_name']; ?></td>
                            <td><?php echo $rowb['product_type']; ?></td>
                            <td><?php echo $rowb['varriety_name']; ?></td>
                            <td><?php echo $rowb['pack_size_name']; ?></td>
                            <td style="text-align: right;">0</td>
                            <td style="text-align: right;">0</td>
                            <td style="text-align: right;"><?php echo $rowb['quantity']; ?></td>
                            <td style="text-align: right;">0</td>
                        </tr>
                        <?php
                        ++$i;
                    }
                }
                ?>

            <tfoot>
                <tr>
                    <td colspan="6" style="text-align: right;">Total: </td>
                    <td style="text-align: right;"><?php echo number_format($price, 2); ?></td>
                    <td style="text-align: right;"><?php echo number_format($qnty, 2) ?></td>
                    <td style="text-align: right;"><?php echo number_format($bqnty, 2) ?></td>
                    <td style="text-align: right;">BDT. <b><?php echo number_format($tprice, 2) ?></b></td>
                </tr>
                <tr>
                    <td colspan="15" >Amount Chargeable (In word)<br />  <b>Taka <?php echo $db->number_convert_inword($tprice) ?> Only</b></td>
                </tr>
            </tfoot>
        </table>
        <br />
        <div style="text-align: right">
            <b>for A.R. Malik & Company (Pvt.) Ltd.</b>
            <br />
            <br />
            <br />
            Authorized Signatory
        </div>
    </div>
    <?php
}?>