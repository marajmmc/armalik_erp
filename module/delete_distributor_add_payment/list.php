<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$tbl = _DB_PREFIX;
if ($_SESSION['user_level'] == "Zone") {
    $zone_id = "AND $tbl" . "distributor_add_payment.zone_id='" . $_SESSION['zone_id'] . "'";
    $territory = '';
    $distributor = '';
} else if ($_SESSION['user_level'] == "Territory") {
    $zone_id = "AND $tbl" . "distributor_add_payment.zone_id='" . $_SESSION['zone_id'] . "'";
    $territory = "AND $tbl" . "distributor_add_payment.territory_id='" . $_SESSION['territory_id'] . "'";
    $distributor = '';
} else if ($_SESSION['user_level'] == "Distributor") {
    $zone_id = "AND $tbl" . "distributor_add_payment.zone_id='" . $_SESSION['zone_id'] . "'";
    $territory = "AND $tbl" . "distributor_add_payment.territory_id='" . $_SESSION['territory_id'] . "'";
    $distributor = "AND $tbl" . "distributor_add_payment.distributor_id='" . $_SESSION['employee_id'] . "'";
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
                                    Payment Date
                                </th>
                                <th style="width:20%">
                                    Customer
                                </th>
                                <th style="width:20%">
                                    Bank Name
                                </th>
                                <th style="width:10%">
                                    Payment Type
                                </th>
                                <th style="width:10%">
                                    Amount
                                </th>
                                <th style="width:10%">
                                    Payment Number
                                </th>
                                <th style="width:10%">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $status="";
                            $sql = "SELECT
                                        $tbl" . "distributor_add_payment.id,
                                        $tbl" . "distributor_add_payment.payment_id,
                                        $tbl" . "distributor_add_payment.payment_date,
                                        CONCAT_WS(' - ', $tbl" . "distributor_info.customer_code, $tbl" . "distributor_info.distributor_name) AS distributor_name,
                                        $tbl" . "bank_info.bank_name,
                                        $tbl" . "distributor_add_payment.payment_type,
                                        $tbl" . "distributor_add_payment.cheque_no,
                                        $tbl" . "distributor_add_payment.status,
                                        $tbl" . "distributor_add_payment.amount
                                    FROM
                                        $tbl" . "distributor_add_payment
                                        LEFT JOIN $tbl" . "distributor_info ON $tbl" . "distributor_info.distributor_id = $tbl" . "distributor_add_payment.distributor_id
                                        LEFT JOIN $tbl" . "bank_info ON $tbl" . "bank_info.bank_id = $tbl" . "distributor_add_payment.bank_id
                                    WHERE $tbl" . "distributor_add_payment.del_status='0' $zone_id $territory $distributor  ".$db->get_zone_access($tbl. "distributor_add_payment")." 
                                    ORDER BY $tbl" . "distributor_add_payment.payment_date DESC
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
                                    if($result_array['status']=='Active'){
                                        $status="Receipt";
                                    }else{
                                        $status=$result_array['status'];
                                    }
                                    ?>
                                    <tr class="<?php echo $rowcolor ?>" id="tr_id<?php echo $i; ?>" onclick="get_rowID('<?php echo $result_array["payment_id"] ?>', '<?php echo $i; ?>')" ondblclick="details_form();">
                                        <td>
                                            <?php echo $i; ?>
                                        </td>
                                        <td><?php echo $db->date_formate($result_array['payment_date']); ?></td>
                                        <td><?php echo $result_array['distributor_name']; ?></td>
                                        <td><?php echo $result_array['bank_name']; ?></td>
                                        <td><?php echo $result_array['payment_type']; ?></td>
                                        <td><?php echo $result_array['amount']; ?></td>
                                        <td><?php echo $result_array['cheque_no']; ?></td>
                                        <td>
                                            <input type="button" value="Del" />
<!--                                            <a class="btn btn-small btn-warning2" onclick='payment_delete_fnc("--><?php //echo $result_array['id'] ?><!--", "--><?php //echo $i ?><!--");'>-->
<!--                                                <i class="icon-white icon-trash" data-original-title="Share"> </i>-->
<!--                                            </a>-->
<!--                                            <a class="btn btn-small btn-warning2" data-original-title="" onclick="payment_delete_fnc('--><?php //echo $result_array["id"] ?><!--', '--><?php //echo $i ?><!--');">-->
<!--                                                <i class="icon-white icon-trash" data-original-title="Share"> </i> -->
<!--                                            </a>-->
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
