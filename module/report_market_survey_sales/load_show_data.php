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

if($_POST['zone_id']!="")
{
    $zone_id=" AND $tbl"."pdo_product_characteristic.zone_id='".$_POST['zone_id']."'";
}
else
{
    $zone_id="";
}

if($_POST['division_id']!="")
{
    $division_id=" AND $tbl"."division_info.division_id='".$_POST['division_id']."'";
}
else
{
    $division_id="";
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
        <tr>
            <th style="text-align: center;"> ARM Sales Quantity </th>
            <th style="text-align: center;"> Competitors Sales Quantity </th>
        </tr>
        <tr>
            <th style="vertical-align: top;">
                <table class="table table-condensed table-striped table-hover table-bordered pull-left report" id="data-table">
                    <thead>
                    <tr>
                        <th>Crop-Type-Variety</th>
                        <th>Location</th>
                        <th>Total Sales</th>
                    </tr>
                    <?php
                    $sql_arm="SELECT
                                CONCAT_WS(' - ', ait_crop_info.crop_name, ait_product_type.product_type, ait_varriety_info.varriety_name, ait_varriety_info.company_name) AS arm_crop_classification,
                                ait_varriety_info.type,
                                ait_varriety_info.hybrid,
                                ait_zone_info.zone_name,
                                CONCAT_WS(' - ', ait_division_info.division_name, ait_territory_info.territory_name, ait_zilla.zillanameeng, ait_upazilla_new.upazilla_name) AS arm_location,
                                Sum(ait_pdo_product_characteristic.sales_quantity) AS sales_quantity
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
                                $division_id $crop_id $product_type_id $zone_id $district_id $upazilla_id
                            GROUP BY
                                ait_pdo_product_characteristic.zone_id,
                                ait_pdo_product_characteristic.territory_id,
                                ait_pdo_product_characteristic.district_id,
                                ait_pdo_product_characteristic.upazilla_id,
                                ait_pdo_product_characteristic.crop_id,
                                ait_pdo_product_characteristic.product_type_id,
                                ait_pdo_product_characteristic.variety_id";
                    if($db->open())
                    {
                        $result=$db->query($sql_arm);
                        while($row_arm=$db->fetchAssoc($result))
                        {
                            ?>
                            <tr>
                                <th><?php echo $row_arm['arm_crop_classification'];?></th>
                                <th><?php echo $row_arm['arm_location'];?></th>
                                <th><?php echo $row_arm['sales_quantity'];?></th>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                    </thead>
                </table>
            </th>
            <th style="vertical-align: top;">
                <table class="table table-condensed table-striped table-hover table-bordered pull-left report" id="data-table">
                    <thead>
                    <tr>
                        <th>Crop-Type-Variety-Company Name</th>
                        <th>Location</th>
                        <th>Total Sales</th>
                    </tr>
                    <?php
                    $sql_arm="SELECT
                                CONCAT_WS(' - ', ait_crop_info.crop_name, ait_product_type.product_type, ait_varriety_info.varriety_name, ait_varriety_info.company_name) AS arm_crop_classification,
                                ait_varriety_info.type,
                                ait_varriety_info.hybrid,
                                ait_zone_info.zone_name,
                                CONCAT_WS(' - ', ait_division_info.division_name, ait_territory_info.territory_name, ait_zilla.zillanameeng, ait_upazilla_new.upazilla_name) AS arm_location,
                                Sum(ait_pdo_product_characteristic.sales_quantity) AS sales_quantity
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
                                $division_id $crop_id $product_type_id $zone_id $district_id $upazilla_id
                            GROUP BY
                                ait_pdo_product_characteristic.zone_id,
                                ait_pdo_product_characteristic.territory_id,
                                ait_pdo_product_characteristic.district_id,
                                ait_pdo_product_characteristic.upazilla_id,
                                ait_pdo_product_characteristic.crop_id,
                                ait_pdo_product_characteristic.product_type_id,
                                ait_pdo_product_characteristic.variety_id";
                    if($db->open())
                    {
                        $result=$db->query($sql_arm);
                        while($row_arm=$db->fetchAssoc($result))
                        {
                            ?>
                            <tr>
                                <th><?php echo $row_arm['arm_crop_classification'];?></th>
                                <th><?php echo $row_arm['arm_location'];?></th>
                                <th><?php echo $row_arm['sales_quantity'];?></th>
                            </tr>
                        <?php
                        }
                    }
                    ?>
                    </thead>
                </table>
            </th>
        </tr>
        </thead>
    </table>
</div>
    <?php include_once '../../libraries/print_page/Print_footer.php'; ?>
</div>