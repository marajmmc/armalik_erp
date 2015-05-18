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
                    <div id="show_data">

                    </div>
                    <table class="table table-condensed table-striped table-hover table-bordered pull-left" id="data-table">
                        <tr>
                            <td>
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th colspan="21" style="text-align: center; background-color: #467FBF; color: #fff;">Product Photo </th>
                                    </tr>
                                    <tr>
                                        <th style="width:5%">
                                            No
                                        </th>
                                        <th style="width:10%">
                                            Date
                                        </th>
                                        <th style="width:20%">
                                            Farmer Name
                                        </th>
                                        <th style="width:10%">
                                            Crop Name
                                        </th>
                                        <th style="width:10%">
                                            Product Type
                                        </th>
                                        <th style="width:10%">
                                            Remark
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $crop_row_id='';
                                    $sql = "SELECT
                                            $tbl"."farmer_info.farmer_name,
                                            $tbl"."crop_info.crop_name,
                                            $tbl"."product_type.product_type,
                                            $tbl"."pdo_variety_setting_img_date.upload_date,
                                            $tbl"."pdo_variety_setting_img_date.id,
                                            $tbl"."pdo_variety_setting_img_date.crop_img_url,
                                            $tbl"."pdo_photo_remark.description
                                        FROM
                                            $tbl"."pdo_variety_setting_img_date
                                            INNER JOIN $tbl"."pdo_variety_setting ON $tbl"."pdo_variety_setting.vs_id = $tbl"."pdo_variety_setting_img_date.vs_id
                                            LEFT JOIN $tbl"."farmer_info ON $tbl"."farmer_info.farmer_id = $tbl"."pdo_variety_setting.farmer_id
                                            LEFT JOIN $tbl"."crop_info ON $tbl"."crop_info.crop_id = $tbl"."pdo_variety_setting.crop_id
                                            LEFT JOIN $tbl"."product_type ON $tbl"."product_type.product_type_id = $tbl"."pdo_variety_setting.product_type_id
                                            LEFT JOIN $tbl"."pdo_photo_remark ON $tbl"."pdo_variety_setting_img_date.id = $tbl"."pdo_photo_remark.crop_img_upload_id
                                        WHERE $tbl"."pdo_variety_setting_img_date.crop_img_url!='' AND ($tbl"."pdo_photo_remark.description IS NULL OR $tbl"."pdo_photo_remark.description='')
                                        ORDER BY $tbl"."pdo_variety_setting_img_date.id DESC
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
                                            $crop_row_id=$result_array['id'];
                                            ?>
                                            <tr class="<?php echo $rowcolor ?> pointer" id="tr_id<?php echo $i; ?>" onclick="get_rowID('<?php echo $result_array["id"] ?>', '<?php echo $i; ?>')" ondblclick="details_form();">
                                                <td>
                                                    <?php echo $crop_row_id; ?>
                                                </td>
                                                <td><?php echo $db->date_formate($result_array['upload_date']); ?></td>
                                                <td><?php echo $result_array['farmer_name']; ?></td>
                                                <td><?php echo $result_array['crop_name']; ?></td>
                                                <td><?php echo $result_array['product_type']; ?></td>
                                                <td onclick='product_image_info("<?php echo $crop_row_id;?>")'><a> View </a></td>
                                            </tr>
                                            <?php
                                            ++$i;
                                        }
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th colspan="21" style="text-align: center; background-color: #BF6C48; color: #fff;">Fruit Photo </th>
                                    </tr>
                                    <tr>
                                        <th style="width:5%">
                                            No
                                        </th>
                                        <th style="width:10%">
                                            Date
                                        </th>
                                        <th style="width:20%">
                                            Farmer Name
                                        </th>
                                        <th style="width:10%">
                                            Crop Name
                                        </th>
                                        <th style="width:10%">
                                            Product Type
                                        </th>
                                        <th style="width:10%">
                                            Remark
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $crop_row_id='';
                                    $sql = "SELECT
                                                $tbl"."farmer_info.farmer_name,
                                                $tbl"."crop_info.crop_name,
                                                $tbl"."product_type.product_type,
                                                $tbl"."pdo_variety_setting_fruit_img.id,
                                                $tbl"."pdo_variety_setting_fruit_img.upload_date,
                                                $tbl"."pdo_variety_setting_fruit_img.fruit_img_url,
                                                $tbl"."pdo_photo_remark.description
                                            FROM
                                                $tbl"."pdo_variety_setting_fruit_img
                                                INNER JOIN $tbl"."pdo_variety_setting ON $tbl"."pdo_variety_setting.vs_id = $tbl"."pdo_variety_setting_fruit_img.vs_id
                                                LEFT JOIN $tbl"."farmer_info ON $tbl"."farmer_info.farmer_id = $tbl"."pdo_variety_setting.farmer_id
                                                LEFT JOIN $tbl"."crop_info ON $tbl"."crop_info.crop_id = $tbl"."pdo_variety_setting.crop_id
                                                LEFT JOIN $tbl"."product_type ON $tbl"."product_type.product_type_id = $tbl"."pdo_variety_setting.product_type_id
                                                LEFT JOIN $tbl"."pdo_photo_remark ON $tbl"."pdo_photo_remark.crop_img_upload_id = $tbl"."pdo_variety_setting_fruit_img.id
                                            WHERE $tbl"."pdo_variety_setting_fruit_img.fruit_img_url!='' AND ($tbl"."pdo_photo_remark.description IS NULL OR $tbl"."pdo_photo_remark.description='')
                                        ORDER BY $tbl"."pdo_variety_setting_fruit_img.id DESC
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
                                            $fruit_row_id=$result_array['id'];
                                            ?>
                                            <tr class="<?php echo $rowcolor ?> pointer" id="tr_id<?php echo $i; ?>" onclick="get_rowID('<?php echo $result_array["id"] ?>', '<?php echo $i; ?>')" ondblclick="details_form();">
                                                <td>
                                                    <?php echo $crop_row_id; ?>
                                                </td>
                                                <td><?php echo $db->date_formate($result_array['upload_date']); ?></td>
                                                <td><?php echo $result_array['farmer_name']; ?></td>
                                                <td><?php echo $result_array['crop_name']; ?></td>
                                                <td><?php echo $result_array['product_type']; ?></td>
                                                <td onclick='product_fruit_image_info("<?php echo $fruit_row_id;?>")'><a> View </a></td>
                                            </tr>
                                            <?php
                                            ++$i;
                                        }
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th colspan="21" style="text-align: center; background-color: #990033; color: #fff;">Disease Photo </th>
                                    </tr>
                                    <tr>
                                        <th style="width:5%">
                                            No
                                        </th>
                                        <th style="width:10%">
                                            Date
                                        </th>
                                        <th style="width:20%">
                                            Farmer Name
                                        </th>
                                        <th style="width:10%">
                                            Crop Name
                                        </th>
                                        <th style="width:10%">
                                            Product Type
                                        </th>
                                        <th style="width:10%">
                                            Remark
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $crop_row_id='';
                                    $sql = "SELECT
                                                $tbl"."pdo_variety_setting_disease_img.disease_id,
                                                $tbl"."pdo_variety_setting_disease_img.disease_img_url,
                                                $tbl"."pdo_variety_setting_disease_img.upload_date,
                                                $tbl"."farmer_info.farmer_name,
                                                $tbl"."crop_info.crop_name,
                                                $tbl"."product_type.product_type,
                                                $tbl"."pdo_photo_remark.description
                                            FROM
                                                $tbl"."pdo_variety_setting_disease_img
                                                INNER JOIN $tbl"."pdo_variety_setting ON $tbl"."pdo_variety_setting.vs_id = $tbl"."pdo_variety_setting_disease_img.vs_id
                                                LEFT JOIN $tbl"."farmer_info ON $tbl"."farmer_info.farmer_id = $tbl"."pdo_variety_setting.farmer_id
                                                LEFT JOIN $tbl"."crop_info ON $tbl"."crop_info.crop_id = $tbl"."pdo_variety_setting.crop_id
                                                LEFT JOIN $tbl"."product_type ON $tbl"."product_type.product_type_id = $tbl"."pdo_variety_setting.product_type_id
                                                LEFT JOIN $tbl"."pdo_photo_remark ON $tbl"."pdo_photo_remark.crop_img_upload_id = $tbl"."pdo_variety_setting_disease_img.disease_id
                                            WHERE $tbl"."pdo_variety_setting_disease_img.disease_img_url!='' AND ($tbl"."pdo_photo_remark.description IS NULL OR $tbl"."pdo_photo_remark.description='')
                                            ORDER BY $tbl"."pdo_variety_setting_disease_img.disease_id DESC
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
                                            $disease_id=$result_array['disease_id'];
                                            ?>
                                            <tr class="<?php echo $rowcolor ?> pointer" id="tr_id<?php echo $i; ?>" onclick="get_rowID('<?php echo $result_array["disease_id"] ?>', '<?php echo $i; ?>')" ondblclick="details_form();">
                                                <td>
                                                    <?php echo $crop_row_id; ?>
                                                </td>
                                                <td><?php echo $db->date_formate($result_array['upload_date']); ?></td>
                                                <td><?php echo $result_array['farmer_name']; ?></td>
                                                <td><?php echo $result_array['crop_name']; ?></td>
                                                <td><?php echo $result_array['product_type']; ?></td>
                                                <td onclick='product_disease_image_info("<?php echo $disease_id;?>")'> <a> View </a> </td>
                                            </tr>
                                            <?php
                                            ++$i;
                                        }
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </table>
                    <div class="clearfix">
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<!--<script type="text/javascript">-->
<!--    //Data Tables-->
<!--    $(document).ready(function () {-->
<!--        $('#data-table').dataTable({-->
<!--            "sPaginationType": "full_numbers"-->
<!--        });-->
<!--    });-->
<!---->
<!--    jQuery('.delete-row').click(function () {-->
<!--        var conf = confirm('Continue delete?');-->
<!--        if (conf) jQuery(this).parents('tr').fadeOut(function () {-->
<!--            jQuery(this).remove();-->
<!--        });-->
<!--        return false;-->
<!--    });-->
<!--</script>-->
<script src="../../system_js/jquery.min.js"></script>