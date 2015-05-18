<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$tbl = _DB_PREFIX;
if ($_SESSION['user_level'] == "Zone") {
    $zone_id = "AND $tbl" . "farmer_info.zone_id='" . $_SESSION['zone_id'] . "'";
    $territory = '';
    $distributor = '';
} else if ($_SESSION['user_level'] == "Territory") {
    $zone_id = "AND $tbl" . "farmer_info.zone_id='" . $_SESSION['zone_id'] . "'";
    $territory = "AND $tbl" . "farmer_info.territory_id='" . $_SESSION['territory_id'] . "'";
    $distributor = '';
} else if ($_SESSION['user_level'] == "Distributor") {
    $zone_id = "AND $tbl" . "farmer_info.zone_id='" . $_SESSION['zone_id'] . "'";
    $territory = "AND $tbl" . "farmer_info.territory_id='" . $_SESSION['territory_id'] . "'";
    $distributor = "AND $tbl" . "farmer_info.distributor_id='" . $_SESSION['employee_id'] . "'";
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
                                    Division
                                </th>
                                <th style="width:10%">
                                    Zone
                                </th>
<!--                                <th style="width:10%">-->
<!--                                    Territory-->
<!--                                </th>-->
<!--                                <th style="width:10%">-->
<!--                                    Distributor-->
<!--                                </th>-->
<!--                                <th style="width:10%">-->
<!--                                    Dealer Name-->
<!--                                </th>-->
                                <th style="width:10%">
                                    Farmer Name
                                </th>
                                <th style="width:10%">
                                    Mobile No
                                </th>
                                <th style="width:5%">
                                    Status
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php

                            $sql = "SELECT
                                        ait_farmer_info.farmer_id,
                                        ait_zone_info.zone_name,
                                        ait_territory_info.territory_name,
                                        CONCAT_WS(', ', ait_distributor_info.customer_code,ait_distributor_info.distributor_name) AS distributor_name,
                                        ait_dealer_info.dealer_name,
                                        ait_farmer_info.farmer_name,
                                        ait_farmer_info.contact_no,
                                        ait_farmer_info.`status`,
                                        ait_division_info.division_name
                                    FROM
                                        ait_farmer_info
                                        LEFT JOIN ait_zone_info ON ait_zone_info.zone_id = ait_farmer_info.zone_id
                                        LEFT JOIN ait_territory_info ON ait_territory_info.territory_id = ait_farmer_info.territory_id
                                        LEFT JOIN ait_distributor_info ON ait_distributor_info.distributor_id = ait_farmer_info.distributor_id
                                        LEFT JOIN ait_dealer_info ON ait_dealer_info.dealer_id = ait_farmer_info.dealer_id
                                        LEFT JOIN ait_zone_user_access ON ait_zone_user_access.zone_id = ait_zone_info.zone_id
                                        LEFT JOIN ait_division_info ON ait_division_info.division_id = ait_zone_user_access.division_id
                                    WHERE ait_farmer_info.del_status='0'
                                        $zone_id $territory $distributor  " . $db->get_zone_access($tbl . "farmer_info") . "
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
                                    <tr class="<?php echo $rowcolor ?> pointer" id="tr_id<?php echo $i; ?>" onclick="get_rowID('<?php echo $result_array["farmer_id"] ?>', '<?php echo $i; ?>')" ondblclick="details_form();">
                                        <td>
                                            <?php echo $i; ?>
                                        </td>
                                        <td><?php echo $result_array['division_name']; ?></td>
                                        <td><?php echo $result_array['zone_name']; ?></td>
                                        <td><?php echo $result_array['farmer_name']; ?></td>
                                        <td><?php echo $result_array['contact_no']; ?></td>
                                        <td><?php echo $result_array['status']; ?></td>
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
