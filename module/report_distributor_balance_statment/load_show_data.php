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
    if(!empty($division) && !empty($zone) && !empty($territory) && !empty($zilla) && !empty($distributor))
    {
        $where_field="AND $tbl" . "distributor_info.distributor_id='$distributor'";
        $column_caption="Distributor";
        $elm_id="distributor_id";
        $elm_name="distributor_name";
        $group_by="GROUP BY ";
    }
    else if(!empty($division) && !empty($zone) && !empty($territory) && !empty($zilla) && empty($distributor))
    {
        $where_field="AND $tbl" . "distributor_info.zilla_id='$zilla'";
        $column_caption="Distributor";
        $elm_id="distributor_id";
        $elm_name="distributor_name";
        $group_by="";
    }
    else if(!empty($division) && !empty($zone) && !empty($territory) && empty($zilla) && empty($distributor))
    {
        $where_field="AND $tbl" . "distributor_info.territory_id='$territory'";
        $column_caption="District";
        $elm_id="zilla_id";
        $elm_name="zillanameeng";
        $group_by="";
    }
    else if(!empty($division) && !empty($zone) && empty($territory) && empty($zilla) && empty($distributor))
    {
        $where_field="AND $tbl" . "distributor_info.zone_id='$zone'";
        $column_caption="Territory";
        $elm_id="territory_id";
        $elm_name="territory_name";
        $group_by="";
    }
    else if(!empty($division) && empty($zone) && empty($territory) && empty($zilla) && empty($distributor))
    {
        $where_field="AND $tbl" . "division_info.division_id='$division'";
        $column_caption="Zone";
        $elm_id="zone_id";
        $elm_name="zone_name";
        $group_by="GROUP BY
        ait_distributor_add_payment.armalik_bank_id,
        ait_division_info.division_id,
        ait_distributor_info.zone_id";
    }
    else if(empty($division) && empty($zone) && empty($territory) && empty($zilla) && empty($distributor))
    {
        $where_field="";
        $column_caption="Division";
        $elm_id="division_id";
        $elm_name="division_name";
        $group_by="GROUP BY
        ait_distributor_add_payment.armalik_bank_id,
        ait_division_info.division_id";
    }
}
$sql="SELECT
            ait_division_info.division_id,
            ait_division_info.division_name,
            ait_distributor_info.zone_id,
            ait_zone_info.zone_name,
            ait_distributor_info.territory_id,
            ait_territory_info.territory_name,
            ait_distributor_info.zilla_id,
            ait_zilla.zillanameeng,
            ait_distributor_info.distributor_id,
            ait_distributor_info.distributor_name,
            ait_distributor_info.due_balance AS opening_balance,
            ait_distributor_add_payment.armalik_bank_id,
            SUM(ait_distributor_add_payment.amount) AS arm_bank_payment_amount,
            (
                SELECT SUM(ait_product_purchase_order_invoice.price * ait_product_purchase_order_invoice.approved_quantity)
                FROM ait_product_purchase_order_invoice
                    LEFT JOIN ait_distributor_info ON ait_distributor_info.distributor_id=ait_product_purchase_order_invoice.distributor_id
                    LEFT JOIN ait_zone_info sale_azi ON sale_azi.zone_id=ait_product_purchase_order_invoice.zone_id
                    LEFT JOIN ait_division_info sale_adi ON sale_adi.division_id=sale_azi.division_id
                WHERE
                    sale_adi.division_id=ait_division_info.division_id
                    AND ait_product_purchase_order_invoice.zone_id=ait_distributor_info.zone_id

            ) AS sales_amount
        FROM
            ait_distributor_info
            LEFT JOIN ait_zone_info ON ait_zone_info.zone_id = ait_distributor_info.zone_id
            LEFT JOIN ait_division_info ON ait_division_info.division_id = ait_zone_info.division_id
            LEFT JOIN ait_territory_info ON ait_territory_info.territory_id = ait_distributor_info.territory_id
            LEFT JOIN ait_zilla ON ait_zilla.zillaid = ait_distributor_info.zilla_id
            INNER JOIN ait_distributor_add_payment ON ait_distributor_add_payment.distributor_id = ait_distributor_info.distributor_id
        WHERE
            ait_distributor_info.status='Active' AND ait_distributor_info.del_status=0
            $where_field
            $group_by";
if($db->open())
{
    $result=$db->query($sql);
    $records=array();
    while($row=$db->fetchAssoc($result))
    {
        $records[$row[$elm_id]]['column_name']=$row[$elm_name];
        $records[$row[$elm_id]]['opening_balance']=$row['opening_balance'];
        $records[$row[$elm_id]]['sales_amount']=$row['sales_amount'];
        $records[$row[$elm_id]]['armalik_bank_account'][$row['armalik_bank_id']]['arm_bank_payment_amount']=$row['arm_bank_payment_amount'];
    }
}

$sql_bank="SELECT
                ait_bank_info.bank_id,
                ait_bank_info.bank_name
            FROM
                ait_bank_info
            WHERE
                ait_bank_info.status='Active'
                AND ait_bank_info.del_status=0
                AND ait_bank_info.channel=1
";

if($db->open())
{
    $result_bank=$db->query($sql_bank);
    $banks=array();
    while($row_bank=$db->fetchAssoc($sql_bank))
    {
        $banks[$row_bank['bank_id']]['bank_name']=$row_bank['bank_name'];
    }
}


//echo "<pre>";
//print_r($records);
//echo "</pre>";
//
//die();
    ?>
<a class="btn btn-small btn-success" data-original-title="" onclick="print_rpt()" style="float: right;">
    <i class="icon-print" data-original-title="Share"> </i> Print
</a>
<div id="PrintArea" style="background-color: white;" >
    <?php include_once '../../libraries/print_page/Print_header.php'; ?>
    <table class="table table-condensed table-striped table-hover table-bordered pull-left report" id="data-table">
        <thead>
        <tr>
            <th style="width:5%" rowspan="2">
                Name of <?php echo $column_caption;?>
            </th>
            <th style="width:5%; text-align: center;" rowspan="2">
                Opening <br />Balance
            </th>
            <th style="width:5%; text-align: center;" rowspan="2">
                Sales
            </th>
            <th style="width:5%; text-align: center;" colspan="<?php echo sizeof($banks);?>">
                ARM Bank Account
            </th>
            <th style="width:5%; text-align: center;" rowspan="2">
                Payment
            </th>
            <th style="width:5%; text-align: center;" rowspan="2">
                Balance
            </th>
            <th style="width:5%; text-align: center;" rowspan="2">
                Percentage(%)of <br />Payment
            </th>
        </tr>
        <tr>
            <?php
            foreach($banks as $bank)
            {
                ?>
            <th style="text-align: center;"><?php echo $bank['bank_name'];?></th>
            <?php
            }
            ?>
        </tr>
        </thead>
        <tbody>
        <?php
        $balance=0;
        foreach($records as $data)
        {
            ?>
            <tr>
                <th><?php echo $data['column_name'];?></th>
                <th style="text-align: center;"><?php echo $data['opening_balance'];?></th>
                <th style="text-align: center;"><?php echo $data['sales_amount'];?></th>
                <?php
                $payment_amount=0;
                $amr_bank_amount=0;
                foreach($banks as $bank_id=>$bank)
                {
                    if(isset($data['armalik_bank_account'][$bank_id]['arm_bank_payment_amount']))
                    {
                        $amr_bank_amount=$data['armalik_bank_account'][$bank_id]['arm_bank_payment_amount'];
                    }
                    else
                    {
                        $amr_bank_amount=0;
                    }
                    ?>
                    <th style="text-align: center;"><?php echo $amr_bank_amount;?></th>
                    <?php
                    $payment_amount+=$amr_bank_amount;
                }

                $balance=(($data['opening_balance']+$data['sales_amount'])-$payment_amount);

                ?>
                <th style="text-align: center;"><?php echo $payment_amount;?></th>
                <th style="text-align: center;"><?php echo $balance;?></th>
                <th style="text-align: center;"><?php echo $payment_amount;?></th>
            </tr>
            <?php
        }
        ?>
        </tbody>
    </table>
    <?php include_once '../../libraries/print_page/Print_footer.php'; ?>
</div>