<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$tbl = _DB_PREFIX;
if ($_SESSION['user_level'] == "Zone") {
    $zone_id = "AND $tbl" . "dealer_info.zone_id='" . $_SESSION['zone_id'] . "'";
    $territory = '';
    $distributor = '';
} else if ($_SESSION['user_level'] == "Territory") {
    $zone_id = "AND $tbl" . "dealer_info.zone_id='" . $_SESSION['zone_id'] . "'";
    $territory = "AND $tbl" . "dealer_info.territory_id='" . $_SESSION['territory_id'] . "'";
    $distributor = '';
} else if ($_SESSION['user_level'] == "Distributor") {
    $zone_id = "AND $tbl" . "dealer_info.zone_id='" . $_SESSION['zone_id'] . "'";
    $territory = "AND $tbl" . "dealer_info.territory_id='" . $_SESSION['territory_id'] . "'";
    $distributor = "AND $tbl" . "dealer_info.distributor_id='" . $_SESSION['employee_id'] . "'";
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
                                    Zone
                                </th>
                                <th style="width:10%">
                                    Territory
                                </th>
                                <th style="width:10%">
                                    Customer
                                </th>
                                <th style="width:10%">
                                    Dealer Name
                                </th>
                                <th style="width:10%">
                                    Phone
                                </th>
                                <th style="width:5%">
                                    Status
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT
                                        $tbl" . "dealer_info.id,
                                        $tbl" . "dealer_info.dealer_id,
                                        $tbl" . "zone_info.zone_name,
                                        $tbl" . "territory_info.territory_name,
                                        CONCAT_WS(' - ', $tbl" . "distributor_info.customer_code, $tbl" . "distributor_info.distributor_name) AS distributor_name,
                                        $tbl" . "dealer_info.status,
                                        $tbl" . "dealer_info.dealer_name,
                                        $tbl" . "dealer_info.phone_no
                                    FROM
                                        $tbl" . "dealer_info
                                        LEFT JOIN $tbl" . "zone_info ON $tbl" . "zone_info.zone_id = $tbl" . "dealer_info.zone_id
                                        LEFT JOIN $tbl" . "territory_info ON $tbl" . "territory_info.territory_id = $tbl" . "dealer_info.territory_id
                                        LEFT JOIN $tbl" . "distributor_info ON $tbl" . "distributor_info.distributor_id = $tbl" . "dealer_info.distributor_id
                                    WHERE $tbl" . "dealer_info.del_status='0' $zone_id $territory $distributor  ".$db->get_zone_access($tbl. "dealer_info")." 
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
                                    <tr class="<?php echo $rowcolor ?> pointer" id="tr_id<?php echo $i; ?>" onclick="get_rowID('<?php echo $result_array["dealer_id"] ?>', '<?php echo $i; ?>')" ondblclick="details_form();">
                                        <td>
                                            <?php echo $i; ?>
                                        </td>
                                        <td><?php echo $result_array['zone_name']; ?></td>
                                        <td><?php echo $result_array['territory_name']; ?></td>
                                        <td><?php echo $result_array['distributor_name']; ?></td>
                                        <td><?php echo $result_array['dealer_name']; ?></td>
                                        <td><?php echo $result_array['phone_no']; ?></td>
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
