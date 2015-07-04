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
if ($_POST['pack_size'] != "")
{
    $pack_size = "AND ait_product_purchase_order_invoice.pack_size='" . $_POST['pack_size'] . "'";
}
else
{
    $pack_size = "";
}
if(!empty($_POST['from_date']) && !empty($_POST['to_date']))
{
    $between="AND ait_product_purchase_order_invoice.invoice_date BETWEEN '".$db->date_formate($_POST['from_date'])."' AND '".$db->date_formate($_POST['to_date'])."'";
    $between_bonus="AND ait_product_purchase_order_bonus.invoice_date BETWEEN '".$db->date_formate($_POST['from_date'])."' AND '".$db->date_formate($_POST['to_date'])."'";
}
else
{
    $between="";
    $between_bonus="";
}
$crop_classification=array();
$column=array();

if(!empty($_POST['division_id']) && !empty($_POST['zone_id']) && !empty($_POST['territory_id']) && !empty($_POST['zilla_id']) && !empty($_POST['distributor_id']))
{
    $location_type='Customer';
    $elm_id="distributor_id";
    $elm_name="distributor_name";
    $where="AND ait_product_purchase_order_invoice.zone_id='".$_POST['zone_id']."' AND ait_product_purchase_order_invoice.territory_id='".$_POST['territory_id']."' AND ait_product_purchase_order_invoice.zilla_id='".$_POST['zilla_id']."' AND ait_product_purchase_order_invoice.distributor_id='".$_POST['distributor_id']."' ";
    $group_by=", ait_division_info.division_id,
                    ait_product_purchase_order_invoice.zone_id,
                    ait_product_purchase_order_invoice.territory_id,
                    ait_product_purchase_order_invoice.zilla_id,
                    ait_product_purchase_order_invoice.distributor_id";
    $b_sub="AND badi.division_id=ait_division_info.division_id
                        AND ait_product_purchase_order_bonus.zone_id=ait_product_purchase_order_invoice.zone_id
                        AND ait_product_purchase_order_bonus.territory_id=ait_product_purchase_order_invoice.territory_id
                        AND ait_product_purchase_order_bonus.zilla_id=ait_product_purchase_order_invoice.zilla_id
                        AND ait_product_purchase_order_bonus.distributor_id=ait_product_purchase_order_invoice.distributor_id";
}
else if(!empty($_POST['division_id']) && !empty($_POST['zone_id']) && !empty($_POST['territory_id']) && !empty($_POST['zilla_id']) && empty($_POST['distributor_id']))
{
    $location_type='Customer';
    $elm_id="distributor_id";
    $elm_name="distributor_name";
    $where="AND ait_product_purchase_order_invoice.zone_id='".$_POST['zone_id']."' AND ait_product_purchase_order_invoice.territory_id='".$_POST['territory_id']."' AND ait_product_purchase_order_invoice.zilla_id='".$_POST['zilla_id']."' ";
    $group_by=", ait_division_info.division_id,
                    ait_product_purchase_order_invoice.zone_id,
                    ait_product_purchase_order_invoice.territory_id,
                    ait_product_purchase_order_invoice.zilla_id,
                    ait_product_purchase_order_invoice.distributor_id";
    $b_sub="AND badi.division_id=ait_division_info.division_id
                        AND ait_product_purchase_order_bonus.zone_id=ait_product_purchase_order_invoice.zone_id
                        AND ait_product_purchase_order_bonus.territory_id=ait_product_purchase_order_invoice.territory_id
                        AND ait_product_purchase_order_bonus.zilla_id=ait_product_purchase_order_invoice.zilla_id
                        AND ait_product_purchase_order_bonus.distributor_id=ait_product_purchase_order_invoice.distributor_id";

}
else if(!empty($_POST['division_id']) && !empty($_POST['zone_id']) && !empty($_POST['territory_id']) && empty($_POST['zilla_id']) && empty($_POST['distributor_id']))
{
    $location_type='District';
    $elm_id="zilla_id";
    $elm_name="zillanameeng";
    $where="AND ait_product_purchase_order_invoice.zone_id='".$_POST['zone_id']."' AND ait_product_purchase_order_invoice.territory_id='".$_POST['territory_id']."'";
    $group_by=", ait_division_info.division_id,
                    ait_product_purchase_order_invoice.zone_id,
                    ait_product_purchase_order_invoice.territory_id,
                    ait_product_purchase_order_invoice.zilla_id";
    $b_sub="AND badi.division_id=ait_division_info.division_id
                        AND ait_product_purchase_order_bonus.zone_id=ait_product_purchase_order_invoice.zone_id
                        AND ait_product_purchase_order_bonus.territory_id=ait_product_purchase_order_invoice.territory_id
                        AND ait_product_purchase_order_bonus.zilla_id=ait_product_purchase_order_invoice.zilla_id";
}
else if(!empty($_POST['division_id']) && !empty($_POST['zone_id']) && empty($_POST['territory_id']) && empty($_POST['zilla_id']) && empty($_POST['distributor_id']))
{
    $location_type='Territory';
    $elm_id="territory_id";
    $elm_name="territory_name";
    $where="AND ait_product_purchase_order_invoice.zone_id='".$_POST['zone_id']."'";
    $group_by=", ait_division_info.division_id,
                    ait_product_purchase_order_invoice.zone_id,
                    ait_product_purchase_order_invoice.territory_id";
    $b_sub="AND badi.division_id=ait_division_info.division_id
                        AND ait_product_purchase_order_bonus.zone_id=ait_product_purchase_order_invoice.zone_id
                        AND ait_product_purchase_order_bonus.territory_id=ait_product_purchase_order_invoice.territory_id";
}
else if(!empty($_POST['division_id']) && empty($_POST['zone_id']) && empty($_POST['territory_id']) && empty($_POST['zilla_id']) && empty($_POST['distributor_id']))
{
    $location_type='Zone';
    $elm_id="zone_id";
    $elm_name="zone_name";
    $where="AND ait_division_info.division_id='".$_POST['division_id']."'";
    $group_by=", ait_division_info.division_id,
                    ait_product_purchase_order_invoice.zone_id";
    $b_sub="AND badi.division_id=ait_division_info.division_id
                        AND ait_product_purchase_order_bonus.zone_id=ait_product_purchase_order_invoice.zone_id
                        ";
}
else if(empty($_POST['division_id']) && empty($_POST['zone_id']) && empty($_POST['territory_id']) && empty($_POST['zilla_id']) && empty($_POST['distributor_id']))
{
    $location_type='Division';
    $elm_id="division_id";
    $elm_name="division_name";
    $where="";
    $group_by=", ait_division_info.division_id";
    $b_sub="AND badi.division_id=ait_division_info.division_id
                        ";
}

$sql_distributor="SELECT
                    ait_crop_info.crop_name,
                    ait_product_type.product_type,
                    ait_varriety_info.varriety_name,
                    ait_product_purchase_order_invoice.invoice_date,
                    ait_product_purchase_order_invoice.crop_id,
                    ait_product_purchase_order_invoice.product_type_id,
                    ait_product_purchase_order_invoice.varriety_id,
                    ait_division_info.division_id,
                    ait_product_purchase_order_invoice.zone_id,
                    ait_product_purchase_order_invoice.territory_id,
                    ait_product_purchase_order_invoice.zilla_id,
                    Sum((ait_product_pack_size.pack_size_name*ait_product_purchase_order_invoice.approved_quantity)/1000) AS sales_quantity,
                    ait_product_purchase_order_invoice.distributor_id,
                    ait_division_info.division_name,
                    ait_zone_info.zone_name,
                    ait_territory_info.territory_name,
                    ait_distributor_info.distributor_name,
                    ait_zilla.zillanameeng,
                    (
                        SELECT
                        SUM((ait_product_purchase_order_bonus.quantity * ait_product_pack_size.pack_size_name)/1000)
                        FROM ait_product_purchase_order_bonus
                        LEFT JOIN ait_product_pack_size ON ait_product_pack_size.pack_size_id = ait_product_purchase_order_bonus.pack_size
                        LEFT JOIN ait_zone_info bazi on bazi.zone_id=ait_product_purchase_order_bonus.zone_id
                        LEFT JOIN ait_division_info badi on badi.division_id = bazi.division_id
                        WHERE
                        ait_product_purchase_order_bonus.year_id=ait_product_purchase_order_invoice.year_id
                        $b_sub
                        AND ait_product_purchase_order_bonus.crop_id=ait_product_purchase_order_invoice.crop_id
                        AND ait_product_purchase_order_bonus.product_type_id=ait_product_purchase_order_invoice.product_type_id
                        AND ait_product_purchase_order_bonus.varriety_id=ait_product_purchase_order_invoice.varriety_id
                        AND ait_product_purchase_order_bonus.pack_size=ait_product_purchase_order_invoice.pack_size
                        $between_bonus
                    ) AS product_bonus_quantity
                FROM
                    ait_product_purchase_order_invoice
                    LEFT JOIN ait_crop_info ON ait_crop_info.crop_id = ait_product_purchase_order_invoice.crop_id
                    LEFT JOIN ait_product_type ON ait_product_type.product_type_id = ait_product_purchase_order_invoice.product_type_id
                    LEFT JOIN ait_varriety_info ON ait_varriety_info.varriety_id = ait_product_purchase_order_invoice.varriety_id
                    LEFT JOIN ait_zone_info ON ait_zone_info.zone_id = ait_product_purchase_order_invoice.zone_id
                    LEFT JOIN ait_territory_info ON ait_territory_info.territory_id = ait_product_purchase_order_invoice.territory_id
                    LEFT JOIN ait_division_info ON ait_division_info.division_id = ait_zone_info.division_id
                    LEFT JOIN ait_distributor_info ON ait_distributor_info.distributor_id = ait_product_purchase_order_invoice.distributor_id
                    LEFT JOIN ait_product_pack_size ON ait_product_pack_size.pack_size_id = ait_product_purchase_order_invoice.pack_size
                    LEFT JOIN ait_zilla ON ait_zilla.zillaid = ait_product_purchase_order_invoice.zilla_id
                WHERE
                    ait_product_purchase_order_invoice.del_status=0
                    $where $crop_id $product_type_id $varriety_id $pack_size
                    $between
                GROUP BY
                    ait_product_purchase_order_invoice.crop_id,
                    ait_product_purchase_order_invoice.product_type_id,
                    ait_product_purchase_order_invoice.varriety_id,
                    ait_product_purchase_order_invoice.pack_size
                    $group_by
                ";
$sales_quantity=0;
$db_distributor=new Database();
if($db_distributor->open())
{
    $result_distributor=$db_distributor->query($sql_distributor);
    while($row_distributor=$db_distributor->fetchAssoc($result_distributor))
    {
        //echo $row_distributor['sales_quantity']+$row_distributor['product_bonus_quantity']."<br />";
        $crop_classification[$row_distributor['crop_id']]['crop_name']=$row_distributor['crop_name'];
        $crop_classification[$row_distributor['crop_id']]['type'][$row_distributor['product_type_id']]['product_type']=$row_distributor['product_type'];
        $crop_classification[$row_distributor['crop_id']]['type'][$row_distributor['product_type_id']]['variety'][$row_distributor['varriety_id']]['variety_name']=$row_distributor['varriety_name'];
        $crop_classification[$row_distributor['crop_id']]['type'][$row_distributor['product_type_id']]['variety'][$row_distributor['varriety_id']][$row_distributor[$elm_id]]['sales_quantity']=($row_distributor['sales_quantity']+$row_distributor['product_bonus_quantity']);

        $column[$row_distributor[$elm_id]]['column_name']=$row_distributor[$elm_name];
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
                <th style="width:5%" rowspan="2">
                    Crop
                </th>
                <th style="width:5%" rowspan="2">
                    Product Type
                </th>
                <th style="width:5%" rowspan="2">
                    Variety
                </th>
                <th style="width:5%; text-align: center;" colspan="<?php echo sizeof($column)?>"><?php echo $location_type;?></th>
                <th style="width:5%; text-align: center;" rowspan="2">
                    Total
                </th>
            </tr>
            <tr>
                <?php
                foreach($column as $column_name)
                {
                    echo "<th style='text-align: center;'>".$column_name['column_name']."</th>";
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
                $type_sub_total=array();
                $sl=0;
                foreach($column as $distributor_id=>$distributor)
                {
                    ++$sl;
                    $type_sub_total[$sl]=0;
                }
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
                        $sl=0;
                        $total=0;
                        foreach($column as $distributor_id=>$distributor)
                        {
                            ++$sl;
                            $dis_qty=0;
                            if(isset($variety[$distributor_id]['sales_quantity']))
                            {
                                $dis_qty=$variety[$distributor_id]['sales_quantity'];
                                $type_sub_total[$sl]+=$variety[$distributor_id]['sales_quantity'];
                                $total+=$dis_qty;
                            }
                            ?>
                            <th style="text-align: center;"><?php echo number_format($dis_qty,2);?></th>
                            <?php
                        }
                        ?>
                        <th style="text-align: center;"><?php echo number_format($total, 2);?></th>
                    </tr>
                    <?php
                    }
                    ?>
                    <tr>
                        <th colspan="3" style="text-align: right;">Product Type Sub Total (<?php echo $product_type['product_type'];?>): </th>
                        <?php
                        $sl=0;
                        $type_sub_t_total=0;
                        foreach($column as $distributor_id=>$distributor)
                        {
                            ++$sl;
                            $type_sub_t_total+=$type_sub_total[$sl];
                            ?>
                            <th style="text-align: center;"><?php echo number_format($type_sub_total[$sl], 2);?></th>
                            <?php
                        }
                        ?>
                        <th style="text-align: center;"><?php echo number_format($type_sub_t_total, 2);?></th>
                    </tr>
                    <?php
                }
                ?>
            <?php
            }
            ?>
        </tbody>
    </table>
    <?php include_once '../../libraries/print_page/Print_footer.php'; ?>
</div>