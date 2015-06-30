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
    $division_id = "AND $tbl"."division_info.division_id='" . $_POST['division_id'] . "'";
}
else
{
    $division_id = "";
}
if ($_POST['zone_id'] != "")
{
    $zone_id = "AND $tbl"."distributor_info.zone_id='" . $_POST['zone_id'] . "'";
}
else
{
    $zone_id = "";
}

if ($_POST['territory_id'] != "")
{
    $territory_id = "AND $tbl"."distributor_info.territory_id='" . $_POST['territory_id'] . "'";
}
else
{
    $territory_id = "";
}
if ($_POST['distributor_id'] != "")
{
    $distributor_id = "AND $tbl"."distributor_info.distributor_id='" . $_POST['distributor_id'] . "'";
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
                    CID
                </th>
                <th style="width:5%">
                    Owner Name
                </th>
                <th style="width:5%">
                    Address
                </th>
                <th style="width:5%">
                    Contact Number
                </th>
            </tr>
        </thead>
        <tbody>
            <?php
            $division_name='';
            $zone_name='';
            $territory_name='';
            $sql="SELECT
                        $tbl"."division_info.division_name,
                        $tbl"."zone_info.zone_name,
                        $tbl"."territory_info.territory_name,
                        $tbl"."distributor_info.id,
                        $tbl"."distributor_info.distributor_name,
                        $tbl"."distributor_info.customer_code,
                        $tbl"."distributor_info.owner_name,
                        $tbl"."distributor_info.address,
                        $tbl"."distributor_info.phone,
                        $tbl"."distributor_info.email
                    FROM
                        $tbl"."distributor_info
                        INNER JOIN $tbl"."zone_info ON $tbl"."zone_info.zone_id = $tbl"."distributor_info.zone_id
                        INNER JOIN $tbl"."zone_user_access ON $tbl"."zone_user_access.zone_id = $tbl"."zone_info.zone_id
                        INNER JOIN $tbl"."division_info ON $tbl"."division_info.division_id = $tbl"."zone_user_access.division_id
                        INNER JOIN $tbl"."territory_info ON $tbl"."territory_info.territory_id = $tbl"."distributor_info.territory_id
                    WHERE
                        $tbl"."distributor_info.del_status='0'
                        $division_id $zone_id $territory_id $distributor_id
                        ".$db->get_zone_access($tbl. "zone_info")."
                    ORDER BY
                            $tbl"."division_info.division_name,
                            $tbl"."zone_info.zone_name,
                            $tbl"."territory_info.territory_name,
                            $tbl"."distributor_info.distributor_name
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
                    ?>
                    <tr class="<?php echo $rowcolor; ?> pointer" id="tr_id<?php echo $i; ?>" onclick="get_rowID('<?php echo $result_array["id"] ?>', '<?php echo $i; ?>')">
                        <td>
                            <?php
                            if ($division_name == '') {
                                echo $result_array['division_name'];
                                $division_name = $result_array['division_name'];
                                //$currentDate = $preDate;
                            } else if ($division_name == $result_array['division_name']) {
                                //exit;
                                echo "&nbsp;";
                            } else {
                                echo $result_array['division_name'];
                                $division_name = $result_array['division_name'];
                            }
                            ?>
                        </td>
                        <td>
                            <?php
                            if ($zone_name == '') {
                                echo $result_array['zone_name'];
                                $zone_name = $result_array['zone_name'];
                                //$currentDate = $preDate;
                            } else if ($zone_name == $result_array['zone_name']) {
                                //exit;
                                echo "&nbsp;";
                            } else {
                                echo $result_array['zone_name'];
                                $zone_name = $result_array['zone_name'];
                            }
                            ?>
                        </td>
                        <td>
                            <?php 
                            if ($territory_name == '') {
                                echo $result_array['territory_name'];
                                $territory_name = $result_array['territory_name'];
                                //$currentDate = $preDate;
                            } else if ($territory_name == $result_array['territory_name']) {
                                //exit;
                                echo "&nbsp;";
                            } else {
                                echo $result_array['territory_name'];
                                $territory_name = $result_array['territory_name'];
                            }
                            ?>
                        </td>
                        <td><?php echo $result_array['distributor_name']; ?></td>
                        <td><?php echo $result_array['customer_code']; ?></td>
                        <td><?php echo $result_array['owner_name']; ?></td>
                        <td><?php echo $result_array['address']; ?></td>
                        <td><?php echo $result_array['phone']; ?></td>

                    </tr>
                    <?php
                    ++$i;
                }
            }
            ?>
        </tbody>
    </table>
    <?php include_once '../../libraries/print_page/Print_footer.php'; ?>
</div>