<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$tbl = _DB_PREFIX;
if($_SESSION['warehouse_id']){
    $warehouse="AND $tbl" . "product_transfer.from_warehouse_id='".$_SESSION['warehouse_id']."'";
}else{
    $warehouse="";
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
                                <th style="width:10%">
                                    From Warehouse
                                </th>
                                <th style="width:10%">
                                    To Warehouse
                                </th>
                                <th style="width:10%">
                                    Date
                                </th>
                                <th style="width:10%">
                                    Crop
                                </th>
                                <th style="width:10%">
                                    Product Type 
                                </th>
                                <th style="width:10%">
                                    Variety 
                                </th>
                                <th style="width:10%">
                                    Pack Size(gm) 
                                </th>
                                <th style="width:10%">
                                    Qty(pieces) 
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                   $sql = "SELECT
                                        wf.warehouse_name as fromwarehouse,
                                        wt.warehouse_name as towarehouse,
                                        $tbl" . "crop_info.crop_name,
                                        $tbl" . "varriety_info.varriety_name,
                                        $tbl" . "product_pack_size.pack_size_name,
                                        $tbl" . "product_transfer.to_warehouse_id,
                                        $tbl" . "product_transfer.quantity,
                                        $tbl" . "product_transfer.transfer_id,
                                        $tbl" . "product_transfer.transfer_date,
                                        $tbl" . "product_type.product_type
                                    FROM
                                        $tbl" . "product_transfer
                                        LEFT JOIN $tbl"."warehouse_info as wf ON wf.warehouse_id = $tbl"."product_transfer.from_warehouse_id
                                        LEFT JOIN $tbl"."warehouse_info as wt ON wt.warehouse_id = $tbl"."product_transfer.to_warehouse_id 
                                        LEFT JOIN $tbl" . "crop_info ON $tbl" . "crop_info.crop_id = $tbl" . "product_transfer.crop_id
                                        LEFT JOIN $tbl" . "varriety_info ON $tbl" . "varriety_info.varriety_id = $tbl" . "product_transfer.varriety_id
                                        LEFT JOIN $tbl" . "product_info ON $tbl" . "product_info.pack_size = $tbl" . "product_transfer.pack_size
                                        LEFT JOIN $tbl" . "product_pack_size ON $tbl" . "product_pack_size.pack_size_id = $tbl" . "product_transfer.pack_size
                                        LEFT JOIN $tbl" . "product_type ON $tbl" . "product_type.product_type_id = $tbl" . "product_transfer.product_type_id
                                    WHERE $tbl" . "product_transfer.del_status='0' $warehouse
                                    GROUP BY $tbl" . "product_transfer.transfer_id
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
                                    <tr class="<?php echo $rowcolor ?> pointer" id="tr_id<?php echo $i; ?>" onclick="get_rowID('<?php echo $result_array["transfer_id"] ?>', '<?php echo $i; ?>')" ondblclick="details_form();">
                                        <td>
                                            <?php echo $i; ?>
                                        </td>
                                        <td><?php echo $result_array['fromwarehouse']; ?></td>
                                        <td><?php echo $result_array['towarehouse']; ?></td>
                                        <td><?php echo $db->date_formate($result_array['transfer_date']); ?></td>
                                        <td><?php echo $result_array['crop_name']; ?></td>
                                        <td><?php echo $result_array['product_type']; ?></td>
                                        <td><?php echo $result_array['varriety_name']; ?></td>
                                        <td><?php echo $result_array['pack_size_name']; ?></td>
                                        <td><?php echo $result_array['quantity']; ?></td>
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
