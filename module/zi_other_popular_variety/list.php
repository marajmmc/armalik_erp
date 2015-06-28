<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$tbl = _DB_PREFIX;
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
                                    Sl.
                                </th>
                                <th style="width:15%">
                                    Territory
                                </th>
                                <th style="width:12%">
                                    District
                                </th>
                                <th style="width:12%">
                                    Upazilla
                                </th>
                                <th style="width:12%">
                                    Crop
                                </th>
                                <th style="width:12%">
                                    Type
                                </th>
                                <th style="width:12%">
                                    Variety
                                </th>
                                <th style="width:30%">
                                    Farmer Name
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php

                        $sql = "SELECT
                            zcf.*,
                            ati.territory_name,
                            zilla.zillanameeng,
                            upa.upazilanameeng,
                            crop.crop_name,
                            ptype.product_type,
                            variety.varriety_name

                            FROM
                            $tbl" . "zi_others_popular_variety zcf

                            LEFT JOIN $tbl" . "territory_info ati ON ati.territory_id = zcf.territory_id
                            LEFT JOIN $tbl" . "zilla zilla ON zilla.zillaid = zcf.district_id
                            LEFT JOIN $tbl" . "crop_info crop ON crop.crop_id = zcf.crop_id
                            LEFT JOIN $tbl" . "product_type ptype ON ptype.product_type_id = zcf.product_type_id
                            LEFT JOIN $tbl" . "varriety_info variety ON variety.varriety_id = zcf.variety_id
                            LEFT JOIN $tbl" . "upazilla upa ON upa.upazilaid = zcf.upazilla_id AND upa.zillaid = zcf.district_id

                            WHERE zcf.zone_id ='".$_SESSION['zone_id']."'
                            GROUP BY zcf.division_id, zcf.zone_id, zcf.territory_id, zcf.district_id, zcf.upazilla_id, zcf.crop_id, zcf.product_type_id, zcf.variety_id, zcf.farmer_name
                            ";

                        if($db->open())
                        {
                            $result = $db->query($sql);
                            $i = 1;
                            while ($result_array = $db->fetchAssoc())
                            {
                                if ($i % 2 == 0)
                                {
                                    $rowcolor = "gradeC";
                                }
                                else
                                {
                                    $rowcolor = "gradeA success";
                                }
                                ?>
                                <tr class="<?php echo $rowcolor ?> pointer" id="tr_id<?php echo $i; ?>" onclick="get_rowID('<?php echo $result_array["id"];?>', '<?php echo $i; ?>')" ondblclick="details_form();">
                                    <td><?php echo $i; ?></td>
                                    <td><?php echo $result_array['territory_name']; ?></td>
                                    <td><?php echo $result_array['zillanameeng']; ?></td>
                                    <td><?php echo $result_array['upazilanameeng']; ?></td>
                                    <td><?php echo $result_array['crop_name']; ?></td>
                                    <td><?php echo $result_array['product_type']; ?></td>
                                    <td><?php echo $result_array['varriety_name']; ?></td>
                                    <td><?php echo $result_array['farmer_name']; ?></td>
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
