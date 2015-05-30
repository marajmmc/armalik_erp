<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$tbl = _DB_PREFIX;
$row_id=$_POST['rowID'];
$editrow = $db->single_data($tbl . "primary_market_survey", "*", "market_survey_group_id", $row_id);
$zone_id=$editrow['zone_id'];
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
    $wholesaler=array();
    while($rowws=$dbws->fetchAssoc($resultws))
    {
        $wholesaler[]=array('wholesaler_name'=>$rowws['wholesaler_name'], 'market_survey_id'=>$rowws['market_survey_id']);
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
                    AND $tbl"."primary_market_survey_details.status='Active'
                    AND $tbl"."varriety_info.type=0
                ";
$dbws_details=new Database();
$wholesaler_details=array();
if($dbws_details->open())
{
    $resultws_details=$dbws_details->query($sqlws_details);
    while($rowws_details = $dbws_details->fetchArray($resultws_details))
    {
        $wholesaler_details[$rowws_details['market_survey_id']][$rowws_details['crop_id']][$rowws_details['product_type_id']][$rowws_details['hybrid']][$rowws_details['varriety_id']]['sales_quantity']=$rowws_details['sales_quantity'];
        $wholesaler_details[$rowws_details['market_survey_id']][$rowws_details['crop_id']][$rowws_details['product_type_id']][$rowws_details['hybrid']][$rowws_details['varriety_id']]['market_size']=$rowws_details['market_size'];
        $wholesaler_details[$row_id][$rowws_details['crop_id']][$rowws_details['product_type_id']][$rowws_details['hybrid']][$rowws_details['varriety_id']]['sales_quantity_other']=$rowws_details['sales_quantity_other'];
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
//echo "<pre>";
//print_r($wholesaler_details_chk);
//echo "</pre>";
?>
<div class="row-fluid">
    <div class="span12">
        <div class="widget span">
            <div class="widget-header">
                <div class="title">
                    <a id="dynamicTable"><?php echo $db->Get_Auto_TaskName() ?></a>
                    <span class="mini-title">

                    </span>
                </div>
                <span class="tools">
                    <a class="btn btn-small" data-original-title="">
                        <i class="icon-plus-sign" data-original-title="Share"> </i>
                    </a>
                </span>
            </div>
            <div class="form-horizontal no-margin">
                <div class="widget-body">
                    <div class="control-group">
                        <label class="control-label" for="zone_id">
                            Zone
                        </label>
                        <div class="controls">
                            <select id="zone_id" name="zone_id" class="span5" placeholder="Zone" validate="Require" onchange="load_district()" >
<!--                                <option value="">Select</option>-->
                                <?php
                                $sql_uesr_group = "select zone_id as fieldkey, zone_name as fieldtext from $tbl" . "zone_info WHERE status='Active' AND del_status='0' ".$db->get_zone_access($tbl. "zone_info")." AND zone_id='".$editrow['zone_id']."'";
                                echo $db->SelectList($sql_uesr_group, $editrow['zone_id']);
                                ?>
                            </select>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="territory_id">
                            Territory
                        </label>
                        <div class="controls">
                            <select id="territory_id" name="territory_id" class="span5" placeholder="Territory" validate="Require">
                                <?php
                                $sql_uesr_group = "select territory_id as fieldkey, territory_name as fieldtext from $tbl" . "territory_info where status='Active' AND del_status='0'  AND territory_id='".$editrow['territory_id']."'  AND zone_id='".$editrow['zone_id']."' ORDER BY territory_name";
                                echo $db->SelectList($sql_uesr_group);
                                ?>
                            </select>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="distributor_id">
                            District
                        </label>
                        <div class="controls">
                            <select id="district_id" name="district_id" class="span5" placeholder="" validate="Require" onchange="load_upazilla_fnc()">
                                <?php
                                $sql_district_group = "SELECT
                                                        $tbl" . "zilla.zillaid as fieldkey,
                                                        $tbl" . "zilla.zillanameeng as fieldtext
                                                    FROM
                                                        $tbl" . "zone_assign_district
                                                        LEFT JOIN $tbl" . "zilla ON $tbl" . "zilla.zillaid = $tbl" . "zone_assign_district.zilla_id
                                                    WHERE
                                                        $tbl" . "zone_assign_district.del_status=0
                                                        AND $tbl" . "zilla.visible=0
                                                        AND $tbl" . "zone_assign_district.status='Active'
                                                        AND $tbl" . "zone_assign_district.zone_id='".$editrow['zone_id']."'
                                                        AND $tbl" . "zone_assign_district.zilla_id='".$editrow['district_id']."'
                                                    ";
                                echo $db->SelectList($sql_district_group, $editrow['district_id']);
                                ?>
                            </select>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="distributor_id">
                            Upazilla
                        </label>
                        <div class="controls">
                            <select id="upazilla_id" name="upazilla_id" class="span5" placeholder="" validate="Require">
                                <?php
                                $sql_uesr_group = "select upazilla_id as fieldkey, upazilla_name as fieldtext from $tbl" . "upazilla_new where upazilla_id='".$editrow['upazilla_id']."' AND zilla_id='".$editrow['district_id']."' ORDER BY upazilla_name";
                                echo $db->SelectList($sql_uesr_group, $editrow['upazilla_id']);
                                ?>
                            </select>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="farmer_id">
                            Year
                        </label>
                        <div class="controls">
                            <select id="pdo_year_id" name="pdo_year_id" class="span5" placeholder="" validate="Require" onchange="check_exist_data()">
                                <?php
                                //echo "<option value=''>Select</option>";
                                $sql_uesr_group = "select pdo_year_id as fieldkey, pdo_year_name as fieldtext from $tbl" . "pdo_year where status='Active' AND pdo_year_id='".$editrow['pdo_year_id']."' ORDER BY $tbl"."pdo_year.pdo_year_name";
                                echo $db->SelectList($sql_uesr_group, $editrow['pdo_year_id']);
                                ?>
                            </select>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="crop_id">
                            Crop
                        </label>
                        <div class="controls">
                            <select id="crop_master_id" name="crop_master_id" class="span5" placeholder="Select Crop" validate="Require" onchange="load_product_type()">
                                <?php
                                $sql_uesr_group = "select crop_id as fieldkey, crop_name as fieldtext from $tbl" . "crop_info where status='Active' AND del_status='0' AND crop_id='".$editrow['crop_id']."' order by order_crop";
                                echo $db->SelectList($sql_uesr_group, $editrow['crop_id']);
                                ?>
                            </select>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="product_type_id">
                            Product Type
                        </label>
                        <div class="controls">
                            <select id="product_master_type_id" name="product_master_type_id" class="span5" placeholder="Product Type" onchange="check_exist_data()" validate="Require">
                                <?php
                                $sql_uesr_group = "select product_type_id as fieldkey, product_type as fieldtext from $tbl" . "product_type where status='Active' AND del_status='0' AND crop_id='".$editrow['crop_id']."' AND product_type_id='".$editrow['product_type_id']."' ORDER BY order_type";
                                echo $db->SelectList($sql_uesr_group, $editrow['product_type_id']);
                                ?>
                            </select>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                </div>
                <div class="widget-body">
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
                            <!--  check variety -->
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
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        session_load_fnc()
    });


</script>