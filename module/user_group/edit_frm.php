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

$tbl = _DB_PREFIX;
$db = new Database();

$sql = "SELECT
        *
        FROM
        $tbl" . "user_group
        where ug_id='" . $_POST['rowID'] . "'";

if ($db->open()) {
    $result = $db->query($sql);
    while ($row = $db->fetchAssoc()) {
        $ugname = $row["ug_name"];
        $moduleID[] = $row['up_sm_id'];
        $taskID[] = $row['up_st_id'];
        $up_eventadd[] = $row['up_eventadd'];
        $up_eventsave[] = $row['up_eventsave'];
        $up_eventedit[] = $row['up_eventedit'];
        $up_eventdelete[] = $row['up_eventdelete'];
        $up_eventview[] = $row['up_eventview'];
        $up_eventreport[] = $row['up_eventreport'];
    }
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
                <div class="control-group">
                    <label class="control-label" for="textName"> Group Name </label>
                    <div class="controls">
                        <div class="input-append">
                            <input class="span6" type="text" name="textName" id="textName" value="<?php echo $ugname; ?>" placeholder="User Group Name" validate="Require">
                        </div>
                    </div>
                </div>
            </div>
            <div class="widget-body">
                <div class="wrapper">
                    <table class="table table-condensed table-striped table-bordered table-hover no-margin" id="TaskTable">
                        <thead>
                            <tr>
                                <th style="width:40%">
                                    Modules Name
                                </th>
                                <th style="width:38%">
                                    Task Name
                                </th>
                                <th style="width:2%">
                                    <a class='btn btn-small btn-inverse' href='#' id='add_btn' title="Add">
                                        <i class='icon-white icon-plus-sign' ></i>
                                    </a>
                                </th>
                                <th style="width:2%">
                                    <a class='btn btn-small btn-inverse' href='#' id='save_btn' title="Save">
                                        <i class='icon-white icon-hdd' ></i>
                                    </a>
                                </th>
                                <th style="width:2%">
                                    <a class='btn btn-small btn-inverse' href='#' id='edit_btn' title="Edit">
                                        <i class='icon-white icon-edit' ></i>
                                    </a>
                                </th>
                                <th style="width:2%">
                                    <a class='btn btn-small btn-inverse' href='#' id='view_btn' title="View Detials">
                                        <i class='icon-white icon-eye-open' ></i>
                                    </a>
                                </th>
                                <th style="width:2%">
                                    <a class='btn btn-small btn-inverse' href='#' id='view_btn' title="Delete">
                                        <i class='icon-white icon-eye-open' ></i>
                                    </a>
                                </th>
                                <th style="width:2%">
                                    <a class='btn btn-small btn-inverse' href='#' id='view_btn' title="Report">
                                        <i class='icon-white icon-eye-open' ></i>
                                    </a>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT
                    $tbl" . "system_task.st_id,
                    $tbl" . "system_module.sm_id,
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
                order by sm_id,st_order";
                            $i = 0;
                            if ($db->open()) {
                                $result = $db->query($sql);
                                $tmp = '';
                                while ($result_array = $db->fetchAssoc()) {
                                    if ($i % 2 == 0) {
                                        $rowcolor = "gradeC";
                                    } else {
                                        $rowcolor = "gradeA success";
                                    }
                                    if ($tmp == $result_array["sm_id"]) {
                                        echo "<tr class='row_hover' class='$rowcolor'>
                                <td align='left'>&nbsp;</td>";
                                    } else {
                                        if (in_array($result_array["sm_id"], $moduleID)) {
                                            $chkMethod = " checked='checked' ";
                                        } else {
                                            $chkMethod = " ";
                                        }
                                        $tmp = $result_array["sm_id"];
                                        echo "<tr class='row_hover' $rowcolor>
                        <td align='left'>
                            <input type='checkbox' class='checkbox_module' $chkMethod name='$result_array[sm_id]' onclick='selectallTask(this)' id='$result_array[sm_id]' value='$result_array[sm_id]'  />
                            $result_array[sm_name] 
                            <input type='hidden' id='elmIndex[]' name='elmIndex[]' value='$i'/>
                        </td>";
                                    }
                                    $taskchkMethod = "";
                                    $addchkMethod = "";
                                    $savechkMethod = "";
                                    $editchkMethod = "";
                                    $deletechkMethod = "";
                                    $viewchkMethod = "";
                                    $reportchkMethod = "";
                                    $position = false;
                                    if (in_array($result_array["st_id"], $taskID)) {
                                        $taskchkMethod = " checked='checked' ";
                                        $position = array_search($result_array["st_id"], $taskID);
                                    }
                                    if ($position !== false) {
                                        if ($result_array["st_eventadd"] == $up_eventadd[$position]) {
                                            $addchkMethod = " checked='checked' ";
                                        }
                                        if ($result_array["st_eventsave"] == $up_eventsave[$position]) {
                                            $savechkMethod = " checked='checked' ";
                                        }
                                        if ($result_array["st_eventedit"] == $up_eventedit[$position]) {
                                            $editchkMethod = " checked='checked' ";
                                        }
                                        if ($result_array["st_eventdelete"] == $up_eventdelete[$position]) {
                                            $deletechkMethod = " checked='checked' ";
                                        }
                                        if ($result_array["st_eventview"] == $up_eventview[$position]) {
                                            $viewchkMethod = " checked='checked' ";
                                        }
                                        if ($result_array["st_eventreport"] == $up_eventreport[$position]) {
                                            $reportchkMethod = " checked='checked' ";
                                        }
                                    }
                                    echo "<td align='left'>
                            <input type='checkbox' class='checkbox_module' $taskchkMethod onclick=selectallEvents(this,'$result_array[sm_id]') name='$result_array[sm_id]-$result_array[st_id]' value='$result_array[sm_id]-$result_array[st_id]'  />
                            $result_array[st_name]
                            <input type='hidden' name='hiddenStid[]' id='hiddenStid[]' value='$result_array[st_id]'>
                            <input type='hidden' name='hiddenSmid[]' id='hiddenSmid[]' value='$result_array[sm_id]'>
                        </td>
                        <td><input type='checkbox' class='checkbox_module' $addchkMethod name='$result_array[sm_id]-$result_array[st_id]-Add' value='add' title='Add' onclick=selectallModuleTask(this,'$result_array[sm_id]','$result_array[sm_id]-$result_array[st_id]')  /></td>
                        <td><input type='checkbox' class='checkbox_module' $savechkMethod name='$result_array[sm_id]-$result_array[st_id]-Save' value='save' title='Save' onclick=selectallModuleTask(this,'$result_array[sm_id]','$result_array[sm_id]-$result_array[st_id]')  /></td>
                        <td><input type='checkbox' class='checkbox_module' $editchkMethod name='$result_array[sm_id]-$result_array[st_id]-Edit' value='edit' title='Edit' onclick=selectallModuleTask(this,'$result_array[sm_id]','$result_array[sm_id]-$result_array[st_id]')  /></td>
                        <td><input type='checkbox' class='checkbox_module' $viewchkMethod name='$result_array[sm_id]-$result_array[st_id]-View' value='details' title='Detail's' onclick=selectallModuleTask(this,'$result_array[sm_id]','$result_array[sm_id]-$result_array[st_id] ')  /></td>                        
                        <td><input type='checkbox' class='checkbox_module' $deletechkMethod name='$result_array[sm_id]-$result_array[st_id]-Delete' value='delete' title='Delete' onclick=selectallModuleTask(this,'$result_array[sm_id]','$result_array[sm_id]-$result_array[st_id]')  /></td>
                        <td><input type='checkbox' class='checkbox_module' $reportchkMethod name='$result_array[sm_id]-$result_array[st_id]-Report' value='report' title='Report' onclick=selectallModuleTask(this,'$result_array[sm_id]','$result_array[sm_id]-$result_array[st_id]')  /></td>
                        <tr>";

                                    ++$i;
                                }
                            }
                            ?>
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
