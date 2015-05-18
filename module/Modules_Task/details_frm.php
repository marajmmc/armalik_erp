<?php
session_start();
ob_start();
if ($_SESSION['logged'] != 'yes') {
    $_REQUEST["msg"] = "TimeoutC";
    header("location:../../index.php");
}
//echo $_SESSION['shop_name_eng']; 
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$okicon = "<a class='btn btn-inverse' href='#' data-original-title='' title='Access'>
                                        <i class='icon-white icon-ok' > 
            </i></a>";
$notokicon = "<a class='btn btn-inverse' href='#' data-original-title='' title='Not Access'>
                                        <i class='icon-white' style='background-position: -312px 0;'> 
            </i></a>";

$tbl = _DB_PREFIX;
$db = new Database();
$sql = "SELECT
            $tbl" . "system_module.sm_name,
            $tbl" . "system_module.sm_icon
        FROM
            $tbl" . "system_module
        WHERE
            $tbl" . "system_module.sm_id='" . $_POST['rowID'] . "'
";
$i = 0;
if ($db->open()) {
    $result = $db->query($sql);
    $result_array = $db->fetchAssoc();
    if ($result_array['sm_icon'] != '') {
        $module_icon = "<img src='../../system_images/module_icon/$result_array[sm_icon]' width=25 height=25 title='$result_array[sm_name] Icon' alt='$result_array[sm_name]'>";
    } else {
        $module_icon = '<a class="btn btn-small" data-original-title="No Icon" title="No Icon">
                                                            <i class="icon-warning-sign" data-original-title="Share"> </i>
                                                        </a>';
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
                            <i class="icon-plus-sign" data-original-title="Share"> </i>
                        </a>
                    </span>
                </div>
                <div class="widget-body">
                    <div class="wrapper">
                        <table class="table table-condensed table-striped table-bordered table-hover no-margin" id="TaskTable">
                            <thead>
                                <tr>
                                    <th style="width:29%">
                                        Module Name
                                    </th>
                                    <th style="width:1%">
                                        :
                                    </th>
                                    <th style="width:70%">
                                        <?php echo $result_array['sm_name'] ?>
                                    </th>
                                </tr>
                                <tr>
                                    <th style="width:29%">
                                        Module Icon
                                    </th>
                                    <th style="width:1%">
                                        :
                                    </th>
                                    <th style="width:70%">
                                        <?php echo $module_icon ?>
                                    </th>
                                </tr>
                            </thead>
                        </table>

                        <table class="table table-condensed table-striped table-bordered table-hover no-margin" id="TaskTable">
                            <thead>
                                <tr>
                                    <th style="width:10%">
                                        Task Name
                                    </th>
                                    <th style="width:5%">
                                        New
                                    </th>
                                    <th style="width:5%">
                                        Save
                                    </th>
                                    <th style="width:5%">
                                        Edit
                                    </th>
                                    <th style="width:5%">
                                        View
                                    </th>
                                    <th style="width:5%">
                                        Del
                                    </th>
                                    <th style="width:5%">
                                        Rpt
                                    </th>
                                    <th style="width:5%">
                                        Task Icon
                                    </th>
                                    <th style="width:10%">
                                        Directory Name
                                    </th>
                                </tr>
                            </thead>
                            <?php
                            $sql = "SELECT
                    $tbl" . "system_task.st_id,
                    $tbl" . "system_module.sm_name,
                    $tbl" . "system_module.sm_icon,
                    $tbl" . "system_task.st_name,
                    $tbl" . "system_task.st_icon,
                    $tbl" . "system_task.st_pram,
                    $tbl" . "system_task.st_eventadd,
                    $tbl" . "system_task.st_eventsave,
                    $tbl" . "system_task.st_eventedit,
                    $tbl" . "system_task.st_eventdelete,
                    $tbl" . "system_task.st_eventview,
                    $tbl" . "system_task.st_eventreport
                FROM
                    $tbl" . "system_task
                LEFT JOIN $tbl" . "system_module ON $tbl" . "system_module.sm_id = $tbl" . "system_task.st_sm_id
                WHERE
                    $tbl" . "system_module.sm_id='" . $_POST['rowID'] . "'  
                ";
                            $i = 0;
                            if ($db->open()) {
                                $result = $db->query($sql);
                                while ($result_array = $db->fetchAssoc()) {
                                    if ($i % 2 == 0) {
                                        $rowcolor = "gradeC";
                                    } else {
                                        $rowcolor = "gradeA success";
                                    }

                                    if ($result_array['st_icon'] != '') {
                                        $task_icon = "<img src='../../system_images/task_icon/$result_array[st_icon]' width=25 height=25 title='$result_array[st_icon] Icon' alt='$result_array[st_icon]'>";
                                    } else {
                                        $task_icon = '<a class="btn btn-small" data-original-title="No Icon" title="No Icon">
                                                            <i class="icon-warning-sign" data-original-title="Share"> </i>
                                                        </a>';
                                    }

                                    $folder_location = explode('/', $result_array['st_pram']);
                                    if ($result_array['st_eventadd'] == "add") {
                                        $check1 = $okicon;
                                    } else {
                                        $check1 = $notokicon;
                                    }
                                    if ($result_array['st_eventsave'] == "save") {
                                        $check2 = $okicon;
                                    } else {
                                        $check2 = $notokicon;
                                    }
                                    if ($result_array['st_eventedit'] == "edit") {
                                        $check3 = $okicon;
                                    } else {
                                        $check3 = $notokicon;
                                    }
                                    if ($result_array['st_eventview'] == "details") {
                                        $check4 = $okicon;
                                    } else {
                                        $check4 = $notokicon;
                                    }
                                    if ($result_array['st_eventdelete'] == "delete") {
                                        $check5 = $okicon;
                                    } else {
                                        $check5 = $notokicon;
                                    }
                                    if ($result_array['st_eventreport'] == "report") {
                                        $check6 = $okicon;
                                    } else {
                                        $check6 = $notokicon;
                                    }
                                    echo "<tr class='$rowcolor'>
                                            <td>
                                                $result_array[st_name]
                                            </td>
                                            <td>$check1</td>
                                            <td>$check2</td>
                                            <td>$check3</td>
                                            <td>$check4</td>
                                            <td>$check5</td>
                                            <td>$check6</td>
                                            <td>$task_icon</td>
                                            <td>$folder_location[0]</td>
                                        <tr>";

                                    ++$i;
                                }
                            }
                            ?>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    </div>

<?php }
?>