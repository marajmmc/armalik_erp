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
                                    Crop Name
                                </th>
                                <th style="width:10%">
                                    Product Type
                                </th>
                                <th style="width:10%">
                                    Variety Name
                                </th>
                                <th style="width:10%">
                                    F1/OP
                                </th>
                                <th style="width:5%">
                                    Variety Type
                                </th>
                                <th style="width:10%">
                                    Characteristics
                                </th>
                                <th style="width:10%">
                                    Image
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $variety_name="";
                            $sql = "SELECT
                                        $tbl"."pdo_product_characteristic_setting.prodcut_characteristic_id,
                                        $tbl"."pdo_product_characteristic_setting.special_characteristics,
                                        $tbl"."pdo_product_characteristic_setting.product_category,
                                        $tbl"."pdo_product_characteristic_setting.image_url,
                                        $tbl"."pdo_product_characteristic_setting.`status`,
                                        $tbl"."pdo_product_characteristic_setting.del_status,
                                        $tbl"."pdo_product_characteristic_setting.entry_by,
                                        $tbl"."pdo_product_characteristic_setting.entry_date,
                                        $tbl"."crop_info.crop_name,
                                        $tbl"."product_type.product_type,
                                        $tbl"."varriety_info.varriety_name,
                                        CASE
                                                WHEN $tbl"."varriety_info.type=0 THEN 'ARM'
                                                WHEN $tbl"."varriety_info.type=1 THEN 'Check Variety'
                                                WHEN $tbl"."varriety_info.type=2 THEN 'Upcoming'
                                        END as variety_type,
                                        $tbl"."varriety_info.hybrid
                                    FROM
                                        $tbl"."pdo_product_characteristic_setting
                                        LEFT JOIN $tbl"."crop_info ON $tbl"."crop_info.crop_id = $tbl"."pdo_product_characteristic_setting.crop_id
                                        LEFT JOIN $tbl"."product_type ON $tbl"."product_type.crop_id = $tbl"."pdo_product_characteristic_setting.crop_id AND $tbl"."product_type.product_type_id = $tbl"."pdo_product_characteristic_setting.product_type_id
                                        LEFT JOIN $tbl"."varriety_info ON $tbl"."varriety_info.crop_id = $tbl"."pdo_product_characteristic_setting.crop_id AND $tbl"."varriety_info.product_type_id = $tbl"."pdo_product_characteristic_setting.product_type_id AND $tbl"."varriety_info.varriety_id = $tbl"."pdo_product_characteristic_setting.variety_id
                                    ";
                            if ($db->open())
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

                                    if($result_array['special_characteristics']=="")
                                    {
                                        $sc_status="<button class='btn-danger'>Not Done</button>";
                                    }
                                    else
                                    {
                                        $sc_status="<button class='btn-info'>Done</button>";
                                    }
                                    if($result_array['image_url']=="")
                                    {
                                        $img_status="<button class='btn-danger'>Not Done</button>";
                                    }
                                    else
                                    {
                                        $img_status="<button class='btn-info'>Done</button>";
                                    }
                                    ?>
                                    <tr class="<?php echo $rowcolor ?> pointer" id="tr_id<?php echo $i; ?>" onclick="get_rowID('<?php echo $result_array["prodcut_characteristic_id"] ?>', '<?php echo $i; ?>')" ondblclick="details_form();">
                                        <td class="alert-danger">
                                            <?php echo $i; ?>
                                        </td>
                                        <td><?php echo $result_array['crop_name']; ?></td>
                                        <td><?php echo $result_array['product_type']; ?></td>
                                        <td><?php echo $result_array['varriety_name']; ?></td>
                                        <td><?php echo $result_array['hybrid']; ?></td>
                                        <td><?php echo $result_array['variety_type']; ?></td>
                                        <td><?php echo $sc_status; ?></td>
                                        <td><?php echo $img_status; ?></td>
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
