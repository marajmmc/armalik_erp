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
    $division_id = "AND $tbl" . "zone_user_access.division_id='" . $_POST['division_id'] . "'";
}
else
{
    $division_id = "";
}
if ($_POST['zone_id'] != "")
{
    $zone_id = "AND $tbl" . "distributor_credit_limit.zone_id='" . $_POST['zone_id'] . "'";
}
else
{
    $zone_id = "";
}
if ($_POST['territory_id'] != "")
{
    $territory_id = "AND $tbl" . "distributor_credit_limit.territory_id='" . $_POST['territory_id'] . "'";
} else {
    $territory_id = "";
}
if ($_POST['distributor_id'] != "")
{
    $distributor_id = "AND $tbl" . "distributor_credit_limit.distributor_id='" . $_POST['distributor_id'] . "'";
}
else
{
    $distributor_id = "";
}

?>

<a class="btn btn-small btn-success" data-original-title="" onclick="print_rpt()" style="float: right;">
    <i class="icon-print" data-original-title="Share"> </i> Print
</a>
<div id="PrintArea" style="background-color: white;" >
    <?php include_once '../../libraries/print_page/Print_header.php'; ?>
    <table class="table table-condensed table-striped table-hover table-bordered pull-left report" id="data-table">

        <thead>
            <tr>
                <th style="width:1%">
                    No
                </th>
                <th style="width:5%">
                    Distributor
                </th>
                <th style="width:5%">
                    Date
                </th>
                <th style="width:5%">
                    Cheque No
                </th>
                <th style="width:5%">
                    Bank Name
                </th>
                <th style="width:5%; text-align: right;">
                    Credit limit (Tk)
                </th>
                <th style="width:5%; text-align: right;">
                    Current Balance (Tk)
                </th>
            </tr>
        </thead>
        <tbody>
            <?php
            $distributor_name = '';
            $credit_limit = '0';
            $balance = '0';
            $BA = '0';
             $sql = "SELECT
                        CONCAT_WS(' - ', $tbl" . "distributor_info.customer_code, $tbl" . "distributor_info.distributor_name) AS distributor_name,
                        $tbl" . "distributor_credit_limit.id,
                        $tbl" . "distributor_credit_limit.credit_limit_id,
                        SUM($tbl" . "distributor_credit_limit.credit_limit) AS credit_limit,
                        $tbl" . "distributor_credit_limit.check_no,
                        $tbl" . "distributor_credit_limit.amount,
                        $tbl" . "distributor_credit_limit.entry_date,
                        $tbl" . "bank_info.bank_name,
                        $tbl" . "bank_branch_info.branch_name,
                        $tbl" . "distributor_info.due_balance,
                        SUM($tbl" . "product_purchase_order_invoice.total_price) AS sales_purchase_amt,
                        SUM($tbl" . "distributor_add_payment.amount) AS total_paid_amt,
                        $tbl" . "division_info.division_name
                    FROM
                        $tbl" . "distributor_credit_limit
                        LEFT JOIN $tbl" . "zone_info ON $tbl" . "zone_info.zone_id = $tbl" . "distributor_credit_limit.zone_id
                        LEFT JOIN $tbl" . "territory_info ON $tbl" . "territory_info.territory_id = $tbl" . "distributor_credit_limit.territory_id
                        LEFT JOIN $tbl" . "distributor_info ON $tbl" . "distributor_info.distributor_id = $tbl" . "distributor_credit_limit.distributor_id
                        LEFT JOIN $tbl" . "bank_info ON $tbl" . "bank_info.bank_id = $tbl" . "distributor_credit_limit.bank_id
                        LEFT JOIN $tbl" . "bank_branch_info ON $tbl" . "bank_branch_info.branch_id = $tbl" . "distributor_credit_limit.branch_id
                        LEFT JOIN $tbl" . "product_purchase_order_invoice ON $tbl" . "product_purchase_order_invoice.distributor_id = $tbl" . "distributor_credit_limit.distributor_id
                        LEFT JOIN $tbl" . "distributor_add_payment ON $tbl" . "distributor_add_payment.distributor_id = $tbl" . "distributor_credit_limit.distributor_id
                        LEFT JOIN ait_zone_user_access ON ait_zone_user_access.zone_id = ait_zone_info.zone_id
                        LEFT JOIN ait_division_info ON ait_division_info.division_id = ait_zone_user_access.division_id
                    WHERE $tbl" . "distributor_credit_limit.del_status='0'
                    $division_id $zone_id $territory_id $distributor_id
                    GROUP BY $tbl" . "distributor_credit_limit.distributor_id
                        
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
                    $credit_limit = $credit_limit + $result_array['credit_limit'];
                    $balance = ($result_array['due_balance'] + $result_array['sales_purchase_amt']) - $result_array['total_paid_amt'];
                    $BA=$BA+$balance;
                    ?>
                    <tr class="<?php echo $rowcolor; ?> pointer" id="tr_id<?php echo $i; ?>" onclick="get_rowID('<?php echo $result_array["id"] ?>', '<?php echo $i; ?>')">
                        <td>
                            <?php echo $i; ?>
                        </td>
                        <td>
                            <?php  
                            if ($distributor_name == '') {
                                echo $result_array['distributor_name'];
                                $distributor_name = $result_array['distributor_name'];
                                //$currentDate = $preDate;
                            } else if ($distributor_name == $result_array['distributor_name']) {
                                //exit;
                                echo "&nbsp;";
                            } else {
                                echo $result_array['distributor_name'];
                                $distributor_name = $result_array['distributor_name'];
                            }
                            ?>
                        </td>
                        <td><?php echo $db->date_formate($result_array['entry_date']); ?></td>
                        <td><?php echo $result_array['check_no']; ?></td>
                        <td><?php echo $result_array['bank_name']; ?></td>
                        <td style="text-align: right;"><?php echo number_format($result_array['credit_limit'],2); ?></td>
                        <td style="text-align: right;"><?php echo number_format($balance,2); ?></td>
                    </tr>
                    <?php
                    ++$i;
                }
            }
            ?>
        <tfoot>
            <tr>
                <td colspan="5" style="text-align: right;">Total: </td>
                <td style="text-align: right;"><?php echo number_format($credit_limit, 2) ?></td>
                <td style="text-align: right;"><?php echo number_format($BA, 2) ?></td>
            </tr>
<!--            <tr>
                <td colspan="15" style="text-align: right;">In word: <?php // echo $db->number_convert_inword($BA) ?> Only</td>
            </tr>-->
        </tfoot>
        </tbody>
    </table>
    <?php include_once '../../libraries/print_page/Print_footer.php'; ?>
</div>