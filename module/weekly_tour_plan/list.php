<?php
session_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$tbl = _DB_PREFIX;
$employee_id=$_SESSION['employee_id'];
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
                                    Employee Name
                                </th>
                                <th style="width:15%" >
                                    Month
                                </th>
                                <th style="width:15%" >
                                    Week
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                             $sql = "SELECT
                                            $tbl" . "weekly_tour_plan.week_plan_id,
                                            $tbl" . "employee_basic_info.employee_name,
                                            $tbl" . "weekly_tour_plan.week_id,
                                            $tbl" . "zone_info.zone_name,
                                            $tbl" . "territory_info.territory_name,
                                            $tbl" . "month_info.month_full_name
                                        FROM
                                            $tbl" . "weekly_tour_plan
                                            LEFT JOIN $tbl" . "employee_basic_info ON $tbl" . "employee_basic_info.employee_id = $tbl" . "weekly_tour_plan.employee_id
                                            LEFT JOIN $tbl" . "month_info ON $tbl" . "month_info.month_id = $tbl" . "weekly_tour_plan.month_id
                                            LEFT JOIN $tbl" . "zone_info ON $tbl" . "zone_info.zone_id = $tbl" . "weekly_tour_plan.zone_id
                                            LEFT JOIN $tbl" . "territory_info ON $tbl" . "territory_info.territory_id = $tbl" . "weekly_tour_plan.territory_id
                                        WHERE $tbl" . "weekly_tour_plan.del_status='0' AND $tbl" . "weekly_tour_plan.employee_id='$employee_id'   ".$db->get_zone_access($tbl. "weekly_tour_plan")." 
                                        GROUP BY $tbl" . "weekly_tour_plan.week_plan_id
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
                                    <tr class="<?php echo $rowcolor ?> pointer" id="tr_id<?php echo $i; ?>" onclick="get_rowID('<?php echo $result_array["week_plan_id"] ?>', '<?php echo $i; ?>')" ondblclick="details_form();">
                                        <td>
                                            <?php echo $i; ?>
                                        </td>
                                        <td>
                                            <?php echo $result_array['zone_name']; ?>
                                        </td>
                                        <td>
                                            <?php echo $result_array['territory_name']; ?>
                                        </td>
                                        <td>
                                            <?php echo $result_array['employee_name']; ?>
                                        </td>
                                        <td>
                                            <?php echo $result_array['month_full_name']; ?>
                                        </td>
                                        <td>
                                            <?php echo $result_array['week_id']; ?>
                                        </td>
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