<?php
session_start();
ob_start();
if ($_SESSION['logged'] != 'yes') {
    $_REQUEST["msg"] = "TimeoutC";
    header("location:../../index.php");
}
//echo $_SESSION['shop_name_eng']; 
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$tbl = _DB_PREFIX;
$db = new Database();
$zone_id=$_POST['zone_id'];
$crop_id=$_POST['crop_master_id'];
$product_type_id=$_POST['product_master_type_id'];
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
            for($i=0; $i<3; $i++)
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
            for($i=0; $i<3; $i++)
            {
                ?>
                <th colspan="2">
                    <input type="text" name="wholesaler_name[]" id="wholesaler_name[]" value="" class="span12" placeholder="Distributor <?php echo $i+1;?>" />
                    <input type="hidden" name="market_survey_id[]" id="market_survey_id[]" value="" class="span12" />
                </th>
            <?php
            }
            ?>
            <th >
                &nbsp;
            </th>
        </tr>
        <?php
        $sql = "SELECT
                    $tbl"."pdo_product_characteristic_setting_zone.pcsz_id,
                    $tbl"."pdo_product_characteristic_setting_zone.prodcut_characteristic_id,
                    $tbl"."pdo_product_characteristic_setting_zone.special_characteristics,
                    $tbl"."pdo_product_characteristic_setting_zone.product_category,
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
                    $tbl"."varriety_info.hybrid
                FROM
                    $tbl"."pdo_product_characteristic_setting_zone
                    LEFT JOIN $tbl"."crop_info ON $tbl"."crop_info.crop_id = $tbl"."pdo_product_characteristic_setting_zone.crop_id
                    LEFT JOIN $tbl"."product_type ON $tbl"."product_type.crop_id = $tbl"."pdo_product_characteristic_setting_zone.crop_id AND $tbl"."product_type.product_type_id = $tbl"."pdo_product_characteristic_setting_zone.product_type_id
                    LEFT JOIN $tbl"."varriety_info ON $tbl"."varriety_info.crop_id = $tbl"."pdo_product_characteristic_setting_zone.crop_id AND $tbl"."varriety_info.product_type_id = $tbl"."pdo_product_characteristic_setting_zone.product_type_id AND $tbl"."varriety_info.varriety_id = $tbl"."pdo_product_characteristic_setting_zone.variety_id
                WHERE
                    $tbl" . "pdo_product_characteristic_setting_zone.del_status=0
                    AND $tbl"."varriety_info.type=0
                    AND $tbl" . "pdo_product_characteristic_setting_zone.zone_id='$zone_id'
                    AND $tbl" . "pdo_product_characteristic_setting_zone.crop_id='$crop_id'
                    AND $tbl" . "pdo_product_characteristic_setting_zone.product_type_id='$product_type_id'
                ORDER BY
                    $tbl"."pdo_product_characteristic_setting_zone.crop_id,
                    $tbl"."pdo_product_characteristic_setting_zone.product_type_id,
                    $tbl"."pdo_product_characteristic_setting_zone.hybrid,
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
                        <input type="hidden" name="type[]" id="" value="<?php echo $row['product_category'];?>" />
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
                    for($i=0; $i<3; $i++)
                    {
                        ?>
                        <td>
                            <input type="text" name="sales_quantity[<?php echo $i;?>][]" value="" class="span12" onkeypress="return numbersOnly(event)" />
                        </td>
                        <td>
                            <input type="text" name="market_size[<?php echo $i;?>][]" value="" class="span12" onkeypress="return numbersOnly(event)" />
                        </td>
                    <?php
                    }
                    ?>
                    <td>
                        <input type="text" name="sales_quantity_other[]" value="" class="span12" onkeypress="return numbersOnly(event)" />
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
                    $tbl"."pdo_product_characteristic_setting_zone.product_category,
                    $tbl"."pdo_product_characteristic_setting_zone.image_url,
                    $tbl"."pdo_product_characteristic_setting_zone.variety_id,
                    $tbl"."pdo_product_characteristic_setting_zone.hybrid,
                    $tbl"."pdo_product_characteristic_setting_zone.`status`,
                    $tbl"."pdo_product_characteristic_setting_zone.del_status,
                    $tbl"."pdo_product_characteristic_setting_zone.entry_by,
                    $tbl"."pdo_product_characteristic_setting_zone.entry_date,
                    $tbl"."pdo_product_characteristic_setting_zone.crop_id,
                    $tbl"."pdo_product_characteristic_setting_zone.product_type_id,
                    $tbl"."crop_info.crop_name,
                    $tbl"."product_type.product_type,
                    $tbl"."varriety_info.varriety_name,
                    $tbl"."varriety_info.hybrid
                FROM
                    $tbl"."pdo_product_characteristic_setting_zone
                    LEFT JOIN $tbl"."crop_info ON $tbl"."crop_info.crop_id = $tbl"."pdo_product_characteristic_setting_zone.crop_id
                    LEFT JOIN $tbl"."product_type ON $tbl"."product_type.crop_id = $tbl"."pdo_product_characteristic_setting_zone.crop_id AND $tbl"."product_type.product_type_id = $tbl"."pdo_product_characteristic_setting_zone.product_type_id
                    LEFT JOIN $tbl"."varriety_info ON $tbl"."varriety_info.varriety_id = $tbl"."pdo_product_characteristic_setting_zone.variety_id
                WHERE
                    $tbl" . "pdo_product_characteristic_setting_zone.del_status=0
                    AND $tbl"."varriety_info.type=1
                    AND $tbl" . "pdo_product_characteristic_setting_zone.zone_id='$zone_id'
                    AND $tbl" . "pdo_product_characteristic_setting_zone.crop_id='$crop_id'
                    AND $tbl" . "pdo_product_characteristic_setting_zone.product_type_id='$product_type_id'
                ORDER BY
                    $tbl"."pdo_product_characteristic_setting_zone.crop_id,
                    $tbl"."pdo_product_characteristic_setting_zone.product_type_id,
                    $tbl"."pdo_product_characteristic_setting_zone.hybrid,
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
                        <input type="hidden" name="type[]" id="" value="<?php echo $row['product_category'];?>" />
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
                    for($i=0; $i<3; $i++)
                    {
                        ?>
                        <td>
                            <input type="text" name="sales_quantity[<?php echo $i;?>][]" value="" class="span12" onkeypress="return numbersOnly(event)" />
                        </td>
                        <td>
                            <input type="text" name="market_size[<?php echo $i;?>][]" value="" class="span12" onkeypress="return numbersOnly(event)" />
                        </td>
                    <?php
                    }
                    ?>
                    <td>
                        <input type="text" name="sales_quantity_other[]" value="" class="span12" onkeypress="return numbersOnly(event)" />
                    </td>
                </tr>
            <?php
            }
        }
        ?>
        </thead>
    </table>
</div>