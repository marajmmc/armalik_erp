<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$tbl = _DB_PREFIX;

if(!empty($_POST['division_id']))
{
    $division="AND ait_division_info.division_id='".$_POST['division_id']."'";
}
else
{
    $division="";
}

if(!empty($_POST['zone_id']))
{
    $zone="AND ait_product_purchase_order_invoice.zone_id='".$_POST['zone_id']."'";
}
else
{
    $zone="";
}

if(!empty($_POST['territory_id']))
{
    $territory="AND ait_product_purchase_order_invoice.territory_id='".$_POST['territory_id']."'";
}
else
{
    $territory="";
}

if(!empty($_POST['zilla_id']))
{
    $zilla="AND ait_product_purchase_order_invoice.zilla_id='".$_POST['zilla_id']."'";
}
else
{
    $zilla="";
}

if(!empty($_POST['distributor_id']))
{
    $distributor="AND ait_product_purchase_order_invoice.distributor_id='".$_POST['distributor_id']."'";
}
else
{
    $distributor="";
}

if(!empty($_POST['from_date']) && !empty($_POST['to_date']))
{
    $between="AND ait_product_purchase_order_invoice.invoice_date BETWEEN '".$db->date_formate($_POST['from_date'])."' AND '".$db->date_formate($_POST['to_date'])."' ";
}
else
{
    $between="";
}

if(!empty($_POST['purchase_order_id']))
{
    $purchase_order_id="AND ait_product_purchase_order_invoice.purchase_order_id LIKE '%".$_POST['purchase_order_id']."%'";
}
else
{
    $purchase_order_id="";
}
if(!empty($_POST['invoice_id']))
{
    $invoice_id="AND ait_product_purchase_order_challan_status.invoice_post_no LIKE '%".$_POST['invoice_id']."%'";
}
else
{
    $invoice_id="";
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
                Division
            </th>
            <th style="width:5%;">
                Zone
            </th>
            <th style="width:5%;">
                District
            </th>
            <th style="width:5%;">
                Customer
            </th>
            <th style="width:5%;">
                PO Date
            </th>
            <th style="width:5%;">
                PO No.
            </th>
            <th style="width:5%;">
                Invoice Date
            </th>
            <th style="width:5%;">
                Invoice No.
            </th>
            <th style="width:5%;">
                Courier Name
            </th>
            <th style="width:5%;">
                Booking Date
            </th>
            <th style="width:5%;">
                Courier Trac. No.
            </th>
            <th style="width:5%;">
                Status
            </th>
            <th style="width:5%;">
                Remark
            </th>
        </tr>
        <?php
        $sql="SELECT
                    ait_zone_info.zone_name,
                    ait_division_info.division_name,
                    ait_territory_info.territory_name,
                    ait_distributor_info.distributor_name,
                    ait_product_purchase_order_challan_status.purchase_order_id,
                    ait_product_purchase_order_challan_status.invoice_date,
                    ait_product_purchase_order_challan_status.invoice_post_date,
                    ait_product_purchase_order_challan_status.invoice_post_no,
                    ait_product_purchase_order_challan_status.courier_name,
                    ait_product_purchase_order_challan_status.booking_date,
                    ait_product_purchase_order_challan_status.courier_trac_no,
                    ait_product_purchase_order_challan_status.remarks,
                    ait_zilla.zillanameeng,
                    ait_product_purchase_order_invoice.`status`
                FROM
                    ait_product_purchase_order_invoice
                    LEFT JOIN ait_product_purchase_order_challan_status ON ait_product_purchase_order_challan_status.purchase_order_id = ait_product_purchase_order_invoice.purchase_order_id
                    LEFT JOIN ait_zone_info ON ait_zone_info.zone_id = ait_product_purchase_order_invoice.zone_id
                    LEFT JOIN ait_division_info ON ait_division_info.division_id = ait_zone_info.division_id
                    LEFT JOIN ait_territory_info ON ait_territory_info.territory_id = ait_product_purchase_order_invoice.territory_id
                    LEFT JOIN ait_distributor_info ON ait_distributor_info.distributor_id = ait_product_purchase_order_invoice.distributor_id
                    LEFT JOIN ait_zilla ON ait_zilla.zillaid = ait_product_purchase_order_invoice.zilla_id
                WHERE
                    ait_product_purchase_order_invoice.del_status=0
                    $division $zone $territory $zilla $distributor
                    $between $purchase_order_id $invoice_id
                GROUP BY
                    ait_product_purchase_order_invoice.purchase_order_id,
                    ait_division_info.division_id,
                    ait_product_purchase_order_invoice.zone_id,
                    ait_product_purchase_order_invoice.territory_id,
                    ait_product_purchase_order_invoice.zilla_id,
                    ait_product_purchase_order_invoice.distributor_id
        ";
        if($db->open())
        {
            $result=$db->query($sql);
            while($row=$db->fetchAssoc($result))
            {
                ?>
                <tr>
                    <td><?php echo $row['division_name'];?></td>
                    <td><?php echo $row['zone_name'];?></td>
                    <td><?php echo $row['zillanameeng'];?></td>
                    <td><?php echo $row['distributor_name'];?></td>
                    <td><?php echo $db->date_formate($row['invoice_date']);?></td>
                    <td><?php echo substr($row['purchase_order_id'],3);?></td>
                    <td><?php echo $db->date_formate($row['invoice_post_date']);?></td>
                    <td><?php echo $row['invoice_post_no'];?></td>
                    <td><?php echo $row['courier_name'];?></td>
                    <td><?php echo $db->date_formate($row['booking_date']);?></td>
                    <td><?php echo $row['courier_trac_no'];?></td>
                    <td><?php echo $row['status'];?></td>
                    <td><?php echo $row['remarks'];?></td>
                </tr>
                <?php
            }
        }
        ?>
        </thead>
        <tbody>

        </tbody>
    </table>
    <?php include_once '../../libraries/print_page/Print_footer.php'; ?>
</div>