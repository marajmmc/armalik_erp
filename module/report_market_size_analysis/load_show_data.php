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
if($_POST['crop_id']!="")
{
    $crop_id=" AND $tbl"."pdo_product_characteristic.crop_id='".$_POST['crop_id']."'";
}
else
{
    $crop_id="";
}
if($_POST['product_type_id']!="")
{
    $product_type_id=" AND $tbl"."pdo_product_characteristic.product_type_id='".$_POST['product_type_id']."'";
}
else
{
    $product_type_id="";
}

if($_POST['division_id']!="")
{
    $division_id=" AND $tbl"."division_info.division_id='".$_POST['division_id']."'";
}
else
{
    $division_id="";
}

if($_POST['zone_id']!="")
{
    $zone_id=" AND $tbl"."pdo_product_characteristic.zone_id='".$_POST['zone_id']."'";
}
else
{
    $zone_id="";
}

if($_POST['territory_id']!="")
{
    $territory_id=" AND $tbl"."pdo_product_characteristic.territory_id='".$_POST['territory_id']."'";
}
else
{
    $territory_id="";
}

if($_POST['zilla_id']!="")
{
    $district_id=" AND $tbl"."pdo_product_characteristic.district_id='".$_POST['zilla_id']."'";
}
else
{
    $district_id="";
}

if($_POST['upazilla_id']!="")
{
    $upazilla_id=" AND $tbl"."pdo_product_characteristic.upazilla_id='".$_POST['upazilla_id']."'";
}
else
{
    $upazilla_id="";
}

if($_POST['pdo_year_id']!="")
{
    $pdo_year_id=" AND $tbl"."primary_market_survey.pdo_year_id='".$_POST['pdo_year_id']."'";
}
else
{
    $pdo_year_id="";
}
if(!empty($_POST['division_id']) && !empty($_POST['zone_id']) && !empty($_POST['territory_id']) && !empty($_POST['zilla_id']) && !empty($_POST['upazilla_id']))
{
    $elm_id="upazilla_id";
    $elm_name="upazilla_name";
    $where=$division_id.$zone_id.$territory_id.$district_id.$upazilla_id;
}
else if(!empty($_POST['division_id']) && !empty($_POST['zone_id']) && !empty($_POST['territory_id']) && !empty($_POST['zilla_id']) && empty($_POST['upazilla_id']))
{
    $elm_id="upazilla_id";
    $elm_name="upazilla_name";
    $where=$division_id.$zone_id.$territory_id.$district_id;
}
else if(!empty($_POST['division_id']) && !empty($_POST['zone_id']) && !empty($_POST['territory_id']) && empty($_POST['zilla_id']) && empty($_POST['upazilla_id']))
{
    $elm_id="district_id";
    $elm_name="zillanameeng";
    $where=$division_id.$zone_id.$territory_id;
}
else if(!empty($_POST['division_id']) && !empty($_POST['zone_id']) && empty($_POST['territory_id']) && empty($_POST['zilla_id']) && empty($_POST['upazilla_id']))
{
    $elm_id="territory_id";
    $elm_name="territory_name";
    $where=$division_id.$zone_id;
}
else if(!empty($_POST['division_id']) && empty($_POST['zone_id']) && empty($_POST['territory_id']) && empty($_POST['zilla_id']) && empty($_POST['upazilla_id']))
{
    $elm_id="zone_id";
    $elm_name="zone_name";
    $where=$division_id;
}
else if(empty($_POST['division_id']) && empty($_POST['zone_id']) && empty($_POST['territory_id']) && empty($_POST['zilla_id']) && empty($_POST['upazilla_id']))
{
    $elm_id="division_id";
    $elm_name="division_name";
    $where="";
}
$sql_arm="SELECT
                CONCAT_WS(' - ', ait_crop_info.crop_name, ait_product_type.product_type, ait_varriety_info.varriety_name, ait_varriety_info.company_name) AS arm_crop_classification,
                ait_varriety_info.type,
                ait_varriety_info.hybrid,
                ait_division_info.division_name,
                ait_zone_info.zone_name,
                ait_territory_info.territory_name,
                ait_zilla.zillanameeng,
                ait_upazilla_new.upazilla_name,
                CONCAT_WS(' - ', ait_division_info.division_name, ait_territory_info.territory_name, ait_zilla.zillanameeng, ait_upazilla_new.upazilla_name) AS arm_location,
                Sum(ait_pdo_product_characteristic.sales_quantity) AS sales_quantity,
                ait_crop_info.crop_name,
                ait_product_type.product_type,
                ait_varriety_info.varriety_name,
                ait_varriety_info.company_name,
                ait_varriety_info.hybrid,
                ait_pdo_product_characteristic.crop_id,
                ait_pdo_product_characteristic.product_type_id,
                ait_pdo_product_characteristic.variety_id,
                ait_division_info.division_id,
                ait_pdo_product_characteristic.zone_id,
                ait_pdo_product_characteristic.territory_id,
                ait_pdo_product_characteristic.district_id,
                ait_pdo_product_characteristic.upazilla_id
            FROM
                ait_pdo_product_characteristic
                LEFT JOIN ait_crop_info ON ait_crop_info.crop_id = ait_pdo_product_characteristic.crop_id
                LEFT JOIN ait_product_type ON ait_product_type.product_type_id = ait_pdo_product_characteristic.product_type_id
                LEFT JOIN ait_varriety_info ON ait_varriety_info.varriety_id = ait_pdo_product_characteristic.variety_id
                LEFT JOIN ait_zone_info ON ait_zone_info.zone_id = ait_pdo_product_characteristic.zone_id
                LEFT JOIN ait_division_info ON ait_division_info.division_id = ait_zone_info.division_id
                LEFT JOIN ait_territory_info ON ait_territory_info.territory_id = ait_pdo_product_characteristic.territory_id
                LEFT JOIN ait_zilla ON ait_zilla.zillaid = ait_pdo_product_characteristic.district_id
                LEFT JOIN ait_upazilla_new ON ait_upazilla_new.zilla_id = ait_pdo_product_characteristic.district_id AND ait_upazilla_new.upazilla_id = ait_pdo_product_characteristic.upazilla_id
            WHERE
                ait_pdo_product_characteristic.`status`='Active'
                AND ait_pdo_product_characteristic.del_status=0
                AND ait_pdo_product_characteristic.sales_quantity!=0
                AND ait_varriety_info.type=0
                $crop_id $product_type_id $pdo_year_id
                $where
            GROUP BY
                ait_division_info.division_id,
                ait_pdo_product_characteristic.zone_id,
                ait_pdo_product_characteristic.territory_id,
                ait_pdo_product_characteristic.district_id,
                ait_pdo_product_characteristic.upazilla_id,
                ait_pdo_product_characteristic.crop_id,
                ait_pdo_product_characteristic.product_type_id,
                ait_pdo_product_characteristic.variety_id";
if($db->open())
{
    $arm_array=array();
    $result=$db->query($sql_arm);
    while($row_arm=$db->fetchAssoc($result))
    {
        $arm_array[$row_arm['crop_id']]['crop_name']=$row_arm['crop_name'];
        $arm_array[$row_arm['crop_id']]['type'][$row_arm['product_type_id']]['product_type']=$row_arm['product_type'];
        $arm_array[$row_arm['crop_id']]['type'][$row_arm['product_type_id']]['variety'][$row_arm['variety_id']]['variety_name']=$row_arm['varriety_name'];
        $arm_array[$row_arm['crop_id']]['type'][$row_arm['product_type_id']]['variety'][$row_arm['variety_id']]['hybrid']=$row_arm['hybrid'];
        $arm_array[$row_arm['crop_id']]['type'][$row_arm['product_type_id']]['variety'][$row_arm['variety_id']][$row_arm[$elm_id]]['sales_quantity']=$row_arm['sales_quantity'];
        $th_elm[$row_arm[$elm_id]]['elm_name']=$row_arm[$elm_name];
    }
}

//echo "<pre>";
//print_r($arm_array);
//echo "</pre>";

$sql_chk="SELECT
                CONCAT_WS(' - ', ait_crop_info.crop_name, ait_product_type.product_type, ait_varriety_info.varriety_name, ait_varriety_info.company_name) AS arm_crop_classification,
                ait_varriety_info.type,
                ait_varriety_info.hybrid,
                ait_division_info.division_name,
                ait_zone_info.zone_name,
                ait_territory_info.territory_name,
                ait_zilla.zillanameeng,
                ait_upazilla_new.upazilla_name,
                CONCAT_WS(' - ', ait_division_info.division_name, ait_territory_info.territory_name, ait_zilla.zillanameeng, ait_upazilla_new.upazilla_name) AS arm_location,
                Sum(ait_pdo_product_characteristic.sales_quantity) AS sales_quantity,
                ait_crop_info.crop_name,
                ait_product_type.product_type,
                ait_varriety_info.varriety_name,
                ait_varriety_info.hybrid,
                ait_varriety_info.company_name,
                ait_pdo_product_characteristic.crop_id,
                ait_pdo_product_characteristic.product_type_id,
                ait_pdo_product_characteristic.variety_id,
                ait_division_info.division_id,
                ait_pdo_product_characteristic.zone_id,
                ait_pdo_product_characteristic.territory_id,
                ait_pdo_product_characteristic.district_id,
                ait_pdo_product_characteristic.upazilla_id
            FROM
                ait_pdo_product_characteristic
                LEFT JOIN ait_crop_info ON ait_crop_info.crop_id = ait_pdo_product_characteristic.crop_id
                LEFT JOIN ait_product_type ON ait_product_type.product_type_id = ait_pdo_product_characteristic.product_type_id
                LEFT JOIN ait_varriety_info ON ait_varriety_info.varriety_id = ait_pdo_product_characteristic.variety_id
                LEFT JOIN ait_zone_info ON ait_zone_info.zone_id = ait_pdo_product_characteristic.zone_id
                LEFT JOIN ait_division_info ON ait_division_info.division_id = ait_zone_info.division_id
                LEFT JOIN ait_territory_info ON ait_territory_info.territory_id = ait_pdo_product_characteristic.territory_id
                LEFT JOIN ait_zilla ON ait_zilla.zillaid = ait_pdo_product_characteristic.district_id
                LEFT JOIN ait_upazilla_new ON ait_upazilla_new.zilla_id = ait_pdo_product_characteristic.district_id AND ait_upazilla_new.upazilla_id = ait_pdo_product_characteristic.upazilla_id
            WHERE
                ait_pdo_product_characteristic.`status`='Active'
                AND ait_pdo_product_characteristic.del_status=0
                AND ait_pdo_product_characteristic.sales_quantity!=0
                AND ait_varriety_info.type=1
                $crop_id $product_type_id $pdo_year_id
                $where
            GROUP BY
                ait_division_info.division_id,
                ait_pdo_product_characteristic.zone_id,
                ait_pdo_product_characteristic.territory_id,
                ait_pdo_product_characteristic.district_id,
                ait_pdo_product_characteristic.upazilla_id,
                ait_pdo_product_characteristic.crop_id,
                ait_pdo_product_characteristic.product_type_id,
                ait_pdo_product_characteristic.variety_id";
if($db->open())
{
    $chk_array=array();
    $result=$db->query($sql_chk);
    while($row_arm=$db->fetchAssoc($result))
    {
        $chk_array[$row_arm['crop_id']]['crop_name']=$row_arm['crop_name'];
        $chk_array[$row_arm['crop_id']]['type'][$row_arm['product_type_id']]['product_type']=$row_arm['product_type'];
        $chk_array[$row_arm['crop_id']]['type'][$row_arm['product_type_id']]['variety'][$row_arm['variety_id']]['variety_name']=$row_arm['varriety_name'];
        $chk_array[$row_arm['crop_id']]['type'][$row_arm['product_type_id']]['variety'][$row_arm['variety_id']]['hybrid']=$row_arm['hybrid'];
        $chk_array[$row_arm['crop_id']]['type'][$row_arm['product_type_id']]['variety'][$row_arm['variety_id']][$row_arm[$elm_id]]['sales_quantity']=$row_arm['sales_quantity'];
        $chk_th_elm[$row_arm[$elm_id]]['elm_name']=$row_arm[$elm_name];
    }
}
//echo "<pre>";
//print_r($th_elm);
//echo "</pre>";
?>
<a class="btn btn-small btn-success" data-original-title="" onclick="print_rpt()" style="float: right;">
    <i class="icon-print" data-original-title="Share"> </i> Print
</a>
<div id="PrintArea" style="background-color: white;" >
    <?php include_once '../../libraries/print_page/Print_header.php';?>
    <br />
    <br />
    <div style="overflow: scroll; width: auto;" >
        <?php
        if(sizeof($arm_array)<1)
        {
            ?>
            <table class="table table-condensed table-striped table-hover table-bordered pull-left report" id="data-table">
                <thead>
                <tr>
                    <th style="text-align: center;"> ARM Variety Not Available. </th>
                </tr>
                </thead>
            </table>
        <?php
        }
        else
        {
            ?>
            <table class="table table-condensed table-striped table-hover table-bordered pull-left report" id="data-table">
                <thead>
                <tr>
                    <th colspan="21" style="text-align: center;"> ARM Variety </th>
                </tr>
                <tr>
                    <th style="text-align: left;" rowspan="2"> Crop</th>
                    <th style="text-align: left;" rowspan="2"> Product Type</th>
                    <th style="text-align: left;" rowspan="2"> F1/OP</th>
                    <th style="text-align: left;" rowspan="2"> ARM Variety</th>
                    <th style="text-align: center;" colspan="<?php echo sizeof($th_elm)?>"> Market Size(Kg)</th>
                    <th style="text-align: center;" rowspan="2"> Total Market Size </th>
                </tr>
                <tr>
                    <?php
                    foreach($th_elm as $elm)
                    {
                        ?>
                        <th style="text-align: center;"> <?php echo $elm['elm_name']?> </th>
                    <?php
                    }
                    ?>
                </tr>
                <?php
                foreach($arm_array as $crop)
                {
                    ?>
                    <tr>
                        <th><?php echo $crop['crop_name']?></th>
                        <th colspan="21">&nbsp;</th>
                    </tr>
                    <?php
                    $type_sub_total=array();
                    $op_sub_total=0;
                    $f1_sub_total=0;
                    $sl=0;
                    foreach($th_elm as $elm_ids=>$elm)
                    {
                        ++$sl;
                        $type_sub_total[$sl]=0;
                    }
                    foreach($crop['type'] as $type)
                    {
                        ?>
                        <tr>
                            <th colspan="">&nbsp;</th>
                            <th><?php echo $type['product_type']?></th>
                            <th colspan="21">&nbsp;</th>
                        </tr>
                        <?php
                        foreach($type['variety'] as $variety)
                        {
                            ?>
                            <tr>
                                <th colspan="2">&nbsp;</th>
                                <th><?php echo $variety['hybrid']?></th>
                                <th><?php echo $variety['variety_name']?></th>
                                <?php
                                $sale_quantity=0;
                                $arm_total_sale_quantity=0;
                                $sl=0;
                                foreach($th_elm as $elm_ids=>$elm)
                                {
                                    ++$sl;
                                    if(isset($variety[$elm_ids]['sales_quantity']))
                                    {
                                        $sale_quantity=$variety[$elm_ids]['sales_quantity'];
                                    }
                                    else
                                    {
                                        $sale_quantity=0;
                                    }
                                    //$sale_quantity=$variety[$elm_ids]['sales_quantity'];
                                    $arm_total_sale_quantity+=$sale_quantity;
                                    $type_sub_total[$sl]+=$sale_quantity;
                                    if($variety['hybrid']=="F1 Hybrid")
                                    {
                                        $f1_sub_total+=$sale_quantity;
                                    }
                                    else if($variety['hybrid']=="OP")
                                    {
                                        $op_sub_total+=$sale_quantity;
                                    }
                                    else
                                    {
                                        $op_sub_total='';
                                        $f1_sub_total='';
                                    }
                                    ?>
                                    <th style="text-align: center;"> <?php echo $sale_quantity;?> </th>
                                <?php
                                }
                                ?>
                                <th style="text-align: center;"><?php echo $arm_total_sale_quantity;?></th>
                            </tr>
                        <?php
                        }
                    }
                    ?>
                    <tr>
                        <th colspan="4" style="text-align: right">Type Sub Total (<?php echo $type['product_type'];?>): </th>
                        <?php
                        $sl=0;
                        $type_sub_t_total=0;
                        foreach($th_elm as $elm_ids=>$elm)
                        {
                            ++$sl;
                            $type_sub_t_total+=$type_sub_total[$sl];
                            ?>
                            <th style="text-align: center;"><?php echo $type_sub_total[$sl];?></th>
                            <?php
                        }
                        ?>
                        <th style="text-align: center;"><?php echo $type_sub_t_total;?></th>
                    </tr>
                    <tr>
                        <th colspan="4" style="text-align: right">Type Sub Total (<?php echo $type['product_type'];?>) Hybrid: <?php echo $f1_sub_total;?> </th>
                        <th colspan="4" style="text-align: right">Type Sub Total (<?php echo $type['product_type'];?>) OP: <?php echo $op_sub_total;?> </th>
                    </tr>
                <?php
                }
                ?>
                </thead>
            </table>
            <?php
        }
        ?>
    </div>
    <br />
    <br />
    <div style="overflow: scroll; width: auto;" >
        <?php
        if(sizeof($chk_array)<1)
        {
            ?>
            <table class="table table-condensed table-striped table-hover table-bordered pull-left report" id="data-table">
                <thead>
                <tr>
                    <th style="text-align: center;"> Competitor Variety Not Available. </th>
                </tr>
                </thead>
            </table>
            <?php
        }
        else
        {
            ?>
            <table class="table table-condensed table-striped table-hover table-bordered pull-left report" id="data-table">
                <thead>
                <tr>
                    <th colspan="21" style="text-align: center;"> Competitor Variety </th>
                </tr>
                <tr>
                    <th style="text-align: left;" rowspan="2"> Crop</th>
                    <th style="text-align: left;" rowspan="2"> Product Type</th>
                    <th style="text-align: left;" rowspan="2"> F1/OP</th>
                    <th style="text-align: left;" rowspan="2"> Competitor Variety</th>
                    <th style="text-align: center;" colspan="<?php echo sizeof($chk_th_elm)?>"> Market Size(Kg)</th>
                    <th style="text-align: center;" rowspan="2"> Total Market Size </th>
                </tr>
                <tr>
                    <?php
                    foreach($chk_th_elm as $elm)
                    {
                        ?>
                        <th style="text-align: center;"> <?php echo $elm['elm_name']?> </th>
                    <?php
                    }
                    ?>
                </tr>
                <?php
                foreach($chk_array as $crop)
                {
                    ?>
                    <tr>
                        <th><?php echo $crop['crop_name']?></th>
                        <th colspan="21">&nbsp;</th>
                    </tr>
                    <?php
                    $chk_type_sub_total=array();
                    $op_sub_total=0;
                    $f1_sub_total=0;
                    $sl=0;
                    foreach($th_elm as $elm_ids=>$elm)
                    {
                        ++$sl;
                        $chk_type_sub_total[$sl]=0;
                    }
                    foreach($crop['type'] as $type)
                    {
                        ?>
                        <tr>
                            <th colspan="">&nbsp;</th>
                            <th><?php echo $type['product_type']?></th>
                            <th colspan="21">&nbsp;</th>
                        </tr>
                        <?php
                        foreach($type['variety'] as $variety)
                        {
                            ?>
                            <tr>
                                <th colspan="2">&nbsp;</th>
                                <th><?php echo $variety['hybrid']?></th>
                                <th><?php echo $variety['variety_name']?></th>
                                <?php
                                $sale_quantity=0;
                                $chk_total_sale_quantity=0;
                                $sl=0;
                                foreach($chk_th_elm as $elm_ids=>$elm)
                                {
                                    ++$sl;
                                    if(isset($variety[$elm_ids]['sales_quantity']))
                                    {
                                        $sale_quantity=$variety[$elm_ids]['sales_quantity'];
                                    }
                                    else
                                    {
                                        $sale_quantity=0;
                                    }
                                    $chk_total_sale_quantity+=$sale_quantity;
                                    $chk_type_sub_total[$sl]+=$sale_quantity;
                                    if($variety['hybrid']=="F1 Hybrid")
                                    {
                                        $f1_sub_total+=$sale_quantity;
                                    }
                                    else if($variety['hybrid']=="OP")
                                    {
                                        $op_sub_total+=$sale_quantity;
                                    }
                                    else
                                    {
                                        $op_sub_total='';
                                        $f1_sub_total='';
                                    }
                                    ?>
                                    <th style="text-align: center;"> <?php echo $sale_quantity?> </th>
                                <?php
                                }
                                ?>
                                <th style="text-align: center;"><?php echo $chk_total_sale_quantity;?></th>
                            </tr>
                        <?php
                        }
                    }
                    ?>
                    <tr>
                        <th colspan="4" style="text-align: right">Type Sub Total (<?php echo $type['product_type'];?>): </th>
                        <?php
                        $sl=0;
                        $chk_type_sub_t_total=0;
                        foreach($th_elm as $elm_ids=>$elm)
                        {
                            ++$sl;
                            $chk_type_sub_t_total+=$chk_type_sub_total[$sl];
                            ?>
                            <th style="text-align: center;"><?php echo $chk_type_sub_total[$sl];?></th>
                        <?php
                        }
                        ?>
                        <th style="text-align: center;"><?php echo $chk_type_sub_t_total;?></th>
                    </tr>
                    <tr>
                        <th colspan="4" style="text-align: right">Type Sub Total (<?php echo $type['product_type'];?>) Hybrid: <?php echo $f1_sub_total;?> </th>
                        <th colspan="4" style="text-align: right">Type Sub Total (<?php echo $type['product_type'];?>) OP: <?php echo $op_sub_total;?> </th>
                    </tr>
                <?php
                }
                ?>
                </thead>
            </table>
            <?php
        }
        ?>
    </div>
</div>
