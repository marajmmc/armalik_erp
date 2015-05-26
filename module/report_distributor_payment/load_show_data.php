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
if ($_POST['zone_id'] != "") {
    $zone_id = "AND $tbl" . "distributor_add_payment.zone_id='" . $_POST['zone_id'] . "'";
} else {
    $zone_id = "";
}
if ($_POST['territory_id'] != "") {
    $territory_id = "AND $tbl" . "distributor_add_payment.territory_id='" . $_POST['territory_id'] . "'";
} else {
    $territory_id = "";
}
if ($_POST['distributor_id'] != "") {
    $distributor_id = "AND $tbl" . "distributor_add_payment.distributor_id='" . $_POST['distributor_id'] . "'";
} else {
    $distributor_id = "";
}
if ($_POST['bank_id'] != "") {
    $bank_id = "AND $tbl" . "distributor_add_payment.bank_id='" . $_POST['bank_id'] . "'";
} else {
    $bank_id = "";
}
if ($_POST['from_date'] != "" && $_POST['to_date'] != "") {
    $between = "AND $tbl" . "distributor_add_payment.payment_date BETWEEN '" . $db->date_formate($_POST['from_date']) . "' AND '" . $db->date_formate($_POST['to_date']) . "'";
} else {
    $between = "";
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
                <th style="width:5%">
                    Date
                </th>
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
                <th style="width:5%">
                    Payment Type
                </th>
                <th style="width:5%">
                    Cheque No
                </th>
                <th style="width:5%">
                    Bank Name
                </th>
                <th style="width:5%; text-align: right;">
                    Payment 
                </th>
            </tr>
        </thead>
        <tbody>
            <?php
            $TA = '0';
            $sql = "SELECT
                        $tbl" . "distributor_add_payment.id,
                        $tbl" . "distributor_add_payment.payment_id,
                        $tbl" . "distributor_add_payment.payment_date,
                        $tbl" . "zone_info.zone_name,
                        $tbl" . "territory_info.territory_name,
                        CONCAT_WS(' - ', $tbl" . "distributor_info.customer_code, $tbl" . "distributor_info.distributor_name) AS distributor_name_width_code,
                        $tbl" . "distributor_info.distributor_name,
                        $tbl" . "distributor_add_payment.payment_type,
                        $tbl" . "distributor_add_payment.amount,
                        $tbl" . "distributor_add_payment.cheque_no,
                        $tbl" . "bank_info.bank_name,
                        $tbl" . "division_info.division_name
                    FROM
                        $tbl" . "distributor_add_payment
                        LEFT JOIN $tbl" . "zone_info ON $tbl" . "zone_info.zone_id = $tbl" . "distributor_add_payment.zone_id
                        LEFT JOIN $tbl" . "territory_info ON $tbl" . "territory_info.territory_id = $tbl" . "distributor_add_payment.territory_id
                        LEFT JOIN $tbl" . "distributor_info ON $tbl" . "distributor_info.distributor_id = $tbl" . "distributor_add_payment.distributor_id
                        LEFT JOIN $tbl" . "bank_info ON $tbl" . "bank_info.bank_id = $tbl" . "distributor_add_payment.bank_id
                        LEFT JOIN ait_zone_user_access ON ait_zone_user_access.zone_id = ait_zone_info.zone_id
                        LEFT JOIN ait_division_info ON ait_division_info.division_id = ait_zone_user_access.division_id
                    WHERE
                        $tbl" . "distributor_add_payment.del_status='0' AND $tbl" . "distributor_add_payment.status='Active'
                        $zone_id $territory_id $distributor_id $between $division_id
                        ".$db->get_zone_access($tbl. "distributor_info")."
                        $bank_id
            ";
            if ($db->open()) {
                $result = $db->query($sql);
                $i = 1;
                while ($result_array = $db->fetchAssoc()) {
                    if ($i % 2 == 0)
                    {
                        $rowcolor = "gradeC";
                    }
                    else
                    {
                        $rowcolor = "gradeA success";
                    }
                    $TA = $TA + $result_array['amount'];
                   
                    ?>
                    <tr class="<?php echo $rowcolor; ?> pointer" id="tr_id<?php echo $i; ?>" onclick="get_rowID('<?php echo $result_array["id"] ?>', '<?php echo $i; ?>')">
                        <td><?php echo $db->date_formate($result_array['payment_date']); ?></td>
                        <td><?php echo $result_array['division_name']; ?></td>
                        <td><?php echo $result_array['zone_name']; ?></td>
                        <td><?php echo $result_array['territory_name']; ?></td>
                        <td><?php echo $result_array['distributor_name']; ?></td>
                        <td><?php echo $result_array['payment_type']; ?></td>
                        <td><?php echo $result_array['cheque_no']; ?></td>
                        <td><?php echo $result_array['bank_name']; ?></td>
                        <td style="text-align: right;"><?php echo @number_format($result_array['amount']); ?></td>
                    </tr>
                    <?php
                    ++$i;
                }
            }
            ?>
        <tfoot>
            <tr>
                <td colspan="8" style="text-align: right;">Total: </td>
                <td style="text-align: right;"><?php echo @number_format($TA) ?></td>
            </tr>
            <tr>
                <td colspan="15" style="text-align: right;">In word: <?php echo $db->number_convert_inword($TA) ?> Only</td>
            </tr>
        </tfoot>
        </tbody>
    </table>
    <?php include_once '../../libraries/print_page/Print_footer.php'; ?>
</div>