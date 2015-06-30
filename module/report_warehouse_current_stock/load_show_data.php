<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$tbl = _DB_PREFIX;

if ($_POST['crop_id'] != "")
{
    $crop_id = "AND $tbl" . "product_info.crop_id='" . $_POST['crop_id'] . "'";
}
else
{
    $crop_id = "";
}
if ($_POST['product_type_id'] != "")
{
    $product_type_id = "AND $tbl" . "product_info.product_type_id='" . $_POST['product_type_id'] . "'";
}
else
{
    $product_type_id = "";
}
if ($_POST['varriety_id'] != "") {
    $varriety_id = "AND $tbl" . "product_info.varriety_id='" . $_POST['varriety_id'] . "'";
} else {
    $varriety_id = "";
}
if ($_POST['pack_size'] != "") {
    $pack_size = "AND $tbl" . "product_info.pack_size='" . $_POST['pack_size'] . "'";
} else {
    $pack_size = "";
}
if ($_POST['warehouse_id'] != "") {
    $warehouse_id = "AND $tbl" . "product_info.warehouse_id='" . $_POST['warehouse_id'] . "'";
} else {
    $warehouse_id = "";
}

$sql = "SELECT
            pi.id,
            ait_warehouse_info.warehouse_name,
            ait_warehouse_info.warehouse_id,
            ait_crop_info.crop_name,
            ait_product_type.product_type,
            ait_varriety_info.varriety_name,
            ait_product_pack_size.pack_size_name,
            pi.crop_id,
            pi.product_type_id,
            pi.varriety_id,
            pi.pack_size,
            (
                SELECT SUM(ppi.opening_balance) FROM ait_product_purchase_info AS ppi
                WHERE
                ppi.year_id=pi.year_id AND
                ppi.warehouse_id=pi.warehouse_id AND
                ppi.crop_id=pi.crop_id AND
                ppi.product_type_id = pi.product_type_id AND
                ppi.varriety_id = pi.varriety_id AND
                ppi.pack_size = pi.pack_size
            ) AS total_opening_balance,
            (
                SELECT SUM(ppi.quantity) FROM ait_product_purchase_info AS ppi
                WHERE
                ppi.year_id=pi.year_id AND
                ppi.warehouse_id=pi.warehouse_id AND
                ppi.crop_id=pi.crop_id AND
                ppi.product_type_id = pi.product_type_id AND
                ppi.varriety_id = pi.varriety_id AND
                ppi.pack_size = pi.pack_size
            ) AS total_product,
            (
                SELECT SUM(ppoi.approved_quantity) FROM ait_product_purchase_order_invoice AS ppoi
                WHERE
                ppoi.year_id=pi.year_id AND
                ppoi.warehouse_id=pi.warehouse_id AND
                ppoi.crop_id=pi.crop_id AND
                ppoi.product_type_id = pi.product_type_id AND
                ppoi.varriety_id = pi.varriety_id AND
                ppoi.pack_size = pi.pack_size
            ) AS total_delivery,
            (
                SELECT SUM(ppob.quantity) FROM ait_product_purchase_order_bonus AS ppob
                WHERE
                ppob.year_id=pi.year_id AND
                ppob.warehouse_id=pi.warehouse_id AND
                ppob.crop_id=pi.crop_id AND
                ppob.product_type_id = pi.product_type_id AND
                ppob.varriety_id = pi.varriety_id AND
                ppob.pack_size = pi.pack_size
            ) AS total_bonus,
            (
                SELECT SUM(pin.damage_quantity) FROM ait_product_inventory AS pin
                WHERE
                pin.year_id=pi.year_id AND
                pin.warehouse_id=pi.warehouse_id AND
                pin.crop_id=pi.crop_id AND
                pin.product_type_id = pi.product_type_id AND
                pin.varriety_id = pi.varriety_id AND
                pin.pack_size = pi.pack_size
            ) AS total_short_qnty,
            (
                SELECT SUM(pin.access_quantity) FROM ait_product_inventory AS pin
                WHERE
                pin.year_id=pi.year_id AND
                pin.warehouse_id=pi.warehouse_id AND
                pin.crop_id=pi.crop_id AND
                pin.product_type_id = pi.product_type_id AND
                pin.varriety_id = pi.varriety_id AND
                pin.pack_size = pi.pack_size
            ) AS total_access_qnty,
            (
                SELECT SUM(pinsq.sample_quantity) FROM ait_product_inventory AS pinsq
                WHERE
                pinsq.year_id=pi.year_id AND
                pinsq.warehouse_id=pi.warehouse_id AND
                pinsq.crop_id=pi.crop_id AND
                pinsq.product_type_id = pi.product_type_id AND
                pinsq.varriety_id = pi.varriety_id AND
                pinsq.pack_size = pi.pack_size
            ) AS total_sample_quantity,
            (
                SELECT SUM(pinrq.rnd_quantity) FROM ait_product_inventory AS pinrq
                WHERE
                pinrq.year_id=pi.year_id AND
                pinrq.warehouse_id=pi.warehouse_id AND
                pinrq.crop_id=pi.crop_id AND
                pinrq.product_type_id = pi.product_type_id AND
                pinrq.varriety_id = pi.varriety_id AND
                pinrq.pack_size = pi.pack_size
            ) AS total_rnd_quantity
        FROM
            ait_product_info AS pi
            LEFT JOIN ait_warehouse_info ON ait_warehouse_info.warehouse_id = pi.warehouse_id
            LEFT JOIN ait_crop_info ON ait_crop_info.crop_id = pi.crop_id
            LEFT JOIN ait_product_type ON ait_product_type.product_type_id = pi.product_type_id
            LEFT JOIN ait_varriety_info ON ait_varriety_info.varriety_id = pi.varriety_id
            LEFT JOIN ait_product_pack_size ON ait_product_pack_size.pack_size_id = pi.pack_size
        WHERE
            pi.del_status=0
            $crop_id $product_type_id $varriety_id $pack_size
        GROUP BY
            pi.year_id, pi.crop_id, pi.product_type_id, pi.varriety_id, pi.pack_size
        ORDER BY
            ait_crop_info.crop_id,
            ait_product_type.product_type_id,
            ait_product_pack_size.pack_size_id
            ";
$data=array();
$warehouse=array();
if($db->open())
{
    $result=$db->query($sql);
    while($row=$db->fetchAssoc($result))
    {
        $data[$row['crop_id']]['crop_name']=$row['crop_name'];
        $data[$row['crop_id']]['type'][$row['product_type_id']]['product_type']=$row['product_type'];
        $data[$row['crop_id']]['type'][$row['product_type_id']]['variety'][$row['varriety_id']]['varriety_name']=$row['varriety_name'];
        $data[$row['crop_id']]['type'][$row['product_type_id']]['variety'][$row['varriety_id']]['pack_size'][$row['pack_size']]['pack_size_name']=$row['pack_size_name'];
        $data[$row['crop_id']]['type'][$row['product_type_id']]['variety'][$row['varriety_id']]['pack_size'][$row['pack_size']]['total_opening_balance']=$row['total_opening_balance'];
        $data[$row['crop_id']]['type'][$row['product_type_id']]['variety'][$row['varriety_id']]['pack_size'][$row['pack_size']]['total_product']=$row['total_product'];
        $data[$row['crop_id']]['type'][$row['product_type_id']]['variety'][$row['varriety_id']]['pack_size'][$row['pack_size']]['total_delivery']=$row['total_delivery'];
        $data[$row['crop_id']]['type'][$row['product_type_id']]['variety'][$row['varriety_id']]['pack_size'][$row['pack_size']]['total_bonus']=$row['total_bonus'];
        $data[$row['crop_id']]['type'][$row['product_type_id']]['variety'][$row['varriety_id']]['pack_size'][$row['pack_size']]['total_short_qnty']=$row['total_short_qnty'];
        $data[$row['crop_id']]['type'][$row['product_type_id']]['variety'][$row['varriety_id']]['pack_size'][$row['pack_size']]['total_access_qnty']=$row['total_access_qnty'];
        $data[$row['crop_id']]['type'][$row['product_type_id']]['variety'][$row['varriety_id']]['pack_size'][$row['pack_size']]['total_sample_quantity']=$row['total_sample_quantity'];
        $data[$row['crop_id']]['type'][$row['product_type_id']]['variety'][$row['varriety_id']]['pack_size'][$row['pack_size']]['total_rnd_quantity']=$row['total_rnd_quantity'];
    }
}


$sql = "SELECT
            pi.id,
            ait_warehouse_info.warehouse_name,
            ait_warehouse_info.warehouse_id,
            ait_crop_info.crop_name,
            ait_product_type.product_type,
            ait_varriety_info.varriety_name,
            ait_product_pack_size.pack_size_name,
            pi.crop_id,
            pi.product_type_id,
            pi.varriety_id,
            pi.pack_size,
            (
                SELECT SUM(ppi.opening_balance) FROM ait_product_purchase_info AS ppi
                WHERE
                ppi.year_id=pi.year_id AND
                ppi.warehouse_id=pi.warehouse_id AND
                ppi.crop_id=pi.crop_id AND
                ppi.product_type_id = pi.product_type_id AND
                ppi.varriety_id = pi.varriety_id AND
                ppi.pack_size = pi.pack_size
            ) AS total_opening_balance,
            (
                SELECT SUM(ppi.quantity) FROM ait_product_purchase_info AS ppi
                WHERE
                ppi.year_id=pi.year_id AND
                ppi.warehouse_id=pi.warehouse_id AND
                ppi.crop_id=pi.crop_id AND
                ppi.product_type_id = pi.product_type_id AND
                ppi.varriety_id = pi.varriety_id AND
                ppi.pack_size = pi.pack_size
            ) AS total_product,
            (
                SELECT SUM(ppoi.approved_quantity) FROM ait_product_purchase_order_invoice AS ppoi
                WHERE
                ppoi.year_id=pi.year_id AND
                ppoi.warehouse_id=pi.warehouse_id AND
                ppoi.crop_id=pi.crop_id AND
                ppoi.product_type_id = pi.product_type_id AND
                ppoi.varriety_id = pi.varriety_id AND
                ppoi.pack_size = pi.pack_size
            ) AS total_delivery,
            (
                SELECT SUM(ppob.quantity) FROM ait_product_purchase_order_bonus AS ppob
                WHERE
                ppob.year_id=pi.year_id AND
                ppob.warehouse_id=pi.warehouse_id AND
                ppob.crop_id=pi.crop_id AND
                ppob.product_type_id = pi.product_type_id AND
                ppob.varriety_id = pi.varriety_id AND
                ppob.pack_size = pi.pack_size
            ) AS total_bonus,
            (
                SELECT SUM(pin.damage_quantity) FROM ait_product_inventory AS pin
                WHERE
                pin.year_id=pi.year_id AND
                pin.warehouse_id=pi.warehouse_id AND
                pin.crop_id=pi.crop_id AND
                pin.product_type_id = pi.product_type_id AND
                pin.varriety_id = pi.varriety_id AND
                pin.pack_size = pi.pack_size
            ) AS total_short_qnty,
            (
                SELECT SUM(pin.access_quantity) FROM ait_product_inventory AS pin
                WHERE
                pin.year_id=pi.year_id AND
                pin.warehouse_id=pi.warehouse_id AND
                pin.crop_id=pi.crop_id AND
                pin.product_type_id = pi.product_type_id AND
                pin.varriety_id = pi.varriety_id AND
                pin.pack_size = pi.pack_size
            ) AS total_access_qnty,
            (
                SELECT SUM(pinsq.sample_quantity) FROM ait_product_inventory AS pinsq
                WHERE
                pinsq.year_id=pi.year_id AND
                pinsq.warehouse_id=pi.warehouse_id AND
                pinsq.crop_id=pi.crop_id AND
                pinsq.product_type_id = pi.product_type_id AND
                pinsq.varriety_id = pi.varriety_id AND
                pinsq.pack_size = pi.pack_size
            ) AS total_sample_quantity,
            (
                SELECT SUM(pinrq.rnd_quantity) FROM ait_product_inventory AS pinrq
                WHERE
                pinrq.year_id=pi.year_id AND
                pinrq.warehouse_id=pi.warehouse_id AND
                pinrq.crop_id=pi.crop_id AND
                pinrq.product_type_id = pi.product_type_id AND
                pinrq.varriety_id = pi.varriety_id AND
                pinrq.pack_size = pi.pack_size
            ) AS total_rnd_quantity
        FROM
            ait_product_info AS pi
            LEFT JOIN ait_warehouse_info ON ait_warehouse_info.warehouse_id = pi.warehouse_id
            LEFT JOIN ait_crop_info ON ait_crop_info.crop_id = pi.crop_id
            LEFT JOIN ait_product_type ON ait_product_type.product_type_id = pi.product_type_id
            LEFT JOIN ait_varriety_info ON ait_varriety_info.varriety_id = pi.varriety_id
            LEFT JOIN ait_product_pack_size ON ait_product_pack_size.pack_size_id = pi.pack_size
        WHERE
            pi.del_status=0
            $crop_id $product_type_id $varriety_id $pack_size $warehouse_id
        GROUP BY
            pi.year_id, pi.warehouse_id, pi.crop_id, pi.product_type_id, pi.varriety_id, pi.pack_size
        ORDER BY
            ait_warehouse_info.warehouse_id,
            ait_crop_info.crop_id,
            ait_product_type.product_type_id,
            ait_product_pack_size.pack_size_id
            ";
$data_wh=array();
$warehouses=array();
if($db->open())
{
    $result=$db->query($sql);
    while($row=$db->fetchAssoc($result))
    {

        $all_ids=$row['crop_id'].$row['product_type_id'].$row['varriety_id'].$row['pack_size'].$row['warehouse_id'];
        $data_wh[$all_ids]['crop_name']=$row['crop_name'];
        $data_wh[$all_ids]['product_type']=$row['product_type'];
        $data_wh[$all_ids]['varriety_name']=$row['varriety_name'];
        $data_wh[$all_ids]['pack_size_name']=$row['pack_size_name'];
        $data_wh[$all_ids]['warehouse_id']=$row['warehouse_id'];
        $data_wh[$all_ids]['total_opening_balance']=$row['total_opening_balance'];
        $data_wh[$all_ids]['total_product']=$row['total_product'];
        $data_wh[$all_ids]['total_delivery']=$row['total_delivery'];
        $data_wh[$all_ids]['total_bonus']=$row['total_bonus'];
        $data_wh[$all_ids]['total_short_qnty']=$row['total_short_qnty'];
        $data_wh[$all_ids]['total_access_qnty']=$row['total_access_qnty'];
        $data_wh[$all_ids]['total_sample_quantity']=$row['total_sample_quantity'];
        $data_wh[$all_ids]['total_rnd_quantity']=$row['total_rnd_quantity'];

        $warehouses[$row['warehouse_id']]['warehouse_name']=$row['warehouse_name'];
    }
}

//echo "<pre>";
//print_r($data_wh);
//echo "</pre>";
//die();
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
                    Opening Stock
                </th>
                <th style="width:5%; text-align: right">
                    Purchase Qty
                </th>
                <th style="width:5%; text-align: right">
                    Sold Qty
                </th>
                <th style="width:5%; text-align: right">
                    Bonus Qty(pieces)
                </th>
                <th style="width:5%; text-align: right">
                    Sample Qty(pieces)
                </th>
                <?php
                foreach($warehouses as $warehouse)
                {
                    ?>
                    <th style="width:5%; text-align: right">
                        <?php echo $warehouse['warehouse_name'];?>
                    </th>
                    <?php
                }
                ?>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
    <?php include_once '../../libraries/print_page/Print_footer.php'; ?>
</div>