<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$tbl = _DB_PREFIX;
if ($_SESSION['warehouse_id']) {
    $warehouse = "AND $tbl" . "warehouse_info.warehouse_id='" . $_SESSION['warehouse_id'] . "'";
} else {
    $warehouse = "";
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
                                    Sl No
                                </th>
                                <!--                                <th style="width:5%">-->
                                <!--                                    p id-->
                                <!--                                </th>-->
                                <th style="width:10%">
                                    Warehouse Name
                                </th>
                                <th style="width:10%">
                                    Crop
                                </th>
                                <th style="width:10%">
                                    Type
                                </th>
                                <th style="width:10%">
                                    Variety
                                </th>
                                <th style="width:5%" class="hidden-phone">
                                    Pack Size(gm)
                                </th>
                                <th style="width:5%" class="hidden-phone">
                                    Qty(pieces)
                                </th>
                                <th style="width:5%" class="hidden-phone">
                                    Total
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT
                                        $tbl" . "product_purchase_info.id,
                                        $tbl" . "product_purchase_info.product_id,
                                        $tbl" . "product_purchase_info.product_type,
                                        $tbl" . "product_purchase_info.pack_size,
                                        SUM($tbl" . "product_purchase_info.quantity) AS quantity,
                                        $tbl" . "product_purchase_info.mfg_date,
                                        $tbl" . "product_purchase_info.exp_date,
                                        $tbl" . "product_purchase_info.`status`,
                                        $tbl" . "warehouse_info.warehouse_name,
                                        $tbl" . "product_pack_size.pack_size_name,
                                        $tbl" . "crop_info.crop_name,
                                        $tbl" . "product_type.product_type as ptype,
                                        $tbl" . "varriety_info.varriety_name
                                    FROM
                                        $tbl" . "product_purchase_info
                                        LEFT JOIN $tbl" . "warehouse_info ON $tbl" . "warehouse_info.warehouse_id = $tbl" . "product_purchase_info.warehouse_id
                                        LEFT JOIN $tbl" . "crop_info ON $tbl" . "crop_info.crop_id = $tbl" . "product_purchase_info.crop_id
                                        LEFT JOIN $tbl" . "product_type ON $tbl" . "product_type.product_type_id = $tbl" . "product_purchase_info.product_type_id
                                        LEFT JOIN $tbl" . "varriety_info ON $tbl" . "varriety_info.varriety_id = $tbl" . "product_purchase_info.varriety_id
                                        LEFT JOIN $tbl" . "product_pack_size ON $tbl" . "product_pack_size.pack_size_id = $tbl" . "product_purchase_info.pack_size
                                    WHERE
                                        $tbl" . "product_purchase_info.del_status=0
                                    GROUP BY
                                        $tbl" . "product_purchase_info.warehouse_id,
                                        $tbl" . "product_purchase_info.crop_id,
                                        $tbl" . "product_purchase_info.product_type_id,
                                        $tbl" . "product_purchase_info.varriety_id,
                                        $tbl" . "product_purchase_info.pack_size
                                    ORDER BY $tbl" . "product_purchase_info.id DESC
                            ";
                            if ($db->open()) {
                                $result = $db->query($sql);
                                $i = 1;
                                $total=0;
                                while ($result_array = $db->fetchAssoc()) {
                                    if ($i % 2 == 0) {
                                        $rowcolor = "gradeC";
                                    } else {
                                        $rowcolor = "gradeA success";
                                    }
                                    $total=($result_array['quantity']*$result_array['pack_size_name'])/1000;
                                    ?>
                                    <tr class="<?php echo $rowcolor ?> pointer" id="tr_id<?php echo $i; ?>" onclick="get_rowID('<?php echo $result_array["product_id"] ?>', '<?php echo $i; ?>')" ondblclick="details_form();">
                                        <td>
                                            <?php echo $i; ?>
                                        </td>
                                        <!--                                        <td>--><?php //echo $result_array['product_id']; ?><!--</td>-->
                                        <td><?php echo $result_array['warehouse_name'] ?></td>
                                        <td><?php echo $result_array['crop_name']; ?></td>
                                        <td><?php echo $result_array['ptype']; ?></td>
                                        <td><?php echo $result_array['varriety_name']; ?></td>
                                        <td class="hidden-phone"><?php echo $result_array['pack_size_name']; ?> gm</td>
                                        <td class="hidden-phone"><?php echo $result_array['quantity']; ?></td>
                                        <td class="hidden-phone"><?php echo $total; ?> kg</td>
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
