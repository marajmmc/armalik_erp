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
                                <th style="width:2%">
                                    Sl No
                                </th>
                                <th style="width:10%">
                                    Crop
                                </th>
                                <th style="width:10%">
                                    Product Type
                                </th>
<!--                                <th style="width:10%">
                                    Variety
                                </th>
                                <th style="width:5%">
                                    Type
                                </th>-->
<!--                                <th style="width:5%">
                                    Status
                                </th>-->
                            </tr>
                        </thead>
                        <tbody>
                            <?php
//                            $sql = "SELECT
//                                        $tbl" . "pdo_product_info.pdo_id,
//                                        $tbl" . "crop_info.crop_name,
//                                        $tbl" . "product_type.product_type,
//                                        $tbl" . "pdo_product_info.pdo_name,
//                                        $tbl" . "pdo_product_info.pdo_type,
//                                        $tbl" . "pdo_product_info.`status`
//                                    FROM
//                                        $tbl" . "pdo_product_info
//                                        LEFT JOIN $tbl" . "crop_info ON $tbl" . "crop_info.crop_id = $tbl" . "pdo_product_info.crop_id
//                                        LEFT JOIN $tbl" . "product_type ON $tbl" . "product_type.product_type_id = $tbl" . "pdo_product_info.product_type_id
//                                    WHERE
//                                        $tbl" . "pdo_product_info.del_status='0'
//                                    GROUP BY    $tbl" . "pdo_product_info.crop_id, 
//                                                $tbl" . "pdo_product_info.product_type_id
//                        ";
//                            GROUP BY    $tbl" . "pdo_photo_upload.crop_id, 
//                                                $tbl" . "pdo_photo_upload.product_type_id, 
//                                                $tbl" . "pdo_photo_upload.varriety_id,
//                                                $tbl" . "pdo_photo_upload.pack_size,
//                                                $tbl" . "pdo_photo_upload.farmer_id

                              $sql = "SELECT
                                        $tbl" . "pdo_photo_upload.id,
                                        $tbl" . "pdo_photo_upload.pdo_id,
                                        $tbl" . "crop_info.crop_name,
                                        $tbl" . "product_type.product_type
                                    FROM
                                        $tbl" . "pdo_photo_upload
                                        LEFT JOIN $tbl" . "pdo_product_info ON $tbl" . "pdo_product_info.pdo_id = $tbl" . "pdo_photo_upload.pdo_id
                                        LEFT JOIN $tbl" . "crop_info ON $tbl" . "crop_info.crop_id = $tbl" . "pdo_photo_upload.crop_id
                                        LEFT JOIN $tbl" . "product_type ON $tbl" . "product_type.product_type_id = $tbl" . "pdo_photo_upload.product_type_id
                                    WHERE
                                        $tbl" . "pdo_photo_upload.status='Active'
                                        AND $tbl" . "pdo_photo_upload.pdo_id !=''
                                        " . $db->get_pri_variety_access($tbl . "pdo_photo_upload") . "
                                    GROUP BY 
                                        $tbl" . "pdo_photo_upload.crop_id, $tbl" . "pdo_photo_upload.product_type_id";
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
                                    <tr class="<?php echo $rowcolor ?> pointer" id="tr_id<?php echo $i; ?>" onclick="get_rowID('<?php echo $result_array["id"] ?>', '<?php echo $i; ?>')" ondblclick="details_form();">
                                        <td>
                                            <?php echo $i; ?>
                                        </td>
                                        <td><?php echo $result_array['crop_name']; ?></td>
                                        <td><?php echo $result_array['product_type']; ?></td>
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
