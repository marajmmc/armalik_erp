<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$tbl = _DB_PREFIX;

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

?>
<table class="table" >
    <tr>
        <td>
            <table class="table table-condensed table-striped table-hover table-bordered pull-left report" id="data-table">
                <thead>
                <tr>
                    <th class="21" style="text-align: center">ARM Variety</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $sql="
                    SELECT
                        ait_farmer_info.zone_id,
                        ait_pdo_variety_setting.pdo_season_id,
                        ait_pdo_variety_setting.pdo_year_id,
                        ait_varriety_info.varriety_name,
                        ait_varriety_info.type as pdo_type,
                        ait_pdo_variety_setting.variety_id
                    FROM
                        ait_pdo_variety_setting
                        LEFT JOIN ait_farmer_info ON ait_farmer_info.farmer_id = ait_pdo_variety_setting.farmer_id
                        LEFT JOIN ait_varriety_info ON ait_varriety_info.varriety_id = ait_pdo_variety_setting.variety_id
                    WHERE
                        ait_pdo_variety_setting.del_status=0
                        AND ait_varriety_info.type=0
                        $division_id
                        $zone_id
                        $territory_id
                        $district_id
                        $upazilla_id
                        $pdo_year_id
                        $pdo_season_id
                        $crop_id
                        $product_type_id
                    GROUP BY
                        ait_pdo_variety_setting.crop_id,
                        ait_pdo_variety_setting.product_type_id,
                        ait_pdo_variety_setting.variety_id
                    ";
                if($db->open()) {
                    $result = $db->query($sql);
                    while ($row = $db->fetchArray($result))
                    {
                        ?>
                        <tr>
                            <th>
                                <input type="checkbox" id="self_variety_id[]" name="self_variety_id[]" value="<?php echo $row['variety_id'];?>" /> <?php echo $row['varriety_name'];?>
                            </th>
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
                <tr>
                    <th class="21" style="text-align: center">Competitor Variety</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $sql="
                     SELECT
                        ait_farmer_info.zone_id,
                        ait_pdo_variety_setting.pdo_season_id,
                        ait_pdo_variety_setting.pdo_year_id,
                        ait_varriety_info.varriety_name,
                        ait_varriety_info.type as pdo_type,
                        ait_pdo_variety_setting.variety_id
                    FROM
                        ait_pdo_variety_setting
                        LEFT JOIN ait_farmer_info ON ait_farmer_info.farmer_id = ait_pdo_variety_setting.farmer_id
                        LEFT JOIN ait_varriety_info ON ait_varriety_info.varriety_id = ait_pdo_variety_setting.variety_id
                    WHERE
                        ait_pdo_variety_setting.del_status=0
                        AND ait_varriety_info.type=1
                        $division_id
                        $zone_id
                        $territory_id
                        $district_id
                        $upazilla_id
                        $pdo_year_id
                        $pdo_season_id
                        $crop_id
                        $product_type_id
                    GROUP BY
                        ait_pdo_variety_setting.crop_id,
                        ait_pdo_variety_setting.product_type_id,
                        ait_pdo_variety_setting.variety_id
                    ";
                if($db->open()) {
                    $result = $db->query($sql);
                    while ($row = $db->fetchArray($result))
                    {
                        ?>
                        <tr>
                            <th>
                                <input type="checkbox" id="chk_variety_id[]" name="chk_variety_id[]" value="<?php echo $row['variety_id'];?>" /> <?php echo $row['varriety_name'];?>
                            </th>
                        </tr>
                    <?php
                    }
                }
                ?>
                </tbody>

            </table>
        </td>

    </tr>
</table>
<table class="table table-bordered">
    <thead>
    <tr>
        <th>
            <select class="span12" id="report_product_type" name="report_product_type">
                <option value="All">All</option>
                <option value="Seven Days Picture">7 Days Picture</option>
                <option value="Fruit">Fruit</option>
                <option value="Disease">Disease</option>
            </select>
        </th>
        <th>
            <select class="span12" id="report_type" name="report_type">
                <option value="All">All</option>
                <option value="Picture">Picture</option>
                <option value="Video">Video</option>
            </select>
        </th>
        <th>
            <a class="btn btn-small btn-success" data-original-title="" onclick="show_report_fnc()">
                <i class="icon-print" data-original-title="Share"> </i> Report View
            </a>
        </th>
    </tr>
    </thead>
</table>


