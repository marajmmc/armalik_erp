<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$tbl = _DB_PREFIX;

if ($_POST['division_id'] != "")
{
    $division_id = "AND $tbl"."zi_monthly_field_visit_pictures.division_id='" . $_POST['division_id'] . "'";
}
else
{
    $division_id = "";
}
if ($_POST['zone_id'] != "")
{
    $zone_id = "AND $tbl"."zi_monthly_field_visit_pictures.zone_id='" . $_POST['zone_id'] . "'";
}
else
{
    $zone_id = "";
}

if ($_POST['territory_id'] != "")
{
    $territory_id = "AND $tbl"."zi_monthly_field_visit_pictures.territory_id='" . $_POST['territory_id'] . "'";
}
else
{
    $territory_id = "";
}

if ($_POST['zilla_id'] != "")
{
    $district_id = "AND $tbl"."zi_monthly_field_visit_pictures.district_id='" . $_POST['zilla_id'] . "'";
}
else
{
    $district_id = "";
}

if ($_POST['upazilla_id'] != "")
{
    $upazilla_id = "AND $tbl"."zi_monthly_field_visit_pictures.upazilla_id='" . $_POST['upazilla_id'] . "'";
}
else
{
    $upazilla_id = "";
}

if ($_POST['crop_id'] != "")
{
    $crop_id = "AND $tbl"."zi_monthly_field_visit_pictures.crop_id='" . $_POST['crop_id'] . "'";
}
else
{
    $crop_id = "";
}

if ($_POST['product_type_id'] != "")
{
    $product_type_id = "AND $tbl"."zi_monthly_field_visit_pictures.product_type_id='" . $_POST['product_type_id'] . "'";
}
else
{
    $product_type_id = "";
}

if ($_POST['varriety_id'] != "")
{
    $variety_id = "AND $tbl"."zi_monthly_field_visit_pictures.variety_id='" . $_POST['varriety_id'] . "'";
}
else
{
    $variety_id = "";
}

?>

<a class="btn btn-small btn-success" data-original-title="" onclick="print_rpt()" style="float: right;">
    <i class="icon-print" data-original-title="Share"> </i> Print
</a>
<div id="PrintArea" style="background-color: white;" >
    <?php include_once '../../libraries/print_page/Print_header.php'; ?>
    <table class="table table-condensed table-striped table-hover table-bordered pull-left report" id="data-table">
        <thead>
            <tr>
                <th style="width:15%">
                    Search Criteria
                </th>
                <th>
                    Pictures
                </th>
            </tr>
        </thead>
        <tbody>
            <?php

            $sql = "SELECT
                $tbl"."zi_monthly_field_visit_pictures.*

                FROM
                $tbl"."zi_monthly_field_visit_pictures

                WHERE
                $tbl"."zi_monthly_field_visit_pictures.del_status='0'
                $division_id $zone_id $territory_id $district_id $upazilla_id $crop_id $product_type_id $variety_id
            ";

            $results = $db->return_result_array($sql);

            $arranged_result = array();
            foreach($results as $result)
            {
                $arranged_result['picture'][$result['farmer_id']][$result['picture_number']]['picture_link'] = $result['picture_link'];
                $arranged_result['picture'][$result['farmer_id']][$result['picture_number']]['remarks'] = $result['remarks'];
                $arranged_result['picture'][$result['farmer_id']][$result['picture_number']]['picture_date'] = $result['picture_date'];
            }

            foreach($arranged_result['picture'] as $i=>$farmer_picture)
            {
                if ($i % 2 == 0)
                {
                    $rowcolor = "gradeC";
                }
                else
                {
                    $rowcolor = "gradeA success";
                }
                if(is_array($results[0])>0)
                {
                ?>
                <tr class="<?php echo $rowcolor; ?> pointer" id="tr_id<?php echo $i; ?>" >
                    <td>
                        <?php
                            $sql = "select *,
                                $tbl"."division_info.division_name,
                                $tbl"."zone_info.zone_name,
                                $tbl"."territory_info.territory_name,
                                $tbl"."zilla.zillanameeng,
                                $tbl"."upazilla.upazilanameeng,
                                $tbl"."crop_info.crop_name,
                                $tbl"."varriety_info.varriety_name

                                from $tbl" . "zi_monthly_field_visit_pictures

                                LEFT JOIN $tbl"."zone_info ON $tbl"."zone_info.zone_id = $tbl"."zi_monthly_field_visit_pictures.zone_id
                                LEFT JOIN $tbl"."division_info ON $tbl"."division_info.division_id = $tbl"."zi_monthly_field_visit_pictures.division_id
                                LEFT JOIN $tbl"."territory_info ON $tbl"."territory_info.territory_id = $tbl"."zi_monthly_field_visit_pictures.territory_id
                                LEFT JOIN $tbl"."zilla ON $tbl"."zilla.zillaid = $tbl"."zi_monthly_field_visit_pictures.district_id

                                LEFT JOIN $tbl"."crop_info ON $tbl"."crop_info.crop_id = $tbl"."zi_monthly_field_visit_pictures.crop_id
                                LEFT JOIN $tbl"."varriety_info ON $tbl"."varriety_info.varriety_id = $tbl"."zi_monthly_field_visit_pictures.variety_id

                                LEFT JOIN $tbl"."upazilla ON $tbl"."upazilla.upazilaid = $tbl"."zi_monthly_field_visit_pictures.upazilla_id AND $tbl"."upazilla.zillaid = $tbl"."zi_monthly_field_visit_pictures.district_id
                                where farmer_id='$i'";

                            $detail = $db->return_result_array($sql);
                            echo $detail[0]['division_name'].'<br/>'.$detail[0]['zone_name'].'<br/>'.$detail[0]['territory_name'].'<br/>'.$detail[0]['zillanameeng'].'<br/>'.$detail[0]['upazilanameeng'].'<br/>'.$detail[0]['crop_name'].'<br/>'.$detail[0]['varriety_name'];
                        ?>
                    </td>
                    <td>
                        <div style="
                        width: 880px !important;
                        height:160px;
                        overflow-x: scroll;
                        overflow-y: hidden;
                        white-space: nowrap;">
                        <?php
                        foreach($farmer_picture as $number=>$picture)
                        {
                            if(isset($picture['picture_link']) && strlen($picture['picture_link'])>0)
                            {
                                ?>
                                <img data-toggle="tooltip" data-placement="left" title="Picture No: <?php echo $number;?> Remark: <?php echo $picture['remarks']?> Picture Date: <?php echo $db->date_formate($picture['picture_date']);?>" style="padding: 10px;" height="120" width="120" src="../../system_images/zi_field_visit/<?php echo $picture['picture_link']?>" />
                            <?php
                            }
                            else
                            {
                                ?>
                                <img style="padding: 10px;" height="120" width="120" src="../../system_images/zi_field_visit/no_image.jpg" />
                            <?php
                            }
                        }
                        ?>
                        </div>
                    </td>
                </tr>
                <?php
                }
            }
            ?>
        </tbody>
    </table>
    <?php include_once '../../libraries/print_page/Print_footer.php'; ?>
</div>

<script>
    jQuery(document).ready(function()
    {
        $('[data-toggle="tooltip"]').tooltip();
    });

</script>