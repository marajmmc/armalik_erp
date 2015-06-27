<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$tbl = _DB_PREFIX;

$year_id= $db->get_current_fiscal_year();
if(empty($year_id))
{
    echo "Please close fiscal year";
    die();
}
$crop_classification=array();
$column=array();
if(!empty($_POST['division_id']) && !empty($_POST['zone_id']))
{
    $location_type='Zone';

    $sql_distributor="SELECT
                            ait_crop_info.crop_name,
                            ait_product_type.product_type,
                            ait_varriety_info.varriety_name,
                            ait_product_purchase_order_invoice.crop_id,
                            ait_product_purchase_order_invoice.product_type_id,
                            ait_product_purchase_order_invoice.varriety_id,
                            ait_product_purchase_order_invoice.zone_id,
                            Sum((ait_product_pack_size.pack_size_name*ait_product_purchase_order_invoice.approved_quantity)/1000) AS sales_quantity,
                            ait_product_purchase_order_invoice.distributor_id,
                            ait_division_info.division_name,
                            ait_zone_info.zone_name,
                            ait_distributor_info.distributor_name,
                            ait_division_info.division_id
                        FROM
                            ait_product_purchase_order_invoice
                            LEFT JOIN ait_crop_info ON ait_crop_info.crop_id = ait_product_purchase_order_invoice.crop_id
                            LEFT JOIN ait_product_type ON ait_product_type.product_type_id = ait_product_purchase_order_invoice.product_type_id
                            LEFT JOIN ait_varriety_info ON ait_varriety_info.varriety_id = ait_product_purchase_order_invoice.varriety_id
                            LEFT JOIN ait_zone_info ON ait_zone_info.zone_id = ait_product_purchase_order_invoice.zone_id
                            LEFT JOIN ait_division_info ON ait_division_info.division_id = ait_zone_info.division_id
                            LEFT JOIN ait_distributor_info ON ait_distributor_info.distributor_id = ait_product_purchase_order_invoice.distributor_id
                            LEFT JOIN ait_product_pack_size ON ait_product_pack_size.pack_size_id = ait_product_purchase_order_invoice.pack_size
                        WHERE
                            ait_product_purchase_order_invoice.del_status=0
                            AND ait_division_info.division_id='".$_POST['division_id']."'
                            AND ait_product_purchase_order_invoice.zone_id='".$_POST['zone_id']."'
                        GROUP BY
                            ait_division_info.division_id,
                            ait_product_purchase_order_invoice.zone_id,
                            ait_product_purchase_order_invoice.distributor_id,
                            ait_product_purchase_order_invoice.crop_id,
                            ait_product_purchase_order_invoice.product_type_id,
                            ait_product_purchase_order_invoice.varriety_id
                        ";
    $sales_quantity=0;
    $db_distributor=new Database();
    if($db_distributor->open())
    {
        $result_distributor=$db_distributor->query($sql_distributor);
        while($row_distributor=$db_distributor->fetchAssoc($result_distributor))
        {
            $crop_classification[$row_distributor['crop_id']]['crop_name']=$row_distributor['crop_name'];
            $crop_classification[$row_distributor['crop_id']]['type'][$row_distributor['product_type_id']]['product_type']=$row_distributor['product_type'];
            $crop_classification[$row_distributor['crop_id']]['type'][$row_distributor['product_type_id']]['variety'][$row_distributor['varriety_id']]['variety_name']=$row_distributor['varriety_name'];
            $crop_classification[$row_distributor['crop_id']]['type'][$row_distributor['product_type_id']]['variety'][$row_distributor['varriety_id']][$row_distributor['distributor_id']]['sales_quantity']=$row_distributor['sales_quantity'];

            $column[$row_distributor['distributor_id']]['column_name']=$row_distributor['distributor_name'];
        }
    }
}

else if(!empty($_POST['division_id']) && empty($_POST['zone_id']))
{
    $location_type='Division';
    $sql_distributor="SELECT
                            ait_crop_info.crop_name,
                            ait_product_type.product_type,
                            ait_varriety_info.varriety_name,
                            ait_product_purchase_order_invoice.crop_id,
                            ait_product_purchase_order_invoice.product_type_id,
                            ait_product_purchase_order_invoice.varriety_id,
                            ait_product_purchase_order_invoice.zone_id,
                            Sum((ait_product_pack_size.pack_size_name*ait_product_purchase_order_invoice.approved_quantity)/1000) AS sales_quantity,
                            ait_product_purchase_order_invoice.distributor_id,
                            ait_division_info.division_name,
                            ait_zone_info.zone_name,
                            ait_distributor_info.distributor_name,
                            ait_division_info.division_id
                        FROM
                            ait_product_purchase_order_invoice
                            LEFT JOIN ait_crop_info ON ait_crop_info.crop_id = ait_product_purchase_order_invoice.crop_id
                            LEFT JOIN ait_product_type ON ait_product_type.product_type_id = ait_product_purchase_order_invoice.product_type_id
                            LEFT JOIN ait_varriety_info ON ait_varriety_info.varriety_id = ait_product_purchase_order_invoice.varriety_id
                            LEFT JOIN ait_zone_info ON ait_zone_info.zone_id = ait_product_purchase_order_invoice.zone_id
                            LEFT JOIN ait_division_info ON ait_division_info.division_id = ait_zone_info.division_id
                            LEFT JOIN ait_distributor_info ON ait_distributor_info.distributor_id = ait_product_purchase_order_invoice.distributor_id
                            LEFT JOIN ait_product_pack_size ON ait_product_pack_size.pack_size_id = ait_product_purchase_order_invoice.pack_size
                        WHERE
                            ait_product_purchase_order_invoice.del_status=0
                            AND ait_division_info.division_id='".$_POST['division_id']."'
                        GROUP BY
                            ait_division_info.division_id,
                            ait_product_purchase_order_invoice.zone_id,
                            ait_product_purchase_order_invoice.crop_id,
                            ait_product_purchase_order_invoice.product_type_id,
                            ait_product_purchase_order_invoice.varriety_id
                        ";
    $sales_quantity=0;
    $db_distributor=new Database();
    if($db_distributor->open())
    {
        $result_distributor=$db_distributor->query($sql_distributor);
        while($row_distributor=$db_distributor->fetchAssoc($result_distributor))
        {
            $crop_classification[$row_distributor['crop_id']]['crop_name']=$row_distributor['crop_name'];
            $crop_classification[$row_distributor['crop_id']]['type'][$row_distributor['product_type_id']]['product_type']=$row_distributor['product_type'];
            $crop_classification[$row_distributor['crop_id']]['type'][$row_distributor['product_type_id']]['variety'][$row_distributor['varriety_id']]['variety_name']=$row_distributor['varriety_name'];
            $crop_classification[$row_distributor['crop_id']]['type'][$row_distributor['product_type_id']]['variety'][$row_distributor['varriety_id']][$row_distributor['zone_id']]['sales_quantity']=$row_distributor['sales_quantity'];

            $column[$row_distributor['zone_id']]['column_name']=$row_distributor['zone_name'];
        }
    }
}
else if(empty($_POST['division_id']) && empty($_POST['zone_id']))
{
    $location_type='All';
    $sql_distributor="SELECT
                            ait_crop_info.crop_name,
                            ait_product_type.product_type,
                            ait_varriety_info.varriety_name,
                            ait_product_purchase_order_invoice.crop_id,
                            ait_product_purchase_order_invoice.product_type_id,
                            ait_product_purchase_order_invoice.varriety_id,
                            ait_product_purchase_order_invoice.zone_id,
                            Sum((ait_product_pack_size.pack_size_name*ait_product_purchase_order_invoice.approved_quantity)/1000) AS sales_quantity,
                            ait_product_purchase_order_invoice.distributor_id,
                            ait_division_info.division_name,
                            ait_zone_info.zone_name,
                            ait_distributor_info.distributor_name,
                            ait_division_info.division_id
                        FROM
                            ait_product_purchase_order_invoice
                            LEFT JOIN ait_crop_info ON ait_crop_info.crop_id = ait_product_purchase_order_invoice.crop_id
                            LEFT JOIN ait_product_type ON ait_product_type.product_type_id = ait_product_purchase_order_invoice.product_type_id
                            LEFT JOIN ait_varriety_info ON ait_varriety_info.varriety_id = ait_product_purchase_order_invoice.varriety_id
                            LEFT JOIN ait_zone_info ON ait_zone_info.zone_id = ait_product_purchase_order_invoice.zone_id
                            LEFT JOIN ait_division_info ON ait_division_info.division_id = ait_zone_info.division_id
                            LEFT JOIN ait_distributor_info ON ait_distributor_info.distributor_id = ait_product_purchase_order_invoice.distributor_id
                            LEFT JOIN ait_product_pack_size ON ait_product_pack_size.pack_size_id = ait_product_purchase_order_invoice.pack_size
                        WHERE
                            ait_product_purchase_order_invoice.del_status=0
                        GROUP BY
                            ait_division_info.division_id,
                            ait_product_purchase_order_invoice.crop_id,
                            ait_product_purchase_order_invoice.product_type_id,
                            ait_product_purchase_order_invoice.varriety_id
                        ";
    $sales_quantity=0;
    $db_distributor=new Database();
    if($db_distributor->open())
    {
        $result_distributor=$db_distributor->query($sql_distributor);
        while($row_distributor=$db_distributor->fetchAssoc($result_distributor))
        {
            $crop_classification[$row_distributor['crop_id']]['crop_name']=$row_distributor['crop_name'];
            $crop_classification[$row_distributor['crop_id']]['type'][$row_distributor['product_type_id']]['product_type']=$row_distributor['product_type'];
            $crop_classification[$row_distributor['crop_id']]['type'][$row_distributor['product_type_id']]['variety'][$row_distributor['varriety_id']]['variety_name']=$row_distributor['varriety_name'];
            $crop_classification[$row_distributor['crop_id']]['type'][$row_distributor['product_type_id']]['variety'][$row_distributor['varriety_id']][$row_distributor['division_id']]['sales_quantity']=$row_distributor['sales_quantity'];

            $column[$row_distributor['division_id']]['column_name']=$row_distributor['division_name'];
        }
    }
}
//echo "<pre>";
//print_r($crop_classification);
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
                <?php
                foreach($column as $column_name)
                {
                    echo "<th>".$column_name['column_name']."</th>";
                }
                ?>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach($crop_classification as $crop)
            {
                ?>
            <tr>
                <th><?php echo $crop['crop_name'];?></th>
                <th colspan="31">&nbsp;</th>
            </tr>
                <?php
                foreach($crop['type'] as $product_type)
                {
                    ?>
                    <tr>
                        <th colspan="">&nbsp;</th>
                        <th><?php echo $product_type['product_type'];?></th>
                        <th colspan="31">&nbsp;</th>
                    </tr>
                <?php
                foreach($product_type['variety'] as $variety)
                {
                    ?>
                    <tr>
                        <th colspan="2">&nbsp;</th>
                        <th><?php echo $variety['variety_name'];?></th>
                        <?php
                        foreach($column as $distributor_id=>$distributor)
                        {
                            $dis_qty=0;
                            if(isset($variety[$distributor_id]['sales_quantity']))
                            {
                                $dis_qty=$variety[$distributor_id]['sales_quantity'];
                            }
                            ?>
                            <th><?php echo number_format($dis_qty,2);?></th>
                            <?php
                        }
                        ?>
                    </tr>
                    <?php
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