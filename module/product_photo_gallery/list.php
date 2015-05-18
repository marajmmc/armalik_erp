<?php
session_start();
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
                                    Sl No
                                </th>
                                <th style="width:10%">
                                    Crop
                                </th>
                                <th style="width:10%" >
                                    Variety 
                                </th>
                                <th style="width:5%">
                                    Farmer Name
                                </th>
                                <th style="width:5%">
                                    Date
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT
                                        ait_crop_info.crop_name,
                                        ait_varriety_info.varriety_name,
                                        ait_product_photo_gallery.photo_gallery_id,
                                        ait_product_photo_gallery.farmer_name,
                                        ait_product_photo_gallery.phone_number,
                                        ait_product_photo_gallery.picture_date
                                    FROM
                                        ait_product_photo_gallery
                                        LEFT JOIN ait_crop_info ON ait_crop_info.crop_id = ait_product_photo_gallery.crop_id
                                        LEFT JOIN ait_varriety_info ON ait_varriety_info.varriety_id = ait_product_photo_gallery.varriety_id
                                    WHERE  $tbl" . "product_photo_gallery.del_status='0'
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
                                    <tr class="<?php echo $rowcolor ?> pointer" id="tr_id<?php echo $i; ?>" onclick="get_rowID('<?php echo $result_array["photo_gallery_id"] ?>', '<?php echo $i; ?>')" ondblclick="details_form();">
                                        <td>
                                            <?php echo $i; ?>
                                        </td>
                                        <td>
                                            <?php echo $result_array['crop_name']; ?>
                                        </td>
                                        <td>
                                            <?php echo $result_array['varriety_name']; ?>
                                        </td>
                                        <td>
                                            <?php echo $result_array['farmer_name']; ?>
                                        </td>
                                        <td>
                                            <?php echo $db->date_formate($result_array['picture_date']); ?>
                                        </td>
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