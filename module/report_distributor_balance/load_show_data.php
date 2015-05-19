<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$tbl = _DB_PREFIX;


if ($_POST['division_id'] != "")
{
    $division_id = "AND $tbl" . "division_info.division_id='" . $_POST['division_id'] . "'";
}
else
{
    $division_id = "";
}

if ($_POST['zone_id'] != "")
{
    $zone_id = "AND $tbl" . "distributor_info.zone_id='" . $_POST['zone_id'] . "'";
}
else
{
    $zone_id = "";
}
if ($_POST['territory_id'] != "")
{
    $territory_id = "AND $tbl" . "distributor_info.territory_id='" . $_POST['territory_id'] . "'";
}
else
{
    $territory_id = "";
}
if ($_POST['distributor_id'] != "")
{
    $distributor_id = "AND $tbl" . "distributor_balance.distributor_id='" . $_POST['distributor_id'] . "'";
}
else
{
    $distributor_id = "";
}
if ($_POST['from_date'] != "" && $_POST['to_date'] != "")
{
    $between_pur = "AND $tbl" . "product_purchase_order_invoice.invoice_date BETWEEN '" . $db->date_formate($_POST['from_date']) . "' AND '" . $db->date_formate($_POST['to_date']) . "'";
    $between_pay = "AND $tbl" . "distributor_add_payment.payment_date BETWEEN '" . $db->date_formate($_POST['from_date']) . "' AND '" . $db->date_formate($_POST['to_date']) . "'";
    $between_date_str = "<tr>
                <th colspan='21' style='text-align: center;background: #e6e6e6;'>
                    From Date: " . $_POST['from_date'] . " To Date: " . $_POST['to_date'] . "</th>
            </tr>";
}
else
{
    $between_pur = "";
    $between_pay = "";
    $between_date_str = "";
}


?>

<a class="btn btn-small btn-success" data-original-title="" onclick="print_rpt()" style="float: right;">
    <i class="icon-print" data-original-title="Share"> </i> Print
</a>
<div id="PrintArea" style="background-color: white;" >
    <?php include_once '../../libraries/print_page/Print_header.php'; ?>
    <table class="table table-condensed table-striped table-hover table-bordered pull-left report" id="data-table">
        <thead>
            <?php echo $between_date_str; ?>
            <tr>
                <th style="width:5%">
                    Division
                </th>
                <th style="width:5%">
                    Zone
                </th>
                <th style="width:5%">
                    Territory
                </th>
                <th style="width:5%">
                    Customer
                </th>
                <th style="width:5%; text-align: right;">
                    Opening Balance
                </th>
                <th style="width:5%; text-align: right;">
                    Purchase
                </th>
                <th style="width:5%; text-align: right;">
                    Total Paid
                </th>
                <th style="width:5%; text-align: right;">
                    Balance
                </th>
                <th style="width:5%; text-align: right;">
                    Payment(%)
                </th>
            </tr>
        </thead>
        <tbody>
            <?php
            $CL = '0';
            $PA = '0';
            $DA = '0';
            $BA = '0';
            $balance = '0';
            $sql = "SELECT
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
                        $tbl" . "product_purchase_order_invoice.distributor_id = $tbl" . "distributor_balance.distributor_id $between_pur) AS sales_purchase_amt,
                        (SELECT Sum($tbl" . "distributor_add_payment.amount) FROM $tbl" . "distributor_add_payment WHERE $tbl" . "distributor_add_payment.`status`='Active' AND $tbl" . "distributor_add_payment.distributor_id = $tbl" . "distributor_balance.distributor_id $between_pay) AS total_paid_amt,
                        ait_division_info.division_name,
                        ait_division_info.division_id
                    FROM $tbl" . "distributor_balance 
                        LEFT JOIN $tbl" . "distributor_info ON $tbl" . "distributor_info.distributor_id = $tbl" . "distributor_balance.distributor_id 
                        LEFT JOIN $tbl" . "zone_info ON $tbl" . "zone_info.zone_id = $tbl" . "distributor_info.zone_id 
                        LEFT JOIN $tbl" . "territory_info ON $tbl" . "territory_info.territory_id = $tbl" . "distributor_info.territory_id 
                        INNER JOIN ait_zone_user_access ON ait_zone_user_access.zone_id = ait_zone_info.zone_id
                        INNER JOIN ait_division_info ON ait_division_info.division_id = ait_zone_user_access.division_id
                    WHERE $tbl" . "distributor_info.status='Active' AND $tbl" . "distributor_balance.del_status='0'
                        $division_id $zone_id $territory_id $distributor_id
                        " . $db->get_zone_access($tbl . "distributor_info") . "
                    GROUP BY $tbl" . "distributor_balance.distributor_id
                    ORDER BY 
                        ait_division_info.division_name,
                        ait_zone_info.zone_name,
                        ait_territory_info.territory_name,
                        ait_distributor_info.distributor_name
                        ";
                        //LEFT JOIN $tbl" . "product_purchase_order_invoice ON $tbl" . "product_purchase_order_invoice.distributor_id = $tbl" . "distributor_balance.distributor_id 
                        //LEFT JOIN $tbl" . "distributor_add_payment ON $tbl" . "distributor_add_payment.distributor_id = $tbl" . "distributor_balance.distributor_id
            if ($db->open()) {
                $result = $db->query($sql);
                $i = 1;
                while ($result_array = $db->fetchAssoc()) {
                    if ($i % 2 == 0) {
                        $rowcolor = "gradeC";
                    } else {
                        $rowcolor = "gradeA success";
                    }
                    $CL = $CL + $result_array['due_balance'];
                    $PA = $PA + $result_array['sales_purchase_amt'];
                    $DA = $DA + $result_array['total_paid_amt'];
                    $balance = ($result_array['due_balance'] + $result_array['sales_purchase_amt']) - $result_array['total_paid_amt'];
                    $BA = $BA + $balance;
                    @$pay=(($result_array['total_paid_amt']-$result_array['due_balance'])/$result_array['sales_purchase_amt']);
                    $payment=($pay*100);

                    ?>
                    <tr class="<?php echo $rowcolor; ?> pointer" id="tr_id<?php echo $i; ?>" onclick="get_rowID('<?php echo $result_array["id"] ?>', '<?php echo $i; ?>')">
                        <td><?php echo $result_array['division_name']; ?></td>
                        <td><?php echo $result_array['zone_name']; ?></td>
                        <td><?php echo $result_array['territory_name']; ?></td>
                        <td><?php echo $result_array['distributor_name']; ?></td>
                        <td style="text-align: right;"><?php echo $result_array['due_balance']; ?>&nbsp;</td>
                        <td style="text-align: right;">
                            <?php
                            if ($result_array['sales_purchase_amt'] == "")
                            {
                                echo "0";
                            }
                            else
                            {
                                echo $result_array['sales_purchase_amt'];
                            }
                            ?>&nbsp;
                        </td>
                        <td style="text-align: right;">
                            <?php
                            if ($result_array['total_paid_amt'] == "")
                            {
                                echo "0";
                            }
                            else
                            {
                                echo $result_array['total_paid_amt'];
                            }
                            ?>&nbsp;
                        </td>
                        <td style="text-align: right;"><?php echo $balance; ?>&nbsp;</td>
                        <td style="text-align: right;"><?php echo number_format($payment, 2); ?>&nbsp;</td>
                    </tr>
                    <?php
                    ++$i;
                }
            }
            ?>
        <tfoot>
            <tr>
                <td colspan="4" style="text-align: right;">Total: </td>
                <td style="text-align: right;"><?php echo $CL; ?></td>
                <td style="text-align: right;"><?php echo $PA; ?></td>
                <td style="text-align: right;"><?php echo $DA; ?></td>
                <td style="text-align: right;"><?php echo $BA; ?></td>
                <td style="text-align: right;">&nbsp;</td>
            </tr>
        </tfoot>
        </tbody>
    </table>
    <?php include_once '../../libraries/print_page/Print_footer.php'; ?>
</div>