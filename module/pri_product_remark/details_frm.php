<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$dbrow = new Database();
$tbl = _DB_PREFIX;
$editrow = $dbrow->single_data($tbl . "pdo_variety_setting", "*", "vs_id", $_POST['rowID']);

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
                    <br />
                    <div id="show_data">

                    </div>
                    <div id="shadow" onclick="close_image()"></div>

                    <?php

                     $sqlcv = "SELECT
                                    $tbl" . "farmer_info.farmer_name,
                                    $tbl" . "crop_info.crop_name,
                                    $tbl" . "product_type.product_type,
                                    $tbl" . "varriety_info.varriety_name,
                                    $tbl" . "pdo_variety_setting.vs_id,
                                    $tbl" . "pdo_variety_setting.number_of_img,
                                    $tbl" . "pdo_variety_setting.sowing_date,
                                    $tbl" . "pdo_variety_setting.transplanting_date,
                                    ait_zilla.zillanameeng,
                                    ait_upazilla.upazilanameeng
                                FROM $tbl" . "pdo_variety_setting
                                     LEFT JOIN $tbl" . "farmer_info ON $tbl" . "farmer_info.farmer_id = $tbl" . "pdo_variety_setting.farmer_id
                                     LEFT JOIN $tbl" . "crop_info ON $tbl" . "crop_info.crop_id = $tbl" . "pdo_variety_setting.crop_id
                                     LEFT JOIN $tbl" . "product_type ON $tbl" . "product_type.crop_id = $tbl" . "pdo_variety_setting.crop_id AND $tbl" . "product_type.product_type_id = $tbl" . "pdo_variety_setting.product_type_id
                                     LEFT JOIN $tbl" . "varriety_info ON $tbl" . "varriety_info.varriety_id = $tbl" . "pdo_variety_setting.variety_id
                                     LEFT JOIN ait_zilla ON ait_zilla.zillaid = ait_farmer_info.district_id
                                     LEFT JOIN ait_upazilla ON ait_upazilla.upazilaid = ait_farmer_info.upazilla_id AND ait_upazilla.zillaid = ait_farmer_info.district_id
                                WHERE
                                    $tbl" . "pdo_variety_setting.farmer_id='" . $editrow['farmer_id'] . "' AND
                                    $tbl" . "pdo_variety_setting.crop_id='" . $editrow['crop_id'] . "' AND
                                    $tbl" . "pdo_variety_setting.product_type_id='" . $editrow['product_type_id'] . "'

                                ";
                    $dbm = new Database();
                    if($dbm->open())
                    {
                        $resultm = $dbm->query($sqlcv);
                        while ($rowm = $dbm->fetchAssoc($resultm))
                        {
                            ?>
                            <div class="control-group">
                                <div id="div_number_of_img" style="width: auto; overflow: auto;margin-top: -20px;">

                                    <table class="" width="" border="" style="float: left;">
<!--                                        <tr>-->
<!--                                            <th colspan="3" style="background-color: #8D2DBF; color: #fff;">-->
<!--                                                -->
<!--                                            </th>-->
<!--                                        </tr>-->
                                        <tr>
                                            <td valign="top">
                                                <table class="" width="" border="" style="float: left;">
                                                    <thead>
                                                    <tr>
                                                        <th colspan="21" style="background-color: #467FBF; color: #fff; ">Upload Product Photo</th>
                                                    </tr>

                                                    <tr>
                                                        <td style="font-size: 12px">
                                                            <?php echo $rowm['varriety_name'];?>,
                                                            <?php echo $rowm['zillanameeng'];?>,
                                                            <?php echo $rowm['upazilanameeng'];?>
                                                        </td>
                                                        <?php
                                                        $sl=1;
                                                        $sql="select * from $tbl"."pdo_variety_setting_img_date where vs_id='".$rowm['vs_id']."'";
                                                        if($db->open())
                                                        {
                                                            $result=$db->query($sql);
                                                            while($row=$db->fetchArray($result))
                                                            {
                                                                $crop_row_id=$row['id'];
                                                                if($row['crop_img_url']=="")
                                                                {
                                                                    $crop_blank_img= "<img src='../../system_images/blank_img.png' width='100' style='border: 1px solid rgba(0, 0, 0, 0.33); padding: 2px '/>";
                                                                }
                                                                else
                                                                {
                                                                    $crop_blank_img="<img src='../../system_images/pdo_upload_image/crop_img_url/$row[crop_img_url]' title='$row[crop_remark]' width='100' style='cursor: pointer; border: 1px solid rgba(0, 0, 0, 0.33); padding: 2px; cursor: pointer;' onclick='product_image_info($crop_row_id)'/>";
                                                                }
                                                                ?>

                                                                <th>
                                                                    <?php echo $crop_blank_img;?>
                                                                    <br />
                                                                    <input readonly="" type="text" class="span10" value="<?php echo $db->date_formate($row['upload_date'])?>"/>
                                                                </th>

                                                                <?php
                                                                ++$sl;
                                                            }
                                                        }
                                                        ?>
                                                    </tr>
                                                    </thead>
                                                </table>
                                            </td>
                                            <td valign="top">
                                                <table class="" width="" border="">
                                                    <thead>
                                                    <tr>
                                                        <th colspan="21" style="background-color: #BF6C48; color: #fff;">Upload Fruit Photo</th>
                                                    </tr>

                                                    <tr>

                                                        <?php
                                                        $sl=1;
                                                        $sql="select * from $tbl"."pdo_variety_setting_fruit_img where vs_id='".$rowm['vs_id']."'";
                                                        if($db->open())
                                                        {
                                                            $result=$db->query($sql);
                                                            $fnumrow=$db->numRows($result);
                                                            if($fnumrow > 1)
                                                            {
                                                                while($row=$db->fetchArray($result))
                                                                {
                                                                    $fruit_row_id=$row['id'];

                                                                    ?>

                                                                    <th>
                                                                        <?php
                                                                        if($row['fruit_img_url']=="")
                                                                        {
                                                                            ?>
                                                                            <img src='../../system_images/blank_img.png' width='100' style='border: 1px solid rgba(0, 0, 0, 0.33); padding: 2px'/>
                                                                        <?php
                                                                        }
                                                                        else
                                                                        {
                                                                            ?>
                                                                            <img src="../../system_images/pdo_upload_image/fruit_img_url/<?php echo $row['fruit_img_url']?>" title="<?php echo $row['fruit_remark']?>" width='100' style='border: 1px solid rgba(0, 0, 0, 0.33); padding: 2px; cursor: pointer;'  onclick="product_fruit_image_info('<?php echo $fruit_row_id?>')"/>
                                                                        <?php
                                                                        }
                                                                        ?>
                                                                        <br />
                                                                        <input disabled type="text" id="fruit_caption[]" name="fruit_caption[]" class="span10" value="<?php echo $row['fruit_caption']?>" />
                                                                    </th>

                                                                    <?php
                                                                    ++$sl;
                                                                }

                                                            }
                                                            else
                                                            {
                                                                //echo "nai";
                                                            }

                                                        }
                                                        ?>
                                                    </tr>
                                                    </thead>
                                                </table>
                                            </td>
                                            <td valign="top">
                                                <table class="" width="" border="">
                                                    <thead>
                                                    <tr>
                                                        <th colspan="21" style="background-color: #990033; color: #fff;">Disease Photo</th>
                                                    </tr>

                                                    <?php
                                                    $sl=1;
                                                    //                                    $j=0;
                                                    $disease_blank_img='';
                                                    $txtstr='';
                                                    $sql="select * from $tbl"."pdo_variety_setting_disease_img where vs_id='".$rowm['vs_id']."'";
                                                    if($db->open())
                                                    {
                                                        $result = $db->query($sql);
                                                        while($row=$db->fetchArray($result))
                                                        {
                                                            $disease_id=$row['disease_id'];

                                                            ?>
                                                            <th>
                                                                <?php
                                                                if($row['disease_img_url']=="")
                                                                {
                                                                    ?>
                                                                    <img src='../../system_images/blank_img.png' width='100' style='border: 1px solid rgba(0, 0, 0, 0.33); padding: 2px'/>
                                                                <?php
                                                                }
                                                                else
                                                                {
                                                                    ?>
                                                                    <img src="../../system_images/pdo_upload_image/disease_img_url/<?php echo $row['disease_img_url']?>" title="<?php echo $row['disease_remark']?>" width='100' style='border: 1px solid rgba(0, 0, 0, 0.33); padding: 2px; cursor: pointer;'  onclick="product_disease_image_info('<?php echo $disease_id?>')"/>
                                                                <?php
                                                                }
                                                                ?>
                                                                <input disabled type="text" class="span10" value="<?php echo $row['upload_date'];?>" />
                                                            </th>

                                                            <?php
                                                            ++$sl;
                                                        }
                                                    }
                                                    ?>
                                                    </thead>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <?php
                        }
                    }
                    $dbm->close();
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
