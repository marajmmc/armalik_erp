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
                    Pack Size
                </th>
                <th style="width:5%; text-align: right">
                    Current Stock Qty(kg)
                </th>
            </tr>
        </thead>
        <tbody>
            <?php
            $tkg = '0';
            $kg = '0';
            $warehouse_name = '';
            $crop_name = '';
            $product_type = '';
            $sql = "SELECT
                                $tbl" . "warehouse_info.warehouse_name,
                                $tbl" . "crop_info.crop_name,
                                $tbl" . "product_type.product_type,
                                $tbl" . "varriety_info.varriety_name,
                                $tbl" . "product_pack_size.pack_size_name,
                                $tbl" . "product_stock.current_stock_qunatity,
                                $tbl" . "product_stock.id
                            FROM $tbl" . "product_stock 
                                LEFT JOIN $tbl" . "warehouse_info ON $tbl" . "warehouse_info.warehouse_id = $tbl" . "product_stock.warehouse_id 
                                LEFT JOIN $tbl" . "crop_info ON $tbl" . "crop_info.crop_id = $tbl" . "product_stock.crop_id 
                                LEFT JOIN $tbl" . "product_type ON $tbl" . "product_type.product_type_id = $tbl" . "product_stock.product_type_id 
                                LEFT JOIN $tbl" . "varriety_info ON $tbl" . "varriety_info.varriety_id = $tbl" . "product_stock.varriety_id 
                                LEFT JOIN $tbl" . "product_pack_size ON $tbl" . "product_pack_size.pack_size_id = $tbl" . "product_stock.pack_size
                            WHERE $tbl" . "product_stock.del_status='0' $crop_id $product_type_id $varriety_id $pack_size $warehouse
                            GROUP BY $tbl" . "product_stock.warehouse_id,
                                $tbl" . "product_stock.crop_id,
                                $tbl" . "product_stock.product_type_id,
                                $tbl" . "product_stock.varriety_id,
                                $tbl" . "product_stock.pack_size
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
                    $kg=($result_array['pack_size_name']*$result_array['current_stock_qunatity'])/1000;
                    $tkg=$tkg+$kg;
                    ?>
                    <tr class="<?php echo $rowcolor; ?> pointer" id="tr_id<?php echo $i; ?>" onclick="get_rowID('<?php echo $result_array["id"] ?>', '<?php echo $i; ?>')">
                        <td>
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
                        <td style="text-align: right;"><?php echo $kg; ?></td>
                    </tr>
                    <?php
                    ++$i;
                }
            }
            ?>
        <tfoot>
            <tr>
                <td colspan="5" style="text-align: right;">Total: </td>
                <td style="text-align: right;"><?php echo number_format($tkg, 2) ?></td>
            </tr>
        </tfoot>
        </tbody>
    </table>
    <?php include_once '../../libraries/print_page/Print_footer.php'; ?>
</div>