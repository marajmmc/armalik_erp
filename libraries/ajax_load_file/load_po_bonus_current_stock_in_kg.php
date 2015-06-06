<?php

require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$tbl = _DB_PREFIX;
$db = new Database();
$purchase_order_id=$_POST['purchase_order_id'];
$crop_id=$_POST['crop_id'];
$product_type_id=$_POST['product_type_id'];
$variety_id=$_POST['varriety_id'];
$pack_size=$_POST['pack_size'];
$warehouse_id = $_POST['warehouse_id'];

$sql="SELECT
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
            ) AS Total_HQ_Purchase_Quantity,
            (
                SELECT SUM(ppo.quantity) FROM ait_product_purchase_order_request AS ppo
                WHERE
                ppo.warehouse_id=pi.warehouse_id AND
                ppo.crop_id=pi.crop_id AND
                ppo.product_type_id = pi.product_type_id AND
                ppo.varriety_id = pi.varriety_id AND
                ppo.pack_size = pi.pack_size AND
                ppo.purchase_order_id='$purchase_order_id'
            ) AS Total_PO_Quantity,
            (
                SELECT SUM(ppoi.approved_quantity) FROM ait_product_purchase_order_invoice AS ppoi
                WHERE
                ppoi.warehouse_id=pi.warehouse_id AND
                ppoi.crop_id=pi.crop_id AND
                ppoi.product_type_id = pi.product_type_id AND
                ppoi.varriety_id = pi.varriety_id AND
                ppoi.pack_size = pi.pack_size
            ) AS Total_Sales_Quantity,
            (
                SELECT SUM(ppob.quantity) FROM ait_product_purchase_order_bonus AS ppob
                WHERE
                ppob.warehouse_id=pi.warehouse_id AND
                ppob.crop_id=pi.crop_id AND
                ppob.product_type_id = pi.product_type_id AND
                ppob.varriety_id = pi.varriety_id AND
                ppob.pack_size = pi.pack_size
            ) AS Total_Bonus_Quantity,
            (
                SELECT SUM(pind.damage_quantity) FROM ait_product_inventory AS pind
                WHERE
                pind.warehouse_id=pi.warehouse_id AND
                pind.crop_id=pi.crop_id AND
                pind.product_type_id = pi.product_type_id AND
                pind.varriety_id = pi.varriety_id AND
                pind.pack_size = pi.pack_size
            ) AS Total_Short_Quantity,
            (
                SELECT SUM(pina.access_quantity) FROM ait_product_inventory AS pina
                WHERE
                pina.warehouse_id=pi.warehouse_id AND
                pina.crop_id=pi.crop_id AND
                pina.product_type_id = pi.product_type_id AND
                pina.varriety_id = pi.varriety_id AND
                pina.pack_size = pi.pack_size
            ) AS Total_Access_Quantity
        FROM
            ait_product_info AS pi
            LEFT JOIN ait_warehouse_info ON ait_warehouse_info.warehouse_id = pi.warehouse_id
            LEFT JOIN ait_crop_info ON ait_crop_info.crop_id = pi.crop_id
            LEFT JOIN ait_product_type ON ait_product_type.product_type_id = pi.product_type_id
            LEFT JOIN ait_varriety_info ON ait_varriety_info.varriety_id = pi.varriety_id
            LEFT JOIN ait_product_pack_size ON ait_product_pack_size.pack_size_id = pi.pack_size
        WHERE
            pi.del_status=0
            AND pi.warehouse_id='$warehouse_id'
            AND pi.crop_id='$crop_id'
            AND pi.product_type_id='$product_type_id'
            AND pi.pack_size='$pack_size'
            AND pi.varriety_id='$variety_id'
        GROUP BY
            pi.warehouse_id, pi.crop_id, pi.product_type_id, pi.varriety_id, pi.pack_size
        ORDER BY
            ait_warehouse_info.warehouse_id,
            ait_crop_info.order_crop,
            ait_product_type.order_type,
            ait_varriety_info.order_variety";
if($db->open())
{
    $result = $db->query($sql);
    $row_result = $db->fetchAssoc($result);
    $c_stock=($row_result['Total_HQ_Purchase_Quantity']-(($row_result['Total_Sales_Quantity']+$row_result['Total_Bonus_Quantity']+$row_result['Total_Access_Quantity']+$row_result['Total_PO_Quantity'])-$row_result['Total_Short_Quantity']));
    $current_stock =  $c_stock?$c_stock:0;
    echo $current_stock_in_kg=($row_result['pack_size_name']*$current_stock)/1000;
}

?>