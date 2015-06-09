<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$tbl = _DB_PREFIX;
if ($_SESSION['user_level'] == "Zone")
{
    $zone_id = "AND $tbl" . "primary_market_survey.zone_id='" . $_SESSION['zone_id'] . "'";
}
else
{
    $zone_id='';
}
?>
<div class="row-fluid">
    <div class="span12">
        <div class="widget">
            <div class="widget-header">
                <div class="title">
                    <a id="dynamicTable"><?php echo $db->Get_Auto_TaskName() ?></a>
                    <span class="mini-title">

                    </span>
                </div>
                <span class="tools">
                    <a class="btn btn-small" data-original-title="">
                        <i class="icon-list-alt" data-original-title="Share"> </i>
                    </a>
                </span>

            </div>
            <div class="widget-body">
                <div id="dt_example" class="example_alt_pagination">
                    <table class="table table-condensed table-striped table-hover table-bordered pull-left" id="data-table">

                        <thead>
                            <tr>
                                <th style="width:5%">
                                    No
                                </th>
                                <th style="width:20%">
                                    Zone
                                </th>
                                <th style="width:20%">
                                    Territory
                                </th>
                                <th style="width:20%">
                                    District
                                </th>
                                <th style="width:20%">
                                    Upazilla
                                </th>
                                <th style="width:20%">
                                    Crop
                                </th>
                                <th style="width:20%">
                                    Product Type
                                </th>
                                <th style="width:10%">
                                    Status
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT
                                        $tbl"."primary_market_survey.market_survey_group_id,
                                        $tbl"."zone_info.zone_name,
                                        $tbl"."zilla.zillanameeng,
                                        $tbl"."upazilla_new.upazilla_name,
                                        $tbl"."primary_market_survey.wholesaler_name,
                                        $tbl"."primary_market_survey.`status`,
                                        $tbl"."primary_market_survey.del_status,
                                        $tbl"."territory_info.territory_name,
                                        $tbl"."crop_info.crop_name,
                                        $tbl"."product_type.product_type
                                    FROM
                                        $tbl"."primary_market_survey
                                        LEFT JOIN $tbl"."zone_info ON $tbl"."zone_info.zone_id = $tbl"."primary_market_survey.zone_id
                                        LEFT JOIN $tbl"."zilla ON $tbl"."zilla.zillaid = $tbl"."primary_market_survey.district_id
                                        LEFT JOIN $tbl"."upazilla_new ON $tbl"."upazilla_new.upazilla_id = $tbl"."primary_market_survey.upazilla_id
                                        LEFT JOIN $tbl"."territory_info ON $tbl"."territory_info.zone_id = $tbl"."primary_market_survey.zone_id AND $tbl"."territory_info.territory_id = $tbl"."primary_market_survey.territory_id
                                        LEFT JOIN $tbl"."crop_info ON $tbl"."crop_info.crop_id = $tbl"."primary_market_survey.crop_id
                                        LEFT JOIN $tbl"."product_type ON $tbl"."product_type.product_type_id = $tbl"."primary_market_survey.product_type_id
                                    WHERE
                                    $tbl"."primary_market_survey.del_status='0'
                                    $zone_id
                                    GROUP BY $tbl"."primary_market_survey.market_survey_group_id
                        ";
                            if ($db->open()) {
                                $result = $db->query($sql);
                                $i = 1;
                                while ($result_array = $db->fetchAssoc()) {
                                    if ($i % 2 == 0) {
                                        $rowcolor = "gradeC";
                                    } else {
                                        $rowcolor = "gradeA success";
                                    }
                                    ?>
                                    <tr class="<?php echo $rowcolor ?> pointer" id="tr_id<?php echo $i; ?>" onclick="get_rowID('<?php echo $result_array["market_survey_group_id"] ?>', '<?php echo $i; ?>')" ondblclick="details_form();">
                                        <td>
                                            <?php echo $i; ?>
                                        </td>
                                        <td><?php echo $result_array['zone_name']; ?></td>
                                        <td><?php echo $result_array['territory_name']; ?></td>
                                        <td><?php echo $result_array['zillanameeng']; ?></td>
                                        <td><?php echo $result_array['upazilla_name']; ?></td>
                                        <td><?php echo $result_array['crop_name']; ?></td>
                                        <td><?php echo $result_array['product_type']; ?></td>
                                        <td><?php echo $result_array['status']; ?></td>
                                    </tr>
                                    <?php
                                    ++$i;
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                    <div class="clearfix">
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<script type="text/javascript">
    //Data Tables
    $(document).ready(function () {
        $('#data-table').dataTable({
            "sPaginationType": "full_numbers"
        });
    });

    jQuery('.delete-row').click(function () {
        var conf = confirm('Continue delete?');
        if (conf) jQuery(this).parents('tr').fadeOut(function () {
            jQuery(this).remove();
        });
        return false;
    });
</script>
