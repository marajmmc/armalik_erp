<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$tbl = _DB_PREFIX;

if ($_POST['employee_id'] != "") {
    $employee_id = "AND $tbl" . "task_management.employee_id='" . $_POST['employee_id'] . "'";
} else {
    $employee_id = "";
}
if ($_POST['status'] != "") {
    $status = "AND $tbl" . "task_management.status='" . $_POST['status'] . "'";
} else {
    $status = "";
}
if ($_POST['from_date'] != "" && $_POST['to_date'] != "") {
    $between = "AND $tbl" . "task_management.start_date BETWEEN '" . $db->date_formate($_POST['from_date']) . "' AND '" . $db->date_formate($_POST['to_date']) . "'";
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
                    Employee Name
                </th>
                <th style="width:5%">
                    Task Name
                </th>
                <th style="width:5%">
                    Start Date
                </th>
                <th style="width:5%">
                    End Date
                </th>
                <th style="width:5%">
                    Status
                </th>
            </tr>
        </thead>
        <tbody>
            <?php
            $employee_name="";
            $sql = "SELECT
                        $tbl" . "task_management.id,
                        $tbl" . "task_management.task_name,
                        $tbl" . "task_management.start_date,
                        $tbl" . "task_management.end_date,
                        $tbl" . "employee_basic_info.employee_name,
                        $tbl" . "task_management.`status`
                    FROM
                        $tbl" . "task_management
                        LEFT JOIN $tbl" . "employee_basic_info ON $tbl" . "employee_basic_info.employee_id = $tbl" . "task_management.employee_id
                    WHERE
                        $tbl" . "employee_basic_info.del_status='0'
                        $employee_id $between $status
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
                    <tr class="<?php echo $rowcolor; ?> pointer" id="tr_id<?php echo $i; ?>" onclick="get_rowID('<?php echo $result_array["id"] ?>', '<?php echo $i; ?>')">
                        <td>
                            <?php
                            if ($employee_name == '') {
                                echo $result_array['employee_name'];
                                $employee_name = $result_array['employee_name'];
                                //$currentDate = $preDate;
                            } else if ($employee_name == $result_array['employee_name']) {
                                //exit;
                                echo "&nbsp;";
                            } else {
                                echo $result_array['employee_name'];
                                $employee_name = $result_array['employee_name'];
                            }
                            ?>
                        </td>
                        <td><?php echo $result_array['task_name']; ?></td>
                        <td><?php echo $db->date_formate($result_array['start_date']); ?></td>
                        <td><?php echo $db->date_formate($result_array['end_date']); ?></td>
                        <td><?php echo $result_array['status']; ?></td>
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