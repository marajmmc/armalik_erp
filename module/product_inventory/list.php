<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$tbl = _DB_PREFIX;
if ($_SESSION['warehouse_id']) {
    $warehouse = "AND $tbl" . "product_inventory.warehouse_id='" . $_SESSION['warehouse_id'] . "'";
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
                                <th style="width:10%">
                                    Date
                                </th>
                                <th style="width:10%">
                                    Year
                                </th>
                                <th style="width:10%">
                                    Warehouse Name
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
                                    Short Qty(pieces)
                                </th>
                                <th style="width:10%">
                                    Access Qty(pieces)
                                </th>
                                <!--                                <th style="width:10%">-->
                                <!--                                    Sample Qty(pieces)-->
                                <!--                                </th>-->
                                <!--                                <th style="width:10%">-->
                                <!--                                    R&D Qty(pieces)-->
                                <!--                                </th>-->
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT
                                        ait_product_inventory.id,
                                        ait_product_inventory.inventory_id,
                                        ait_product_inventory.inventory_date,
                                        ait_year.year_name,
                                        ait_warehouse_info.warehouse_name,
                                        ait_crop_info.crop_name,
                                        ait_product_type.product_type,
                                        ait_varriety_info.varriety_name,
                                        ait_product_pack_size.pack_size_name,
                                        ait_product_inventory.current_stock_qunatity,
                                        ait_product_inventory.damage_quantity,
                                        ait_product_inventory.access_quantity,
                                        ait_product_inventory.sample_quantity,
                                        ait_product_inventory.rnd_quantity,
                                        ait_product_inventory.purpose,
                                        ait_distributor_info.distributor_name
                                    FROM
                                        ait_product_inventory
                                        LEFT JOIN ait_year ON ait_year.year_id = ait_product_inventory.year_id
                                        LEFT JOIN ait_warehouse_info ON ait_warehouse_info.warehouse_id = ait_product_inventory.warehouse_id
                                        LEFT JOIN ait_crop_info ON ait_crop_info.crop_id = ait_product_inventory.crop_id
                                        LEFT JOIN ait_product_type ON ait_product_type.product_type_id = ait_product_inventory.product_type_id
                                        LEFT JOIN ait_varriety_info ON ait_varriety_info.varriety_id = ait_product_inventory.varriety_id
                                        LEFT JOIN ait_product_pack_size ON ait_product_pack_size.pack_size_id = ait_product_inventory.pack_size
                                        LEFT JOIN ait_distributor_info ON ait_distributor_info.distributor_id = ait_product_inventory.distributor_id
                                    WHERE
                                        $tbl" . "product_inventory.del_status='0'
                                        AND (ait_product_inventory.damage_quantity!=0 OR ait_product_inventory.access_quantity!=0)
                                        $warehouse
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
                                    ?>
                                    <tr class="<?php echo $rowcolor ?> pointer" id="tr_id<?php echo $i; ?>" onclick="get_rowID('<?php echo $result_array["id"] ?>', '<?php echo $i; ?>')" ondblclick="details_form();">
                                        <td>
                                            <?php echo $i; ?>
                                        </td>
                                        <td><?php echo $db->date_formate($result_array['inventory_date']); ?></td>
                                        <td><?php echo $result_array['year_name']; ?></td>
                                        <td><?php echo $result_array['warehouse_name']; ?></td>
                                        <td><?php echo $result_array['crop_name']; ?></td>
                                        <td><?php echo $result_array['product_type']; ?></td>
                                        <td><?php echo $result_array['varriety_name']; ?></td>
                                        <td><?php echo $result_array['pack_size_name']; ?></td>
                                        <td><?php echo $result_array['damage_quantity']; ?></td>
                                        <td><?php echo $result_array['access_quantity']; ?></td>
                                        <!--                                        <td>--><?php //echo $result_array['sample_quantity']; ?><!--</td>-->
                                        <!--                                        <td>--><?php //echo $result_array['rnd_quantity']; ?><!--</td>-->
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
