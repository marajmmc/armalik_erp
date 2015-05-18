<?php
session_start();
if ($_SESSION['logged'] != 'yes') {
    $_REQUEST["msg"] = "TimeoutC";
    header("location:../../index.php");
}
//echo $_SESSION['shop_name_eng']; 
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$db = new Database();
$db2 = new Database();
$user_id = $_SESSION['user_id'];
$employee_id = $_SESSION['employee_id'];
$tbl = _DB_PREFIX;
$file_name = '';
/*
  @@ Create Array For Getting Post value
 */
$task_name = array();
$task_add = array();
$task_save = array();
$task_edit = array();
$task_view = array();
$task_delete = array();
$task_report = array();
$task_folder = array();

$task_name = $_POST["textTsName"];
$task_add = $_POST["chkTaskAdd"];
$task_save = $_POST["chkTaskSave"];
$task_edit = $_POST["chkTaskEdit"];
$task_view = $_POST["chkTaskView"];
$task_delete = $_POST["chkTaskDelete"];
$task_report = $_POST["chkTaskReport"];
$task_folder = $_POST["textPrma"];
$file_name = '';


if (sizeof($_POST["st_id"]) > 0) {
    $maxID_mod = 'SM-' . $db->getMaxID_six_digit($tbl . 'system_module', 'sm_id');
    if (@$_FILES["sm_icon"]['name'] != "") {
        $ext = end(explode(".", $_FILES["sm_icon"]['name']));
        $file_name = $maxID_mod . "." . $ext;
        copy($_FILES['sm_icon']['tmp_name'], "../../system_images/module_icon/$file_name");
    }

    $MaxPos = $db->MaxID($tbl . 'system_module', 'sm_id', '0');

    $mSQL_mod = "insert into `$tbl" . "system_module` (
        `sm_id`, 
        `sm_name`, 
        `sm_icon`, 
        `sm_order`,
        `sm_entry_date`
        ) Values(
        '$maxID_mod',
        '" . $_POST["sm_name"] . "',
        '$file_name',
        '$MaxPos',
        ''
        )";
    if ($db->open()) {
        $db->query($mSQL_mod);
        $db->freeResult();
    }
    $db->system_event_log('', $user_id, $employee_id, $maxID_mod, '', $tbl . 'system_module', 'Save', '');
    //    echo $maxID_mod;
    for ($j = 0; $j < sizeof($_POST["st_id"]); $j++) {
        /* $i = $_POST["elmIndex"][$j];

          $filestIconPic = "filestIconPic" . $i; */

        $MaxID_task = 'ST-' . $db->getMaxID_six_digit($tbl . 'system_task', 'st_id');
        
        if (@$_FILES["st_icon"]['name'][$j] != "") {
            $ext = end(explode(".", $_FILES["st_icon"]['name'][$j]));
            $filestIconPic = $MaxID_task . "." . $ext;
            copy($_FILES['st_icon']['tmp_name'][$j], "../../system_images/task_icon/$filestIconPic");
        }

        $folder_location = $task_folder[$j] . "/list_frm.php";
        $MaxPos = $j + 1;
        $mSQL_task = "insert into `$tbl" . "system_task` (
            `st_id`, 
            `st_sm_id`, 
            `st_name`, 
            `st_icon`, 
            `st_order`, 
            `st_pram`, 
            `st_eventadd`,
            `st_eventsave`, 
            `st_eventedit`, 
            `st_eventdelete`, 
            `st_eventview`, 
            `st_eventreport`, 
            `st_entry_date`
            ) Values(
            '$MaxID_task',
            '$maxID_mod',
            '" . $task_name[$j] . "',
            '$filestIconPic',
            '$MaxPos',
            '" . $folder_location . "',
            '" . $task_add[$j] . "',
            '" . $task_save[$j] . "',
            '" . $task_edit[$j] . "',
            '" . $task_delete[$j] . "',
            '" . $task_view[$j] . "',
            '" . $task_report[$j] . "',
            ''
            )";
        if ($db2->open()) {
            $db2->query($mSQL_task);
            $db2->freeResult();
        }
        $db->system_event_log('', $user_id, $employee_id, $maxID_mod, $MaxID_task, $tbl . 'system_task', 'Save', '');
        $db->recurse_copy("../../libraries/demo_task_copy", "../../module/$task_folder[$j]");
    }
}
?>
<script>
    window.location.href = "list_frm.php?menuID=<?php echo $_SESSION['sm_id']; ?>&buttonID=<?php echo $_SESSION['st_id']; ?>";
</script>