<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$tbl = _DB_PREFIX;
$crop_name = '';
$product_type = '';
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
                                <th style="width:1%">
                                    Sl No
                                    
                                </th>
                                <th style="width:20%">
                                    Crop
                                </th>
                                <th style="width:20%">
                                    Product Type
                                </th>
                                <th style="width:20%">
                                    Variety
                                </th>
                                <th style="width:20%">
                                    Type
                                </th>
                                <th style="width:20%">
                                    Order
                                </th>
                                <th style="width:10%">
                                    Status
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT
                                            $tbl" . "varriety_info.varriety_id,
                                            $tbl" . "crop_info.crop_name,
                                            $tbl" . "product_type.product_type,
                                            $tbl" . "varriety_info.varriety_name,
                                            $tbl" . "varriety_info.order_variety,
                                            $tbl" . "varriety_info.company_name,
                                            CASE
                                                    WHEN $tbl" . "varriety_info.type=0 THEN 'ARM'
                                                    WHEN $tbl" . "varriety_info.type=1 THEN $tbl" . "varriety_info.company_name
                                                    WHEN $tbl" . "varriety_info.type=2 THEN 'Upcoming'
                                            END as variety_type,
                                            $tbl" . "varriety_info.`status`
                                        FROM
                                            $tbl" . "varriety_info
                                            LEFT JOIN $tbl" . "crop_info ON $tbl" . "crop_info.crop_id = $tbl" . "varriety_info.crop_id
                                            LEFT JOIN $tbl" . "product_type ON $tbl" . "product_type.product_type_id = $tbl" . "varriety_info.product_type_id
                                        WHERE $tbl" . "varriety_info.del_status='0'
                                        ORDER BY  $tbl" . "varriety_info.varriety_id DESC 
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
                                    <tr class="<?php echo $rowcolor ?> pointer" id="tr_id<?php echo $i; ?>" onclick="get_rowID('<?php echo $result_array["varriety_id"] ?>', '<?php echo $i; ?>')" ondblclick="details_form();">
                                        <td>
                                            <?php  echo $i; ?>
                                        </td>
                                        <td>
                                            <?php
											echo $result_array['crop_name'];
                                            /*if ($crop_name == '') {
                                                echo $result_array['crop_name'];
                                                $crop_name = $result_array['crop_name'];
                                            } else if ($crop_name == $result_array['crop_name']) {
                                                echo "&nbsp;";
                                            } else {
                                                echo $result_array['crop_name'];
                                                $crop_name = $result_array['crop_name'];
                                            }*/
                                            ?>
                                        </td>
                                        <td>
                                            <?php
											echo $result_array['product_type'];
                                            /*if ($product_type == '') {
                                                echo $result_array['product_type'];
                                                $product_type = $result_array['product_type'];
                                            } else if ($product_type == $result_array['product_type']) {
                                                echo "&nbsp;";
                                            } else {
                                                echo $result_array['product_type'];
                                                $product_type = $result_array['product_type'];
                                            }*/
                                            ?>
                                        </td>
                                        <td><?php echo $result_array['varriety_name']; ?></td>
                                        <td><?php echo $result_array['variety_type']; ?></td>
                                        <td><?php echo $result_array['order_variety']; ?></td>
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
