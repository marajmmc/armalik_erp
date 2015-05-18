<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$tbl = _DB_PREFIX;
$self_variety_id= str_replace('[','',str_replace(']','',json_encode(@$_POST['self_variety_id'])));
$chk_variety_id= str_replace('[','',str_replace(']','',json_encode(@$_POST['chk_variety_id'])));
$wcondition="AND (ait_pdo_variety_setting.variety_id IN ($self_variety_id) OR
        ait_pdo_variety_setting.variety_id IN ($chk_variety_id))";

if ($_POST['pdo_year_id'] != "")
{
    $pdo_year_id = "AND $tbl" . "pdo_variety_setting.pdo_year_id='" . $_POST['pdo_year_id'] . "'";
}
else
{
    $pdo_year_id = "";
}

if ($_POST['pdo_season_id'] != "")
{
    $pdo_season_id = "AND $tbl" . "pdo_variety_setting.pdo_season_id='" . $_POST['pdo_season_id'] . "'";
}
else
{
    $pdo_season_id = "";
}

if ($_POST['crop_id'] != "")
{
    $crop_id = "AND $tbl" . "pdo_variety_setting.crop_id='" . $_POST['crop_id'] . "'";
}
else
{
    $crop_id = "";
}

if ($_POST['product_type_id'] != "")
{
    $product_type_id = "AND $tbl" . "pdo_variety_setting.product_type_id='" . $_POST['product_type_id'] . "'";
}
else
{
    $product_type_id = "";
}

if ($_POST['division_id'] != "")
{
    $division_id = "AND $tbl" . "farmer_info.division_id='" . $_POST['division_id'] . "'";
}
else
{
    $division_id = "";
}

if ($_POST['zone_id'] != "")
{
    $zone_id = "AND $tbl" . "farmer_info.zone_id='" . $_POST['zone_id'] . "'";
}
else
{
    $zone_id = "";
}

if ($_POST['territory_id'] != "")
{
    $territory_id = "AND $tbl" . "farmer_info.territory_id='" . $_POST['territory_id'] . "'";
}
else
{
    $territory_id = "";
}
if ($_POST['district_id'] != "")
{
    $district_id = "AND $tbl" . "farmer_info.district_id='" . $_POST['district_id'] . "'";
}
else
{
    $district_id = "";
}
if ($_POST['upazilla_id'] != "")
{
    $upazilla_id = "AND $tbl" . "farmer_info.upazilla_id='" . $_POST['upazilla_id'] . "'";
}
else
{
    $upazilla_id = "";
}
?>
<a class="btn btn-small btn-success" data-original-title="" onclick="print_rpt()" style="float: right;">
    <i class="icon-print" data-original-title="Share"> </i> Print
</a>
<div id="PrintArea" style="background-color: white;" >
<?php include_once '../../libraries/print_page/Print_header.php';?>
<div class="widget-body">
<br />
<div id="show_data">

</div>
<div id="shadow" onclick="close_image()"></div>


<?php

if($_POST['report_type']=="Picture" || $_POST['report_type']=="All")
{
    $sqlcv = "  SELECT
                $tbl" . "farmer_info.farmer_name,
                $tbl" . "crop_info.crop_name,
                $tbl" . "varriety_info.varriety_name,
                $tbl" . "pdo_variety_setting.vs_id,
                $tbl" . "pdo_variety_setting.number_of_img,
                $tbl" . "pdo_variety_setting.sowing_date,
                $tbl" . "pdo_variety_setting.transplanting_date,
                (SELECT count(id) FROM ait_pdo_variety_setting_img_date WHERE ait_pdo_variety_setting_img_date.vs_id = ait_pdo_variety_setting.vs_id) as img_date,
                (SELECT count(id) FROM ait_pdo_variety_setting_fruit_img WHERE ait_pdo_variety_setting_fruit_img.vs_id = ait_pdo_variety_setting.vs_id) as fruit_img,
                (SELECT count(disease_id) FROM ait_pdo_variety_setting_disease_img WHERE ait_pdo_variety_setting_disease_img.vs_id = ait_pdo_variety_setting.vs_id) as disease_img,
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
                ait_pdo_variety_setting.del_status=0
                $wcondition
                $division_id
                $zone_id
                $district_id
                $upazilla_id
                $pdo_year_id
                $pdo_season_id
                $crop_id
                $product_type_id
            ORDER BY ait_varriety_info.type
                                ";
    $dbm = new Database();
    if($dbm->open())
    {
        $resultm = $dbm->query($sqlcv);
        while ($rowm = $dbm->fetchAssoc($resultm))
        {
            if($rowm['img_date']!=0 || $rowm['fruit_img']!=0 || $rowm['disease_img']!=0)
            {

                ?>
                <div class="control-group">

                    <div id="div_number_of_img" style="width: auto; overflow: auto; margin-top: -10px;">

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
                                            <td style="font-size: 12px" rowspan="4">
                                                <?php echo $rowm['varriety_name'];?>,<br />
                                                <?php echo $rowm['zillanameeng'];?>,
                                                <?php echo $rowm['upazilanameeng'];?>,
                                            </td>
                                        </tr>
                                        <?php
                                        if($_POST['report_product_type']=="Seven Days Picture" || $_POST['report_product_type']=="All")
                                        {
                                            ?>
                                            <tr>
                                                <th colspan="21" style="background-color: #467FBF; color: #fff; ">Upload Product Photo</th>
                                            </tr>
                                            <tr>
                                                <?php
                                                $sl=1;
                                                $sql="select * from $tbl"."pdo_variety_setting_img_date where vs_id='".$rowm['vs_id']."'";
                                                if($db->open())
                                                {
                                                    $result=$db->query($sql);
                                                    if($result)
                                                    {
                                                        while($row=$db->fetchArray($result))
                                                        {
                                                            $crop_row_id=$row['id'];
                                                            if($row['crop_img_url']=="")
                                                            {
                                                                $crop_blank_img= "<img src='../../system_images/blank_img.png' width='100' style='border: 1px solid rgba(0, 0, 0, 0.33); padding: 2px '/>";
                                                            }
                                                            else
                                                            {
                                                                $crop_blank_img="<img src='../../system_images/pdo_upload_image/crop_img_url/$row[crop_img_url]' title='$row[crop_remark]'  width='100' style='cursor: pointer; border: 1px solid rgba(0, 0, 0, 0.33); padding: 2px' onclick='product_image_info($crop_row_id)'/>";
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
                                                }
                                                ?>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                        </thead>
                                    </table>
                                </td>
                                <?php
                                if($_POST['report_product_type']=="Fruit" || $_POST['report_product_type']=="All")
                                {
                                    ?>
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
                                                                    <img src="../../system_images/pdo_upload_image/fruit_img_url/<?php echo $row['fruit_img_url']?>" title="<?php echo $row['fruit_remark']?>" width='100' style='cursor: pointer;border: 1px solid rgba(0, 0, 0, 0.33); padding: 2px'  onclick="product_fruit_image_info('<?php echo $fruit_row_id?>')"/>
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
                                <?php
                                }
                                ?>
                                <?php
                                if($_POST['report_product_type']=="Disease" || $_POST['report_product_type']=="All")
                                {
                                    ?>
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
                                                            <img src="../../system_images/pdo_upload_image/disease_img_url/<?php echo $row['disease_img_url']?>" title="<?php echo $row['disease_remark']?>" width='100' style='cursor: pointer;border: 1px solid rgba(0, 0, 0, 0.33); padding: 2px'  onclick="product_disease_image_info('<?php echo $disease_id?>')"/>
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
                                <?php
                                }
                                ?>
                            </tr>
                        </table>
                    </div>
                </div>
            <?php
            }
        }
    }
    $dbm->close();
    ?>

<?php
}
if($_POST['report_type']=="Video" || $_POST['report_type']=="All")
{
?>
    <table width="100%">
        <thead>
        <?php
        $sqlv="SELECT
                ait_pdo_variety_video_upload.id,
                ait_pdo_variety_video_upload.file_url,
                ait_crop_info.crop_name,
                ait_product_type.product_type,
                CONCAT_WS(' to ', arm_variety.varriety_name, chk_variety.varriety_name) as variety_name,
                ait_pdo_variety_video_upload.`status`
            FROM
                ait_pdo_variety_video_upload
                LEFT JOIN ait_crop_info ON ait_crop_info.crop_id = ait_pdo_variety_video_upload.crop_id
                LEFT JOIN ait_product_type ON ait_product_type.crop_id = ait_pdo_variety_video_upload.crop_id AND ait_product_type.product_type_id = ait_pdo_variety_video_upload.product_type_id
                LEFT JOIN ait_varriety_info AS arm_variety ON arm_variety.varriety_id = ait_pdo_variety_video_upload.arm_variety_id
                LEFT JOIN ait_varriety_info AS chk_variety ON chk_variety.varriety_id = ait_pdo_variety_video_upload.check_variety_id
            WHERE ait_pdo_variety_video_upload.del_status=0 AND (ait_pdo_variety_video_upload.arm_variety_id IN ($self_variety_id) OR
            ait_pdo_variety_video_upload.check_variety_id IN ($chk_variety_id))
          ";

        if($db->open())
        {
            $resultuv=$db->query($sqlv);
            while($rowuv=$db->fetchArray())
            {
                ?>

                <tr>
                    <th>Variety Name: <?php echo $rowuv['variety_name']; ?> </th>
                    <th>


                        <video width="400" controls>
                            <source src="../../system_images/pdo_upload_image/crop_img_url/<?php echo $rowuv['file_url']; ?>" type="video/mp4">
                            <source src="../../system_images/pdo_upload_image/crop_img_url/<?php echo $rowuv['file_url']; ?>" type="video/ogg">
                            Your browser does not support HTML5 video.
                        </video>

                    </th>
                    <th><a target="_blank" href="../../system_images/pdo_upload_image/crop_img_url/<?php echo $rowuv['file_url']; ?>">Download</a> </th>
                </tr>

            <?php
            }
        }
        ?>
        </thead>
    </table>
<?php
}
?>

</div>
<?php include_once '../../libraries/print_page/Print_footer.php';?>
</div>