<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$tbl = _DB_PREFIX;

$division=$_POST['division_id'];
$zone=$_POST['zone_id'];
$territory=$_POST['territory_id'];
$zilla=$_POST['zilla_id'];
$distributor=$_POST['distributor_id'];
if(!empty($_POST['bank_id']))
{
    $bank_id="AND $tbl" . "distributor_add_payment.armalik_bank_id='".$_POST['bank_id']."'";
}
else
{
    $bank_id="";
}
if(!empty($division) && !empty($zone) && !empty($territory) && !empty($zilla) && !empty($distributor))
{
    include_once 'load_individual_party_report.php';
}
else
{
    if(!empty($division) && !empty($zone) && !empty($territory) && !empty($zilla) && empty($distributor))
    {
        $where_field="AND $tbl" . "distributor_info.zilla_id='$zilla'";
        $group_field="GROUP BY $tbl" . "distributor_info.zilla_id, $tbl" . "distributor_info.distributor_id";
        $column_caption="Distributor";
    }
    else if(!empty($division) && !empty($zone) && !empty($territory) && empty($zilla) && empty($distributor))
    {
        $where_field="AND $tbl" . "distributor_info.territory_id='$territory'";
        $group_field="GROUP BY $tbl" . "distributor_info.territory_id, $tbl" . "distributor_info.zilla_id";
        $column_caption="District";
    }
    else if(!empty($division) && !empty($zone) && empty($territory) && empty($zilla) && empty($distributor))
    {
        $where_field="AND $tbl" . "distributor_info.zone_id='$zone'";
        $group_field="GROUP BY $tbl" . "distributor_info.zone_id, $tbl" . "distributor_info.territory_id";
        $column_caption="Territory";
    }
    else if(!empty($division) && empty($zone) && empty($territory) && empty($zilla) && empty($distributor))
    {
        $where_field="AND $tbl" . "zone_info.division_id='$division'";
        $group_field="GROUP BY $tbl" . "zone_info.division_id, $tbl" . "distributor_info.zone_id";
        $column_caption="Zone";
    }
    else if(empty($division) && empty($zone) && empty($territory) && empty($zilla) && empty($distributor))
    {
        $where_field="";
        $group_field="GROUP BY $tbl" . "zone_info.division_id";
        $column_caption="Division";
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
            Name of <?php echo $column_caption;?>
        </th>
        <th style="width:5%; text-align: right;">
            Opening <br />Balance
        </th>
        <th style="width:5%; text-align: right;">
            Sales
        </th>
        <th style="width:5%; text-align: right;">
            Payment
        </th>
        <th style="width:5%; text-align: right;">
            Balance
        </th>
        <th style="width:5%; text-align: right;">
            Payment(%)of <br />Payment
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
                (
                SELECT
                    Sum($tbl" . "product_purchase_order_invoice.total_price)
                    FROM $tbl" . "product_purchase_order_invoice
                    WHERE
                    $tbl" . "product_purchase_order_invoice.read_status='0' AND
                    $tbl" . "product_purchase_order_invoice.`status`='Delivery' AND
                    $tbl" . "product_purchase_order_invoice.del_status='0' AND
                    $tbl" . "product_purchase_order_invoice.distributor_id = $tbl" . "distributor_balance.distributor_id
                ) AS sales_purchase_amt,
                (
                SELECT
                    Sum($tbl" . "distributor_add_payment.amount)
                    FROM $tbl" . "distributor_add_payment
                    WHERE $tbl" . "distributor_add_payment.`status`='Active'
                    AND $tbl" . "distributor_add_payment.distributor_id = $tbl" . "distributor_balance.distributor_id
                    $bank_id
                ) AS total_paid_amt,
                ait_division_info.division_name,
                ait_division_info.division_id,
                ait_zilla.zillanameeng
            FROM $tbl" . "distributor_balance
                LEFT JOIN ait_distributor_info ON ait_distributor_info.distributor_id = ait_distributor_balance.distributor_id
                LEFT JOIN ait_zone_info ON ait_zone_info.zone_id = ait_distributor_info.zone_id
                LEFT JOIN ait_territory_info ON ait_territory_info.territory_id = ait_distributor_info.territory_id
                LEFT JOIN ait_division_info ON ait_division_info.division_id = ait_zone_info.division_id
                LEFT JOIN ait_zilla ON ait_zilla.zillaid = ait_distributor_info.zilla_id
            WHERE
                $tbl" . "distributor_info.status='Active'
                AND $tbl" . "distributor_balance.del_status='0'
                $where_field
            $group_field
            ORDER BY
                ait_division_info.division_name,
                ait_zone_info.zone_name,
                ait_territory_info.territory_name,
                ait_distributor_info.distributor_name
                ";
//LEFT JOIN $tbl" . "product_purchase_order_invoice ON $tbl" . "product_purchase_order_invoice.distributor_id = $tbl" . "distributor_balance.distributor_id
//LEFT JOIN $tbl" . "distributor_add_payment ON $tbl" . "distributor_add_payment.distributor_id = $tbl" . "distributor_balance.distributor_id
    if ($db->open())
    {
        $result = $db->query($sql);
        $i = 1;
        while ($result_array = $db->fetchAssoc())
        {
            $CL = $CL + $result_array['due_balance'];
            $PA = $PA + $result_array['sales_purchase_amt'];
            $DA = $DA + $result_array['total_paid_amt'];
            $balance = ($result_array['due_balance'] + $result_array['sales_purchase_amt']) - $result_array['total_paid_amt'];
            $BA = $BA + $balance;
            @$pay=(($result_array['total_paid_amt']-$result_array['due_balance'])/$result_array['sales_purchase_amt']);
            $payment=($pay*100);

            ?>
            <tr class="pointer" id="tr_id" >
                <?php
                if(!empty($division) && !empty($zone) && !empty($territory) && !empty($zilla) && empty($distributor))
                {
                    $field_name= $result_array['distributor_name'];
                }
                else if(!empty($division) && !empty($zone) && !empty($territory) && empty($zilla) && empty($distributor))
                {
                    $field_name= $result_array['zillanameeng'];
                }
                else if(!empty($division) && !empty($zone) && empty($territory) && empty($zilla) && empty($distributor))
                {
                    $field_name= $result_array['territory_name'];
                }
                else if(!empty($division) && empty($zone) && empty($territory) && empty($zilla) && empty($distributor))
                {
                    $field_name= $result_array['zone_name'];
                }
                else if(empty($division) && empty($zone) && empty($territory) && empty($zilla) && empty($distributor))
                {
                    $field_name= $result_array['division_name'];
                }
                ?>
                <td><?php echo $field_name;?></td>
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

<?php
}
//include_once 'load_party_report.php';
?>
    <tfoot>
<!--    <tr>-->
<!--        <td colspan="4" style="text-align: right;">Total: </td>-->
<!--        <td style="text-align: right;">--><?php //echo $CL; ?><!--</td>-->
<!--        <td style="text-align: right;">--><?php //echo $PA; ?><!--</td>-->
<!--        <td style="text-align: right;">--><?php //echo $DA; ?><!--</td>-->
<!--        <td style="text-align: right;">--><?php //echo $BA; ?><!--</td>-->
<!--        <td style="text-align: right;">&nbsp;</td>-->
<!--    </tr>-->
    </tfoot>
    </tbody>
    </table>
        <?php include_once '../../libraries/print_page/Print_footer.php'; ?>
    </div>