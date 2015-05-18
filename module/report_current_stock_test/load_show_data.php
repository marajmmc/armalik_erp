<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$tbl = _DB_PREFIX;

if ($_POST['crop_id'] != "") {
    $crop_id = "AND $tbl" . "product_stock.crop_id='" . $_POST['crop_id'] . "'";
} else {
    $crop_id = "";
}
if ($_POST['product_type_id'] != "") {
    $product_type_id = "AND $tbl" . "product_stock.product_type_id='" . $_POST['product_type_id'] . "'";
} else {
    $product_type_id = "";
}
if ($_POST['varriety_id'] != "") {
    $varriety_id = "AND $tbl" . "product_stock.varriety_id='" . $_POST['varriety_id'] . "'";
} else {
    $varriety_id = "";
}
if ($_POST['pack_size'] != "") {
    $pack_size = "AND $tbl" . "product_stock.pack_size='" . $_POST['pack_size'] . "'";
} else {
    $pack_size = "";
}
if ($_POST['warehouse_id'] != "") {
    $warehouse = "AND $tbl" . "product_stock.warehouse_id='" . $_POST['warehouse_id'] . "'";
} else {
    $warehouse = "";
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
                    Warehouse
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
                <th style="width:5%; text-align: right">
                    Delivery Qty(pieces)
                </th>
                <th style="width:5%; text-align: right">
                    Bonus Qty(pieces)
                </th>
                <th style="width:5%; text-align: right">
                    Short Qty(pieces)
                </th>
                <th style="width:5%; text-align: right">
                    Access Qty(pieces)
                </th>
                <th style="width:5%; text-align: right">
                    Sample Qty(pieces)
                </th>
                <th style="width:5%; text-align: right">
                    RND Qty(pieces)
                </th>
                <th style="width:5%; text-align: right">
                    Transfer Qty(pieces)
                </th>
                <th style="width:5%; text-align: right">
                    Transfer Received Qty(pieces)
                </th>
                <th style="width:5%; text-align: right">
                    Current Stock
                </th>
            </tr>
        </thead>
        <tbody>
            <?php
            $current_stock=0;
            $warehouse_name = '';
            $crop_name = '';
            $product_type = '';
            $sql = "SELECT
                        pi.id,
                        ait_warehouse_info.warehouse_name,
                        ait_crop_info.crop_name,
                        ait_product_type.product_type,
                        ait_varriety_info.varriety_name,
                        ait_product_pack_size.pack_size_name,
                        pi.crop_id,
                        pi.product_type_id,
                        pi.varriety_id,
                        pi.pack_size,
                        (
                        SELECT SUM(ppi.quantity) FROM ait_product_purchase_info AS ppi
                        WHERE
                        ppi.warehouse_id=pi.warehouse_id AND
                        ppi.crop_id=pi.crop_id AND
                        ppi.product_type_id = pi.product_type_id AND
                        ppi.varriety_id = pi.varriety_id AND
                        ppi.pack_size = pi.pack_size
                        ) AS total_product,
                        (
                        SELECT SUM(ppoi.approved_quantity) FROM ait_product_purchase_order_invoice AS ppoi
                        WHERE
                        ppoi.warehouse_id=pi.warehouse_id AND
                        ppoi.crop_id=pi.crop_id AND
                        ppoi.product_type_id = pi.product_type_id AND
                        ppoi.varriety_id = pi.varriety_id AND
                        ppoi.pack_size = pi.pack_size
                        ) AS total_delivery,
                        (
                        SELECT SUM(ppob.quantity) FROM ait_product_purchase_order_bonus AS ppob
                        WHERE
                        ppob.warehouse_id=pi.warehouse_id AND
                        ppob.crop_id=pi.crop_id AND
                        ppob.product_type_id = pi.product_type_id AND
                        ppob.varriety_id = pi.varriety_id AND
                        ppob.pack_size = pi.pack_size
                        ) AS total_bonus,
                        (
                        SELECT SUM(pin.damage_quantity) FROM ait_product_inventory AS pin
                        WHERE
                        pin.warehouse_id=pi.warehouse_id AND
                        pin.crop_id=pi.crop_id AND
                        pin.product_type_id = pi.product_type_id AND
                        pin.varriety_id = pi.varriety_id AND
                        pin.pack_size = pi.pack_size
                        ) AS total_short_qnty,
                        (
                        SELECT SUM(pin.access_quantity) FROM ait_product_inventory AS pin
                        WHERE
                        pin.warehouse_id=pi.warehouse_id AND
                        pin.crop_id=pi.crop_id AND
                        pin.product_type_id = pi.product_type_id AND
                        pin.varriety_id = pi.varriety_id AND
                        pin.pack_size = pi.pack_size
                        ) AS total_access_qnty,
                        (
                        SELECT SUM(ptt.quantity) FROM ait_product_transfer AS ptt
                        WHERE
                        ptt.from_warehouse_id=pi.warehouse_id AND
                        ptt.crop_id=pi.crop_id AND
                        ptt.product_type_id = pi.product_type_id AND
                        ptt.varriety_id = pi.varriety_id AND
                        ptt.pack_size = pi.pack_size
                        ) AS total_transfer_qnty,
                        (
                        SELECT SUM(pttr.quantity) FROM ait_product_transfer AS pttr
                        WHERE
                        pttr.to_warehouse_id=pi.warehouse_id AND
                        pttr.crop_id=pi.crop_id AND
                        pttr.product_type_id = pi.product_type_id AND
                        pttr.varriety_id = pi.varriety_id AND
                        pttr.pack_size = pi.pack_size
                        ) AS total_transfer_receive_qnty,
                        (
                        SELECT SUM(psq.sample_quantity) FROM ait_product_inventory AS psq
                        WHERE
                        psq.warehouse_id=pi.warehouse_id AND
                        psq.crop_id=pi.crop_id AND
                        psq.product_type_id = pi.product_type_id AND
                        psq.varriety_id = pi.varriety_id AND
                        psq.pack_size = pi.pack_size
                        ) AS total_sample_qnty,
                        (
                        SELECT SUM(prq.rnd_quantity) FROM ait_product_inventory AS prq
                        WHERE
                        prq.warehouse_id=pi.warehouse_id AND
                        prq.crop_id=pi.crop_id AND
                        prq.product_type_id = pi.product_type_id AND
                        prq.varriety_id = pi.varriety_id AND
                        prq.pack_size = pi.pack_size
                        ) AS total_rnd_qnty,
                        (
                        SELECT SUM(ppocrq.return_quantity) FROM ait_product_purchase_order_challan_return AS ppocrq
                        WHERE
                        ppocrq.warehouse_id=pi.warehouse_id AND
                        ppocrq.crop_id=pi.crop_id AND
                        ppocrq.product_type_id = pi.product_type_id AND
                        ppocrq.varriety_id = pi.varriety_id AND
                        ppocrq.pack_size = pi.pack_size
                        ) AS total_product_return_qnty
                        FROM
                        ait_product_info AS pi
                        LEFT JOIN ait_warehouse_info ON ait_warehouse_info.warehouse_id = pi.warehouse_id
                        LEFT JOIN ait_crop_info ON ait_crop_info.crop_id = pi.crop_id
                        LEFT JOIN ait_product_type ON ait_product_type.product_type_id = pi.product_type_id
                        LEFT JOIN ait_varriety_info ON ait_varriety_info.varriety_id = pi.varriety_id
                        LEFT JOIN ait_product_pack_size ON ait_product_pack_size.pack_size_id = pi.pack_size
                        WHERE
                        pi.del_status=0
                        $crop_id $product_type_id $varriety_id $pack_size $warehouse
                        GROUP BY
                        ait_warehouse_info.warehouse_id,pi.crop_id, pi.product_type_id, pi.varriety_id, pi.pack_size
                        ORDER BY
                        ait_warehouse_info.warehouse_id,
                        ait_crop_info.crop_id,
                        ait_product_type.product_type_id,
                        ait_product_pack_size.pack_size_id
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
                    $current_stock=((($result_array['total_product']-($result_array['total_delivery']+$result_array['total_bonus']+$result_array['total_sample_qnty']+$result_array['total_rnd_qnty']))-$result_array['total_short_qnty']))+$result_array['total_access_qnty'];
                        ?>
                        <tr class="<?php echo $rowcolor; ?> pointer" id="tr_id<?php echo $i; ?>" onclick="get_rowID('<?php echo $result_array["id"] ?>', '<?php echo $i; ?>')">
                            <td title="Warehouse">
                                <?php
                                if ($warehouse_name == '') {
                                    echo $result_array['warehouse_name'];
                                    $warehouse_name = $result_array['warehouse_name'];
                                    //$currentDate = $preDate;
                                } else if ($warehouse_name == $result_array['warehouse_name']) {
                                    //exit;
                                    echo "&nbsp;";
                                } else {
                                    echo $result_array['warehouse_name'];
                                    $warehouse_name = $result_array['warehouse_name'];
                                }
                                ?>
                            </td>
                           <td title="Crop">
                                <?php
                                if ($crop_name == '') {
                                    echo $result_array['crop_name']."-".$result_array['crop_id'];
                                    $crop_name = $result_array['crop_name'];
                                    //$currentDate = $preDate;
                                } else if ($crop_name == $result_array['crop_name']) {
                                    //exit;
                                    echo "&nbsp;";
                                } else {
                                    echo $result_array['crop_name']."-".$result_array['crop_id'];
                                    $crop_name = $result_array['crop_name'];
                                }
                                ?>
                            </td>
                            <td title="Product Type">
                                <?php
                                if ($product_type == '') {
                                    echo $result_array['product_type']."-".$result_array['product_type_id'];
                                    $product_type = $result_array['product_type'];
                                    //$currentDate = $preDate;
                                } else if ($product_type == $result_array['product_type']) {
                                    //exit;
                                    echo "&nbsp;";
                                } else {
                                    echo $result_array['product_type']."-".$result_array['product_type_id'];
                                    $product_type = $result_array['product_type'];
                                }
                                ?>
                            </td>
                            <td title="Variety"><?php echo $result_array['varriety_name']."-".$result_array['varriety_id']; ?></td>
                            <td title="Pack Size(gm)"><?php echo $result_array['pack_size_name']."-".$result_array['pack_size']; ?></td>
                            <td title="Purchase Qty(pieces) " style="text-align: right;"><?php echo $result_array['total_product']; ?></td>
                            <td title="Delivery Qty(pieces)" style="text-align: right;"><?php echo $result_array['total_delivery']; ?></td>
                            <td title="Bonus Qty(pieces)" style="text-align: right;"><?php echo $result_array['total_bonus']; ?></td>
                            <td title="Short Qty(pieces) " style="text-align: right;"><?php echo $result_array['total_short_qnty']; ?></td>
                            <td title="Access Qty(pieces) " style="text-align: right;"><?php echo $result_array['total_access_qnty']; ?></td>
                            <td title="Sample Qty(pieces) " style="text-align: right;"><?php echo $result_array['total_sample_qnty']; ?></td>
                            <td title="RND Qty(pieces) " style="text-align: right;"><?php echo $result_array['total_rnd_qnty']; ?></td>
                            <td title="Transfer Qty(pieces) " style="text-align: right;"><?php echo $result_array['total_transfer_qnty']; ?></td>
                            <td title="Transfer Receive Qty(pieces) " style="text-align: right;"><?php echo $result_array['total_transfer_receive_qnty']; ?></td>
                            <td title="Return Qty(pieces) " style="text-align: right;"><?php echo $result_array['total_product_return_qnty']; ?></td>
                            <td title="Current Stock" style="text-align: right;"><?php echo $current_stock; ?></td>
                        </tr>
                        <?php
                        ++$i;
                    }
                }
            ?>
<!--        <tfoot>-->
<!--            <tr>-->
<!--                <td colspan="5" style="text-align: right;">Total: </td>-->
<!--                <!--<td style="text-align: right;">--><?php //// echo number_format($INQ, 2)  ?><!--</td>-->-->
<!--                <td style="text-align: right;">--><?php //echo number_format($TQ, 2) ?><!--</td>-->
<!--                <td style="text-align: right;">--><?php //echo number_format($TRQ, 2) ?><!--</td>-->
<!--                <td style="text-align: right;">--><?php //echo number_format($DQ, 2) ?><!--</td>-->
<!--                <td style="text-align: right;">--><?php //echo number_format($BQ, 2) ?><!--</td>-->
<!--                <td style="text-align: right;">--><?php //echo number_format($DaQ, 2) ?><!--</td>-->
<!--                <td style="text-align: right;">--><?php //echo number_format($AcQ, 2) ?><!--</td>-->
<!--                <td style="text-align: right;">--><?php //echo number_format($ReQ, 2) ?><!--</td>-->
<!--                <td style="text-align: right;">--><?php //echo number_format($CQ, 2); ?><!--</td>-->
<!--            </tr>-->
<!--        </tfoot>-->
        </tbody>
    </table>
    <?php include_once '../../libraries/print_page/Print_footer.php'; ?>
</div>