<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$dbM = new Database();
$tbl = _DB_PREFIX;
$division_zone_str="Head Office";
if ($_POST['crop_id'] != "")
{
    $crop_id = "AND $tbl" . "product_sale_target.crop_id='" . $_POST['crop_id'] . "'";
}
else
{
    $crop_id = "";
}
if ($_POST['product_type_id'] != "")
{
    $product_type_id = "AND $tbl" . "product_sale_target.product_type_id='" . $_POST['product_type_id'] . "'";
}
else
{
    $product_type_id = "";
}
if ($_POST['varriety_id'] != "")
{
    $varriety_id = "AND $tbl" . "product_sale_target.varriety_id='" . $_POST['varriety_id'] . "'";
}
else
{
    $varriety_id = "";
}

if ($_POST['zone_id'] != "")
{
    $zone_id = "AND $tbl" . "product_sale_target.zone_id='" . $_POST['zone_id'] . "'";
}
else
{
    $zone_id = "";
}

if ($_POST['division_id'] != "")
{
    $division_id = "AND $tbl" . "product_sale_target.zone_id IN (select zone_id from $tbl" . "zone_user_access where division_id='" . $_POST['division_id'] . "')";
    $division=$db->single_data_w($tbl."division_info", "division_name","division_id='".$_POST['division_id']."'");
    $division_zone_str.=", Division: ". $division['division_name'];
}
else
{
    $division_id = "";
}
if ($_POST['division_id'] != "")
{
    $division_ida = "AND $tbl" . "product_purchase_order_challan.zone_id IN (select zone_id from $tbl" . "zone_user_access where division_id='" . $_POST['division_id'] . "')";
    $division_id_bonus = "AND $tbl" . "zone_user_access.division_id='" . $_POST['division_id'] . "'";
}
else
{
    $division_ida = "";
    $division_id_bonus = "";
}

if ($_POST['zone_id'] != "")
{
    $zone_ida = "AND $tbl" . "product_purchase_order_challan.zone_id='" . $_POST['zone_id'] . "'";
    $zone_id_bonus = "AND $tbl" . "product_purchase_order_bonus.zone_id='" . $_POST['zone_id'] . "'";
    $zone_group="$tbl" . "zone_info.zone_name,";
    $zone=$db->single_data_w($tbl."zone_info", "zone_name","zone_id='".$_POST['zone_id']."'");
    $division_zone_str.=", Zone: ". $zone['zone_name'];
}
else
{
    $zone_ida = "";
    $zone_id_bonus = "";
    $zone_group="";
}

//if ($_POST['division_id'] != "" || $_POST['zone_id'] != "") {
//    $zone_group="$tbl" . "zone_info.zone_name,";
//} else {
//    $zone_group="";
//}

@$fdate = $db->DB_date_convert_year($db->date_formate($_POST['from_date']));
@$tdate = $db->DB_date_convert_year($db->date_formate($_POST['to_date']));
@$fyear = $db->date_formate($_POST['from_date']);
@$tyear = $db->date_formate($_POST['to_date']);

if ($_POST['from_date'] != "" || $_POST['to_date'] != "")
{
    $date="AND $tbl" . "product_purchase_order_challan.challan_date BETWEEN '$fyear' AND '$tyear'";
    $date_bonus="AND $tbl" . "product_purchase_order_bonus.invoice_date BETWEEN '$fyear' AND '$tyear'";
    $year="AND substr($tbl" . "product_sale_target.start_date,1,4) BETWEEN '$fdate' AND '$tdate'";
    $date_str="Sales Target From Date: " . $_POST['from_date'] . " To Date: ".$_POST['to_date'];
}
else
{
    $date="";
    $date_bonus="";
    $year="";
    $date_str="Product Sale Target (2014)";
}


?>
<?php
$price = 0;
$qnty = 0;
$aqnty = 0;
$tprice = 0;
$atprice = 0;
$totalp = 0;
$persentance = 0;
$invoice_id = '';
$invoice_date = '';
$zone_name = '';
$crop_name = '';
$product_type = '';
$varriety_name = '';
$territory_name = '';
$distributor_name = '';
$tpersentance = 0;
$totaltbkg = 0;
$total_achieve_bonus = 0;
$total_bonus_kg = 0;
$sl = 1;
$show_zone=true;
$sql = "SELECT
                        $tbl" . "product_sale_target.zone_id,
                        $tbl" . "product_sale_target.start_date,
                        $tbl" . "product_sale_target.crop_id,
                        $tbl" . "product_sale_target.product_type_id,
                        $tbl" . "product_sale_target.varriety_id,
                        $tbl" . "product_sale_target.price AS price,
                        SUM($tbl" . "product_sale_target.quantity) AS quantity,
                        SUM($tbl" . "product_sale_target.value) AS value,
                        $tbl" . "zone_info.zone_name,
                        $tbl" . "crop_info.crop_name,
                        $tbl" . "product_type.product_type,
                        $tbl" . "varriety_info.varriety_name,
                        (SELECT
                            SUM($tbl" . "product_purchase_order_challan.total_price)
                        FROM $tbl" . "product_purchase_order_challan
                        WHERE
                            $tbl" . "product_purchase_order_challan.crop_id=$tbl" . "product_sale_target.crop_id AND
                            $tbl" . "product_purchase_order_challan.product_type_id=$tbl" . "product_sale_target.product_type_id AND
                            $tbl" . "product_purchase_order_challan.varriety_id=$tbl" . "product_sale_target.varriety_id AND
                            $tbl" . "product_purchase_order_challan.`status`='Received' AND
                            $tbl" . "product_purchase_order_challan.del_status='0'
                            " . $db->get_zone_access($tbl . "product_purchase_order_challan") . "
                            $division_ida $zone_ida $date
                        GROUP BY
                            $zone_group
                            $tbl" . "product_purchase_order_challan.crop_id,
                            $tbl" . "product_purchase_order_challan.product_type_id,
                            $tbl" . "product_purchase_order_challan.varriety_id
                        ) AS sale_target_persentance,
                        (SELECT
                            SUM(($tbl" . "product_purchase_order_challan.quantity * $tbl" . "product_pack_size.pack_size_name)/1000)
                        FROM $tbl" . "product_purchase_order_challan
                            LEFT JOIN $tbl" . "product_pack_size ON $tbl" . "product_pack_size.pack_size_id = $tbl" . "product_purchase_order_challan.pack_size
                        WHERE
                            $tbl" . "product_purchase_order_challan.crop_id=$tbl" . "product_sale_target.crop_id AND
                            $tbl" . "product_purchase_order_challan.product_type_id=$tbl" . "product_sale_target.product_type_id AND
                            $tbl" . "product_purchase_order_challan.varriety_id=$tbl" . "product_sale_target.varriety_id AND
                            $tbl" . "product_purchase_order_challan.`status`='Received' AND
                            $tbl" . "product_purchase_order_challan.del_status='0'
                            " . $db->get_zone_access($tbl . "product_purchase_order_challan") . "
                            $division_ida $zone_ida $date
                        GROUP BY
                            $zone_group
                            $tbl" . "product_purchase_order_challan.crop_id,
                            $tbl" . "product_purchase_order_challan.product_type_id,
                            $tbl" . "product_purchase_order_challan.varriety_id
                        ) AS achieve_target_kg,
                        (
                        SELECT
                            SUM(($tbl" . "product_purchase_order_bonus.quantity * $tbl" . "product_pack_size.pack_size_name)/1000)
                        FROM $tbl"."product_purchase_order_bonus
                            LEFT JOIN $tbl"."product_pack_size ON $tbl"."product_pack_size.pack_size_id = $tbl"."product_purchase_order_bonus.pack_size
                            INNER JOIN $tbl"."zone_user_access ON $tbl"."zone_user_access.zone_id = $tbl"."product_purchase_order_bonus.zone_id
                        WHERE
                            $tbl"."product_purchase_order_bonus.crop_id=$tbl"."product_sale_target.crop_id
                            AND $tbl"."product_purchase_order_bonus.product_type_id=$tbl"."product_sale_target.product_type_id
                            AND $tbl"."product_purchase_order_bonus.varriety_id=$tbl"."product_sale_target.varriety_id
                            $division_id_bonus $zone_id_bonus $date_bonus
                        ) AS total_bonus_kg
                    FROM
                        $tbl" . "product_sale_target
                        LEFT JOIN $tbl" . "zone_info ON $tbl" . "zone_info.zone_id = $tbl" . "product_sale_target.zone_id
                        LEFT JOIN $tbl" . "crop_info ON $tbl" . "crop_info.crop_id = $tbl" . "product_sale_target.crop_id
                        LEFT JOIN $tbl" . "product_type ON $tbl" . "product_type.product_type_id = $tbl" . "product_sale_target.product_type_id
                        LEFT JOIN $tbl" . "varriety_info ON $tbl" . "varriety_info.varriety_id = $tbl" . "product_sale_target.varriety_id
                    WHERE $tbl" . "product_sale_target.del_status='0' AND
                        $tbl" . "product_sale_target.`status`='Active' AND
                        $tbl" . "product_sale_target.channel='Zone'
                        $zone_id " . $db->get_zone_access($tbl . "product_sale_target") . "
                        $division_id $year
                        $crop_id $product_type_id  $varriety_id
                    GROUP BY
                        $zone_group
                        $tbl" . "product_sale_target.crop_id,
                        $tbl" . "product_sale_target.product_type_id,
                        $tbl" . "product_sale_target.varriety_id
                    ORDER BY
                        $zone_group
                        $tbl" . "crop_info.order_crop,
                        $tbl" . "product_type.order_type,
                        $tbl" . "varriety_info.order_variety
                    ";
$records=array();
if ($db->open())
{
    $result = $db->query($sql);
    $i = 1;
    while ($result_array = $db->fetchAssoc())
    {
        //$records[]=$result_array;
        $records[$result_array['crop_id']]['crop']=$result_array['crop_name'];
        $records[$result_array['crop_id']]['types'][$result_array['product_type_id']]['product_type']=$result_array['product_type'];
        $records[$result_array['crop_id']]['types'][$result_array['product_type_id']]['varieties'][]=
        array
        (
            'variety_name'=>$result_array['varriety_name'],
            'price'=>$result_array['price'],
            'quantity'=>$result_array['quantity'],
            'achieve_target_kg'=>$result_array['achieve_target_kg'],
            'total_bonus_kg'=>$result_array['total_bonus_kg'],
            'value'=>$result_array['value'],
            'sale_target_percentage'=>$result_array['sale_target_persentance']
        );
    }
}
//echo "<pre>";
//print_r($records);
//echo "</pre>";
//die();
?>

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
        <th style="width:5%; text-align: right;">
            Price(TK/Kg)
        </th>
        <th style="width:5%; text-align: right;">
            Target(Kg)
        </th>
        <th style="width:5%; text-align: right;">
            Achieve(Kg)
        </th>
        <th style="width:5%; text-align: right;">
            Bonus qty(kg)
        </th>
        <th style="width:5%; text-align: right;">
            Total qty(kg)
        </th>
        <th style="width:5%; text-align: right;">
            Target Value(TK)
        </th>
        <th style="width:5%; text-align: right;">
            Achieve(TK)
        </th>
        <th style="width:5%; text-align: right;">
            Achieve(%)
        </th>
    </tr>
    </thead>
    <tbody>
    <?php
    $grand_percentage=0;
    $grand_total_quantity=0;
    $grand_target=0;
    $grand_achieve_kg=0;
    $grand_bonus_kg=0;
    $grand_achieve_bonus=0;
    $grand_target_value=0;
    $grand_achieve_value=0;
    foreach ($records as $crop)
    {
        ?>
    <tr>
        <th class="btn-info"><?php echo $crop['crop'];?></th>
        <th class="btn-info" colspan="10">&nbsp;</th>
    </tr>
    <?php
        $crop_percentage=0;
        $product_type_percentage=0;
        $crop_total_quantity=0;
        $crop_target=0;
        $crop_achieve_kg=0;
        $crop_bonus_kg=0;
        $crop_achieve_bonus=0;
        $crop_target_value=0;
        $crop_achieve_value=0;
        foreach($crop['types'] as $type)
        {
            ?>
            <tr>
                <th>&nbsp;</th>
                <th class="btn-success"><?php echo $type['product_type'];?></th>
                <th class="btn-success" colspan="9">&nbsp;</th>
            </tr>
        <?php
            $total_quantity=0;
            $percentage=0;
            $product_type_target=0;
            $product_type_achieve_kg=0;
            $product_type_bonus_kg=0;
            $product_type_achieve_bonus=0;
            $product_type_target_value=0;
            $product_type_achieve_value=0;
            for($i=0; $i<sizeof($type['varieties']); $i++)
            {
                @$percentage = (($type['varieties'][$i]['sale_target_percentage'] / $type['varieties'][$i]['value']) * 100);
                $total_quantity+=($type['varieties'][$i]['achieve_target_kg']+$type['varieties'][$i]['total_bonus_kg']);
                $product_type_target+=$type['varieties'][$i]['quantity'];
                $product_type_achieve_kg+=$type['varieties'][$i]['achieve_target_kg'];
                $product_type_bonus_kg+=$type['varieties'][$i]['total_bonus_kg'];
                $product_type_achieve_bonus+=$total_quantity;
                $product_type_target_value+=$type['varieties'][$i]['value'];
                $product_type_achieve_value+=$type['varieties'][$i]['sale_target_percentage'];

                ?>
                <tr>
                    <th colspan="2">&nbsp;</th>
                    <th title="Variety Name"><?php echo $type['varieties'][$i]['variety_name'];?></th>
                    <th style="text-align: right;" title="Price(TK/Kg)"><?php echo $type['varieties'][$i]['price'];?></th>
                    <th style="text-align: right;" title="Target(Kg)"><?php echo $type['varieties'][$i]['quantity'];?></th>
                    <th style="text-align: right;" title="Achieve(Kg)"><?php echo $type['varieties'][$i]['achieve_target_kg'];?></th>
                    <th style="text-align: right;" title="Bonus qty(kg)"><?php echo $type['varieties'][$i]['total_bonus_kg'];?></th>
                    <th style="text-align: right;" title="Total qty(kg) "><?php echo $total_quantity;?></th>
                    <th style="text-align: right;" title="Target Value(TK)"><?php echo $type['varieties'][$i]['value'];?></th>
                    <th style="text-align: right;" title="Achieve(TK)"><?php echo $type['varieties'][$i]['sale_target_percentage'];?></th>
                    <th style="text-align: right;" title="Achieve(%)"><?php echo number_format($percentage,2);?></th>
                </tr>
            <?php
            }
            $product_type_percentage = (($product_type_achieve_value / $product_type_target_value) * 100);
            $crop_total_quantity+=($product_type_achieve_kg+$product_type_bonus_kg);
            $crop_target+=$product_type_target;
            $crop_achieve_kg+=$product_type_achieve_kg;
            $crop_bonus_kg+=$product_type_bonus_kg;
            $crop_achieve_bonus+=$product_type_achieve_bonus;
            $crop_target_value+=$product_type_target_value;
            $crop_achieve_value+=$product_type_achieve_value;
            ?>
            <tr>
                <th>&nbsp;</th>
                <th colspan="3" style="text-align: right;" class="btn-success">Product Type Sub Total: </th>
                <th style="text-align: right;" class="btn-success" title="Target(Kg)"><?php echo $product_type_target;?></th>
                <th style="text-align: right;" class="btn-success" title="Achieve(Kg)"><?php echo $product_type_achieve_kg;?></th>
                <th style="text-align: right;" class="btn-success" title="Bonus qty(kg)"><?php echo $product_type_bonus_kg;?></th>
                <th style="text-align: right;" class="btn-success" title="Total qty(kg) "><?php echo $total_quantity;?></th>
                <th style="text-align: right;" class="btn-success" title="Target Value(TK)"><?php echo $product_type_target_value;?></th>
                <th style="text-align: right;" class="btn-success" title="Achieve(TK)"><?php echo $product_type_achieve_value;?></th>
                <th style="text-align: right;" class="btn-success" title="Achieve(%)"><?php echo number_format($product_type_percentage,2);?></th>
            </tr>
            <?php
        }
        $crop_percentage = (($crop_achieve_value / $crop_target_value) * 100);
        $grand_total_quantity+=($crop_achieve_kg+$crop_bonus_kg);
        $grand_target+=$crop_target;
        $grand_achieve_kg+=$crop_achieve_kg;
        $grand_bonus_kg+=$crop_bonus_kg;
        $grand_achieve_bonus+=$crop_achieve_bonus;
        $grand_target_value+=$crop_target_value;
        $grand_achieve_value+=$crop_achieve_value;
        ?>
        <tr>
            <th colspan="4" style="text-align: right;" class="btn-info">Crop Sub Total: </th>
            <th style="text-align: right;" class="btn-info"><?php echo $crop_target;?></th>
            <th style="text-align: right;" class="btn-info"><?php echo $crop_achieve_kg;?></th>
            <th style="text-align: right;" class="btn-info"><?php echo $crop_bonus_kg;?></th>
            <th style="text-align: right;" class="btn-info"><?php echo $crop_total_quantity;?></th>
            <th style="text-align: right;" class="btn-info"><?php echo $crop_target_value;?></th>
            <th style="text-align: right;" class="btn-info"><?php echo $crop_achieve_value;?></th>
            <th style="text-align: right;" class="btn-info"><?php echo number_format($crop_percentage,2);?></th>
        </tr>
    <?php
    }
    $grand_percentage = (($grand_achieve_value / $grand_target_value) * 100);
    ?>
    <tr>
        <th colspan="4" style="text-align: right;" class="btn-danger">Grand Total: </th>
        <th style="text-align: right;" class="btn-danger"><?php echo $grand_target;?></th>
        <th style="text-align: right;" class="btn-danger"><?php echo $grand_achieve_kg;?></th>
        <th style="text-align: right;" class="btn-danger"><?php echo $grand_bonus_kg;?></th>
        <th style="text-align: right;" class="btn-danger"><?php echo $grand_total_quantity;?></th>
        <th style="text-align: right;" class="btn-danger"><?php echo $grand_target_value;?></th>
        <th style="text-align: right;" class="btn-danger"><?php echo $grand_achieve_value;?></th>
        <th style="text-align: right;" class="btn-danger"><?php echo number_format($grand_percentage,2);?></th>
    </tr>
    </tbody>
</table>