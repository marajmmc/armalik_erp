<?php
session_start();
if ($_SESSION['logged'] != 'yes') {
    $_REQUEST["msg"] = "TimeoutC";
    header("location:../../index.php");
}

require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$db = new Database();
$db2 = new Database();
$user_id = $_SESSION['user_id'];
$employee_id = $_SESSION['employee_id'];
$tbl = _DB_PREFIX;


$task_name = array();
$task_add = array();
$task_save = array();
$task_edit = array();
$task_view = array();
$task_delete = array();
$task_report = array();
$task_folder = array();
$task_folderh = array();

$task_name = $_POST["textTsName"];
$task_add = $_POST["chkTaskAdd"];
$task_save = $_POST["chkTaskSave"];
$task_edit = $_POST["chkTaskEdit"];
$task_view = $_POST["chkTaskView"];
$task_delete = $_POST["chkTaskDelete"];
$task_report = $_POST["chkTaskReport"];
$task_folder = $_POST["textPrma"];
$task_folderh = $_POST["textPrmah"];
$file_name = '';
$filestIconPic = '';
$sm_icon = '';

if (@$_FILES["sm_icon"]['name'] != "") {
    @$sm_icon_tmp=$_POST['sm_icon_tmp'];
    @unlink("../../system_images/module_icon/$sm_icon_tmp");
    @$ext = end(explode(".", $_FILES["sm_icon"]['name']));
    @$file_name = $_POST['rowID'] . "." . $ext;
    @copy($_FILES['sm_icon']['tmp_name'], "../../system_images/module_icon/$file_name");
    @$sm_icon = ", sm_icon='$file_name'";
}

$mSQL_mod = "update `$tbl" . "system_module` set
        sm_name='" . $_POST["sm_name"] . "' $sm_icon
    where sm_id='" . $_POST['rowID'] . "'    
";
if ($db->open()) {
    $db->query($mSQL_mod);
    $db->freeResult();
}
$db->system_event_log('', $user_id, $employee_id, $_POST['rowID'], '', $tbl . 'system_module', 'Update', '');
if (sizeof($_POST["st_id"]) > 0) {

    $i = 0;
    for ($j = 0; $j < sizeof($_POST["st_id"]); $j++) {
        $tmpic=$_POST['st_icon_tmp'][$j];
        if (@$_FILES["st_icon"]['name'][$j] != "") {
            @unlink("../../system_images/task_icon/$tmpic");
            @$extt = end(explode(".", $_FILES["st_icon"]['name'][$j]));
            @$filestIconPic = $_POST['st_id'][$j] . "." . $extt;
            @copy($_FILES['st_icon']['tmp_name'][$j], "../../system_images/task_icon/$filestIconPic");
            @$st_icon = ", st_icon='$filestIconPic'";
        }
        
        if ($_POST['st_id'][$j] != "") {

            $folder_location = $task_folder[$j] . "/list_frm.php";

            $MaxPos = $j + 1;

            $mSQL_task = "update `$tbl" . "system_task` set
				`st_name`='" . $task_name[$j] . "', 
				`st_pram`='" . $folder_location . "', 
				`st_eventadd`='" . $task_add[$j] . "',
				`st_eventsave`='" . $task_save[$j] . "', 
				`st_eventedit`='" . $task_edit[$j] . "', 
				`st_eventdelete`='" . $task_delete[$j] . "', 
				`st_eventview`='" . $task_view[$j] . "', 
				`st_eventreport`='" . $task_report[$j] . "'
                                    $st_icon
			where st_id='" . $_POST['st_id'][$j] . "'
				";

            if ($db2->open()) {
                $db2->query($mSQL_task);
                $db2->freeResult();
            }
            $db->system_event_log('', $user_id, $employee_id, $_POST['rowID'], $_POST['st_id'][$j], $tbl . 'system_task', 'Update', '');
            $db->rename_task_name("../../module/$task_folderh[$j]", "../../module/$task_folder[$j]");
        } else {
            $file_name = '';
            $folder_location = $task_folder[$j] . "/list_frm.php";

            $MaxPos = $j + 1;
            $MaxID_task = 'ST-' . $db->getMaxID_six_digit($tbl . 'system_task', 'st_id');

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
                `st_entry_date`) Values(
                '$MaxID_task',
                '" . $_POST['rowID'] . "',
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
                '')";

            $i++;

            if ($db2->open()) {
                $db2->query($mSQL_task);
                $db2->freeResult();
            }
            $db->system_event_log('', $user_id, $employee_id, $_POST['rowID'], $MaxID_task, $tbl . 'system_task', 'Save', '');
            $db->recurse_copy("../../libraries/demo_task_copy", "../../module/$task_folder[$j]");
        }
    }
}
?>
<script>
    window.location.href = "list_frm.php?menuID=<?php echo $_SESSION['sm_id']; ?>&buttonID=<?php echo $_SESSION['st_id']; ?>";
</script>