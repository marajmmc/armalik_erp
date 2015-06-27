<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$dbr = new Database();
$dbs = new Database();
$dbc = new Database();
$dbop = new Database();
$dbhybrid = new Database();
$tbl = _DB_PREFIX;

//$variety= str_replace('[','',str_replace(']','',json_encode(@$_POST['variety_id'])));
//$varietytxt= str_replace('[','',str_replace(']','',json_encode(@$_POST['variety_name_txt'])));
//
//$wcondition="AND ($tbl"."pdo_product_characteristic.variety_id IN ($variety) OR
//        $tbl"."pdo_product_characteristic.variety_name_txt IN ($varietytxt))";
//
//
if($_POST['zone_id']!="")
{
    $zone_id=" AND $tbl"."primary_market_survey.zone_id='".$_POST['zone_id']."'";
}
else
{
    $zone_id="";
}

if($_POST['district_id']!="")
{
    $district_id=" AND $tbl"."primary_market_survey.district_id='".$_POST['district_id']."'";
}
else
{
    $district_id="";
}

if($_POST['upazilla_id']!="")
{
    $upazilla_id=" AND $tbl"."primary_market_survey.upazilla_id='".$_POST['upazilla_id']."'";
}
else
{
    $upazilla_id="";
}

if($_POST['crop_id']!="")
{
    $crop_id=" AND $tbl"."primary_market_survey_details.crop_id='".$_POST['crop_id']."'";
}
else
{
    $crop_id="";
}

if($_POST['product_type_id']!="")
{
    $product_type_id=" AND $tbl"."primary_market_survey_details.product_type_id='".$_POST['product_type_id']."'";
}
else
{
    $product_type_id="";
}
if($_POST['pdo_year_id']!="")
{
    $product_type_id=" AND $tbl"."primary_market_survey.pdo_year_id='".$_POST['pdo_year_id']."'";
}
else
{
    $product_type_id="";
}
?>
    <?php

    $dbpmsd=new Database();

    $sqlpmsd="SELECT
                    $tbl"."primary_market_survey_details.id,
                    $tbl"."primary_market_survey.market_survey_id,
                    $tbl"."primary_market_survey.pdo_year_id,
                    $tbl"."primary_market_survey.zone_id,
                    $tbl"."primary_market_survey.district_id,
                    $tbl"."primary_market_survey.upazilla_id,
                    $tbl"."primary_market_survey.wholesaler_name,
                    $tbl"."primary_market_survey.`status`,
                    $tbl"."primary_market_survey.del_status,
                    $tbl"."crop_info.crop_name,
                    $tbl"."product_type.product_type,
                    $tbl"."primary_market_survey_details.varriety_id,
                    $tbl"."primary_market_survey_details.sales_quantity,
                    $tbl"."primary_market_survey_details.market_size,
                    $tbl"."primary_market_survey_details.sales_quantity_other,
                    concat_ws(' - ', $tbl"."varriety_info.varriety_name) AS arm_variety,
                    concat_ws(' - ', $tbl"."pdo_product_characteristic_setting.variety_name_txt, $tbl"."pdo_product_characteristic_setting.company_name) as comparator_variety,
                    $tbl"."primary_market_survey_details.crop_id,
                    $tbl"."primary_market_survey_details.product_type_id,
                    $tbl"."primary_market_survey_details.varriety_id
                FROM
                    $tbl"."primary_market_survey
                    LEFT JOIN $tbl"."primary_market_survey_details ON $tbl"."primary_market_survey_details.market_survey_id = $tbl"."primary_market_survey.market_survey_id
                    LEFT JOIN $tbl"."crop_info ON $tbl"."crop_info.crop_id = $tbl"."primary_market_survey_details.crop_id
                    LEFT JOIN $tbl"."product_type ON $tbl"."product_type.crop_id = $tbl"."primary_market_survey_details.crop_id AND $tbl"."product_type.product_type_id = $tbl"."primary_market_survey_details.product_type_id
                    LEFT JOIN $tbl"."varriety_info ON $tbl"."varriety_info.crop_id = $tbl"."primary_market_survey_details.crop_id AND $tbl"."varriety_info.product_type_id = $tbl"."primary_market_survey_details.product_type_id AND $tbl"."varriety_info.varriety_id = $tbl"."primary_market_survey_details.varriety_id
                    LEFT JOIN $tbl"."pdo_product_characteristic_setting ON $tbl"."pdo_product_characteristic_setting.prodcut_characteristic_id = $tbl"."primary_market_survey_details.varriety_id
                WHERE
                    $tbl"."primary_market_survey_details.del_status=0

                    $zone_id $district_id $upazilla_id $crop_id $product_type_id
            ";
    $all_data=array();
    $ths=array();
    if($dbpmsd->open())
    {
        $resultpmsd=$dbpmsd->query($sqlpmsd);

        while($rowpmsd=$dbpmsd->fetchAssoc($resultpmsd))
        {

            $ths[$rowpmsd['market_survey_id']]=$rowpmsd['wholesaler_name'];
            if(!empty($rowpmsd['arm_variety']))
            {
                $variety=$rowpmsd['arm_variety'];
            }
            if(!empty($rowpmsd['comparator_variety']))
            {
                $variety=$rowpmsd['comparator_variety'];
            }
            $all_data[$variety]['crop_id']=$rowpmsd['crop_id'];
            $all_data[$variety]['crop_name']=$rowpmsd['crop_name'];
            $all_data[$variety]['product_type_id']=$rowpmsd['product_type_id'];
            $all_data[$variety]['product_type']=$rowpmsd['product_type'];
            $all_data[$variety]['varriety_id']=$rowpmsd['varriety_id'];
            $all_data[$variety]['variety_name']=$variety;
            $all_data[$variety]['sales_quantity_other']=$rowpmsd['sales_quantity_other'];
            $all_data[$variety]['pdo_year_id']=$rowpmsd['pdo_year_id'];
            $all_data[$variety]['amount'][$rowpmsd['market_survey_id']][]=array('sales_quantity'=>$rowpmsd['sales_quantity'], 'market_size'=>$rowpmsd['market_size'],'market_survey_id'=>$rowpmsd['market_survey_id']);
        }
    }

//echo "<pre>";
//print_r($all_data);
//echo "</pre>";
//    echo "<pre>";
//    print_r($ths);
//    echo "</pre>";
    $distributor_list=array();
//    foreach($alldatas as $dist)
//    {
//        $i=0;
//        foreach($dist['amount'] as $key=>$dis)
//        {
//            $i=$i+1;
//            echo "<pre>";
//            print_r($dis);
//            echo "</pre>";
//            //$distributor_list[$dis['market_survey_id']]=array('market_survey_id'=> $dis['market_survey_id']);
//        }
//    }

//
//if(!empty($alldatas))
//{
//    foreach($alldatas as $key=>$data)
//    {
//        foreach($data['amount'] as $distributors)
//        {
//            $distributor_data[$distributors['market_survey_id']]=$distributors['wholesaler_name'];
//        }
//    }
//}

    ?>

<a class="btn btn-small btn-success" data-original-title="" onclick="print_rpt()" style="float: right;">
    <i class="icon-print" data-original-title="Share"> </i> Print
</a>
<div id="PrintArea" style="background-color: white;" >
    <?php include_once '../../libraries/print_page/Print_header.php';?>
    <br />
    <br />
    <div style="width: auto;" >
    <table class="table table-condensed table-striped table-hover table-bordered pull-left report" id="data-table">
        <thead>
        <tr>
            <th colspan="3"></th>
            <th colspan="<?php echo sizeof($ths); ?>" style="text-align: center;">Individual Sales Quantity</th>
            <th colspan="<?php echo sizeof($ths); ?>" style="text-align: center;">Market Size</th>
            <th>Assumed Market Size</th>
        </tr>

        </thead>
        <tbody>
            <tr style="text-align: center;font-weight: bold;">
                <td style="text-align: center;">Crop</td>
                <td style="text-align: center;">Produce Type</td>
                <td style="text-align: center;">Variety</td>
                <?php
                    foreach($ths as $th)
                    {
                        ?>
                        <td style="text-align: center;"><?php echo $th; ?></td>
                        <?php
                    }
                ?>
                <?php
                foreach($ths as $th)
                {
                    ?>
                    <td style="text-align: center;"><?php echo $th; ?></td>
                <?php
                }
                ?>
                <td>&nbsp;</td>
            </tr>
            <?php
                foreach($all_data as $data)
                {
                    ?>
                    <tr>
                        <td><?php echo $data['crop_name']; ?></td>
                        <td><?php echo $data['product_type']; ?></td>
                        <td><?php echo $data['variety_name']." ( ".$data['pdo_year_id']." )"; ?></td>
                        <?php
                            $total_sale_quantity=0;
                            foreach($ths as $key=>$th)
                            {
                                $sale_quantity=0;
                                if(isset($data['amount'][$key]))
                                {
                                    foreach($data['amount'][$key] as $d)
                                    {
                                        $sale_quantity=$d['sales_quantity'];
                                    }
                                    $total_sale_quantity+=$sale_quantity;
                                }
                                ?>
                                <td>
                                    <?php
                                        echo $sale_quantity>0?$sale_quantity:'-';
                                    ?>
                                </td>
                                <?php
                            }
                        ?>

                        <?php
                        $total_market_size=0;
                        foreach($ths as $key=>$th)
                        {
                            $market_size=0;
                            if(isset($data['amount'][$key]))
                            {
                                foreach($data['amount'][$key] as $d)
                                {
                                    $market_size=$d['market_size'];
                                }
                                $total_market_size+=$market_size;
                            }
                            ?>
                            <td>
                                <?php
                                echo $market_size>0?$market_size:'-';
                                ?>
                            </td>
                        <?php
                        }
                        ?>
                        <td><?php echo $data['sales_quantity_other']?$data['sales_quantity_other']:'-'; ?></td>
                    </tr>
                    <?php
                }
            ?>
        </tbody>
    </table>
</div>
<?php include_once '../../libraries/print_page/Print_footer.php'; ?>
</div>