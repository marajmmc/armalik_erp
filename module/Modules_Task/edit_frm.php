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

//echo $_POST['rowID'];
$tbl = _DB_PREFIX;
$db = new Database();
$sql = "SELECT
            $tbl" . "system_task.st_id,
            $tbl" . "system_module.sm_name,
            $tbl" . "system_module.sm_icon,
            $tbl" . "system_task.st_name,
            $tbl" . "system_task.st_icon,
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
    $result_array = $db->fetchAssoc();
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
                        <div class="controls span6">
                            <label class="control-label" for="sm_name">
                                Module Name
                            </label>
                            <input class="span12" type="text" name="sm_name" id="sm_name" value="<?php echo $result_array['sm_name'] ?>" placeholder="Module Name" validate="Require">
                        </div>
                        <div class="controls span6">
                            <label class="control-label" for="sm_icon">
                                Module Icon
                            </label>
                            <input class="span12" type="file" name="sm_icon" id="sm_icon" placeholder="Module Icon" validate="Picture">
                            <input class="span12" type="hidden" name="sm_icon_tmp" id="sm_icon_tmp" placeholder="Module Icon" value="<?php echo $result_array['sm_icon']?>">
                        </div>
                        <div class="controls controls-row">
                            <span class="label label label-info" style="cursor: pointer; float: right" onclick="RowIncrement()"> + Add More </span>
                        </div>
                    </div>
                </div>
                <div class="widget-body">
                    <div class="wrapper">
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
                                    <th style="width:10%">
                                        Task Icon
                                    </th>
                                    <th style="width:10%">
                                        Directory Name
                                    </th>
                                    <th style="width:5%">
                                        Action
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
                                    $folder_location = explode('/', $result_array['st_pram']);
                                    if ($result_array['st_eventadd'] == "add") {
                                        $check1 = 'checked';
                                    } else {
                                        $check1 = '';
                                    }
                                    if ($result_array['st_eventsave'] == "save") {
                                        $check2 = 'checked';
                                    } else {
                                        $check2 = '';
                                    }
                                    if ($result_array['st_eventedit'] == "edit") {
                                        $check3 = 'checked';
                                    } else {
                                        $check3 = '';
                                    }
                                    if ($result_array['st_eventview'] == "details") {
                                        $check4 = 'checked';
                                    } else {
                                        $check4 = '';
                                    }
                                    if ($result_array['st_eventdelete'] == "delete") {
                                        $check5 = 'checked';
                                    } else {
                                        $check5 = '';
                                    }
                                    if ($result_array['st_eventreport'] == "report") {
                                        $check6 = 'checked';
                                    } else {
                                        $check6 = '';
                                    }
                                    echo "<tr class='$rowcolor'>
                                            <td>
                                                <input type='text' name='textTsName[]' value='$result_array[st_name]' maxlength='50' id='textTsName_$i' class='span12'/>        
                                                <input type='hidden' id='st_id[]' name='st_id[]' value='$result_array[st_id]'/>
                                                <input type='hidden' id='elmIndex[]' name='elmIndex[]' value='$i'/>
                                            </td>
                                            <td><input type='checkbox' class='' $check1 name='chkTaskAdd[]' id='chkTaskAdd$i' value='add'  /></td>
                                            <td><input type='checkbox' class='' $check2 name='chkTaskSave[]' id='chkTaskSave$i' value='save'  /></td>
                                            <td><input type='checkbox' class='' $check3 name='chkTaskEdit[]' id='chkTaskEdit$i' value='edit'  /></td>
                                            <td><input type='checkbox' class='' $check4 name='chkTaskView[]' id='chkTaskView$i' value='details'  /></td>
                                            <td><input type='checkbox' class='' $check5 name='chkTaskDelete[]' id='chkTaskDelete$i' value='delete'  /></td>
                                            <td><input type='checkbox' class='' $check6 name='chkTaskReport[]' id='chkTaskReport$i' value='report'  /></td>
                                            <td>
                                                <input type='file' name='st_icon[]' maxlength='250' id='st_icon$i' class='span12' />
                                                <input type='hidden' name='st_icon_tmp[]' maxlength='250' id='st_icon_tmp$i' value='$result_array[st_icon]' />
                                            </td>
                                            <td>
                                                <input type='text' value='$folder_location[0]' name='textPrma[]' maxlength='250' id='textPrma$i' validate='Require' class='span12' />
                                                <input type='hidden' value='$folder_location[0]' name='textPrmah[]' maxlength='250' id='textPrmah$i'  />
                                            </td>
                                            <td>    </td>
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

<?php }
?>