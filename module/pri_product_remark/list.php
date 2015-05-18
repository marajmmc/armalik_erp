<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$tbl = _DB_PREFIX;
$user_id= $_SESSION['user_id'];
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
                            <th style="width:2%">
                                Sl No
                            </th>
                            <th style="width:10%">
                                Farmer Name
                            </th>
                            <th style="width:5%">
                                Crop Name
                            </th>
                            <th style="width:10%">
                                Product Type
                            </th>

                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $sql = "SELECT
                                    $tbl"."farmer_info.farmer_name,
                                    $tbl"."crop_info.crop_name,
                                    $tbl"."product_type.product_type,
                                    $tbl"."pdo_variety_setting.vs_id,
                                    $tbl"."pdo_variety_setting.number_of_img,
                                    $tbl"."pdo_variety_setting.sowing_date,
                                    $tbl"."pdo_variety_setting.transplanting_date
                                FROM $tbl"."pdo_variety_setting
                                     LEFT JOIN $tbl"."farmer_info ON $tbl"."farmer_info.farmer_id = $tbl"."pdo_variety_setting.farmer_id
                                     LEFT JOIN $tbl"."crop_info ON $tbl"."crop_info.crop_id = $tbl"."pdo_variety_setting.crop_id
                                     LEFT JOIN $tbl"."product_type ON $tbl"."product_type.crop_id = $tbl"."pdo_variety_setting.crop_id AND $tbl"."product_type.product_type_id = $tbl"."pdo_variety_setting.product_type_id
                                GROUP BY
                                    $tbl"."pdo_variety_setting.farmer_id,
                                    $tbl"."pdo_variety_setting.crop_id,
                                    $tbl"."pdo_variety_setting.product_type_id
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
                                <tr class="<?php echo $rowcolor ?> pointer" id="tr_id<?php echo $i; ?>" onclick="get_rowID('<?php echo $result_array["vs_id"] ?>', '<?php echo $i; ?>')" ondblclick="details_form();">
                                    <td>
                                        <?php echo $i; ?>
                                    </td>
                                    <td><?php echo $result_array['farmer_name']; ?></td>
                                    <td><?php echo $result_array['crop_name']; ?></td>
                                    <td><?php echo $result_array['product_type']; ?></td>
<!--                                    <td>--><?php //echo $result_array['number_of_img']; ?><!--</td>-->
<!--                                    <td>--><?php //echo $db->date_formate($result_array['sowing_date']); ?><!--</td>-->
<!--                                    <td>--><?php //echo $db->date_formate($result_array['transplanting_date']); ?><!--</td>-->
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
