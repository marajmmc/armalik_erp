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

$variety= str_replace('[','',str_replace(']','',json_encode(@$_POST['variety_id'])));
$varietytxt= str_replace('[','',str_replace(']','',json_encode(@$_POST['variety_name_txt'])));

$wcondition="AND (ait_pdo_product_characteristic.variety_id IN ($variety) OR
        ait_pdo_product_characteristic.variety_id IN ($varietytxt))";


if($_POST['zone_id']!="")
{
    $zone_id=" AND ait_pdo_product_characteristic.zone_id='".$_POST['zone_id']."'";
}
else
{
    $zone_id="";
}

if($_POST['district_id']!="")
{
    $district_id=" AND ait_pdo_product_characteristic.district_id='".$_POST['district_id']."'";
}
else
{
    $district_id="";
}

if($_POST['upazilla_id']!="")
{
    $upazilla_id=" AND ait_pdo_product_characteristic.upazilla_id='".$_POST['upazilla_id']."'";
}
else
{
    $upazilla_id="";
}
?>

<a class="btn btn-small btn-success" data-original-title="" onclick="print_rpt()" style="float: right;">
    <i class="icon-print" data-original-title="Share"> </i> Print
</a>
<div id="PrintArea" style="background-color: white;" >
<?php include_once '../../libraries/print_page/Print_header.php';?>
<br />
<br />
<div style="overflow: scroll; width: auto;" >
<table class="table table-condensed table-striped table-hover table-bordered pull-left report" id="data-table">
<thead>
<?php
 $sqlr="
        SELECT
        ait_pdo_product_characteristic.crop_id,
        ait_pdo_product_characteristic.product_type_id,
        ait_crop_info.crop_name,
        ait_product_type.product_type,
        sum(ait_pdo_product_characteristic.sales_quantity) AS total_market_size
        FROM ait_pdo_product_characteristic
        LEFT JOIN ait_crop_info  ON ait_crop_info.crop_id = ait_pdo_product_characteristic.crop_id
        LEFT JOIN ait_product_type ON ait_product_type.crop_id = ait_pdo_product_characteristic.crop_id AND ait_product_type.product_type_id = ait_pdo_product_characteristic.product_type_id
        WHERE ait_pdo_product_characteristic.del_status=0 AND
        ait_pdo_product_characteristic.crop_id='".$_POST['crop_id']."' AND
        ait_pdo_product_characteristic.product_type_id='".$_POST['product_type_id']."'
        $wcondition $zone_id $district_id $upazilla_id
        GROUP BY
        ait_pdo_product_characteristic.crop_id,
        ait_pdo_product_characteristic.product_type_id
        ";
if($dbr->open())
{
    $resultr=$dbr->query($sqlr);
    while($rowr=$dbr->fetchArray($resultr))
    {
        ?>

        <tr class="btn-success">
            <td colspan="21">
                <?php echo $rowr['crop_name']." - ".$rowr['product_type']?>
                <a href='#' class='btn-warning2'>Total Market Size: <?php echo $rowr['total_market_size']?> Kg,
                    <!--                Total Market: Hybrid -->
                    <?php
                    $sqlhybrid="
                                    SELECT
                                        sum(ait_pdo_product_characteristic.sales_quantity) AS sales_quantity
                                    FROM ait_pdo_product_characteristic
                                    LEFT JOIN $tbl"."varriety_info ON ait_varriety_info.varriety_id= $tbl"."pdo_product_characteristic.variety_id
                                    WHERE
                                        $tbl"."varriety_info.hybrid='F1 Hybrid' AND
                                        ait_pdo_product_characteristic.crop_id='".$rowr['crop_id']."' AND
                                        ait_pdo_product_characteristic.product_type_id='".$rowr['product_type_id']."'
                                        $wcondition $zone_id $district_id $upazilla_id
                                    GROUP BY
                                        ait_pdo_product_characteristic.crop_id,
                                        ait_pdo_product_characteristic.product_type_id";
                    if($dbhybrid->open())
                    {
                        $resulth=$dbhybrid->query($sqlhybrid);
                        $rowh=$dbhybrid->fetchAssoc($resulth);

                        echo "F1 Hybrid: ";
                        echo $rowh['sales_quantity']?$rowh['sales_quantity']:0;
                        echo " Kg, ";
                    }
                    ?>

                    <!--                Total Market: OP -->
                    <?php
                    $sqlop="
                    SELECT
                        sum(ait_pdo_product_characteristic.sales_quantity) AS sales_quantity
                    FROM ait_pdo_product_characteristic
                    LEFT JOIN $tbl"."varriety_info ON ait_varriety_info.varriety_id= $tbl"."pdo_product_characteristic.variety_id
                    WHERE
                        $tbl"."varriety_info.hybrid='OP' AND
                        ait_pdo_product_characteristic.crop_id='".$rowr['crop_id']."' AND
                        ait_pdo_product_characteristic.product_type_id='".$rowr['product_type_id']."'
                        $wcondition $zone_id $district_id $upazilla_id
                        ";
                    if($dbop->open())
                    {
                        $resultop=$dbop->query($sqlop);
                        $rowop=$dbop->fetchAssoc($resultop);
                        echo "OP: ";
                        echo $rowop['sales_quantity']?$rowop['sales_quantity']:0;
                        echo " Kg </a>";
                    }
                    ?>
            </td>
        </tr>
        <tr>
            <td>

                <table class="table table-condensed table-striped table-hover table-bordered pull-left report" id="data-table">
                    <thead>
                    <tr class="btn-info">
                        <th>Location</th>
                        <th>Cultivation Period</th>
                        <th>Name Variety</th>
                        <th>F1/ OP</th>
                        <th>Sales Quantity</th>
                        <th>Special Characteristics</th>
                        <th>Picture</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $sql = "
                                SELECT
                                    $tbl"."varriety_info.varriety_name,
                                    $tbl"."varriety_info.hybrid,
                                    $tbl"."pdo_product_characteristic.sales_quantity,
                                    $tbl"."pdo_product_characteristic_setting_zone.special_characteristics,
                                    $tbl"."pdo_product_characteristic_setting_zone.image_url,
                                    CONCAT_WS
                                    (' To ',
                                    DATE_FORMAT($tbl" . "pdo_product_characteristic_setting_zone.cultivation_period_start,'%d %M'),
                                    DATE_FORMAT($tbl" . "pdo_product_characteristic_setting_zone.cultivation_period_end,'%d %M')
                                    ) AS cultivation_period,
                                    CONCAT_WS(', ', $tbl"."division_info.division_name, $tbl"."zone_info.zone_name, $tbl"."zilla.zillanameeng, $tbl"."upazilla_new.upazilla_name) AS location
                                FROM $tbl"."pdo_product_characteristic
                                    LEFT JOIN $tbl"."pdo_product_characteristic_setting_zone ON
                                        $tbl"."pdo_product_characteristic_setting_zone.zone_id = $tbl"."pdo_product_characteristic.zone_id AND
                                        $tbl"."pdo_product_characteristic_setting_zone.crop_id = $tbl"."pdo_product_characteristic.crop_id AND
                                        $tbl"."pdo_product_characteristic_setting_zone.product_type_id = $tbl"."pdo_product_characteristic.product_type_id AND
                                        $tbl"."pdo_product_characteristic_setting_zone.variety_id = $tbl"."pdo_product_characteristic.variety_id
                                    LEFT JOIN $tbl"."varriety_info ON
                                        $tbl"."varriety_info.crop_id = $tbl"."pdo_product_characteristic_setting_zone.crop_id AND
                                        $tbl"."varriety_info.product_type_id = $tbl"."pdo_product_characteristic_setting_zone.product_type_id AND
                                        $tbl"."varriety_info.varriety_id = $tbl"."pdo_product_characteristic_setting_zone.variety_id
                                    LEFT JOIN $tbl"."zone_info ON $tbl"."zone_info.zone_id=$tbl"."pdo_product_characteristic.zone_id
                                    LEFT JOIN $tbl"."zilla ON $tbl"."zilla.zillaid=$tbl"."pdo_product_characteristic.district_id
                                    LEFT JOIN $tbl"."upazilla_new ON $tbl"."upazilla_new.zilla_id=$tbl"."pdo_product_characteristic.district_id AND $tbl"."upazilla_new.upazilla_id=$tbl"."pdo_product_characteristic.upazilla_id
                                    INNER JOIN $tbl"."zone_user_access ON $tbl"."zone_user_access.zone_id = $tbl"."pdo_product_characteristic.zone_id
                                    INNER JOIN $tbl"."division_info ON $tbl"."division_info.division_id = $tbl"."zone_user_access.division_id
                                WHERE
                                    $tbl"."varriety_info.type=0 AND
                                    $tbl"."pdo_product_characteristic.crop_id='".$rowr['crop_id']."' AND
                                    $tbl"."pdo_product_characteristic.product_type_id='".$rowr['product_type_id']."'
                                    $wcondition $zone_id $district_id $upazilla_id
                                GROUP BY $tbl"."pdo_product_characteristic_setting_zone.pcsz_id

                        ";
                    if($dbs->open()) {
                        $result = $dbs->query($sql);
                        while ($row = $dbs->fetchArray($result)) {

                            ?>

                            <tr>
                                <td><?php echo ucwords(strtolower($row['location'])) ?></td>
                                <td><?php echo $row['cultivation_period'] ?></td>
                                <td><?php echo $row['varriety_name'] ?></td>
                                <td><?php echo $row['hybrid'] ?></td>
                                <td><?php echo $row['sales_quantity'] ?></td>
                                <td><?php echo $row['special_characteristics'] ?></td>
                                <td>
                                    <?php if ($row['image_url'] == "") {
                                        $img_url = "../../system_images/blank_img.png";
                                    } else {
                                        $img_url = "../../system_images/pdo_upload_image/pdo_product_characteristic/" . $row['image_url'];
                                    } ?>
                                    <img src="<?php echo $img_url; ?>" width="150" id="blah" />
                                </td>
                            </tr>

                        <?php
                        }
                    }
                    ?>
                    </tbody>
                </table>
            </td>
            <td>

                <table class="table table-condensed table-striped table-hover table-bordered pull-left report" id="data-table">
                    <thead>
                    <tr class="btn-info">
                        <th>Location</th>
                        <th>Cultivation Period</th>
                        <th>Name Variety</th>
                        <th>F1/ OP</th>
                        <th>Sales Quantity</th>
                        <th>Special Characteristics</th>
                        <th>Picture</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                     $sql = "
                                SELECT
                                    $tbl"."varriety_info.varriety_name,
                                    $tbl"."varriety_info.hybrid,
                                    $tbl"."pdo_product_characteristic.sales_quantity,
                                    $tbl"."pdo_product_characteristic_setting_zone.special_characteristics,
                                    $tbl"."pdo_product_characteristic_setting_zone.image_url,
                                    CONCAT_WS
                                    (' To ',
                                    DATE_FORMAT($tbl" . "pdo_product_characteristic_setting_zone.cultivation_period_start,'%d %M'),
                                    DATE_FORMAT($tbl" . "pdo_product_characteristic_setting_zone.cultivation_period_end,'%d %M')
                                    ) AS cultivation_period,
                                    CONCAT_WS(', ', $tbl"."division_info.division_name, $tbl"."zone_info.zone_name, $tbl"."zilla.zillanameeng, $tbl"."upazilla_new.upazilla_name) AS location

                                FROM $tbl"."pdo_product_characteristic
                                    LEFT JOIN $tbl"."pdo_product_characteristic_setting_zone ON
                                        $tbl"."pdo_product_characteristic_setting_zone.zone_id = $tbl"."pdo_product_characteristic.zone_id AND
                                        $tbl"."pdo_product_characteristic_setting_zone.crop_id = $tbl"."pdo_product_characteristic.crop_id AND
                                        $tbl"."pdo_product_characteristic_setting_zone.product_type_id = $tbl"."pdo_product_characteristic.product_type_id AND
                                        $tbl"."pdo_product_characteristic_setting_zone.variety_id = $tbl"."pdo_product_characteristic.variety_id
                                    LEFT JOIN $tbl"."varriety_info ON
                                        $tbl"."varriety_info.crop_id = $tbl"."pdo_product_characteristic_setting_zone.crop_id AND
                                        $tbl"."varriety_info.product_type_id = $tbl"."pdo_product_characteristic_setting_zone.product_type_id AND
                                        $tbl"."varriety_info.varriety_id = $tbl"."pdo_product_characteristic_setting_zone.variety_id
                                    LEFT JOIN $tbl"."zone_info ON $tbl"."zone_info.zone_id=$tbl"."pdo_product_characteristic.zone_id
                                    LEFT JOIN $tbl"."zilla ON $tbl"."zilla.zillaid=$tbl"."pdo_product_characteristic.district_id
                                    LEFT JOIN $tbl"."upazilla_new ON $tbl"."upazilla_new.zilla_id=$tbl"."pdo_product_characteristic.district_id AND $tbl"."upazilla_new.upazilla_id=$tbl"."pdo_product_characteristic.upazilla_id
                                    INNER JOIN $tbl"."zone_user_access ON $tbl"."zone_user_access.zone_id = $tbl"."pdo_product_characteristic.zone_id
                                    INNER JOIN $tbl"."division_info ON $tbl"."division_info.division_id = $tbl"."zone_user_access.division_id
                                WHERE
                                    $tbl"."varriety_info.type=1 AND
                                    $tbl"."pdo_product_characteristic.crop_id='".$rowr['crop_id']."' AND
                                    $tbl"."pdo_product_characteristic.product_type_id='".$rowr['product_type_id']."'
                                    $wcondition $zone_id $district_id $upazilla_id
                                GROUP BY $tbl"."pdo_product_characteristic_setting_zone.pcsz_id
                        ";
                    if($dbc->open()) {
                        $result = $dbc->query($sql);
                        while ($row = $dbc->fetchArray($result)) {

                            ?>

                            <tr>
                                <td><?php echo ucwords(strtolower($row['location'])) ?></td>
                                <td><?php echo $row['cultivation_period'] ?></td>
                                <td><?php echo $row['varriety_name'] ?></td>
                                <td><?php echo $row['hybrid'] ?></td>
                                <td><?php echo $row['sales_quantity'] ?></td>
                                <td><?php echo $row['special_characteristics'] ?></td>
                                <td>
                                    <?php if ($row['image_url'] == "") {
                                        $img_url = "../../system_images/blank_img.png";
                                    } else {
                                        $img_url = "../../system_images/pdo_upload_image/pdo_product_characteristic/" . $row['image_url'];
                                    } ?>
                                    <img src="<?php echo $img_url; ?>" width="150" id="blah"/>
                                </td>
                            </tr>

                        <?php
                        }
                    }
                    ?>
                    </tbody>
                </table>

            </td>

        </tr>
    <?php
    }
}
?>
</thead>
</table>
</div>
<?php include_once '../../libraries/print_page/Print_footer.php'; ?>
</div>