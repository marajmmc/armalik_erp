<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$tbl = _DB_PREFIX;
//if ($_SESSION['user_level'] == "Zone") {
//    $zone_id = "AND $tbl" . "product_purchase_order_challan.zone_id='" . $_SESSION['zone_id'] . "'";
//    $territory = '';
//    $distributor = '';
//} else if ($_SESSION['user_level'] == "Territory") {
//    $zone_id = "AND $tbl" . "product_purchase_order_challan.zone_id='" . $_SESSION['zone_id'] . "'";
//    $territory = "AND $tbl" . "product_purchase_order_challan.territory_id='" . $_SESSION['territory_id'] . "'";
//    $distributor = '';
//} else if ($_SESSION['user_level'] == "Distributor") {
//    $zone_id = "AND $tbl" . "product_purchase_order_challan.zone_id='" . $_SESSION['zone_id'] . "'";
//    $territory = "AND $tbl" . "product_purchase_order_challan.territory_id='" . $_SESSION['territory_id'] . "'";
//    $distributor = "AND $tbl" . "product_purchase_order_challan.distributor_id='" . $_SESSION['employee_id'] . "'";
//} else {
//    $zone_id = '';
//    $territory = '';
//    $distributor = '';
//}

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
                            <th style="width:5%">
                                Challan No
                            </th>
                            <th style="width:5%">
                                Date
                            </th>
                            <th style="width:10%">
                                Customer
                            </th>
                            <th style="width:5%">
                                Total Qty(pieces)
                            </th>
                            <th style="width:5%">
                                Total Value
                            </th>
                            <th style="width:5%">
                                Status
                            </th>
                            <th style="width:1%">
                                Action
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        
                        $sql = "SELECT
                                        $tbl" . "product_purchase_order_challan.id,
                                        $tbl" . "product_purchase_order_challan.invoice_id,
                                        $tbl" . "product_purchase_order_challan.challan_id,
                                        $tbl" . "product_purchase_order_challan.purchase_order_id,
                                        $tbl" . "product_purchase_order_challan.challan_date,
                                        Sum(ait_product_purchase_order_challan.quantity) AS quantity,
                                        Sum(ait_product_purchase_order_challan.total_price) AS total_price,
                                        CONCAT_WS(' - ', ait_distributor_info.customer_code, ait_distributor_info.distributor_name) AS distributor_name,
                                        $tbl" . "product_purchase_order_challan.`status`,
                                        $tbl" . "product_purchase_order_challan.del_status
                                    FROM
                                        $tbl" . "product_purchase_order_challan
                                        LEFT JOIN $tbl" . "distributor_info ON $tbl" . "distributor_info.distributor_id = $tbl" . "product_purchase_order_challan.distributor_id
                                    WHERE $tbl" . "product_purchase_order_challan.del_status='0' AND
                                         $tbl" . "product_purchase_order_challan.status='Received' AND
                                        $tbl" . "product_purchase_order_challan.warehouse_id!=''

                                    GROUP BY $tbl" . "product_purchase_order_challan.challan_id
                                    ORDER BY $tbl" . "product_purchase_order_challan.challan_id DESC
                        ";
                        
                        //                              AND $tbl" . "product_purchase_order_challan.warehouse_id!=''
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
                                <tr class=" pointer " id="tr_id<?php echo $i; ?>" onclick="get_rowID('<?php echo $result_array["challan_id"] ?>', '<?php echo $i; ?>')" ondblclick="details_form();">
                                    <td>
                                        <?php echo $i; ?>
                                    </td>
                                    <td><?php echo $result_array['challan_id']; ?></td>
                                    <td><?php echo $db->date_formate($result_array['challan_date']); ?></td>
                                    <td><?php echo $result_array['distributor_name']; ?></td>
                                    <td><?php echo $result_array['quantity']; ?></td>
                                    <td><?php echo $result_array['total_price']; ?></td>
                                    <td><?php echo $result_array['status']; ?></td>
                                    <td>
                                        <a class="btn btn-small btn-warning2" data-original-title="" onclick="po_delete_fnc('<?php echo $result_array["invoice_id"] ?>', '<?php echo $i ?>');">
                                            <i class="icon-white icon-trash" data-original-title="Share"> </i>
                                        </a>
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