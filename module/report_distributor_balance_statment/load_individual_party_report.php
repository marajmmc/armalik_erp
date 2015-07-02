<?php

require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$tbl = _DB_PREFIX;

$credit_limit=$db->single_data_w($tbl."distributor_credit_limit", "SUM($tbl" . "distributor_credit_limit.credit_limit) AS credit_limit", "$tbl" . "distributor_credit_limit.distributor_id='" . $distributor . "'");
$credit_limit_tk = $credit_limit['credit_limit'];
$opening_balance=$db->single_data_w($tbl."distributor_info", "$tbl" . "distributor_info.due_balance as opening_balance", "$tbl" . "distributor_info.distributor_id='" . $distributor . "'");
$opening_balance_tk=$opening_balance['opening_balance'];

$sql_balance = "SELECT
                    $tbl" . "zone_info.zone_name,
                    $tbl" . "territory_info.territory_name,
                    CONCAT_WS(' - ', $tbl" . "distributor_info.distributor_id, $tbl" . "distributor_info.customer_code, $tbl" . "distributor_info.distributor_name) AS distributor_name_with_code,
                    $tbl" . "distributor_info.distributor_name,
                    $tbl" . "distributor_balance.id,
                    $tbl" . "distributor_balance.distributor_id,
                    $tbl" . "distributor_info.due_balance,
                    (SELECT Sum($tbl" . "product_purchase_order_invoice.total_price) FROM $tbl" . "product_purchase_order_invoice WHERE $tbl" . "product_purchase_order_invoice.read_status='0' AND
                    $tbl" . "product_purchase_order_invoice.`status`='Delivery' AND
                    $tbl" . "product_purchase_order_invoice.del_status='0' AND
                    $tbl" . "product_purchase_order_invoice.distributor_id = $tbl" . "distributor_balance.distributor_id) AS sales_purchase_amt,
                    (SELECT Sum($tbl" . "distributor_add_payment.amount) FROM $tbl" . "distributor_add_payment WHERE $tbl" . "distributor_add_payment.`status`='Active' AND $tbl" . "distributor_add_payment.distributor_id = $tbl" . "distributor_balance.distributor_id $bank_id) AS total_paid_amt,
                    ait_division_info.division_name,
                    ait_division_info.division_id
                FROM $tbl" . "distributor_balance
                    LEFT JOIN $tbl" . "distributor_info ON $tbl" . "distributor_info.distributor_id = $tbl" . "distributor_balance.distributor_id
                    LEFT JOIN $tbl" . "zone_info ON $tbl" . "zone_info.zone_id = $tbl" . "distributor_info.zone_id
                    LEFT JOIN $tbl" . "territory_info ON $tbl" . "territory_info.territory_id = $tbl" . "distributor_info.territory_id
                    INNER JOIN ait_zone_user_access ON ait_zone_user_access.zone_id = ait_zone_info.zone_id
                    INNER JOIN ait_division_info ON ait_division_info.division_id = ait_zone_user_access.division_id
                WHERE
                    $tbl" . "distributor_info.status='Active'
                    AND $tbl" . "distributor_balance.del_status='0'
                    AND $tbl" . "distributor_balance.distributor_id='$distributor'
                GROUP BY $tbl" . "distributor_balance.distributor_id
                ORDER BY
                    ait_division_info.division_name,
                    ait_zone_info.zone_name,
                    ait_territory_info.territory_name,
                    ait_distributor_info.distributor_name
                    ";
if($db->open())
{
    $result_balance=$db->query($sql_balance);
    $row_balance=$db->fetchAssoc($result_balance);
    $balance = ($row_balance['due_balance'] + $row_balance['sales_purchase_amt']) - $row_balance['total_paid_amt'];
    @$pay=(($row_balance['total_paid_amt']-$row_balance['due_balance'])/$row_balance['sales_purchase_amt']);
    $payment_percentage=($pay*100);
}
?>


<a class="btn btn-small btn-success" data-original-title="" onclick="print_rpt()" style="float: right;">
    <i class="icon-print" data-original-title="Share"> </i> Print
</a>
<div id="PrintArea" style="background-color: white;" >
    <?php include_once '../../libraries/print_page/Print_header.php'; ?>
    <h5>Individual Party Report - </h5>
    <table class="table table-condensed table-striped table-hover table-bordered pull-left report" id="data-table">

        <thead>
        <tr>
            <th style="width:1%; text-align: center;">
                Credit Limit
            </th>
            <th style="width:5%; text-align: center;">
                Opening Balance
            </th>
            <th style="width:5%; text-align: center;">
                Sales
            </th>
            <th style="width:5%; text-align: center;">
                Payment
            </th>
            <th style="width:5%; text-align: center;">
                Balance
            </th>
            <th style="width:5%; text-align: center;">
                Percentage (%) of Payment
            </th>
        </tr>
        <tr>
            <th style="vertical-align: top;"> <?php echo $credit_limit_tk;?> </th>
            <th style="vertical-align: top;"> <?php echo $opening_balance_tk;?> </th>
            <th style="vertical-align: top;">
                <table class="table table-condensed table-striped table-hover table-bordered pull-left report" id="data-table">
                    <thead>
                    <tr>
                    <th style="text-align: center;">Date</th>
                    <th style="text-align: center;">Inv. No</th>
                    <th style="text-align: center;">Amount</th>
                    </tr>
                    <?php
                        $sql_sales="SELECT
                                        ait_product_purchase_order_invoice.invoice_date,
                                        ait_product_purchase_order_invoice.purchase_order_id,
                                        ait_product_purchase_order_invoice.price,
                                        ait_product_purchase_order_invoice.approved_quantity,
                                        SUM(ait_product_purchase_order_invoice.price*ait_product_purchase_order_invoice.approved_quantity) as sales_amount
                                    FROM
                                        ait_product_purchase_order_invoice
                                    WHERE
                                        ait_product_purchase_order_invoice.distributor_id='$distributor'
                                        AND ait_product_purchase_order_invoice.del_status=0
                                    GROUP BY
                                        ait_product_purchase_order_invoice.invoice_date,
                                        ait_product_purchase_order_invoice.purchase_order_id
                        ";
                        if($db->open())
                        {
                            $result_sales=$db->query($sql_sales);
                            while($row_sales=$db->fetchAssoc($result_sales))
                            {
                                ?>
                                <tr>
                                    <th><?php echo $db->date_formate($row_sales['invoice_date']);?></th>
                                    <th><?php echo substr($row_sales['purchase_order_id'],3);?></th>
                                    <th><?php echo $row_sales['sales_amount'];?></th>
                                </tr>
                                <?php
                            }
                        }
                    ?>
                    </thead>
                </table>
            </th>
            <th style="vertical-align: top;">
                <table class="table table-condensed table-striped table-hover table-bordered pull-left report" id="data-table">
                    <thead>
                    <tr>
                        <th style="text-align: center;">Date</th>
                        <th style="text-align: center;">Amount</th>
                        <th style="text-align: center;">Bank Name</th>
                    </tr>
                    <?php
                    $sql_payment="SELECT
                                        ait_distributor_add_payment.payment_date,
                                        ait_distributor_add_payment.amount,
                                        ait_bank_info.bank_name
                                    FROM
                                        ait_distributor_add_payment
                                        LEFT JOIN ait_bank_info ON ait_bank_info.bank_id = ait_distributor_add_payment.bank_id
                                    WHERE
                                        ait_distributor_add_payment.`status`='Active'
                                        AND ait_distributor_add_payment.del_status=0
                                        AND ait_distributor_add_payment.distributor_id='$distributor'
                                        $bank_id
                    ";
                    if($db->open())
                    {
                        $result_pay=$db->query($sql_payment);
                        while($row_pay=$db->fetchAssoc($result_pay))
                        {
                            ?>
                            <tr>
                                <th><?php echo $db->date_formate($row_pay['payment_date']);?></th>
                                <th><?php echo $row_pay['amount'];?></th>
                                <th><?php echo $row_pay['bank_name'];?></th>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                    </thead>
                </table>
            </th>
            <th style="vertical-align: top;"><?php echo $balance;?></th>
            <th style="vertical-align: top;"><?php echo number_format($payment_percentage,2);?></th>
        </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
    <?php include_once '../../libraries/print_page/Print_footer.php'; ?>
</div>

