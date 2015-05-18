<?php
session_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$tbl = _DB_PREFIX;
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
                                    Employee Name
                                </th>
                                <th style="width:15%" >
                                    Task Name
                                </th>
                                <th style="width:10%" >
                                    Start Date
                                </th>
                                <th style="width:10%" >
                                    End Date
                                </th>
                                <th style="width:10%" >
                                    Status
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT
                                        $tbl" . "task_management.task_plan_id,
                                        $tbl" . "task_management.task_name,
                                        $tbl" . "task_management.start_date,
                                        $tbl" . "task_management.end_date,
                                        $tbl" . "task_management.task_description,
                                        $tbl" . "task_management.status,
                                        $tbl" . "employee_basic_info.employee_name
                                    FROM
                                        $tbl" . "task_management
                                        LEFT JOIN $tbl" . "employee_basic_info ON $tbl" . "employee_basic_info.employee_id = $tbl" . "task_management.employee_id
                                    WHERE $tbl" . "task_management.del_status='0'
                                    GROUP BY
                                        $tbl" . "task_management.task_plan_id
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
                                    <tr class="<?php echo $rowcolor ?> pointer" id="tr_id<?php echo $i; ?>" onclick="get_rowID('<?php echo $result_array["task_plan_id"] ?>', '<?php echo $i; ?>')" ondblclick="details_form();">
                                        <td>
                                            <?php echo $i; ?>
                                        </td>
                                        <td>
                                            <?php echo $result_array['employee_name']; ?>
                                        </td>
                                        <td>
                                            <?php echo $result_array['task_name']; ?>
                                        </td>
                                        <td>
                                            <?php echo $db->date_formate($result_array['start_date']); ?>
                                        </td>
                                        <td>
                                            <?php echo $db->date_formate($result_array['end_date']); ?>
                                        </td>
                                        <td>
                                            <?php echo $result_array['status']; ?>
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