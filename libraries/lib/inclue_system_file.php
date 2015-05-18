<?php
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
session_start();
ob_start();

if (isset($_SESSION['task_name'])) {
    unset($_SESSION['task_name']);
}
if ($_SESSION['logged'] != 'yes') {
    $_REQUEST["msg"] = "TimeoutC";
    header("location:../../index.php");
}

$db = new Database();
$tbl = _DB_PREFIX;
$user_id = $_SESSION['user_id'];
$employee_id = $_SESSION['employee_id'];
$user_name = $_SESSION['user_name'];
$ModuleDirName = '';
$page_title = '';
$sql = "SELECT `employee_name`,user_name, user_status FROM `$tbl" . "user_login` WHERE user_id = '" . $user_id . "'";
if ($db->open()) {
    $db->query($sql);
    $user_head_name = $db->singleFieldData('employee_name');
    $user_name_top_menu = $db->singleFieldData('user_name');
    $user_status = $db->singleFieldData('user_status');
}
if($user_status!="Active"){
   header("location:../../index.php"); 
}
if (!@$_GET['buttonID']) {
    $taskw = "";
} else {
    $taskw = "and $tbl" . "system_task.st_id='" . @$_GET['buttonID'] . "'";
}
$sql_modules = "SELECT
                $tbl" . "system_module.sm_name,
                $tbl" . "system_module.sm_icon,
                $tbl" . "system_task.st_pram,
                $tbl" . "system_task.st_name,
                $tbl" . "system_task.st_id
            FROM
                $tbl" . "system_module
                LEFT JOIN $tbl" . "system_task ON $tbl" . "system_task.st_sm_id = $tbl" . "system_module.sm_id
            WHERE $tbl" . "system_module.sm_id='" . @$_GET['menuID'] . "' $taskw";
if ($db->open()) {
    @$result_mod = $db->query($sql_modules);
    @$_SESSION['page_name'] = $result_mod = $db->singleFieldData('sm_name');
    @$dir = $result_mod = $db->singleFieldData('st_pram');
    @$stname = $result_mod = $db->singleFieldData('st_name');
    @$smicon = $result_mod = $db->singleFieldData('sm_icon');
    $DirName = explode("/", $dir);
    $ModuleDirName = $DirName['0'];
}
$TaskName = $db->TaskName(@$_GET['menuID'], @$_GET['buttonID']);
if ($TaskName != "") {
    $TN = " - " . $TaskName;
    $_SESSION['task_name'] = $TaskName;
} else {
    $TN = "";
}
?>
<script src="../../system_js/form_js.js"></script>
<?php
if ($ModuleDirName != "") {
    $page_title = $_SESSION['page_name'] . $TN;
    ?>
    <link rel="icon" type="image/ico" href="../../system_images/module_icon/<?php echo $smicon; ?>">
    <script src="../../module/<?php echo $ModuleDirName ?>/js/action.js"></script>
    <?php
} else {
    $page_title = 'Dashboard';
    echo "<link rel='icon' type='image/ico' href='../../system_images/logo-title.png'>";
}
?>

<title>
    <?php echo $page_title ?>
</title>
    <script src="system_js/jquery.min.js"></script>
<!DOCTYPE html>
<!--[if lt IE 7]>

<html class="lt-ie9 lt-ie8 lt-ie7" lang="en">

<![endif]-->
<!--[if IE 7]>

<html class="lt-ie9 lt-ie8" lang="en">

<![endif]-->
<!--[if IE 8]>

<html class="lt-ie9" lang="en">

<![endif]-->
<!--[if gt IE 8]>
<!-->
