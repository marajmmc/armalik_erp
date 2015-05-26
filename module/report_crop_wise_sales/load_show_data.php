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
if ($_POST['crop_id'] != "")
{
    $crop_id = "AND $tbl"."product_purchase_order_invoice.crop_id='" . $_POST['crop_id'] . "'";
}
else
{
    $crop_id = "";
}
if ($_POST['product_type_id'] != "")
{
    $product_type_id = "AND $tbl"."product_purchase_order_invoice.product_type_id='" . $_POST['product_type_id'] . "'";
}
else
{
    $product_type_id = "";
}
if ($_POST['varriety_id'] != "")
{
    $variety_id = "AND $tbl"."product_purchase_order_invoice.varriety_id='" . $_POST['varriety_id'] . "'";
}
else
{
    $variety_id = "";
}

if ($_POST['division_id'] != "")
{
    $division_id = "AND inv_zua.division_id='" . $_POST['division_id'] . "'";
}
else
{
    $division_id = "";
}
if ($_POST['zone_id'] != "")
{
    $zone_id = "AND $tbl"."product_purchase_order_invoice.zone_id='" . $_POST['zone_id'] . "'";
}
else
{
    $zone_id = "";
}

if ($_POST['territory_id'] != "")
{
    $territory_id = "AND $tbl"."product_purchase_order_invoice.territory_id='" . $_POST['territory_id'] . "'";
}
else
{
    $territory_id = "";
}

if ($_POST['from_date'] != "" && $_POST['to_date'] != "")
{
    $between = "AND $tbl"."product_purchase_order_invoice.invoice_date BETWEEN '" . $db->date_formate($_POST['from_date']) . "' AND '" . $db->date_formate($_POST['to_date']) . "'";
}
else
{
    $between = "";
}

$sql_sales="
            SELECT
                $tbl"."division_info.division_name,
                $tbl"."zone_info.zone_name,
                $tbl"."territory_info.territory_name,
                $tbl"."crop_info.crop_name,
                $tbl"."product_type.product_type,
                $tbl"."varriety_info.varriety_name,
                SUM(($tbl" . "product_pack_size.pack_size_name * $tbl" . "product_purchase_order_invoice.approved_quantity)/1000) AS sales_quantity,
                inv_zua.division_id,
                $tbl"."product_purchase_order_invoice.zone_id,
                $tbl"."product_purchase_order_invoice.territory_id,
                $tbl"."product_purchase_order_invoice.crop_id,
                $tbl"."product_purchase_order_invoice.product_type_id,
                $tbl"."product_purchase_order_invoice.varriety_id,
                (
                    SELECT
                    SUM(($tbl"."product_pack_size.pack_size_name * $tbl"."product_purchase_order_bonus.quantity)/1000)
                    FROM $tbl"."product_purchase_order_bonus
                    LEFT JOIN $tbl"."product_pack_size ON $tbl"."product_pack_size.pack_size_id = $tbl"."product_purchase_order_bonus.pack_size
                    LEFT JOIN $tbl"."zone_info ON $tbl"."zone_info.zone_id = $tbl"."product_purchase_order_bonus.zone_id
                    LEFT JOIN $tbl"."zone_user_access as bonus_zua ON bonus_zua.zone_id = $tbl"."zone_info.zone_id
                    WHERE
                    $tbl"."product_purchase_order_bonus.crop_id=$tbl"."product_purchase_order_invoice.crop_id
                    AND $tbl"."product_purchase_order_bonus.product_type_id=$tbl"."product_purchase_order_invoice.product_type_id
                    AND $tbl"."product_purchase_order_bonus.varriety_id=$tbl"."product_purchase_order_invoice.varriety_id
                    AND bonus_zua.division_id=inv_zua.division_id
                    AND $tbl"."product_purchase_order_bonus.zone_id=$tbl"."product_purchase_order_invoice.zone_id
                    AND $tbl"."product_purchase_order_bonus.territory_id=$tbl"."product_purchase_order_invoice.territory_id
                    GROUP BY
                    bonus_zua.division_id,
                    $tbl"."product_purchase_order_bonus.zone_id,
                    $tbl"."product_purchase_order_bonus.territory_id,
                    $tbl"."product_purchase_order_bonus.crop_id,
                    $tbl"."product_purchase_order_bonus.product_type_id,
                    $tbl"."product_purchase_order_bonus.varriety_id
                ) as bonus_quantity
            FROM
                $tbl"."product_purchase_order_invoice
                LEFT JOIN $tbl"."zone_info ON $tbl"."zone_info.zone_id = $tbl"."product_purchase_order_invoice.zone_id
                LEFT JOIN $tbl"."zone_user_access as inv_zua  ON inv_zua.zone_id = $tbl"."zone_info.zone_id
                LEFT JOIN $tbl"."division_info ON $tbl"."division_info.division_id = inv_zua.division_id
                LEFT JOIN $tbl"."territory_info ON $tbl"."territory_info.territory_id = $tbl"."product_purchase_order_invoice.territory_id
                LEFT JOIN $tbl"."crop_info ON $tbl"."crop_info.crop_id = $tbl"."product_purchase_order_invoice.crop_id
                LEFT JOIN $tbl"."product_type ON $tbl"."product_type.product_type_id = $tbl"."product_purchase_order_invoice.product_type_id
                LEFT JOIN $tbl"."varriety_info ON $tbl"."varriety_info.varriety_id = $tbl"."product_purchase_order_invoice.varriety_id
                LEFT JOIN $tbl" . "product_pack_size ON $tbl" . "product_pack_size.pack_size_id = $tbl" . "product_purchase_order_invoice.pack_size
            WHERE $tbl"."product_purchase_order_invoice.del_status=0
            $division_id $zone_id $territory_id
            $crop_id $product_type_id $variety_id
            $between
            GROUP BY
                inv_zua.division_id,
                $tbl"."product_purchase_order_invoice.zone_id,
                $tbl"."product_purchase_order_invoice.territory_id,
                $tbl"."product_purchase_order_invoice.crop_id,
                $tbl"."product_purchase_order_invoice.product_type_id,
                $tbl"."product_purchase_order_invoice.varriety_id
            ORDER BY
                inv_zua.division_id,
                $tbl"."product_purchase_order_invoice.zone_id,
                $tbl"."product_purchase_order_invoice.territory_id,
                $tbl"."crop_info.order_crop,
                $tbl"."product_type.order_type,
                $tbl"."varriety_info.order_variety

            ";
$db_sales=new Database();
if($db_sales->open())
{
    $data_sales=array();
    $result_sales=$db_sales->query($sql_sales);
    while($row_sales=$db_sales->fetchAssoc())
    {
        //$data_sales[]=$row_sales;
        $data_sales[$row_sales['division_id']]['division_name']=$row_sales['division_name'];
        $data_sales[$row_sales['division_id']]['zones'][$row_sales['zone_id']]['zone_name']=$row_sales['zone_name'];
        $data_sales[$row_sales['division_id']]['zones'][$row_sales['zone_id']]['territories'][$row_sales['territory_id']]['territory_name']=$row_sales['territory_name'];
        $data_sales[$row_sales['division_id']]['zones'][$row_sales['zone_id']]['territories'][$row_sales['territory_id']]['crops'][$row_sales['crop_id']]['crop_name']=$row_sales['crop_name'];
        $data_sales[$row_sales['division_id']]['zones'][$row_sales['zone_id']]['territories'][$row_sales['territory_id']]['crops'][$row_sales['crop_id']]['product_types'][$row_sales['product_type_id']]['product_type']=$row_sales['product_type'];
        $data_sales[$row_sales['division_id']]['zones'][$row_sales['zone_id']]['territories'][$row_sales['territory_id']]['crops'][$row_sales['crop_id']]['product_types'][$row_sales['product_type_id']]['variety_name'][]=
            array
            (
                'variety_name'=>$row_sales['varriety_name'],
                'sales_quantity'=>$row_sales['sales_quantity'],
                'bonus_quantity'=>$row_sales['bonus_quantity']
            );
    }
}
//echo "<pre>";
//print_r($data_sales);
//echo "</pre>";
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
                Division
            </th>
            <th style="width:5%">
                Zone
            </th>
            <th style="width:5%">
                Territory
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
            <th style="width:5%; text-align: center;">
                Sales Qty(Kg)
            </th>
            <th style="width:5%; text-align: center;">
                Bonus Qty(Kg)
            </th>
        </tr>
        </thead>
        <?php
        $grand_sales_quantity=0;
        $grand_bonus_quantity=0;
        foreach($data_sales as $divisions)
        {
            ?>
            <tr>
                <th class="btn-info"><?php echo $divisions['division_name'];?></th>
                <th class="btn-info" colspan="7">&nbsp;</th>
            </tr>
            <?php
            $division_sales_quantity=0;
            $division_bonus_quantity=0;
            foreach($divisions['zones'] as $zones)
            {
                ?>
                <tr>
                    <th>&nbsp;</th>
                    <th class="btn-success"><?php echo $zones['zone_name'];?></th>
                    <th class="btn-success" colspan="6">&nbsp;</th>
                </tr>
                <?php
                $zone_sales_quantity=0;
                $zone_bonus_quantity=0;
                foreach($zones['territories'] as $territory)
                {
                    ?>
                    <tr>
                        <th colspan="2">&nbsp;</th>
                        <th class="btn-warning"><?php echo $territory['territory_name'];?></th>
                        <th class="btn-warning" colspan="5">&nbsp;</th>
                    </tr>
                    <?php
                    $territory_sales_quantity=0;
                    $territory_bonus_quantity=0;
                    foreach($territory['crops'] as $crops)
                    {
                        ?>
                        <tr>
                            <th colspan="3">&nbsp;</th>
                            <th class="btn-warning2"><?php echo $crops['crop_name'];?></th>
                            <th class="btn-warning2" colspan="4">&nbsp;</th>
                        </tr>
                        <?php
                        $crop_sales_quantity=0;
                        $crop_bonus_quantity=0;
                        foreach($crops['product_types'] as $types)
                        {
                            ?>
                            <tr>
                                <th colspan="4">&nbsp;</th>
                                <th class="btn-info"><?php echo $types['product_type'];?></th>
                                <th class="btn-info" colspan="5">&nbsp;</th>
                            </tr>
                            <?php
                            $product_type_sales_quantity=0;
                            $product_type_bonus_quantity=0;
                            for($i=0; $i<sizeof($types['variety_name']); $i++)
                            {
                                $product_type_sales_quantity+=$types['variety_name'][$i]['sales_quantity'];
                                $product_type_bonus_quantity+=$types['variety_name'][$i]['bonus_quantity'];
                                ?>
                                <tr>
                                    <th colspan="5">&nbsp;</th>
                                    <th><?php echo $types['variety_name'][$i]['variety_name'];?></th>
                                    <th style="width:5%; text-align: center;"><?php echo number_format($types['variety_name'][$i]['sales_quantity'], 2);?></th>
                                    <th style="width:5%; text-align: center;"><?php echo number_format($types['variety_name'][$i]['bonus_quantity'], 2);?></th>
                                </tr>
                            <?php
                            }
                            $crop_sales_quantity+=$product_type_sales_quantity;
                            $crop_bonus_quantity+=$product_type_bonus_quantity;
                            ?>
                            <tr>
                                <th colspan="4"></th>
                                <th colspan="2" class="btn-inverse" style="text-align: right">Product Type Sub Total: </th>
                                <th class="btn-inverse" style="text-align: center">
                                    <?php echo $product_type_sales_quantity;?>
                                </th>
                                <th class="btn-inverse" style="text-align: center">
                                    <?php echo $product_type_bonus_quantity;?>
                                </th>
                            </tr>
                        <?php
                        }
                        $territory_sales_quantity+=$crop_sales_quantity;
                        $territory_bonus_quantity+=$crop_bonus_quantity;
                        ?>
                        <tr>
                            <th colspan="3"></th>
                            <th colspan="3" class="btn-warning2" style="text-align: right">Crop Sub Total: </th>
                            <th class="btn-warning2" style="text-align: center">
                                <?php echo $crop_sales_quantity;?>
                            </th>
                            <th class="btn-warning2" style="text-align: center">
                                <?php echo $crop_bonus_quantity;?>
                            </th>
                        </tr>
                    <?php
                    }
                    $zone_sales_quantity+=$territory_sales_quantity;
                    $zone_bonus_quantity+=$territory_bonus_quantity;
                    ?>
                    <tr>
                        <th colspan="2"></th>
                        <th colspan="4" class="btn-warning" style="text-align: right">Territory Sub Total: </th>
                        <th class="btn-warning" style="text-align: center">
                            <?php echo $territory_sales_quantity;?>
                        </th>
                        <th class="btn-warning" style="text-align: center">
                            <?php echo $territory_bonus_quantity;?>
                        </th>
                    </tr>
                <?php
                }
                $division_sales_quantity+=$zone_sales_quantity;
                $division_bonus_quantity+=$zone_bonus_quantity;
                ?>
                <tr>
                    <th ></th>
                    <th colspan="5" class="btn-success" style="text-align: right">Zone Sub Total: </th>
                    <th class="btn-success" style="text-align: center">
                        <?php echo $zone_sales_quantity;?>
                    </th>
                    <th class="btn-success" style="text-align: center">
                        <?php echo $zone_bonus_quantity;?>
                    </th>
                </tr>
            <?php
            }
            $grand_sales_quantity+=$division_sales_quantity;
            $grand_bonus_quantity+=$division_bonus_quantity;
            ?>
            <tr>
                <th colspan="6" class="btn-info" style="text-align: right">Division Sub Total: </th>
                <th class="btn-info" style="text-align: center">
                    <?php echo $division_sales_quantity;?>
                </th>
                <th class="btn-info" style="text-align: center">
                    <?php echo $division_bonus_quantity;?>
                </th>
            </tr>
        <?php
        }
        ?>
        <tr>
            <th colspan="6" class="btn-info" style="text-align: right">Grand Total: </th>
            <th class="btn-info" style="text-align: center">
                <?php echo $grand_sales_quantity;?>
            </th>
            <th class="btn-info" style="text-align: center">
                <?php echo $grand_bonus_quantity;?>
            </th>
        </tr>
    </table>
    <?php include_once '../../libraries/print_page/Print_footer.php'; ?>
</div>