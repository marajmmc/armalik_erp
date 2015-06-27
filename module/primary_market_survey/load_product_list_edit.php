<?php
session_start();
ob_start();
if ($_SESSION['logged'] != 'yes')
{
    $_REQUEST["msg"] = "TimeoutC";
    header("location:../../index.php");
}
//echo $_SESSION['shop_name_eng']; 
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$tbl = _DB_PREFIX;
$db = new Database();

$row_id=$_POST['rowID'];
$zone_id=$_POST['zone_id'];
$crop_id=$_POST['crop_master_id'];
$product_type_id=$_POST['product_master_type_id'];

$sqlws="
        SELECT
            $tbl"."primary_market_survey.market_survey_id,
            $tbl"."primary_market_survey.market_survey_group_id,
            $tbl"."primary_market_survey.wholesaler_name
        FROM `$tbl"."primary_market_survey`
        WHERE
            $tbl"."primary_market_survey.market_survey_group_id='".$row_id."'
            AND $tbl"."primary_market_survey.del_status=0
            AND $tbl"."primary_market_survey.status='Active'
";
$dbws=new Database();
if($dbws->open())
{
    $resultws=$dbws->query($sqlws);
    $count_whole_sale=$dbws->numRows($sqlws);
    $wholesaler=array();
    if($count_whole_sale>0)
    {
        while($rowws=$dbws->fetchAssoc($resultws))
        {
            $wholesaler[]=array('wholesaler_name'=>$rowws['wholesaler_name'], 'market_survey_id'=>$rowws['market_survey_id']);
        }
    }
    else
    {
        for($w=0; $w<3; $w++)
        {
            $wholesaler[]=array('wholesaler_name'=>'', 'market_survey_id'=>'');
        }
    }
}

$sqlws_details="SELECT
                    $tbl"."primary_market_survey_details.id,
                    $tbl"."primary_market_survey_details.market_survey_id,
                    $tbl"."primary_market_survey_details.crop_id,
                    $tbl"."primary_market_survey_details.product_type_id,
                    $tbl"."primary_market_survey_details.varriety_id,
                    $tbl"."primary_market_survey_details.sales_quantity,
                    $tbl"."primary_market_survey_details.market_size,
                    $tbl"."primary_market_survey_details.sales_quantity_other,
                    $tbl"."varriety_info.hybrid,
                    $tbl"."varriety_info.type
                FROM `$tbl"."primary_market_survey_details`
                LEFT JOIN $tbl"."varriety_info ON $tbl"."varriety_info.varriety_id = $tbl"."primary_market_survey_details.varriety_id
                WHERE
                    $tbl"."primary_market_survey_details.del_status=0
                    AND $tbl"."primary_market_survey_details.market_survey_group_id='".$row_id."'
                    AND $tbl"."primary_market_survey_details.crop_id='".$crop_id."'
                    AND $tbl"."primary_market_survey_details.product_type_id='".$product_type_id."'
                    AND $tbl"."primary_market_survey_details.status='Active'
                    AND $tbl"."varriety_info.type=0
                ";
$dbws_details=new Database();
$wholesaler_details=array();
if($dbws_details->open())
{
    $resultws_details=$dbws_details->query($sqlws_details);
    $count_details=$dbws_details->numRows($sqlws_details);
    if($count_details>0)
    {
        while($rowws_details = $dbws_details->fetchArray($resultws_details))
        {
            $wholesaler_details[$rowws_details['market_survey_id']][$rowws_details['crop_id']][$rowws_details['product_type_id']][$rowws_details['hybrid']][$rowws_details['varriety_id']]['sales_quantity']=$rowws_details['sales_quantity'];
            $wholesaler_details[$rowws_details['market_survey_id']][$rowws_details['crop_id']][$rowws_details['product_type_id']][$rowws_details['hybrid']][$rowws_details['varriety_id']]['market_size']=$rowws_details['market_size'];
            $wholesaler_details[$row_id][$rowws_details['crop_id']][$rowws_details['product_type_id']][$rowws_details['hybrid']][$rowws_details['varriety_id']]['sales_quantity_other']=$rowws_details['sales_quantity_other'];
        }
    }
    else
    {

    }
}
$sqlws_details="SELECT
                    $tbl"."primary_market_survey_details.id,
                    $tbl"."primary_market_survey_details.market_survey_id,
                    $tbl"."primary_market_survey_details.crop_id,
                    $tbl"."primary_market_survey_details.product_type_id,
                    $tbl"."primary_market_survey_details.varriety_id,
                    $tbl"."primary_market_survey_details.sales_quantity,
                    $tbl"."primary_market_survey_details.market_size,
                    $tbl"."primary_market_survey_details.sales_quantity_other,
                    $tbl"."varriety_info.hybrid,
                    $tbl"."varriety_info.type
                FROM `$tbl"."primary_market_survey_details`
                LEFT JOIN $tbl"."varriety_info ON $tbl"."varriety_info.varriety_id = $tbl"."primary_market_survey_details.varriety_id
                WHERE
                    $tbl"."primary_market_survey_details.del_status=0
                    AND $tbl"."primary_market_survey_details.market_survey_group_id='".$row_id."'
                    AND $tbl"."primary_market_survey_details.crop_id='".$crop_id."'
                    AND $tbl"."primary_market_survey_details.product_type_id='".$product_type_id."'
                    AND $tbl"."primary_market_survey_details.status='Active'
                    AND $tbl"."varriety_info.type=1
                ";
$dbws_details=new Database();
$wholesaler_details_chk=array();
if($dbws_details->open())
{
    $resultws_details=$dbws_details->query($sqlws_details);
    while($rowws_details = $dbws_details->fetchArray($resultws_details))
    {
        $wholesaler_details_chk[$rowws_details['market_survey_id']][$rowws_details['crop_id']][$rowws_details['product_type_id']][$rowws_details['hybrid']][$rowws_details['varriety_id']]['sales_quantity']=$rowws_details['sales_quantity'];
        $wholesaler_details_chk[$rowws_details['market_survey_id']][$rowws_details['crop_id']][$rowws_details['product_type_id']][$rowws_details['hybrid']][$rowws_details['varriety_id']]['market_size']=$rowws_details['market_size'];
        $wholesaler_details_chk[$row_id][$rowws_details['crop_id']][$rowws_details['product_type_id']][$rowws_details['hybrid']][$rowws_details['varriety_id']]['sales_quantity_other']=$rowws_details['sales_quantity_other'];
    }
}


?>
<div class="wrapper">
    <table class="table table-condensed table-striped table-bordered table-hover no-margin" >
        <thead>
        <tr>
            <th colspan="7">ARM Variety</th>
        </tr>
        <tr>
            <th style="width:15%">
                Crop
            </th>
            <th style="width:15%">
                Product Type
            </th>
            <th style="width:15%">
                F1/OP
            </th>
            <th style="width:15%">
                Variety
            </th>
            <?php
            $whole_count=count($wholesaler);
            for($i=0; $i<$whole_count; $i++)
            {
                ?>
                <th style="width:10%">
                    Individual Sales Quantity
                </th>
                <th style="width:10%">
                    Market Size
                </th>
                <?php
            }
            ?>
            <th style="width:15%">
                Assumed Market Size
            </th>
        </tr>
        <tr>
            <th colspan="4"></th>
            <?php
            $whole_count=count($wholesaler);
            for($i=0; $i<$whole_count; $i++)
            {
                ?>
                <th colspan="2">
                    <input type="text" name="wholesaler_name[]" id="wholesaler_name[]" value="<?php echo $wholesaler[$i]['wholesaler_name'];?>" <?php if(isset($wholesaler[$i]['wholesaler_name']) && !empty($wholesaler[$i]['wholesaler_name'])){echo "readonly='readonly'";}?> class="span12" placeholder="Distributor <?php echo $i+1;?>" />
                    <input type="hidden" name="market_survey_id[]" id="market_survey_id[]" value="<?php echo $wholesaler[$i]['market_survey_id'];?>" class="span12" />
                </th>
            <?php
            }
            ?>
            <th>
                &nbsp;
            </th>
        </tr>
        <?php
        $sql = "SELECT
                    $tbl"."pdo_product_characteristic_setting_zone.pcsz_id,
                    $tbl"."pdo_product_characteristic_setting_zone.prodcut_characteristic_id,
                    $tbl"."pdo_product_characteristic_setting_zone.special_characteristics,
                    $tbl"."pdo_product_characteristic_setting_zone.image_url,
                    $tbl"."pdo_product_characteristic_setting_zone.variety_id,
                    $tbl"."pdo_product_characteristic_setting_zone.`status`,
                    $tbl"."pdo_product_characteristic_setting_zone.del_status,
                    $tbl"."pdo_product_characteristic_setting_zone.entry_by,
                    $tbl"."pdo_product_characteristic_setting_zone.entry_date,
                    $tbl"."pdo_product_characteristic_setting_zone.crop_id,
                    $tbl"."pdo_product_characteristic_setting_zone.product_type_id,
                    $tbl"."pdo_product_characteristic_setting_zone.variety_id,
                    $tbl"."crop_info.crop_name,
                    $tbl"."product_type.product_type,
                    $tbl"."varriety_info.varriety_name,
                    $tbl"."varriety_info.hybrid,
                    $tbl"."varriety_info.type
                FROM
                    $tbl"."pdo_product_characteristic_setting_zone
                    LEFT JOIN $tbl"."crop_info ON $tbl"."crop_info.crop_id = $tbl"."pdo_product_characteristic_setting_zone.crop_id
                    LEFT JOIN $tbl"."product_type ON $tbl"."product_type.crop_id = $tbl"."pdo_product_characteristic_setting_zone.crop_id AND $tbl"."product_type.product_type_id = $tbl"."pdo_product_characteristic_setting_zone.product_type_id
                    LEFT JOIN $tbl"."varriety_info ON $tbl"."varriety_info.crop_id = $tbl"."pdo_product_characteristic_setting_zone.crop_id AND $tbl"."varriety_info.product_type_id = $tbl"."pdo_product_characteristic_setting_zone.product_type_id AND $tbl"."varriety_info.varriety_id = $tbl"."pdo_product_characteristic_setting_zone.variety_id
                WHERE
                    $tbl" . "pdo_product_characteristic_setting_zone.del_status=0
                    AND $tbl"."varriety_info.type=0
                    AND $tbl" . "pdo_product_characteristic_setting_zone.zone_id='$zone_id'
                    AND $tbl" . "pdo_product_characteristic_setting_zone.crop_id='".$crop_id."'
                    AND $tbl" . "pdo_product_characteristic_setting_zone.product_type_id='".$product_type_id."'
                ORDER BY
                    $tbl"."pdo_product_characteristic_setting_zone.crop_id,
                    $tbl"."pdo_product_characteristic_setting_zone.product_type_id,
                    $tbl"."pdo_product_characteristic_setting_zone.variety_id
                ";
        if($db->open())
        {
            $result=$db->query($sql);
            while($row=$db->fetchAssoc($result))
            {
                ?>
                <tr>
                    <td>
                        <?php echo $row['crop_name'];?>
                        <input type="hidden" name="crop_id[]" id="" value="<?php echo $row['crop_id'];?>" />
                        <input type="hidden" name="self_variety_id[]" id="" value="" />
                    </td>
                    <td>
                        <?php echo $row['product_type'];?>
                        <input type="hidden" name="product_type_id[]" id="" value="<?php echo $row['product_type_id'];?>" />
                    </td>
                    <td>
                        <?php echo $row['hybrid'];?>
                        <input type="hidden" name="hybrid[]" id="" value="<?php echo $row['hybrid'];?>" />
                    </td>
                    <td>
                        <?php echo $row['varriety_name'];?>
                        <input type="hidden" name="varriety_id[]" id="" value="<?php echo $row['variety_id'];?>" />
                    </td>
                    <?php
                    $sale_quantity=0;
                    $market_size=0;
                    $sale_quantity_other=0;
                    $whole_count=count($wholesaler);
                    for($i=0; $i<$whole_count; $i++)
                    {
                        if(isset($wholesaler_details[$wholesaler[$i]['market_survey_id']][$row['crop_id']][$row['product_type_id']][$row['hybrid']][$row['variety_id']]['sales_quantity']))
                        {
                            $sale_quantity=$wholesaler_details[$wholesaler[$i]['market_survey_id']][$row['crop_id']][$row['product_type_id']][$row['hybrid']][$row['variety_id']]['sales_quantity'];
                        }
                        if(isset($wholesaler_details[$wholesaler[$i]['market_survey_id']][$row['crop_id']][$row['product_type_id']][$row['hybrid']][$row['variety_id']]['market_size']))
                        {
                            $market_size=$wholesaler_details[$wholesaler[$i]['market_survey_id']][$row['crop_id']][$row['product_type_id']][$row['hybrid']][$row['variety_id']]['market_size'];
                        }
                        if(isset($wholesaler_details[$row_id][$row['crop_id']][$row['product_type_id']][$row['hybrid']][$row['variety_id']]['sales_quantity_other']))
                        {
                            $sale_quantity_other=$wholesaler_details[$row_id][$row['crop_id']][$row['product_type_id']][$row['hybrid']][$row['variety_id']]['sales_quantity_other'];
                        }
                        ?>
                        <td>
                            <input type="text" name="sales_quantity[<?php echo $i;?>][]" value="<?php if(isset($sale_quantity) && !empty($sale_quantity)){echo $sale_quantity;};?>" <?php if(isset($sale_quantity) && !empty($sale_quantity)){echo "readonly='readonly'";}?> class="span12" onkeypress="return numbersOnly(event)" />
                        </td>
                        <td>
                            <input type="text" name="market_size[<?php echo $i;?>][]" value="<?php if(isset($market_size) && !empty($market_size)){echo $market_size;}?>" <?php if(isset($market_size) && !empty($market_size)){echo "readonly='readonly'";}?> class="span12" onkeypress="return numbersOnly(event)" />
                        </td>
                    <?php
                    }
                    ?>
                    <td>
                        <input type="text" name="sales_quantity_other[]" value="<?php if(isset($sale_quantity_other) && !empty($sale_quantity_other)){echo $sale_quantity_other;};?>" <?php if(isset($sale_quantity_other) && !empty($sale_quantity_other)){echo "readonly='readonly'";}?> class="span12" onkeypress="return numbersOnly(event)" />
                    </td>
                </tr>
            <?php
            }
        }
        ?>
        <!--check variety-->
        <tr>
            <th colspan="21">
                Competitor Variety
            </th>
        </tr>
        <?php
        $sql = "SELECT
                    $tbl"."pdo_product_characteristic_setting_zone.pcsz_id,
                    $tbl"."pdo_product_characteristic_setting_zone.prodcut_characteristic_id as variety_id,
                    $tbl"."pdo_product_characteristic_setting_zone.special_characteristics,
                    $tbl"."pdo_product_characteristic_setting_zone.image_url,
                    $tbl"."pdo_product_characteristic_setting_zone.variety_id,
                    $tbl"."pdo_product_characteristic_setting_zone.`status`,
                    $tbl"."pdo_product_characteristic_setting_zone.del_status,
                    $tbl"."pdo_product_characteristic_setting_zone.entry_by,
                    $tbl"."pdo_product_characteristic_setting_zone.entry_date,
                    $tbl"."pdo_product_characteristic_setting_zone.crop_id,
                    $tbl"."pdo_product_characteristic_setting_zone.product_type_id,
                    $tbl"."crop_info.crop_name,
                    $tbl"."product_type.product_type,
                    $tbl"."varriety_info.varriety_name,
                    $tbl"."varriety_info.hybrid,
                    $tbl"."varriety_info.type
                FROM
                    $tbl"."pdo_product_characteristic_setting_zone
                    LEFT JOIN $tbl"."crop_info ON $tbl"."crop_info.crop_id = $tbl"."pdo_product_characteristic_setting_zone.crop_id
                    LEFT JOIN $tbl"."product_type ON $tbl"."product_type.crop_id = $tbl"."pdo_product_characteristic_setting_zone.crop_id AND $tbl"."product_type.product_type_id = $tbl"."pdo_product_characteristic_setting_zone.product_type_id
                    LEFT JOIN $tbl"."varriety_info ON $tbl"."varriety_info.crop_id = $tbl"."pdo_product_characteristic_setting_zone.crop_id AND $tbl"."varriety_info.product_type_id = $tbl"."pdo_product_characteristic_setting_zone.product_type_id AND $tbl"."varriety_info.varriety_id = $tbl"."pdo_product_characteristic_setting_zone.variety_id
                WHERE
                    $tbl" . "pdo_product_characteristic_setting_zone.del_status=0
                    AND $tbl"."varriety_info.type=1
                    AND $tbl" . "pdo_product_characteristic_setting_zone.zone_id='$zone_id'
                    AND $tbl" . "pdo_product_characteristic_setting_zone.crop_id='".$crop_id."'
                    AND $tbl" . "pdo_product_characteristic_setting_zone.product_type_id='".$product_type_id."'
                ORDER BY
                    $tbl"."pdo_product_characteristic_setting_zone.crop_id,
                    $tbl"."pdo_product_characteristic_setting_zone.product_type_id,
                    $tbl"."pdo_product_characteristic_setting_zone.variety_id
                ";
        if($db->open())
        {
            $result=$db->query($sql);
            while($row=$db->fetchAssoc($result))
            {
                ?>
                <tr>
                    <td>
                        <?php echo $row['crop_name'];?>
                        <input type="hidden" name="crop_id[]" id="" value="<?php echo $row['crop_id'];?>" />
                        <input type="hidden" name="self_variety_id[]" id="" value="" />
                    </td>
                    <td>
                        <?php echo $row['product_type'];?>
                        <input type="hidden" name="product_type_id[]" id="" value="<?php echo $row['product_type_id'];?>" />
                    </td>
                    <td>
                        <?php echo $row['hybrid'];?>
                        <input type="hidden" name="hybrid[]" id="" value="<?php echo $row['hybrid'];?>" />
                    </td>
                    <td>
                        <?php echo $row['varriety_name'];?>
                        <input type="hidden" name="varriety_id[]" id="" value="<?php echo $row['variety_id'];?>" />
                    </td>
                    <?php
                    $sale_quantity=0;
                    $market_size=0;
                    $sale_quantity_other=0;
                    $whole_count=count($wholesaler);
                    for($i=0; $i<$whole_count; $i++)
                    {
                        if(isset($wholesaler_details_chk[$wholesaler[$i]['market_survey_id']][$row['crop_id']][$row['product_type_id']][$row['hybrid']][$row['variety_id']]['sales_quantity']))
                        {
                            $sale_quantity=$wholesaler_details_chk[$wholesaler[$i]['market_survey_id']][$row['crop_id']][$row['product_type_id']][$row['hybrid']][$row['variety_id']]['sales_quantity'];
                        }
                        if(isset($wholesaler_details_chk[$wholesaler[$i]['market_survey_id']][$row['crop_id']][$row['product_type_id']][$row['hybrid']][$row['variety_id']]['market_size']))
                        {
                            $market_size=$wholesaler_details_chk[$wholesaler[$i]['market_survey_id']][$row['crop_id']][$row['product_type_id']][$row['hybrid']][$row['variety_id']]['market_size'];
                        }
                        if(isset($wholesaler_details_chk[$row_id][$row['crop_id']][$row['product_type_id']][$row['hybrid']][$row['variety_id']]['sales_quantity_other']))
                        {
                            $sale_quantity_other=$wholesaler_details_chk[$row_id][$row['crop_id']][$row['product_type_id']][$row['hybrid']][$row['variety_id']]['sales_quantity_other'];
                        }
                        ?>
                        <td>
                            <input type="text" name="sales_quantity[<?php echo $i;?>][]" value="<?php if(isset($sale_quantity) && !empty($sale_quantity)){echo $sale_quantity;};?>" <?php if(isset($sale_quantity) && !empty($sale_quantity)){echo "readonly='readonly'";}?> class="span12" onkeypress="return numbersOnly(event)" />
                        </td>
                        <td>
                            <input type="text" name="market_size[<?php echo $i;?>][]" value="<?php if(isset($market_size) && !empty($market_size)){echo $market_size;}?>" <?php if(isset($market_size) && !empty($market_size)){echo "readonly='readonly'";}?> class="span12" onkeypress="return numbersOnly(event)" />
                        </td>
                    <?php
                    }
                    ?>
                    <td>
                        <input type="text" name="sales_quantity_other[]" value="<?php if(isset($sale_quantity_other) && !empty($sale_quantity_other)){echo $sale_quantity_other;};?>" <?php if(isset($sale_quantity_other) && !empty($sale_quantity_other)){echo "readonly='readonly'";}?> class="span12" onkeypress="return numbersOnly(event)" />
                    </td>
                </tr>
            <?php
            }
        }
        ?>
        </thead>
    </table>
</div>