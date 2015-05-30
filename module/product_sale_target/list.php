<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$tbl = _DB_PREFIX;
if ($_SESSION['user_level'] == "Zone") {
    $zone_id = "AND $tbl" . "product_purchase_order_request.zone_id='" . $_SESSION['zone_id'] . "'";
    $territory = '';
    $distributor = '';
} else if ($_SESSION['user_level'] == "Territory") {
    $zone_id = "AND $tbl" . "product_purchase_order_request.zone_id='" . $_SESSION['zone_id'] . "'";
    $territory = "AND $tbl" . "product_purchase_order_request.territory_id='" . $_SESSION['territory_id'] . "'";
    $distributor = '';
} else if ($_SESSION['user_level'] == "Distributor") {
    $zone_id = "AND $tbl" . "product_purchase_order_request.zone_id='" . $_SESSION['zone_id'] . "'";
    $territory = "AND $tbl" . "product_purchase_order_request.territory_id='" . $_SESSION['territory_id'] . "'";
    $distributor = "AND $tbl" . "product_purchase_order_request.distributor_id='" . $_SESSION['employee_id'] . "'";
} else {
    $zone_id = '';
    $territory = '';
    $distributor = '';
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
                                    Customer
                                </th>
                                <th style="width:10%">
                                    Target(Kg) 
                                </th>
                                <th style="width:10%">
                                    Value(TK) 
                                </th>
                                <th style="width:10%">
                                    Target Year 
                                </th>
<!--                                <th style="width:10%">
                                    End Date 
                                </th>-->
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT
                                        $tbl" . "product_sale_target.sale_target_id,
                                        CONCAT_WS(' - ', $tbl" . "distributor_info.customer_code, $tbl" . "distributor_info.distributor_name) AS distributor_name,
                                        $tbl" . "product_sale_target.start_date,
                                        SUM($tbl" . "product_sale_target.quantity) as quantity,
                                        SUM($tbl" . "product_sale_target.value) as total_value
                                    FROM
                                        $tbl" . "product_sale_target
                                        LEFT JOIN $tbl" . "distributor_info ON $tbl" . "distributor_info.distributor_id = $tbl" . "product_sale_target.distributor_id
                                    WHERE   
                                        $tbl" . "product_sale_target.del_status='0' AND 
                                        $tbl" . "product_sale_target.channel='Distributor'  
                                    GROUP BY $tbl" . "product_sale_target.sale_target_id
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
                                    <tr class="<?php echo $rowcolor ?> pointer" id="tr_id<?php echo $i; ?>" onclick="get_rowID('<?php echo $result_array["sale_target_id"] ?>', '<?php echo $i; ?>')" ondblclick="details_form();">
                                        <td>
                                            <?php echo $i; ?>
                                        </td>
                                        <td><?php echo $result_array['distributor_name']; ?></td>
                                        <td><?php echo $result_array['quantity']; ?></td>
                                        <td><?php echo $result_array['total_value']; ?></td>
                                        <td><?php echo $db->DB_date_convert_year($result_array['start_date']); ?></td>
                                        <!--<td><?php // echo $db->date_formate($result_array['end_date']);     ?></td>-->
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
