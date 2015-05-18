<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$dbb = new Database();
$tbl = _DB_PREFIX;
$sqli = "SELECT
            $tbl" . "product_purchase_order_challan.challan_id,
            $tbl" . "product_purchase_order_challan.challan_date,
            $tbl" . "distributor_info.distributor_name,
            $tbl" . "distributor_info.owner_name,
            $tbl" . "distributor_info.address,
            $tbl" . "distributor_info.phone,
            $tbl" . "product_purchase_order_challan.`status`
        FROM
            $tbl" . "product_purchase_order_challan
            LEFT JOIN $tbl" . "distributor_info ON $tbl" . "distributor_info.distributor_id = $tbl" . "product_purchase_order_challan.distributor_id
        WHERE $tbl" . "product_purchase_order_challan.challan_id='" . $_POST['invoice_id'] . "' AND 
            $tbl" . "product_purchase_order_challan.del_status='0' AND 
            $tbl" . "product_purchase_order_challan.status='Received'
        GROUP BY $tbl" . "product_purchase_order_challan.challan_id";
if ($db->open()) {
    $resulti = $db->query($sqli);
    $rowi = $db->fetchAssoc($resulti);
}
if ($rowi['challan_id'] == "") {
    echo "<div style='text-align: center; color: red;'><b>Challan No Not Found! Please Try Again.</b></div>";
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
            <b><u><?php echo $db->Get_Auto_TaskName() ?></u></b>
        </div>


        <label style="float: right; font-size: 11px;">Print Date: <?php echo $db->date_formate($db->ToDayDate()) ?></label>
        Challan Date: <?php echo $db->date_formate($rowi['challan_date']) ?><br />
        Challan No: <?php echo $rowi['challan_id'] ?>
        <!--Ref. No: <?php // echo $rowi['purchase_order_id']     ?><br />-->
        <!--Challan No: <?php // echo $rowi['challan_id']     ?><br />-->
        <!--Employee ID: <?php // echo $rowi['employee_id_no']     ?><br />-->
        <div style="text-align: left;">
            <b>Customer: <u><?php echo $rowi['distributor_name'] ?></u></b><br />
            <b>Address: <u><?php echo $rowi['address'] ?></u></b>
        </div>
        <table class="table table-condensed table-striped table-hover table-bordered pull-left report" id="data-table">
            <thead>
                <tr>
                    <th style="width:5%">
                        Sl No
                    </th>
                    <th style="width:50%">
                        Description
                    </th>
                    <th style="width:5%; text-align: right;">
                        Qty(pieces)
                    </th>
                    <th style="width:5%; text-align: right;">
                        Bonus Qty(pieces)
                    </th>
                    <th style="width:5%">
                        Remark's
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
                                $tbl" . "crop_info.crop_name,
                                $tbl" . "product_type.product_type,
                                $tbl" . "varriety_info.varriety_name,
                                $tbl" . "product_pack_size.pack_size_name,
                                $tbl" . "product_purchase_order_challan.quantity
                            FROM
                                $tbl" . "product_purchase_order_challan
                                LEFT JOIN $tbl" . "crop_info ON $tbl" . "crop_info.crop_id = $tbl" . "product_purchase_order_challan.crop_id
                                LEFT JOIN $tbl" . "product_type ON $tbl" . "product_type.product_type_id = $tbl" . "product_purchase_order_challan.product_type_id
                                LEFT JOIN $tbl" . "varriety_info ON $tbl" . "varriety_info.varriety_id = $tbl" . "product_purchase_order_challan.varriety_id
                                LEFT JOIN $tbl" . "product_pack_size ON $tbl" . "product_pack_size.pack_size_id = $tbl" . "product_purchase_order_challan.pack_size
                            WHERE
                                $tbl" . "product_purchase_order_challan.del_status='0' AND
                                $tbl" . "product_purchase_order_challan.challan_id='" . $_POST['invoice_id'] . "'
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
                        $qnty = $qnty + $result_array['quantity'];
                        ?>
                        <tr class="<?php echo $rowcolor; ?> pointer" id="tr_id<?php echo $i; ?>" onclick="get_rowID('<?php echo $result_array["id"] ?>', '<?php echo $i; ?>')">
                            <td>
                                <?php echo $i; ?>
                            </td>
                            <td>
                                <?php echo $result_array['crop_name']; ?>, 
                                <?php echo $result_array['product_type']; ?>,
                                <?php echo $result_array['varriety_name']; ?>,
                                <?php echo $result_array['pack_size_name']; ?>(gm)
                            </td>
                            <td style="text-align: right;"><?php echo $result_array['quantity']; ?></td>
                            <td style="text-align: right;">0</td>
                            <td>&nbsp;</td>
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
                        WHERE $tbl" . "product_purchase_order_bonus.challan_id='" . $rowi['challan_id'] . "'
                    ";
                if ($dbb->open()) {
                    $resultb = $dbb->query($sqlb);
                    while ($rowb = $dbb->fetchAssoc($resultb)) {
                        $bqnty = $bqnty + $rowb['quantity'];
                        ?>
                        <tr class="<?php echo $rowcolor; ?> pointer" id="tr_id<?php echo $i; ?>" onclick="get_rowID('<?php echo $result_array["id"] ?>', '<?php echo $i; ?>')">
                            <td>
                                <?php echo $i; ?>bonus
                            </td>
                            <td>
                                <?php echo $rowb['crop_name']; ?>, 
                                <?php echo $rowb['product_type']; ?>,
                                <?php echo $rowb['varriety_name']; ?>,
                                <?php echo $rowb['pack_size_name']; ?>(gm)
                            </td>
                            <td style="text-align: right;">0</td>
                            <td style="text-align: right;"><?php echo $rowb['quantity']; ?></td>
                            <td>&nbsp;</td>
                        </tr>
                        <?php
                        ++$i;
                    }
                }
                ?>
            <tfoot>
                <tr>
                    <td colspan="2" style="text-align: right;">Total: </td>
                    <td style="text-align: right;"><?php echo number_format($qnty, 2) ?></td>
                    <td style="text-align: right;"><?php echo number_format($bqnty, 2) ?></td>
                </tr>
<!--                <tr>
                    <td colspan="15" >Amount Chargeable (In word)<br />  <b>Taka <?php echo $db->number_convert_inword($qnty) ?> Only</b></td>
                </tr>-->
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
<?php }
?>