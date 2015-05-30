<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$tbl = _DB_PREFIX;
//if ($_POST['session_id'] != "") {
//    $session_id = "AND $tbl" . "product_purchase_order_invoice.session_id='" . $_POST['session_id'] . "'";
//} else {
//    $session_id = "";
//}

if(empty($_POST['year_id']) || empty($_POST['from_date']) || empty($_POST['to_date']))
{
    echo "<h4 style='text-align: center; color: red;'>Please select year, from date & to date...</h4>";
    die();
}

if ($_POST['crop_id'] != "")
{
    $crop_id = "AND appoi.crop_id='" . $_POST['crop_id'] . "'";
}
else
{
    $crop_id = "";
}
if ($_POST['product_type_id'] != "")
{
    $product_type_id = "AND appoi.product_type_id='" . $_POST['product_type_id'] . "'";
}
else
{
    $product_type_id = "";
}
if ($_POST['varriety_id'] != "")
{
    $varriety_id = "AND appoi.varriety_id='" . $_POST['varriety_id'] . "'";
}
else
{
    $varriety_id = "";
}
if ($_POST['pack_size'] != "")
{
    $pack_size = "AND appoi.pack_size='" . $_POST['pack_size'] . "'";
}
else
{
    $pack_size = "";
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
    $zone_id = "AND appoi.zone_id='" . $_POST['zone_id'] . "'";
}
else
{
    $zone_id = "";
}
if ($_POST['territory_id'] != "")
{
    $territory_id = "AND appoi.territory_id='" . $_POST['territory_id'] . "'";
}
else
{
    $territory_id = "";
}
if ($_POST['distributor_id'] != "")
{
    $distributor_id = "AND appoi.distributor_id='" . $_POST['distributor_id'] . "'";
}
else
{
    $distributor_id = "";
}
if ($_POST['year_id'] != "")
{
    $year=$db->single_data_w($tbl.'year', 'start_date, end_date', "year_id='".$_POST['year_id']."'");
    $fiscal_year_between = "AND appoi.invoice_date BETWEEN '" . $year['start_date'] . "' AND '" . $year['end_date'] . "'";
}
else
{
    $fiscal_year_between = "";
}
@$fyear = $db->DB_date_convert_year($db->date_formate($_POST['from_date']));
@$tyear = $db->DB_date_convert_year($db->date_formate($_POST['to_date']));
if ($_POST['from_date'] != "" && $_POST['to_date'] != "")
{
    $between = "AND appoi.invoice_date BETWEEN '" . $db->date_formate($_POST['from_date']) . "' AND '" . $db->date_formate($_POST['to_date']) . "'";
    $show_date="From Date: ".$_POST['from_date']." To Date: ".$_POST['to_date'];
    //$year="AND appobproduct_sale_target.year_id='".$_POST['year_id']."'";
}
else
{
    $between = "";
    $show_date="";
    //$year="";
}

$sql = "SELECT
            ait_division_info.division_name,
            ait_zone_info.zone_name,
            ait_territory_info.territory_name,
            ait_crop_info.crop_name,
            ait_product_type.product_type,
            ait_product_pack_size.pack_size_name,
            ait_varriety_info.varriety_name,
            ait_distributor_info.distributor_name,
            appoi.invoice_id,
            appoi.warehouse_id,
            appoi.purchase_order_id,
            appoi.invoice_date,
            ait_division_info.division_id,
            appoi.zone_id,
            appoi.territory_id,
            appoi.distributor_id,
            appoi.crop_id,
            appoi.product_type_id,
            appoi.varriety_id,
            appoi.pack_size,
            appoi.price,
            SUM(appoi.approved_quantity) AS sales_quantity,
            (
            SELECT SUM(appob.quantity)
            FROM ait_product_purchase_order_bonus as appob
            WHERE
                appob.zone_id=appoi.zone_id
                AND appob.territory_id=appoi.territory_id
                AND appob.distributor_id=appoi.distributor_id
                AND appob.crop_id=appoi.crop_id
                AND appob.product_type_id=appoi.product_type_id
                AND appob.varriety_id=appoi.varriety_id
                AND appob.pack_size=appoi.pack_size
            ) as bonus_quantity
        FROM
            ait_product_purchase_order_invoice as appoi
            LEFT JOIN ait_zone_info ON ait_zone_info.zone_id = appoi.zone_id
            LEFT JOIN ait_zone_user_access ON ait_zone_user_access.zone_id = ait_zone_info.zone_id
            LEFT JOIN ait_division_info ON ait_division_info.division_id = ait_zone_user_access.division_id
            LEFT JOIN ait_territory_info ON ait_territory_info.territory_id = appoi.territory_id
            LEFT JOIN ait_distributor_info ON ait_distributor_info.distributor_id = appoi.distributor_id
            LEFT JOIN ait_crop_info ON ait_crop_info.crop_id = appoi.crop_id
            LEFT JOIN ait_product_type ON ait_product_type.product_type_id = appoi.product_type_id
            LEFT JOIN ait_varriety_info ON ait_varriety_info.varriety_id = appoi.varriety_id
            LEFT JOIN ait_product_pack_size ON ait_product_pack_size.pack_size_id = appoi.pack_size
        WHERE
            appoi.del_status=0
            $crop_id $product_type_id $varriety_id $pack_size
            $division_id $zone_id $territory_id $distributor_id
            $between $fiscal_year_between
            ".$db->get_zone_access($tbl. "zone_info")."
        GROUP BY
            ait_division_info.division_id,
            appoi.zone_id,
            appoi.territory_id,
            appoi.distributor_id,
            appoi.crop_id,
            appoi.product_type_id,
            appoi.varriety_id,
            appoi.pack_size
";

if($db->open())
{
    $data_sales=array();
    $result=$db->query($sql);
    while($row_sales=$db->fetchAssoc($result))
    {
        //$data_sales[]=$row_sales;
        $data_sales[$row_sales['division_id']]['division_name']=$row_sales['division_name'];
        $data_sales[$row_sales['division_id']]['zones'][$row_sales['zone_id']]['zone_name']=$row_sales['zone_name'];
        $data_sales[$row_sales['division_id']]['zones'][$row_sales['zone_id']]['territories'][$row_sales['territory_id']]['territory_name']=$row_sales['territory_name'];
        $data_sales[$row_sales['division_id']]['zones'][$row_sales['zone_id']]['territories'][$row_sales['territory_id']]['distributors'][$row_sales['distributor_id']]['distributor_name']=$row_sales['distributor_name'];
        $data_sales[$row_sales['division_id']]['zones'][$row_sales['zone_id']]['territories'][$row_sales['territory_id']]['distributors'][$row_sales['distributor_id']]['crops'][$row_sales['crop_id']]['crop_name']=$row_sales['crop_name'];
        $data_sales[$row_sales['division_id']]['zones'][$row_sales['zone_id']]['territories'][$row_sales['territory_id']]['distributors'][$row_sales['distributor_id']]['crops'][$row_sales['crop_id']]['product_types'][$row_sales['product_type_id']]['product_type_name']=$row_sales['product_type'];
        $data_sales[$row_sales['division_id']]['zones'][$row_sales['zone_id']]['territories'][$row_sales['territory_id']]['distributors'][$row_sales['distributor_id']]['crops'][$row_sales['crop_id']]['product_types'][$row_sales['product_type_id']]['pack_sizes'][$row_sales['pack_size']]['pack_size_name']=$row_sales['pack_size_name'];
        $data_sales[$row_sales['division_id']]['zones'][$row_sales['zone_id']]['territories'][$row_sales['territory_id']]['distributors'][$row_sales['distributor_id']]['crops'][$row_sales['crop_id']]['product_types'][$row_sales['product_type_id']]['pack_sizes'][$row_sales['pack_size']]['varieties'][]=
        array
        (
            'price'=>$row_sales['price'],
            'pack_size_name'=>$row_sales['pack_size_name'],
            'variety_name'=>$row_sales['varriety_name'],
            'sales_quantity'=>$row_sales['sales_quantity'],
            'bonus_quantity'=>$row_sales['bonus_quantity']
        );
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
            <td colspan="21" class="right-align-text"><?php echo $show_date; ?></td>
        </tr>
        <tr>
            <th style="width:5%">
                Division
            </th>
            <th style="width:5%">
                Zone
            </th>
            <th style="width:5%">
                Territory
            </th>
            <th style="width:5%">
                Customer
            </th>
            <th style="width:5%">
                Crop
            </th>
            <th style="width:5%">
                Product Type
            </th>
            <th style="width:5%">
                Pack Size
            </th>
            <th style="width:5%">
                Variety
            </th>
            <th style="width:5%; text-align: right">
                Price(tk/gm)
            </th>
            <th style="width:5%; text-align: right">
                Sales Qty(pieces)
            </th>
            <th style="width:5%; text-align: right">
                Bonus Qty(pieces)
            </th>
            <th style="width:5%; text-align: right">
                Total Qty(pieces)
            </th>
            <th style="width:5%; text-align: right">
                Total Qty(kg)
            </th>
            <th style="width:5%; text-align: right">
                Total Value (tk)
            </th>
        </tr>
        </thead>
        <?php
        $grand_total_sale_quantity=0;
        $grand_total_bonus_quantity=0;
        $grand_total_sales_bonus_quantity=0;
        $grand_total_sale_kg=0;
        $grand_total_sale_value=0;
        foreach($data_sales as $divisions)
        {
            ?>
            <tr>
                <th><?php echo $divisions['division_name'];?></th>
                <th colspan="13">&nbsp;</th>
            </tr>
            <?php
            foreach($divisions['zones'] as $zones)
            {
                ?>
                <tr>
                    <th>&nbsp;</th>
                    <th><?php echo $zones['zone_name'];?></th>
                    <th colspan="12">&nbsp;</th>
                </tr>
                <?php
                foreach($zones['territories'] as $territories)
                {
                    ?>
                    <tr>
                        <th colspan="2">&nbsp;</th>
                        <th><?php echo $territories['territory_name'];?></th>
                        <th colspan="11">&nbsp;</th>
                    </tr>
                    <?php
                    foreach($territories['distributors'] as $distributors)
                    {
                        ?>
                        <tr>
                            <th colspan="3">&nbsp;</th>
                            <th><?php echo $distributors['distributor_name'];?></th>
                            <th colspan="10">&nbsp;</th>
                        </tr>
                        <?php
                        foreach($distributors['crops'] as $crops)
                        {
                            ?>
                            <tr>
                                <th colspan="4">&nbsp;</th>
                                <th><?php echo $crops['crop_name'];?></th>
                                <th colspan="9">&nbsp;</th>
                            </tr>
                            <?php
                            foreach($crops['product_types'] as $product_types)
                            {
                                ?>
                                <tr>
                                    <th colspan="5">&nbsp;</th>
                                    <th><?php echo $product_types['product_type_name'];?></th>
                                    <th colspan="8">&nbsp;</th>
                                </tr>
                                <?php
                                foreach($product_types['pack_sizes'] as $pack_sizes)
                                {
                                    ?>
                                    <tr>
                                        <th colspan="6">&nbsp;</th>
                                        <th><?php echo $pack_sizes['pack_size_name'];?></th>
                                        <th colspan="7">&nbsp;</th>
                                    </tr>
                                    <?php
                                    $total_sale_bonus=0;
                                    $total_sale_value=0;
                                    $sales_kg=0;
                                    for($i=0; $i<sizeof($pack_sizes['varieties']); $i++)
                                    {
                                        $total_sale_bonus=($pack_sizes['varieties'][$i]['sales_quantity']+$pack_sizes['varieties'][$i]['bonus_quantity']);
                                        $total_sale_value=($pack_sizes['varieties'][$i]['price']*$pack_sizes['varieties'][$i]['sales_quantity']);
                                        $sales_kg=($pack_sizes['varieties'][$i]['pack_size_name']*$total_sale_bonus)/1000;

                                        $grand_total_sale_quantity+=$pack_sizes['varieties'][$i]['sales_quantity'];
                                        $grand_total_bonus_quantity+=$pack_sizes['varieties'][$i]['bonus_quantity'];
                                        $grand_total_sales_bonus_quantity+=$total_sale_bonus;
                                        $grand_total_sale_value+=$total_sale_value;
                                        $grand_total_sale_kg+=$sales_kg;


                                        ?>
                                        <tr>
                                            <th colspan="7">&nbsp;</th>
                                            <th><?php echo $pack_sizes['varieties'][$i]['variety_name'];?></th>
                                            <th><?php echo $pack_sizes['varieties'][$i]['price'];?></th>
                                            <th><?php echo $pack_sizes['varieties'][$i]['sales_quantity'];?></th>
                                            <th><?php echo $pack_sizes['varieties'][$i]['bonus_quantity']?$pack_sizes['varieties'][$i]['bonus_quantity']:0;?></th>
                                            <th><?php echo $total_sale_bonus;?></th>
                                            <th><?php echo $sales_kg;?></th>
                                            <th><?php echo $total_sale_value;?></th>
                                        </tr>
                                    <?php
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        ?>
        <tr class="btn-danger">
            <th colspan="9" style="text-align: right">Grand Total: </th>
            <th><?php echo $grand_total_sale_quantity;?></th>
            <th><?php echo $grand_total_bonus_quantity;?></th>
            <th><?php echo $grand_total_sales_bonus_quantity;?></th>
            <th><?php echo $grand_total_sale_kg;?></th>
            <th><?php echo $grand_total_sale_value;?></th>
        </tr>
    </table>
    <?php include_once '../../libraries/print_page/Print_footer.php'; ?>
</div>

