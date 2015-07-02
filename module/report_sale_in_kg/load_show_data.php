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
if ($_POST['zilla_id'] != "")
{
    $zilla_id = "AND appoi.zilla_id='" . $_POST['zilla_id'] . "'";
}
else
{
    $zilla_id = "";
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

$sql="SELECT
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
            SUM((appoi.price/ait_product_pack_size.pack_size_name)*1000) AS price_in_kg,
            SUM((appoi.approved_quantity * ait_product_pack_size.pack_size_name)/1000) AS sales_quantity_in_kg,
            (
            SELECT SUM((appob.quantity * ait_product_pack_size.pack_size_name)/1000)
            FROM ait_product_purchase_order_bonus as appob
						LEFT JOIN ait_product_pack_size ON ait_product_pack_size.pack_size_id = appob.pack_size
            WHERE
								appob.year_id=appoi.year_id
                AND appob.zone_id=appoi.zone_id
                AND appob.territory_id=appoi.territory_id
								AND appob.zilla_id=appoi.zilla_id
                AND appob.distributor_id=appoi.distributor_id
                AND appob.crop_id=appoi.crop_id
                AND appob.product_type_id=appoi.product_type_id
                AND appob.varriety_id=appoi.varriety_id
                AND appob.pack_size=appoi.pack_size
            ) as bonus_quantity_in_kg,
						(
						SELECT SUM((ppocr.return_quantity*ait_product_pack_size.pack_size_name)/1000)
						FROM ait_product_purchase_order_challan_return AS ppocr
						LEFT JOIN ait_product_pack_size ON ait_product_pack_size.pack_size_id = ppocr.pack_size
						WHERE
								ppocr.year_id=appoi.year_id
                AND ppocr.zone_id=appoi.zone_id
                AND ppocr.territory_id=appoi.territory_id
								AND ppocr.zilla_id=appoi.zilla_id
                AND ppocr.distributor_id=appoi.distributor_id
                AND ppocr.crop_id=appoi.crop_id
                AND ppocr.product_type_id=appoi.product_type_id
                AND ppocr.varriety_id=appoi.varriety_id
                AND ppocr.pack_size=appoi.pack_size
						) AS sales_return_quantity_in_kg
        FROM
            ait_product_purchase_order_invoice as appoi
            LEFT JOIN ait_zone_info ON ait_zone_info.zone_id = appoi.zone_id
            LEFT JOIN ait_division_info ON ait_division_info.division_id = ait_zone_info.division_id
            LEFT JOIN ait_territory_info ON ait_territory_info.territory_id = appoi.territory_id
            LEFT JOIN ait_distributor_info ON ait_distributor_info.distributor_id = appoi.distributor_id
            LEFT JOIN ait_crop_info ON ait_crop_info.crop_id = appoi.crop_id
            LEFT JOIN ait_product_type ON ait_product_type.product_type_id = appoi.product_type_id
            LEFT JOIN ait_varriety_info ON ait_varriety_info.varriety_id = appoi.varriety_id
            LEFT JOIN ait_product_pack_size ON ait_product_pack_size.pack_size_id = appoi.pack_size
        WHERE
            appoi.del_status=0
            $crop_id $product_type_id $varriety_id $pack_size
            $division_id $zone_id $territory_id $zilla_id $distributor_id
            $between $fiscal_year_between
            ".$db->get_zone_access($tbl. "zone_info")."

        GROUP BY
            appoi.year_id,
            ait_division_info.division_id,
            appoi.zone_id,
            appoi.territory_id,
            appoi.zilla_id,
            appoi.distributor_id,
            appoi.crop_id,
            appoi.product_type_id,
            appoi.varriety_id,
            appoi.pack_size";
if($db->open())
{
    $data_sales=array();
    $result=$db->query($sql);
    while($row_sales=$db->fetchAssoc($result))
    {
        $data_sales[$row_sales['crop_id']]['crop_name']=$row_sales['crop_name'];
        $data_sales[$row_sales['crop_id']]['type'][$row_sales['product_type_id']]['product_type']=$row_sales['product_type'];
        $data_sales[$row_sales['crop_id']]['type'][$row_sales['product_type_id']]['variety'][$row_sales['varriety_id']]['variety_name']=$row_sales['varriety_name'];
        $data_sales[$row_sales['crop_id']]['type'][$row_sales['product_type_id']]['variety'][$row_sales['varriety_id']]['pack_size'][$row_sales['pack_size']]['pack_size_name']=$row_sales['pack_size_name'];
        $data_sales[$row_sales['crop_id']]['type'][$row_sales['product_type_id']]['variety'][$row_sales['varriety_id']]['pack_size'][$row_sales['pack_size']]['price_in_kg']=$row_sales['price_in_kg'];
        $data_sales[$row_sales['crop_id']]['type'][$row_sales['product_type_id']]['variety'][$row_sales['varriety_id']]['pack_size'][$row_sales['pack_size']]['sales_quantity_in_kg']=$row_sales['sales_quantity_in_kg'];
        $data_sales[$row_sales['crop_id']]['type'][$row_sales['product_type_id']]['variety'][$row_sales['varriety_id']]['pack_size'][$row_sales['pack_size']]['bonus_quantity_in_kg']=$row_sales['bonus_quantity_in_kg'];
        $data_sales[$row_sales['crop_id']]['type'][$row_sales['product_type_id']]['variety'][$row_sales['varriety_id']]['pack_size'][$row_sales['pack_size']]['sales_return_quantity_in_kg']=$row_sales['sales_return_quantity_in_kg'];
        //$data_sales[$row_sales['crop_name']][$row_sales['product_type']][$row_sales['varriety_name']][$row_sales['pack_size_name']]['product_type']=$row_sales['product_type'];
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
        <?php
        if(!empty($_POST['distributor_id']))
        {
            ?>
            <tr>
                <th colspan="21" style="text-align: center">
                    <?php
                    $distributor=$db->single_data_w($tbl."distributor_info", "distributor_name", "distributor_id='".$_POST['distributor_id']."'");
                    echo $distributor['distributor_name'];
                    ?>
                </th>
            </tr>
            <?php
        }
        ?>
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
                Pack Size<br/>(gm)
            </th>
            <th style="width:5%; text-align: center">
                Price<br/>(Kg)
            </th>
            <th style="width:5%; text-align: center">
                Quantity<br/>(Kg)
            </th>
            <th style="width:5%; text-align: center">
                Bonus <br />Quantity
            </th>
            <th style="width:5%; text-align: center">
                Sales <br />Return(Kg)
            </th>
            <th style="width:5%; text-align: center">
                Total Sales<br/>(Kg)
            </th>
            <th style="width:5%; text-align: center">
                Actual Sales<br />(kg)
            </th>
            <th style="width:5%; text-align: center">
                Total Sales<br />(value)
            </th>
        </tr>
        </thead>
        <?php
        $grand_total_price_in_kg=0;
        $grand_total_sales_quantity_in_kg=0;
        $grand_total_bonus_quantity_in_kg=0;
        $grand_total_sales_return_quantity_in_kg=0;
        $grand_total_total_sales_in_kg=0;
        $grand_total_actual_sales_in_kg=0;
        $grand_total_net_sales_in_kg=0;
        foreach($data_sales as $crop)
        {
            ?>
            <tr>
                <th title="Crop: <?php echo $crop['crop_name'];?>"><?php echo $crop['crop_name'];?></th>
                <th colspan="21">&nbsp;</th>
            </tr>
                <?php
                $type_total_price_in_kg=0;
                $type_total_sales_quantity_in_kg=0;
                $type_total_bonus_quantity_in_kg=0;
                $type_total_sales_return_quantity_in_kg=0;
                $type_total_total_sales_in_kg=0;
                $type_total_actual_sales_in_kg=0;
                $type_total_net_sales_in_kg=0;
                foreach($crop['type'] as $product_type)
                {
                    ?>
                    <tr>
                    <th colspan="">&nbsp;</th>
                    <th title="Product Type: <?php echo $product_type['product_type'];?>"><?php echo $product_type['product_type'];?></th>
                    <th colspan="21">&nbsp;</th>
                    </tr>
                    <?php
                    foreach($product_type['variety'] as $variety)
                    {
                        ?>
                        <tr>
                            <th colspan="2">&nbsp;</th>
                            <th title="Variety Name: <?php echo $variety['variety_name'];?>"><?php echo $variety['variety_name'];?></th>
                            <th colspan="21">&nbsp;</th>
                        </tr>
                        <?php
                        $price_in_kg=0;
                        $sales_quantity_in_kg=0;
                        $bonus_quantity_in_kg=0;
                        $sales_return_quantity_in_kg=0;
                        $total_sales_in_kg=0;
                        $actual_sales_in_kg=0;
                        $net_sales_in_kg=0;
                        foreach($variety['pack_size'] as $pack_size)
                        {
                            $price_in_kg=$pack_size['price_in_kg'];
                            $sales_quantity_in_kg=$pack_size['sales_quantity_in_kg'];
                            $bonus_quantity_in_kg=$pack_size['bonus_quantity_in_kg'];
                            $sales_return_quantity_in_kg=$pack_size['sales_return_quantity_in_kg'];
                            $total_sales_in_kg=(($sales_quantity_in_kg+$bonus_quantity_in_kg)-$sales_return_quantity_in_kg);
                            $actual_sales_in_kg=($total_sales_in_kg-$sales_return_quantity_in_kg)-$bonus_quantity_in_kg;
                            $net_sales_in_kg=($actual_sales_in_kg*$price_in_kg);
                            ?>
                            <tr>
                                <th colspan="3">&nbsp;</th>
                                <th style="text-align: center;" title="Pack Size(gm) : <?php echo $pack_size['pack_size_name'];?>"><?php echo $pack_size['pack_size_name'];?></th>
                                <th style="text-align: center;" title="Price(Kg): <?php echo number_format($price_in_kg, 2);?>"><?php echo number_format($price_in_kg, 2);?></th>
                                <th style="text-align: center;" title="Quantity (Kg) : <?php echo number_format($sales_quantity_in_kg, 2);?>"><?php echo number_format($sales_quantity_in_kg, 2);?></th>
                                <th style="text-align: center;" title="Bonus Quantity : <?php echo number_format($bonus_quantity_in_kg, 2);?>"><?php echo number_format($bonus_quantity_in_kg, 2);?></th>
                                <th style="text-align: center;" title="Sales Return(Kg) : <?php echo number_format($sales_return_quantity_in_kg, 2);?>"><?php echo number_format($sales_return_quantity_in_kg, 2);?></th>
                                <th style="text-align: center;" title="Total Sales(Kg) : <?php echo number_format($total_sales_in_kg, 2);?>"><?php echo number_format($total_sales_in_kg, 2);?></th>
                                <th style="text-align: center;" title="Actual Sales(Kg) : <?php echo number_format($actual_sales_in_kg, 2);?>"><?php echo number_format($actual_sales_in_kg, 2);?></th>
                                <th style="text-align: center;" title="Net Sales(Kg) : <?php echo number_format($net_sales_in_kg, 2);?>"><?php echo number_format($net_sales_in_kg, 2);?></th>
                            </tr>
                            <?php
                            $type_total_price_in_kg+=$price_in_kg;
                            $type_total_sales_quantity_in_kg+=$sales_quantity_in_kg;
                            $type_total_bonus_quantity_in_kg+=$bonus_quantity_in_kg;
                            $type_total_sales_return_quantity_in_kg+=$sales_return_quantity_in_kg;
                            $type_total_total_sales_in_kg+=$total_sales_in_kg;
                            $type_total_actual_sales_in_kg+=$actual_sales_in_kg;
                            $type_total_net_sales_in_kg+=$net_sales_in_kg;

                            $grand_total_price_in_kg+=$price_in_kg;
                            $grand_total_sales_quantity_in_kg+=$sales_quantity_in_kg;
                            $grand_total_bonus_quantity_in_kg+=$bonus_quantity_in_kg;
                            $grand_total_sales_return_quantity_in_kg+=$sales_return_quantity_in_kg;
                            $grand_total_total_sales_in_kg+=$total_sales_in_kg;
                            $grand_total_actual_sales_in_kg+=$actual_sales_in_kg;
                            $grand_total_net_sales_in_kg+=$net_sales_in_kg;
                        }
                    }
                }
                ?>
            <tr>
                <th colspan="2" style="text-align: right;">Product Type (<?php echo $product_type['product_type'];?>) Sub Total: </th>
                <th></th>
                <th></th>
                <th style="text-align: center;" ><?php echo number_format($type_total_price_in_kg, 2);?></th>
                <th style="text-align: center;" ><?php echo number_format($type_total_sales_quantity_in_kg, 2);?></th>
                <th style="text-align: center;" ><?php echo number_format($type_total_bonus_quantity_in_kg, 2);?></th>
                <th style="text-align: center;" ><?php echo number_format($type_total_sales_return_quantity_in_kg, 2);?></th>
                <th style="text-align: center;" ><?php echo number_format($type_total_total_sales_in_kg, 2);?></th>
                <th style="text-align: center;" ><?php echo number_format($type_total_actual_sales_in_kg, 2);?></th>
                <th style="text-align: center;" ><?php echo number_format($type_total_net_sales_in_kg, 2);?></th>
            </tr>
            <?php
        }
        ?>
        <tr>
            <th colspan="10" style="text-align: right;">Grand Total: </th>
            <th style="text-align: center;" ><?php echo number_format($grand_total_net_sales_in_kg, 2);?></th>
        </tr>
    </table>
    <?php include_once '../../libraries/print_page/Print_footer.php'; ?>
</div>

