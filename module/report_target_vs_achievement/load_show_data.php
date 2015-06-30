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
    $crop_id = "AND ait_product_purchase_order_invoice.crop_id='" . $_POST['crop_id'] . "'";
}
else
{
    $crop_id = "";
}
if ($_POST['product_type_id'] != "")
{
    $product_type_id = "AND ait_product_purchase_order_invoice.product_type_id='" . $_POST['product_type_id'] . "'";
}
else
{
    $product_type_id = "";
}
if ($_POST['varriety_id'] != "")
{
    $varriety_id = "AND ait_product_purchase_order_invoice.varriety_id='" . $_POST['varriety_id'] . "'";
}
else
{
    $varriety_id = "";
}

if ($_POST['division_id'] != "")
{
    $division_id = "AND ait_division_info.division_id='" . $_POST['division_id'] . "'";
}
else
{
    $division_id="";
}
if ($_POST['zone_id'] != "")
{
    $zone_id = "AND ait_product_purchase_order_invoice.zone_id='" . $_POST['zone_id'] . "'";
}
else
{
    $zone_id = "";
}
if ($_POST['territory_id'] != "")
{
    $territory_id = "AND ait_product_purchase_order_invoice.territory_id='" . $_POST['territory_id'] . "'";
}
else
{
    $territory_id = "";
}
if ($_POST['zilla_id'] != "")
{
    $zilla_id = "AND ait_product_purchase_order_invoice.zilla_id='" . $_POST['zilla_id'] . "'";
}
else
{
    $zilla_id = "";
}
if ($_POST['distributor_id'] != "")
{
    $distributor_id = "AND ait_product_purchase_order_invoice.distributor_id='" . $_POST['distributor_id'] . "'";
}
else
{
    $distributor_id = "";
}
$th_caption='';
if(!empty($_POST['division_id']) && !empty($_POST['zone_id']) && !empty($_POST['territory_id']) && !empty($_POST['zilla_id']) && !empty($_POST['distributor_id']))
{
    $th_caption="Distributor Name";
    $sql="SELECT
            ait_distributor_info.distributor_name,
            ait_crop_info.crop_name,
            ait_product_type.product_type,
            ait_varriety_info.varriety_name,
            SUM((ait_product_purchase_order_invoice.approved_quantity*ait_product_pack_size.pack_size_name)/1000) AS actual_sales_in_kg,
            SUM(ait_product_purchase_order_invoice.approved_quantity*ait_product_purchase_order_invoice.price) AS actual_sales_in_tk,
            ait_product_purchase_order_invoice.price,
            (
                SELECT SUM(budget_sales_target_record.quantity_ti)
                FROM budget_sales_target_record
                WHERE
                budget_sales_target_record.division_id=ait_division_info.division_id
                AND budget_sales_target_record.zone_id=ait_product_purchase_order_invoice.zone_id
                AND budget_sales_target_record.territory_id=ait_product_purchase_order_invoice.territory_id
                AND budget_sales_target_record.zilla_id=ait_product_purchase_order_invoice.zilla_id
                AND budget_sales_target_record.customer_id=ait_product_purchase_order_invoice.distributor_id
                AND budget_sales_target_record.`year`=ait_product_purchase_order_invoice.year_id
                AND budget_sales_target_record.crop_id=ait_product_purchase_order_invoice.crop_id
                AND budget_sales_target_record.type_id=ait_product_purchase_order_invoice.product_type_id
                AND budget_sales_target_record.variety_id=ait_product_purchase_order_invoice.varriety_id
            ) AS customer_demand,
            (
                SELECT SUM(budget_purchase.price_per_kg)
                FROM budget_purchase
                WHERE
                budget_purchase.`year`=ait_product_purchase_order_invoice.year_id
                AND budget_purchase.crop_id=ait_product_purchase_order_invoice.crop_id
                AND budget_purchase.type_id=ait_product_purchase_order_invoice.product_type_id
                AND budget_purchase.variety_id=ait_product_purchase_order_invoice.varriety_id
            ) AS price_per_kg,
            (
                SELECT SUM(budget_sales_target.quantity)
                FROM budget_sales_target
                WHERE
                budget_sales_target.division_id=ait_division_info.division_id
                AND budget_sales_target.zone_id=ait_product_purchase_order_invoice.zone_id
                AND budget_sales_target.territory_id=ait_product_purchase_order_invoice.territory_id
                AND budget_sales_target.zilla_id=ait_product_purchase_order_invoice.zilla_id
                AND budget_sales_target.customer_id=ait_product_purchase_order_invoice.distributor_id
                AND budget_sales_target.`year`=ait_product_purchase_order_invoice.year_id
                AND budget_sales_target.crop_id=ait_product_purchase_order_invoice.crop_id
                AND budget_sales_target.type_id=ait_product_purchase_order_invoice.product_type_id
                AND budget_sales_target.variety_id=ait_product_purchase_order_invoice.varriety_id
            ) AS targeted_sales,
            ait_division_info.division_id,
            ait_product_purchase_order_invoice.zone_id,
            ait_product_purchase_order_invoice.territory_id,
            ait_product_purchase_order_invoice.zilla_id,
            ait_product_purchase_order_invoice.distributor_id,
            ait_product_purchase_order_invoice.crop_id,
            ait_product_purchase_order_invoice.product_type_id,
            ait_product_purchase_order_invoice.varriety_id
        FROM
            ait_product_purchase_order_invoice
            LEFT JOIN ait_distributor_info ON ait_distributor_info.distributor_id = ait_product_purchase_order_invoice.distributor_id
            LEFT JOIN ait_crop_info ON ait_crop_info.crop_id = ait_product_purchase_order_invoice.crop_id
            LEFT JOIN ait_product_type ON ait_product_type.product_type_id = ait_product_purchase_order_invoice.product_type_id
            LEFT JOIN ait_varriety_info ON ait_varriety_info.varriety_id = ait_product_purchase_order_invoice.varriety_id
            LEFT JOIN ait_zone_info ON ait_zone_info.zone_id = ait_product_purchase_order_invoice.zone_id
            LEFT JOIN ait_division_info ON ait_division_info.division_id = ait_zone_info.division_id
            LEFT JOIN ait_product_pack_size ON ait_product_pack_size.pack_size_id = ait_product_purchase_order_invoice.pack_size
        WHERE
              ait_product_purchase_order_invoice.del_status=0
              $crop_id $product_type_id $varriety_id
              $division_id $zone_id $territory_id $zilla_id $distributor_id
        GROUP BY
            ait_division_info.division_id,
            ait_product_purchase_order_invoice.zone_id,
            ait_product_purchase_order_invoice.territory_id,
            ait_product_purchase_order_invoice.zilla_id,
            ait_product_purchase_order_invoice.distributor_id,
            ait_product_purchase_order_invoice.crop_id,
            ait_product_purchase_order_invoice.product_type_id,
            ait_product_purchase_order_invoice.varriety_id";
    if($db->open())
    {
        $data_sales=array();
        $result=$db->query($sql);
        while($row_sales=$db->fetchAssoc($result))
        {
            $data_sales[$row_sales['distributor_id']]['column_name']=$row_sales['distributor_name'];
            $data_sales[$row_sales['distributor_id']]['crop'][$row_sales['crop_id']]['crop_name']=$row_sales['crop_name'];
            $data_sales[$row_sales['distributor_id']]['crop'][$row_sales['crop_id']]['type'][$row_sales['product_type_id']]['product_type_name']=$row_sales['product_type'];
            $data_sales[$row_sales['distributor_id']]['crop'][$row_sales['crop_id']]['type'][$row_sales['product_type_id']]['variety'][$row_sales['varriety_id']]['variety_name']=$row_sales['varriety_name'];
            $data_sales[$row_sales['distributor_id']]['crop'][$row_sales['crop_id']]['type'][$row_sales['product_type_id']]['variety'][$row_sales['varriety_id']]['customer_demand']=$row_sales['customer_demand'];
            $data_sales[$row_sales['distributor_id']]['crop'][$row_sales['crop_id']]['type'][$row_sales['product_type_id']]['variety'][$row_sales['varriety_id']]['projected_ar_malik']=0;
            $data_sales[$row_sales['distributor_id']]['crop'][$row_sales['crop_id']]['type'][$row_sales['product_type_id']]['variety'][$row_sales['varriety_id']]['actual_sales_in_kg']=$row_sales['actual_sales_in_kg'];
            $data_sales[$row_sales['distributor_id']]['crop'][$row_sales['crop_id']]['type'][$row_sales['product_type_id']]['variety'][$row_sales['varriety_id']]['price_per_kg']=$row_sales['price_per_kg'];
            $data_sales[$row_sales['distributor_id']]['crop'][$row_sales['crop_id']]['type'][$row_sales['product_type_id']]['variety'][$row_sales['varriety_id']]['targeted_sales']=$row_sales['targeted_sales'];
            $data_sales[$row_sales['distributor_id']]['crop'][$row_sales['crop_id']]['type'][$row_sales['product_type_id']]['variety'][$row_sales['varriety_id']]['actual_sales_in_tk']=$row_sales['actual_sales_in_tk'];
        }
    }
}
else if(!empty($_POST['division_id']) && !empty($_POST['zone_id']) && !empty($_POST['territory_id']) && !empty($_POST['zilla_id']) && empty($_POST['distributor_id']))
{
    $th_caption="Distributor Name";
    $sql="SELECT
            ait_distributor_info.distributor_name,
            ait_crop_info.crop_name,
            ait_product_type.product_type,
            ait_varriety_info.varriety_name,
            SUM((ait_product_purchase_order_invoice.approved_quantity*ait_product_pack_size.pack_size_name)/1000) AS actual_sales_in_kg,
            SUM(ait_product_purchase_order_invoice.approved_quantity*ait_product_purchase_order_invoice.price) AS actual_sales_in_tk,
            ait_product_purchase_order_invoice.price,
            (
                SELECT SUM(budget_sales_target_record.quantity_ti)
                FROM budget_sales_target_record
                WHERE
                budget_sales_target_record.division_id=ait_division_info.division_id
                AND budget_sales_target_record.zone_id=ait_product_purchase_order_invoice.zone_id
                AND budget_sales_target_record.territory_id=ait_product_purchase_order_invoice.territory_id
                AND budget_sales_target_record.zilla_id=ait_product_purchase_order_invoice.zilla_id
                AND budget_sales_target_record.customer_id=ait_product_purchase_order_invoice.distributor_id
                AND budget_sales_target_record.`year`=ait_product_purchase_order_invoice.year_id
                AND budget_sales_target_record.crop_id=ait_product_purchase_order_invoice.crop_id
                AND budget_sales_target_record.type_id=ait_product_purchase_order_invoice.product_type_id
                AND budget_sales_target_record.variety_id=ait_product_purchase_order_invoice.varriety_id
            ) AS customer_demand,
            (
                SELECT SUM(budget_purchase.price_per_kg)
                FROM budget_purchase
                WHERE
                budget_purchase.`year`=ait_product_purchase_order_invoice.year_id
                AND budget_purchase.crop_id=ait_product_purchase_order_invoice.crop_id
                AND budget_purchase.type_id=ait_product_purchase_order_invoice.product_type_id
                AND budget_purchase.variety_id=ait_product_purchase_order_invoice.varriety_id
            ) AS price_per_kg,
            (
                SELECT SUM(budget_sales_target.quantity)
                FROM budget_sales_target
                WHERE
                budget_sales_target.division_id=ait_division_info.division_id
                AND budget_sales_target.zone_id=ait_product_purchase_order_invoice.zone_id
                AND budget_sales_target.territory_id=ait_product_purchase_order_invoice.territory_id
                AND budget_sales_target.zilla_id=ait_product_purchase_order_invoice.zilla_id
                AND budget_sales_target.customer_id=ait_product_purchase_order_invoice.distributor_id
                AND budget_sales_target.`year`=ait_product_purchase_order_invoice.year_id
                AND budget_sales_target.crop_id=ait_product_purchase_order_invoice.crop_id
                AND budget_sales_target.type_id=ait_product_purchase_order_invoice.product_type_id
                AND budget_sales_target.variety_id=ait_product_purchase_order_invoice.varriety_id
            ) AS targeted_sales,
            ait_division_info.division_id,
            ait_product_purchase_order_invoice.zone_id,
            ait_product_purchase_order_invoice.territory_id,
            ait_product_purchase_order_invoice.zilla_id,
            ait_product_purchase_order_invoice.distributor_id,
            ait_product_purchase_order_invoice.crop_id,
            ait_product_purchase_order_invoice.product_type_id,
            ait_product_purchase_order_invoice.varriety_id
        FROM
            ait_product_purchase_order_invoice
            LEFT JOIN ait_distributor_info ON ait_distributor_info.distributor_id = ait_product_purchase_order_invoice.distributor_id
            LEFT JOIN ait_crop_info ON ait_crop_info.crop_id = ait_product_purchase_order_invoice.crop_id
            LEFT JOIN ait_product_type ON ait_product_type.product_type_id = ait_product_purchase_order_invoice.product_type_id
            LEFT JOIN ait_varriety_info ON ait_varriety_info.varriety_id = ait_product_purchase_order_invoice.varriety_id
            LEFT JOIN ait_zone_info ON ait_zone_info.zone_id = ait_product_purchase_order_invoice.zone_id
            LEFT JOIN ait_division_info ON ait_division_info.division_id = ait_zone_info.division_id
            LEFT JOIN ait_product_pack_size ON ait_product_pack_size.pack_size_id = ait_product_purchase_order_invoice.pack_size
        WHERE
              ait_product_purchase_order_invoice.del_status=0
              $crop_id $product_type_id $varriety_id
              $division_id $zone_id $territory_id $zilla_id
        GROUP BY
            ait_division_info.division_id,
            ait_product_purchase_order_invoice.zone_id,
            ait_product_purchase_order_invoice.territory_id,
            ait_product_purchase_order_invoice.zilla_id,
            ait_product_purchase_order_invoice.distributor_id,
            ait_product_purchase_order_invoice.crop_id,
            ait_product_purchase_order_invoice.product_type_id,
            ait_product_purchase_order_invoice.varriety_id";
    if($db->open())
    {
        $data_sales=array();
        $result=$db->query($sql);
        while($row_sales=$db->fetchAssoc($result))
        {
            $data_sales[$row_sales['distributor_id']]['column_name']=$row_sales['distributor_name'];
            $data_sales[$row_sales['distributor_id']]['crop'][$row_sales['crop_id']]['crop_name']=$row_sales['crop_name'];
            $data_sales[$row_sales['distributor_id']]['crop'][$row_sales['crop_id']]['type'][$row_sales['product_type_id']]['product_type_name']=$row_sales['product_type'];
            $data_sales[$row_sales['distributor_id']]['crop'][$row_sales['crop_id']]['type'][$row_sales['product_type_id']]['variety'][$row_sales['varriety_id']]['variety_name']=$row_sales['varriety_name'];
            $data_sales[$row_sales['distributor_id']]['crop'][$row_sales['crop_id']]['type'][$row_sales['product_type_id']]['variety'][$row_sales['varriety_id']]['customer_demand']=$row_sales['customer_demand'];
            $data_sales[$row_sales['distributor_id']]['crop'][$row_sales['crop_id']]['type'][$row_sales['product_type_id']]['variety'][$row_sales['varriety_id']]['projected_ar_malik']=0;
            $data_sales[$row_sales['distributor_id']]['crop'][$row_sales['crop_id']]['type'][$row_sales['product_type_id']]['variety'][$row_sales['varriety_id']]['actual_sales_in_kg']=$row_sales['actual_sales_in_kg'];
            $data_sales[$row_sales['distributor_id']]['crop'][$row_sales['crop_id']]['type'][$row_sales['product_type_id']]['variety'][$row_sales['varriety_id']]['price_per_kg']=$row_sales['price_per_kg'];
            $data_sales[$row_sales['distributor_id']]['crop'][$row_sales['crop_id']]['type'][$row_sales['product_type_id']]['variety'][$row_sales['varriety_id']]['targeted_sales']=$row_sales['targeted_sales'];
            $data_sales[$row_sales['distributor_id']]['crop'][$row_sales['crop_id']]['type'][$row_sales['product_type_id']]['variety'][$row_sales['varriety_id']]['actual_sales_in_tk']=$row_sales['actual_sales_in_tk'];
        }
    }
}
else if(!empty($_POST['division_id']) && !empty($_POST['zone_id']) && !empty($_POST['territory_id']) && empty($_POST['zilla_id']) && empty($_POST['distributor_id']))
{
    $th_caption="District Name";
    $sql="SELECT
            ait_distributor_info.distributor_name,
            ait_crop_info.crop_name,
            ait_product_type.product_type,
            ait_varriety_info.varriety_name,
            SUM((ait_product_purchase_order_invoice.approved_quantity*ait_product_pack_size.pack_size_name)/1000) AS actual_sales_in_kg,
            SUM(ait_product_purchase_order_invoice.approved_quantity*ait_product_purchase_order_invoice.price) AS actual_sales_in_tk,
            ait_product_purchase_order_invoice.price,
            (
                SELECT SUM(budget_sales_target_record.quantity_ti)
                FROM budget_sales_target_record
                WHERE
                budget_sales_target_record.division_id=ait_division_info.division_id
                AND budget_sales_target_record.zone_id=ait_product_purchase_order_invoice.zone_id
                AND budget_sales_target_record.territory_id=ait_product_purchase_order_invoice.territory_id
                AND budget_sales_target_record.zilla_id=ait_product_purchase_order_invoice.zilla_id
                AND budget_sales_target_record.customer_id=ait_product_purchase_order_invoice.distributor_id
                AND budget_sales_target_record.`year`=ait_product_purchase_order_invoice.year_id
                AND budget_sales_target_record.crop_id=ait_product_purchase_order_invoice.crop_id
                AND budget_sales_target_record.type_id=ait_product_purchase_order_invoice.product_type_id
                AND budget_sales_target_record.variety_id=ait_product_purchase_order_invoice.varriety_id
            ) AS customer_demand,
            (
                SELECT SUM(budget_purchase.price_per_kg)
                FROM budget_purchase
                WHERE
                budget_purchase.`year`=ait_product_purchase_order_invoice.year_id
                AND budget_purchase.crop_id=ait_product_purchase_order_invoice.crop_id
                AND budget_purchase.type_id=ait_product_purchase_order_invoice.product_type_id
                AND budget_purchase.variety_id=ait_product_purchase_order_invoice.varriety_id
            ) AS price_per_kg,
            (
                SELECT SUM(budget_sales_target.quantity)
                FROM budget_sales_target
                WHERE
                budget_sales_target.division_id=ait_division_info.division_id
                AND budget_sales_target.zone_id=ait_product_purchase_order_invoice.zone_id
                AND budget_sales_target.territory_id=ait_product_purchase_order_invoice.territory_id
                AND budget_sales_target.zilla_id=ait_product_purchase_order_invoice.zilla_id
                AND budget_sales_target.customer_id=ait_product_purchase_order_invoice.distributor_id
                AND budget_sales_target.`year`=ait_product_purchase_order_invoice.year_id
                AND budget_sales_target.crop_id=ait_product_purchase_order_invoice.crop_id
                AND budget_sales_target.type_id=ait_product_purchase_order_invoice.product_type_id
                AND budget_sales_target.variety_id=ait_product_purchase_order_invoice.varriety_id
            ) AS targeted_sales,
            ait_division_info.division_id,
            ait_product_purchase_order_invoice.zone_id,
            ait_product_purchase_order_invoice.territory_id,
            ait_product_purchase_order_invoice.zilla_id,
            ait_product_purchase_order_invoice.distributor_id,
            ait_product_purchase_order_invoice.crop_id,
            ait_product_purchase_order_invoice.product_type_id,
            ait_product_purchase_order_invoice.varriety_id,
            ait_territory_info.territory_name,
            ait_zilla.zillanameeng,
            ait_division_info.division_name,
            ait_zone_info.zone_name
        FROM
            ait_product_purchase_order_invoice
            LEFT JOIN ait_distributor_info ON ait_distributor_info.distributor_id = ait_product_purchase_order_invoice.distributor_id
            LEFT JOIN ait_crop_info ON ait_crop_info.crop_id = ait_product_purchase_order_invoice.crop_id
            LEFT JOIN ait_product_type ON ait_product_type.product_type_id = ait_product_purchase_order_invoice.product_type_id
            LEFT JOIN ait_varriety_info ON ait_varriety_info.varriety_id = ait_product_purchase_order_invoice.varriety_id
            LEFT JOIN ait_zone_info ON ait_zone_info.zone_id = ait_product_purchase_order_invoice.zone_id
            LEFT JOIN ait_division_info ON ait_division_info.division_id = ait_zone_info.division_id
            LEFT JOIN ait_product_pack_size ON ait_product_pack_size.pack_size_id = ait_product_purchase_order_invoice.pack_size
            LEFT JOIN ait_territory_info ON ait_territory_info.territory_id = ait_product_purchase_order_invoice.territory_id
            LEFT JOIN ait_zilla ON ait_zilla.zillaid = ait_product_purchase_order_invoice.zilla_id
        WHERE
              ait_product_purchase_order_invoice.del_status=0
              $crop_id $product_type_id $varriety_id
              $division_id $zone_id $territory_id
        GROUP BY
            ait_division_info.division_id,
            ait_product_purchase_order_invoice.zone_id,
            ait_product_purchase_order_invoice.territory_id,
            ait_product_purchase_order_invoice.zilla_id,
            ait_product_purchase_order_invoice.distributor_id,
            ait_product_purchase_order_invoice.crop_id,
            ait_product_purchase_order_invoice.product_type_id,
            ait_product_purchase_order_invoice.varriety_id";
    if($db->open())
    {
        $data_sales=array();
        $result=$db->query($sql);
        while($row_sales=$db->fetchAssoc($result))
        {
            $data_sales[$row_sales['zilla_id']]['column_name']=$row_sales['zillanameeng'];
            $data_sales[$row_sales['zilla_id']]['crop'][$row_sales['crop_id']]['crop_name']=$row_sales['crop_name'];
            $data_sales[$row_sales['zilla_id']]['crop'][$row_sales['crop_id']]['type'][$row_sales['product_type_id']]['product_type_name']=$row_sales['product_type'];
            $data_sales[$row_sales['zilla_id']]['crop'][$row_sales['crop_id']]['type'][$row_sales['product_type_id']]['variety'][$row_sales['varriety_id']]['variety_name']=$row_sales['varriety_name'];
            $data_sales[$row_sales['zilla_id']]['crop'][$row_sales['crop_id']]['type'][$row_sales['product_type_id']]['variety'][$row_sales['varriety_id']]['customer_demand']=$row_sales['customer_demand'];
            $data_sales[$row_sales['zilla_id']]['crop'][$row_sales['crop_id']]['type'][$row_sales['product_type_id']]['variety'][$row_sales['varriety_id']]['projected_ar_malik']=0;
            $data_sales[$row_sales['zilla_id']]['crop'][$row_sales['crop_id']]['type'][$row_sales['product_type_id']]['variety'][$row_sales['varriety_id']]['actual_sales_in_kg']=$row_sales['actual_sales_in_kg'];
            $data_sales[$row_sales['zilla_id']]['crop'][$row_sales['crop_id']]['type'][$row_sales['product_type_id']]['variety'][$row_sales['varriety_id']]['price_per_kg']=$row_sales['price_per_kg'];
            $data_sales[$row_sales['zilla_id']]['crop'][$row_sales['crop_id']]['type'][$row_sales['product_type_id']]['variety'][$row_sales['varriety_id']]['targeted_sales']=$row_sales['targeted_sales'];
            $data_sales[$row_sales['zilla_id']]['crop'][$row_sales['crop_id']]['type'][$row_sales['product_type_id']]['variety'][$row_sales['varriety_id']]['actual_sales_in_tk']=$row_sales['actual_sales_in_tk'];
        }
    }
}
else if(!empty($_POST['division_id']) && !empty($_POST['zone_id']) && empty($_POST['territory_id']) && empty($_POST['zilla_id']) && empty($_POST['distributor_id']))
{
    $th_caption="Territory Name";
    $sql="SELECT
            ait_distributor_info.distributor_name,
            ait_crop_info.crop_name,
            ait_product_type.product_type,
            ait_varriety_info.varriety_name,
            SUM((ait_product_purchase_order_invoice.approved_quantity*ait_product_pack_size.pack_size_name)/1000) AS actual_sales_in_kg,
            SUM(ait_product_purchase_order_invoice.approved_quantity*ait_product_purchase_order_invoice.price) AS actual_sales_in_tk,
            ait_product_purchase_order_invoice.price,
            (
                SELECT SUM(budget_sales_target_record.quantity_ti)
                FROM budget_sales_target_record
                WHERE
                budget_sales_target_record.division_id=ait_division_info.division_id
                AND budget_sales_target_record.zone_id=ait_product_purchase_order_invoice.zone_id
                AND budget_sales_target_record.territory_id=ait_product_purchase_order_invoice.territory_id
                AND budget_sales_target_record.zilla_id=ait_product_purchase_order_invoice.zilla_id
                AND budget_sales_target_record.customer_id=ait_product_purchase_order_invoice.distributor_id
                AND budget_sales_target_record.`year`=ait_product_purchase_order_invoice.year_id
                AND budget_sales_target_record.crop_id=ait_product_purchase_order_invoice.crop_id
                AND budget_sales_target_record.type_id=ait_product_purchase_order_invoice.product_type_id
                AND budget_sales_target_record.variety_id=ait_product_purchase_order_invoice.varriety_id
            ) AS customer_demand,
            (
                SELECT SUM(budget_purchase.price_per_kg)
                FROM budget_purchase
                WHERE
                budget_purchase.`year`=ait_product_purchase_order_invoice.year_id
                AND budget_purchase.crop_id=ait_product_purchase_order_invoice.crop_id
                AND budget_purchase.type_id=ait_product_purchase_order_invoice.product_type_id
                AND budget_purchase.variety_id=ait_product_purchase_order_invoice.varriety_id
            ) AS price_per_kg,
            (
                SELECT SUM(budget_sales_target.quantity)
                FROM budget_sales_target
                WHERE
                budget_sales_target.division_id=ait_division_info.division_id
                AND budget_sales_target.zone_id=ait_product_purchase_order_invoice.zone_id
                AND budget_sales_target.territory_id=ait_product_purchase_order_invoice.territory_id
                AND budget_sales_target.zilla_id=ait_product_purchase_order_invoice.zilla_id
                AND budget_sales_target.customer_id=ait_product_purchase_order_invoice.distributor_id
                AND budget_sales_target.`year`=ait_product_purchase_order_invoice.year_id
                AND budget_sales_target.crop_id=ait_product_purchase_order_invoice.crop_id
                AND budget_sales_target.type_id=ait_product_purchase_order_invoice.product_type_id
                AND budget_sales_target.variety_id=ait_product_purchase_order_invoice.varriety_id
            ) AS targeted_sales,
            ait_division_info.division_id,
            ait_product_purchase_order_invoice.zone_id,
            ait_product_purchase_order_invoice.territory_id,
            ait_product_purchase_order_invoice.zilla_id,
            ait_product_purchase_order_invoice.distributor_id,
            ait_product_purchase_order_invoice.crop_id,
            ait_product_purchase_order_invoice.product_type_id,
            ait_product_purchase_order_invoice.varriety_id,
            ait_territory_info.territory_name,
            ait_zilla.zillanameeng,
            ait_division_info.division_name,
            ait_zone_info.zone_name
        FROM
            ait_product_purchase_order_invoice
            LEFT JOIN ait_distributor_info ON ait_distributor_info.distributor_id = ait_product_purchase_order_invoice.distributor_id
            LEFT JOIN ait_crop_info ON ait_crop_info.crop_id = ait_product_purchase_order_invoice.crop_id
            LEFT JOIN ait_product_type ON ait_product_type.product_type_id = ait_product_purchase_order_invoice.product_type_id
            LEFT JOIN ait_varriety_info ON ait_varriety_info.varriety_id = ait_product_purchase_order_invoice.varriety_id
            LEFT JOIN ait_zone_info ON ait_zone_info.zone_id = ait_product_purchase_order_invoice.zone_id
            LEFT JOIN ait_division_info ON ait_division_info.division_id = ait_zone_info.division_id
            LEFT JOIN ait_product_pack_size ON ait_product_pack_size.pack_size_id = ait_product_purchase_order_invoice.pack_size
            LEFT JOIN ait_territory_info ON ait_territory_info.territory_id = ait_product_purchase_order_invoice.territory_id
            LEFT JOIN ait_zilla ON ait_zilla.zillaid = ait_product_purchase_order_invoice.zilla_id
        WHERE
              ait_product_purchase_order_invoice.del_status=0
              $crop_id $product_type_id $varriety_id
              $division_id $zone_id
        GROUP BY
            ait_division_info.division_id,
            ait_product_purchase_order_invoice.zone_id,
            ait_product_purchase_order_invoice.territory_id,
            ait_product_purchase_order_invoice.zilla_id,
            ait_product_purchase_order_invoice.distributor_id,
            ait_product_purchase_order_invoice.crop_id,
            ait_product_purchase_order_invoice.product_type_id,
            ait_product_purchase_order_invoice.varriety_id";
    if($db->open())
    {
        $data_sales=array();
        $result=$db->query($sql);
        while($row_sales=$db->fetchAssoc($result))
        {
            $data_sales[$row_sales['territory_id']]['column_name']=$row_sales['territory_name'];
            $data_sales[$row_sales['territory_id']]['crop'][$row_sales['crop_id']]['crop_name']=$row_sales['crop_name'];
            $data_sales[$row_sales['territory_id']]['crop'][$row_sales['crop_id']]['type'][$row_sales['product_type_id']]['product_type_name']=$row_sales['product_type'];
            $data_sales[$row_sales['territory_id']]['crop'][$row_sales['crop_id']]['type'][$row_sales['product_type_id']]['variety'][$row_sales['varriety_id']]['variety_name']=$row_sales['varriety_name'];
            $data_sales[$row_sales['territory_id']]['crop'][$row_sales['crop_id']]['type'][$row_sales['product_type_id']]['variety'][$row_sales['varriety_id']]['customer_demand']=$row_sales['customer_demand'];
            $data_sales[$row_sales['territory_id']]['crop'][$row_sales['crop_id']]['type'][$row_sales['product_type_id']]['variety'][$row_sales['varriety_id']]['projected_ar_malik']=0;
            $data_sales[$row_sales['territory_id']]['crop'][$row_sales['crop_id']]['type'][$row_sales['product_type_id']]['variety'][$row_sales['varriety_id']]['actual_sales_in_kg']=$row_sales['actual_sales_in_kg'];
            $data_sales[$row_sales['territory_id']]['crop'][$row_sales['crop_id']]['type'][$row_sales['product_type_id']]['variety'][$row_sales['varriety_id']]['price_per_kg']=$row_sales['price_per_kg'];
            $data_sales[$row_sales['territory_id']]['crop'][$row_sales['crop_id']]['type'][$row_sales['product_type_id']]['variety'][$row_sales['varriety_id']]['targeted_sales']=$row_sales['targeted_sales'];
            $data_sales[$row_sales['territory_id']]['crop'][$row_sales['crop_id']]['type'][$row_sales['product_type_id']]['variety'][$row_sales['varriety_id']]['actual_sales_in_tk']=$row_sales['actual_sales_in_tk'];
        }
    }
}
else if(!empty($_POST['division_id']) && empty($_POST['zone_id']) && empty($_POST['territory_id']) && empty($_POST['zilla_id']) && empty($_POST['distributor_id']))
{
    $th_caption="Zone Name";
    $sql="SELECT
            ait_distributor_info.distributor_name,
            ait_crop_info.crop_name,
            ait_product_type.product_type,
            ait_varriety_info.varriety_name,
            SUM((ait_product_purchase_order_invoice.approved_quantity*ait_product_pack_size.pack_size_name)/1000) AS actual_sales_in_kg,
            SUM(ait_product_purchase_order_invoice.approved_quantity*ait_product_purchase_order_invoice.price) AS actual_sales_in_tk,
            ait_product_purchase_order_invoice.price,
            (
                SELECT SUM(budget_sales_target_record.quantity_ti)
                FROM budget_sales_target_record
                WHERE
                budget_sales_target_record.division_id=ait_division_info.division_id
                AND budget_sales_target_record.zone_id=ait_product_purchase_order_invoice.zone_id
                AND budget_sales_target_record.territory_id=ait_product_purchase_order_invoice.territory_id
                AND budget_sales_target_record.zilla_id=ait_product_purchase_order_invoice.zilla_id
                AND budget_sales_target_record.customer_id=ait_product_purchase_order_invoice.distributor_id
                AND budget_sales_target_record.`year`=ait_product_purchase_order_invoice.year_id
                AND budget_sales_target_record.crop_id=ait_product_purchase_order_invoice.crop_id
                AND budget_sales_target_record.type_id=ait_product_purchase_order_invoice.product_type_id
                AND budget_sales_target_record.variety_id=ait_product_purchase_order_invoice.varriety_id
            ) AS customer_demand,
            (
                SELECT SUM(budget_purchase.price_per_kg)
                FROM budget_purchase
                WHERE
                budget_purchase.`year`=ait_product_purchase_order_invoice.year_id
                AND budget_purchase.crop_id=ait_product_purchase_order_invoice.crop_id
                AND budget_purchase.type_id=ait_product_purchase_order_invoice.product_type_id
                AND budget_purchase.variety_id=ait_product_purchase_order_invoice.varriety_id
            ) AS price_per_kg,
            (
                SELECT SUM(budget_sales_target.quantity)
                FROM budget_sales_target
                WHERE
                budget_sales_target.division_id=ait_division_info.division_id
                AND budget_sales_target.zone_id=ait_product_purchase_order_invoice.zone_id
                AND budget_sales_target.territory_id=ait_product_purchase_order_invoice.territory_id
                AND budget_sales_target.zilla_id=ait_product_purchase_order_invoice.zilla_id
                AND budget_sales_target.customer_id=ait_product_purchase_order_invoice.distributor_id
                AND budget_sales_target.`year`=ait_product_purchase_order_invoice.year_id
                AND budget_sales_target.crop_id=ait_product_purchase_order_invoice.crop_id
                AND budget_sales_target.type_id=ait_product_purchase_order_invoice.product_type_id
                AND budget_sales_target.variety_id=ait_product_purchase_order_invoice.varriety_id
            ) AS targeted_sales,
            ait_division_info.division_id,
            ait_product_purchase_order_invoice.zone_id,
            ait_product_purchase_order_invoice.territory_id,
            ait_product_purchase_order_invoice.zilla_id,
            ait_product_purchase_order_invoice.distributor_id,
            ait_product_purchase_order_invoice.crop_id,
            ait_product_purchase_order_invoice.product_type_id,
            ait_product_purchase_order_invoice.varriety_id,
            ait_territory_info.territory_name,
            ait_zilla.zillanameeng,
            ait_division_info.division_name,
            ait_zone_info.zone_name
        FROM
            ait_product_purchase_order_invoice
            LEFT JOIN ait_distributor_info ON ait_distributor_info.distributor_id = ait_product_purchase_order_invoice.distributor_id
            LEFT JOIN ait_crop_info ON ait_crop_info.crop_id = ait_product_purchase_order_invoice.crop_id
            LEFT JOIN ait_product_type ON ait_product_type.product_type_id = ait_product_purchase_order_invoice.product_type_id
            LEFT JOIN ait_varriety_info ON ait_varriety_info.varriety_id = ait_product_purchase_order_invoice.varriety_id
            LEFT JOIN ait_zone_info ON ait_zone_info.zone_id = ait_product_purchase_order_invoice.zone_id
            LEFT JOIN ait_division_info ON ait_division_info.division_id = ait_zone_info.division_id
            LEFT JOIN ait_product_pack_size ON ait_product_pack_size.pack_size_id = ait_product_purchase_order_invoice.pack_size
            LEFT JOIN ait_territory_info ON ait_territory_info.territory_id = ait_product_purchase_order_invoice.territory_id
            LEFT JOIN ait_zilla ON ait_zilla.zillaid = ait_product_purchase_order_invoice.zilla_id
        WHERE
              ait_product_purchase_order_invoice.del_status=0
              $crop_id $product_type_id $varriety_id
              $division_id
        GROUP BY
            ait_division_info.division_id,
            ait_product_purchase_order_invoice.zone_id,
            ait_product_purchase_order_invoice.territory_id,
            ait_product_purchase_order_invoice.zilla_id,
            ait_product_purchase_order_invoice.distributor_id,
            ait_product_purchase_order_invoice.crop_id,
            ait_product_purchase_order_invoice.product_type_id,
            ait_product_purchase_order_invoice.varriety_id";
    if($db->open())
    {
        $data_sales=array();
        $result=$db->query($sql);
        while($row_sales=$db->fetchAssoc($result))
        {
            $data_sales[$row_sales['zone_id']]['column_name']=$row_sales['zone_name'];
            $data_sales[$row_sales['zone_id']]['crop'][$row_sales['crop_id']]['crop_name']=$row_sales['crop_name'];
            $data_sales[$row_sales['zone_id']]['crop'][$row_sales['crop_id']]['type'][$row_sales['product_type_id']]['product_type_name']=$row_sales['product_type'];
            $data_sales[$row_sales['zone_id']]['crop'][$row_sales['crop_id']]['type'][$row_sales['product_type_id']]['variety'][$row_sales['varriety_id']]['variety_name']=$row_sales['varriety_name'];
            $data_sales[$row_sales['zone_id']]['crop'][$row_sales['crop_id']]['type'][$row_sales['product_type_id']]['variety'][$row_sales['varriety_id']]['customer_demand']=$row_sales['customer_demand'];
            $data_sales[$row_sales['zone_id']]['crop'][$row_sales['crop_id']]['type'][$row_sales['product_type_id']]['variety'][$row_sales['varriety_id']]['projected_ar_malik']=0;
            $data_sales[$row_sales['zone_id']]['crop'][$row_sales['crop_id']]['type'][$row_sales['product_type_id']]['variety'][$row_sales['varriety_id']]['actual_sales_in_kg']=$row_sales['actual_sales_in_kg'];
            $data_sales[$row_sales['zone_id']]['crop'][$row_sales['crop_id']]['type'][$row_sales['product_type_id']]['variety'][$row_sales['varriety_id']]['price_per_kg']=$row_sales['price_per_kg'];
            $data_sales[$row_sales['zone_id']]['crop'][$row_sales['crop_id']]['type'][$row_sales['product_type_id']]['variety'][$row_sales['varriety_id']]['targeted_sales']=$row_sales['targeted_sales'];
            $data_sales[$row_sales['zone_id']]['crop'][$row_sales['crop_id']]['type'][$row_sales['product_type_id']]['variety'][$row_sales['varriety_id']]['actual_sales_in_tk']=$row_sales['actual_sales_in_tk'];
        }
    }
}
else if(empty($_POST['division_id']) && empty($_POST['zone_id']) && empty($_POST['territory_id']) && empty($_POST['zilla_id']) && empty($_POST['distributor_id']))
{
    $th_caption="Division Name";
    $sql="SELECT
            ait_distributor_info.distributor_name,
            ait_crop_info.crop_name,
            ait_product_type.product_type,
            ait_varriety_info.varriety_name,
            SUM((ait_product_purchase_order_invoice.approved_quantity*ait_product_pack_size.pack_size_name)/1000) AS actual_sales_in_kg,
            SUM(ait_product_purchase_order_invoice.approved_quantity*ait_product_purchase_order_invoice.price) AS actual_sales_in_tk,
            ait_product_purchase_order_invoice.price,
            (
                SELECT SUM(budget_sales_target_record.quantity_ti)
                FROM budget_sales_target_record
                WHERE
                budget_sales_target_record.division_id=ait_division_info.division_id
                AND budget_sales_target_record.zone_id=ait_product_purchase_order_invoice.zone_id
                AND budget_sales_target_record.territory_id=ait_product_purchase_order_invoice.territory_id
                AND budget_sales_target_record.zilla_id=ait_product_purchase_order_invoice.zilla_id
                AND budget_sales_target_record.customer_id=ait_product_purchase_order_invoice.distributor_id
                AND budget_sales_target_record.`year`=ait_product_purchase_order_invoice.year_id
                AND budget_sales_target_record.crop_id=ait_product_purchase_order_invoice.crop_id
                AND budget_sales_target_record.type_id=ait_product_purchase_order_invoice.product_type_id
                AND budget_sales_target_record.variety_id=ait_product_purchase_order_invoice.varriety_id
            ) AS customer_demand,
            (
                SELECT SUM(budget_purchase.price_per_kg)
                FROM budget_purchase
                WHERE
                budget_purchase.`year`=ait_product_purchase_order_invoice.year_id
                AND budget_purchase.crop_id=ait_product_purchase_order_invoice.crop_id
                AND budget_purchase.type_id=ait_product_purchase_order_invoice.product_type_id
                AND budget_purchase.variety_id=ait_product_purchase_order_invoice.varriety_id
            ) AS price_per_kg,
            (
                SELECT SUM(budget_sales_target.quantity)
                FROM budget_sales_target
                WHERE
                budget_sales_target.division_id=ait_division_info.division_id
                AND budget_sales_target.zone_id=ait_product_purchase_order_invoice.zone_id
                AND budget_sales_target.territory_id=ait_product_purchase_order_invoice.territory_id
                AND budget_sales_target.zilla_id=ait_product_purchase_order_invoice.zilla_id
                AND budget_sales_target.customer_id=ait_product_purchase_order_invoice.distributor_id
                AND budget_sales_target.`year`=ait_product_purchase_order_invoice.year_id
                AND budget_sales_target.crop_id=ait_product_purchase_order_invoice.crop_id
                AND budget_sales_target.type_id=ait_product_purchase_order_invoice.product_type_id
                AND budget_sales_target.variety_id=ait_product_purchase_order_invoice.varriety_id
            ) AS targeted_sales,
            ait_division_info.division_id,
            ait_product_purchase_order_invoice.zone_id,
            ait_product_purchase_order_invoice.territory_id,
            ait_product_purchase_order_invoice.zilla_id,
            ait_product_purchase_order_invoice.distributor_id,
            ait_product_purchase_order_invoice.crop_id,
            ait_product_purchase_order_invoice.product_type_id,
            ait_product_purchase_order_invoice.varriety_id,
            ait_territory_info.territory_name,
            ait_zilla.zillanameeng,
            ait_division_info.division_name,
            ait_zone_info.zone_name
        FROM
            ait_product_purchase_order_invoice
            LEFT JOIN ait_distributor_info ON ait_distributor_info.distributor_id = ait_product_purchase_order_invoice.distributor_id
            LEFT JOIN ait_crop_info ON ait_crop_info.crop_id = ait_product_purchase_order_invoice.crop_id
            LEFT JOIN ait_product_type ON ait_product_type.product_type_id = ait_product_purchase_order_invoice.product_type_id
            LEFT JOIN ait_varriety_info ON ait_varriety_info.varriety_id = ait_product_purchase_order_invoice.varriety_id
            LEFT JOIN ait_zone_info ON ait_zone_info.zone_id = ait_product_purchase_order_invoice.zone_id
            LEFT JOIN ait_division_info ON ait_division_info.division_id = ait_zone_info.division_id
            LEFT JOIN ait_product_pack_size ON ait_product_pack_size.pack_size_id = ait_product_purchase_order_invoice.pack_size
            LEFT JOIN ait_territory_info ON ait_territory_info.territory_id = ait_product_purchase_order_invoice.territory_id
            LEFT JOIN ait_zilla ON ait_zilla.zillaid = ait_product_purchase_order_invoice.zilla_id
        WHERE
              ait_product_purchase_order_invoice.del_status=0
              $crop_id $product_type_id $varriety_id
        GROUP BY
            ait_division_info.division_id,
            ait_product_purchase_order_invoice.zone_id,
            ait_product_purchase_order_invoice.territory_id,
            ait_product_purchase_order_invoice.zilla_id,
            ait_product_purchase_order_invoice.distributor_id,
            ait_product_purchase_order_invoice.crop_id,
            ait_product_purchase_order_invoice.product_type_id,
            ait_product_purchase_order_invoice.varriety_id";
    if($db->open())
    {
        $data_sales=array();
        $result=$db->query($sql);
        while($row_sales=$db->fetchAssoc($result))
        {
            $data_sales[$row_sales['division_id']]['column_name']=$row_sales['division_name'];
            $data_sales[$row_sales['division_id']]['crop'][$row_sales['crop_id']]['crop_name']=$row_sales['crop_name'];
            $data_sales[$row_sales['division_id']]['crop'][$row_sales['crop_id']]['type'][$row_sales['product_type_id']]['product_type_name']=$row_sales['product_type'];
            $data_sales[$row_sales['division_id']]['crop'][$row_sales['crop_id']]['type'][$row_sales['product_type_id']]['variety'][$row_sales['varriety_id']]['variety_name']=$row_sales['varriety_name'];
            $data_sales[$row_sales['division_id']]['crop'][$row_sales['crop_id']]['type'][$row_sales['product_type_id']]['variety'][$row_sales['varriety_id']]['customer_demand']=$row_sales['customer_demand'];
            $data_sales[$row_sales['division_id']]['crop'][$row_sales['crop_id']]['type'][$row_sales['product_type_id']]['variety'][$row_sales['varriety_id']]['projected_ar_malik']=0;
            $data_sales[$row_sales['division_id']]['crop'][$row_sales['crop_id']]['type'][$row_sales['product_type_id']]['variety'][$row_sales['varriety_id']]['actual_sales_in_kg']=$row_sales['actual_sales_in_kg'];
            $data_sales[$row_sales['division_id']]['crop'][$row_sales['crop_id']]['type'][$row_sales['product_type_id']]['variety'][$row_sales['varriety_id']]['price_per_kg']=$row_sales['price_per_kg'];
            $data_sales[$row_sales['division_id']]['crop'][$row_sales['crop_id']]['type'][$row_sales['product_type_id']]['variety'][$row_sales['varriety_id']]['targeted_sales']=$row_sales['targeted_sales'];
            $data_sales[$row_sales['division_id']]['crop'][$row_sales['crop_id']]['type'][$row_sales['product_type_id']]['variety'][$row_sales['varriety_id']]['actual_sales_in_tk']=$row_sales['actual_sales_in_tk'];
        }
    }
}


//echo "<pre>";
//print_r($data_sales);
//echo "</pre> <br />";
////
////
//die();
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
            <th style="width:5%">
                <?php echo $th_caption;?>
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
            <th style="width:5%; text-align: center">
                Demand From<br/>Customer
            </th>
            <th style="width:5%; text-align: center">
                Projected From<br/>AR Malik
            </th>
            <th style="width:5%; text-align: center">
                Actual Sales <br />(Kg)
            </th>
            <th style="width:5%; text-align: center">
                Price/(Kg)
            </th>
            <th style="width:5%; text-align: center">
                Targeted Sales
            </th>
            <th style="width:5%; text-align: center">
                Actual Sales<br />(kg)
            </th>
            <th style="width:5%; text-align: center">
                Variance
            </th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach($data_sales as $column_name)
        {
            ?>
            <tr>
                <th><?php echo $column_name['column_name'];?></th>
                <th colspan="21"></th>
            </tr>
                <?php
                foreach($column_name['crop'] as $crop)
                {
                    ?>
                    <tr>
                        <th colspan="">&nbsp;</th>
                        <th><?php echo $crop['crop_name'];?></th>
                        <th colspan="21"></th>
                    </tr>
                    <?php
                    foreach($crop['type'] as $type)
                    {
                        ?>
                        <tr>
                            <th colspan="2">&nbsp;</th>
                            <th><?php echo $type['product_type_name'];?></th>
                            <th colspan="21"></th>
                        </tr>
                        <?php
                        $variance=0;
                        $customer_demand=0;
                        $projected_ar_malik=0;
                        $actual_sales_in_kg=0;
                        $price_per_kg=0;
                        $targeted_sales=0;
                        $actual_sales_in_tk=0;
                        foreach($type['variety'] as $variety)
                        {
                            $customer_demand=$customer_demand;
                            $projected_ar_malik=number_format($variety['projected_ar_malik'],2);
                            $actual_sales_in_kg=number_format($variety['actual_sales_in_kg'],2);
                            $price_per_kg=number_format($variety['price_per_kg'],2);
                            $targeted_sales=number_format($variety['targeted_sales'],2);
                            $actual_sales_in_tk=number_format($variety['actual_sales_in_tk'],2);
                            $variance=number_format(($variety['targeted_sales']-$variety['actual_sales_in_tk']),2);
                            ?>
                            <tr>
                                <th colspan="3">&nbsp;</th>
                                <th title="Variety: <?php echo $variety['variety_name'];?>"><?php echo $variety['variety_name'];?></th>
                                <th title="Demand From Customer: <?php echo $customer_demand;?>"><?php echo $customer_demand;?></th>
                                <th title="Projected From AR Malik: <?php echo $projected_ar_malik;?>"><?php echo $projected_ar_malik;?></th>
                                <th title="Actual Sales(Kg): <?php echo $actual_sales_in_kg;?>"><?php echo $actual_sales_in_kg;?></th>
                                <th title="Price/(Kg): <?php echo $price_per_kg;?>"><?php echo $price_per_kg;?></th>
                                <th title="Targeted Sales: <?php echo $targeted_sales;?>"><?php echo $targeted_sales;?></th>
                                <th title="Actual Sales(kg): <?php echo $actual_sales_in_tk;?>"><?php echo $actual_sales_in_tk;?></th>
                                <th title="Variance: <?php echo $variance;?>"><?php echo $variance;?></th>
                            </tr>
                        <?php
                        }
                    }
                }
                ?>
            <?php
        }
        ?>
        </tbody>
    </table>
    <?php include_once '../../libraries/print_page/Print_footer.php'; ?>
</div>

