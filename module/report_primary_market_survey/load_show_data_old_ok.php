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
//$wcondition="AND (ait_pdo_product_characteristic.variety_id IN ($variety) OR
//        ait_pdo_product_characteristic.variety_name_txt IN ($varietytxt))";
//
//
if($_POST['zone_id']!="")
{
    $zone_id=" AND ait_primary_market_survey.zone_id='".$_POST['zone_id']."'";
}
else
{
    $zone_id="";
}

if($_POST['district_id']!="")
{
    $district_id=" AND ait_primary_market_survey.district_id='".$_POST['district_id']."'";
}
else
{
    $district_id="";
}

if($_POST['upazilla_id']!="")
{
    $upazilla_id=" AND ait_primary_market_survey.upazilla_id='".$_POST['upazilla_id']."'";
}
else
{
    $upazilla_id="";
}

if($_POST['crop_id']!="")
{
    $crop_id=" AND ait_primary_market_survey_details.crop_id='".$_POST['crop_id']."'";
}
else
{
    $crop_id="";
}

if($_POST['product_type_id']!="")
{
    $product_type_id=" AND ait_primary_market_survey_details.product_type_id='".$_POST['product_type_id']."'";
}
else
{
    $product_type_id="";
}
?>
    <?php

    $dbpmsd=new Database();
    $alldata=array();
    $sqlpmsd="SELECT
                    ait_primary_market_survey_details.id,
                    ait_primary_market_survey.market_survey_id,
                    ait_primary_market_survey.zone_id,
                    ait_primary_market_survey.district_id,
                    ait_primary_market_survey.upazilla_id,
                    ait_primary_market_survey.wholesaler_name,
                    ait_primary_market_survey.`status`,
                    ait_primary_market_survey.del_status,
                    ait_crop_info.crop_name,
                    ait_product_type.product_type,
                    ait_primary_market_survey_details.varriety_id,
                    SUM(ait_primary_market_survey_details.sales_quantity) AS sales_quantity,
                    SUM(ait_primary_market_survey_details.market_size) AS market_size,
                    concat_ws(' - ', ait_varriety_info.varriety_name) AS arm_variety,
                    concat_ws(' - ', ait_pdo_product_characteristic_setting.variety_name_txt, ait_pdo_product_characteristic_setting.company_name) as comparator_variety,
                    ait_primary_market_survey_details.crop_id,
                    ait_primary_market_survey_details.product_type_id,
                    ait_primary_market_survey_details.varriety_id
                FROM
                    ait_primary_market_survey
                    LEFT JOIN ait_primary_market_survey_details ON ait_primary_market_survey_details.market_survey_id = ait_primary_market_survey.market_survey_id
                    LEFT JOIN ait_crop_info ON ait_crop_info.crop_id = ait_primary_market_survey_details.crop_id
                    LEFT JOIN ait_product_type ON ait_product_type.crop_id = ait_primary_market_survey_details.crop_id AND ait_product_type.product_type_id = ait_primary_market_survey_details.product_type_id
                    LEFT JOIN ait_varriety_info ON ait_varriety_info.crop_id = ait_primary_market_survey_details.crop_id AND ait_varriety_info.product_type_id = ait_primary_market_survey_details.product_type_id AND ait_varriety_info.varriety_id = ait_primary_market_survey_details.varriety_id
                    LEFT JOIN ait_pdo_product_characteristic_setting ON ait_pdo_product_characteristic_setting.prodcut_characteristic_id = ait_primary_market_survey_details.varriety_id
                WHERE ait_primary_market_survey_details.del_status=0 $zone_id $district_id $upazilla_id $crop_id $product_type_id
                GROUP BY
                    ait_primary_market_survey_details.crop_id,
                    ait_primary_market_survey_details.product_type_id,
                    ait_primary_market_survey_details.varriety_id,
                    ait_primary_market_survey.market_survey_id
            ";
    if($dbpmsd->open())
    {
        $resultpmsd=$dbpmsd->query($sqlpmsd);
        while($rowpmsd=$dbpmsd->fetchAssoc($resultpmsd))
        {
            if(!empty($rowpmsd['arm_variety']))
            {
                $variety=$rowpmsd['arm_variety'];
            }
            if(!empty($rowpmsd['comparator_variety']))
            {
                $variety=$rowpmsd['comparator_variety'];
            }
            $alldatas[$variety]['crop_id']=$rowpmsd['crop_id'];
            $alldatas[$variety]['crop_name']=$rowpmsd['crop_name'];
            $alldatas[$variety]['product_type_id']=$rowpmsd['product_type_id'];
            $alldatas[$variety]['product_type']=$rowpmsd['product_type'];
            $alldatas[$variety]['varriety_id']=$rowpmsd['varriety_id'];
            $alldatas[$variety]['variety_name']=$variety;
            $alldatas[$variety]['amount'][]=array('sales_quantity'=>$rowpmsd['sales_quantity'], 'market_size'=>$rowpmsd['market_size'], 'wholesaler_name'=>$rowpmsd['wholesaler_name'], 'market_survey_id'=>$rowpmsd['market_survey_id']);
        }
    }

if(!empty($alldatas))
{
    foreach($alldatas as $key=>$data)
    {
        foreach($data['amount'] as $distributors)
        {
            $distributor_data[$distributors['market_survey_id']]=$distributors['wholesaler_name'];
        }
    }
}

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
            <th></th>
            <th></th>
            <th></th>
            <th>Individual Sales Quantity</th>
            <th></th>
            <th>Market Size</th>
            <th></th>
        </tr>
        <tr>
            <th>Crop</th>
            <th>Type</th>
            <th>Variety</th>
            <th>
                <table class="table table-condensed table-striped table-hover table-bordered pull-left report">
                    <thead>
                    <tr>
                        <?php
                        if(!empty($distributor_data))
                        {
                            foreach($distributor_data as $key=>$distributor)
                            {
                                ?>
                                <th ><?php echo $distributor;?></th>
                            <?php
                            }
                        }
                        ?>
                    </tr>
                    </thead>
                </table>
            </th>
            <th>Total</th>
            <th>
                <table class="table table-condensed table-striped table-hover table-bordered pull-left report">
                    <thead>
                    <tr>
                        <?php
                        if(!empty($distributor_data))
                        {
                            foreach($distributor_data as $key=>$distributor)
                            {
                                ?>
                                <th ><?php echo $distributor;?></th>
                            <?php
                            }
                        }
                        ?>
                    </tr>
                    </thead>
                </table>
            </th>
            <th>Total</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $i=0;
        if(!empty($alldatas))
        {
            foreach($alldatas as $alldata)
            {
                ?>
        <tr>
            <td> <?php echo $alldata['crop_name'];?> </td>
            <td> <?php echo $alldata['product_type'];?> </td>
            <td> <?php echo $alldata['variety_name'];?> </td>
            <td>
                <table class="table table-condensed table-striped table-hover table-bordered pull-left report">
                    <thead>
                    <tr>
                        <?php
                        $i=0;
                        $total_sales_qunatity=0;
                        if(!empty($distributor_data))
                        {
                            foreach($distributor_data as $key=>$distributor)
                            {
                                if(isset($alldata['amount'][$i]['market_survey_id'])==$key)
                                {
                                    $total_sales_qunatity=$total_sales_qunatity+$alldata['amount'][$i]['sales_quantity'];
                                ?>
                                    <td title="<?php echo $distributor;?>"> <?php echo $alldata['amount'][$i]['sales_quantity'];?> </td>
                                <?php
                                }
                                else
                                {
                                    ?>
                                    <td title="<?php echo $distributor;?>"> <?php echo 0.00;?> </td>
                                <?php
                                }
                                ++$i;
                            }
                        }
                        ?>
                    </tr>
                    </thead>
                </table>
            </td>
            <td>
                <?php echo $total_sales_qunatity;?>
            </td>
            <td>
                <table class="table table-condensed table-striped table-hover table-bordered pull-left report">
                    <thead>
                    <tr>
                        <?php
                        $i=0;
                        $total_market_size=0;
                        if(!empty($distributor_data))
                        {
                            foreach($distributor_data as $key=>$distributor)
                            {
                                if(isset($alldata['amount'][$i]['market_survey_id'])==$key)
                                {
                                    $total_market_size=$total_market_size+$alldata['amount'][$i]['market_size'];
                                ?>
                                    <td title="<?php echo $distributor;?>"> <?php echo $alldata['amount'][$i]['market_size'];?> </td>
                                <?php
                                }
                                else
                                {
                                    ?>
                                    <td title="<?php echo $distributor;?>"> <?php echo 0.00;?> </td>
                                <?php
                                }
                                ++$i;
                            }
                        }
                        ?>
                    </tr>
                    </thead>
                </table>
            </td>
            <td>
                <?php echo $total_market_size;?>
            </td>


        </tr>

        <?php
        }
    }
?>
        </tbody>
    </table>
</div>
<?php include_once '../../libraries/print_page/Print_footer.php'; ?>
</div>