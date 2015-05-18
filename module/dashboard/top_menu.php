<?php
if (isset($_SESSION['sm_id'])) {
    unset($_SESSION['sm_id']);
}
if (isset($_SESSION['st_id'])) {
    unset($_SESSION['st_id']);
}
//if ($_SESSION['logged'] != 'yes') {
//    $_REQUEST["msg"] = "TimeoutC";
//    header("location:../../index.php");
//}

require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$db = new Database();
$db2 = new Database();

$user_name = $_SESSION['employee_name'];
$user_group_id = $_SESSION['user_group_id'];
@$_SESSION['sm_id'] = $_GET['menuID'];
@$_SESSION['st_id'] = $_GET['buttonID'];
$db->TaskName(@$_SESSION['sm_id'], @$_SESSION['st_id']);
$tbl = _DB_PREFIX;
$type = @$_SESSION['page_name'];
$modules_id = '';
$modules_name = '';
$module_icon_select = '';
$modules_title = '';
?>
<!--<div class="top-nav">
    <ul>-->
        <?php
//        $sql_modules = "SELECT
//                $tbl" . "system_module.sm_name, $tbl" . "system_module.sm_id, $tbl" . "system_module.sm_icon, $tbl" . "user_group.up_st_id
//                FROM
//                $tbl" . "user_group
//                LEFT JOIN $tbl" . "system_module ON $tbl" . "system_module.sm_id = $tbl" . "user_group.up_sm_id
//                where ug_id='$user_group_id'
//                GROUP BY
//                $tbl" . "system_module.sm_id 
//                ORDER BY $tbl" . "system_module.sm_order asc 
//                ";
//        if ($db->open()) {
//            $result_mod = $db->query($sql_modules);
//            while ($result_array_mod = $db->fetchAssoc($result_mod)) {
////                echo $result_array_mod['sm_name'];
//                if ($result_array_mod['sm_icon'] != '') {
//                    $module_icon = "<img src='../../system_images/module_icon/$result_array_mod[sm_icon]' width=25 height=25 alt=''>";
//                } else {
//                    $module_icon = '';
//                }
//                if ($result_array_mod['sm_name'] == $type) {
//                    if ($result_array_mod['sm_icon'] != '') {
//                        $module_icon_select = "<img src='../../system_images/module_icon/$result_array_mod[sm_icon]' width=25 height=25 alt=''>";
//                    } else {
//                        $module_icon_select = '';
//                    }
//                    $modules_id = $result_array_mod['sm_id'];
//                    $modules_name = $result_array_mod['sm_name'];
//                    echo "<li>
//                    <a href='../../module/dashboard/dashboard.php?menuID=$result_array_mod[sm_id]' class='selected'>
//                        <div class='fs1' aria-hidden='true' >
//                            $module_icon
//                        </div>
//                        $result_array_mod[sm_name]
//                    </a>
//                </li>";
//                } else {
//                    
//                    echo "<li>
//                    <a href='../../module/dashboard/dashboard.php?menuID=$result_array_mod[sm_id]'>
//                        <div class='fs1' aria-hidden='true' >
//                            $module_icon
//                        </div>
//                        $result_array_mod[sm_name]
//                    </a>
//                </li>
//                    ";
//                }
//            }
//            if($modules_name==""){
//                $modules_title='Hey, '. $user_name.'. Welcome to the online dashboard '.$_SESSION['page_title'];
//            }else{
//                $modules_title=$modules_name;
//            }
//            echo "</ul>
//            <div class='clearfix''></div>
//        </div><div class='sub-nav'>
//                    <ul>
//                        <li><a href='#' class='heading'>$module_icon_select $modules_title</a></li>";
//            $sql_task = "Select
//                        $tbl" . "system_task.st_id,
//                        $tbl" . "system_task.st_name,
//                        $tbl" . "system_task.st_pram,
//                        $tbl" . "system_task.st_status,
//                        st_order
//                        FROM
//                        $tbl" . "user_group
//                        LEFT JOIN $tbl" . "system_task ON $tbl" . "system_task.st_id = $tbl" . "user_group.up_st_id
//                        where  ug_id='$user_group_id' and st_sm_id='$modules_id' AND $tbl" . "system_task.st_status = 'Active'
//                        order by st_order
//                        ";
//            if ($db2->open()) {
//                $result_task = $db2->query($sql_task);
//
//                while ($result_array_task = $db2->fetchAssoc()) {
//                    echo "<li>
//                            <a href='../../module/$result_array_task[st_pram]?menuID=$modules_id&buttonID=$result_array_task[st_id]'>
//                                $result_array_task[st_name]
//                            </a>
//                        </li>
//                        ";
//                }
////                echo "</ul><input type='search' placeholder='Search' class='input-search hidden-phone' /></div>";
//                echo "</ul></div>";
//            }
//        }
        ?>
        <!--
        <div class="top-nav">
            <ul>
                <li>
                    <a href='index.php' class='selected'>
                        <div class='fs1' aria-hidden='true' data-icon='&#xe0a0;'></div>
                        Dashboard
                    </a>
                </li>
                <li>
                    <a href="forms.html">
                        <div class="fs1" aria-hidden="true" data-icon="&#xe0b8;"></div>
                        Forms
                    </a>
                </li>
                <li>
                    <a href="graphs.html">
                        <div class="fs1" aria-hidden="true" data-icon="&#xe096;"></div>
                        Graphs
                    </a>
                </li>
                <li>
                    <a href="ui-elements.html">
                        <div class="fs1" aria-hidden="true" data-icon="&#xe0d2;"></div>
                        UI Elements
                    </a>
                </li>
                <li>
                    <a href="icons.html">
                        <div class="fs1" aria-hidden="true" data-icon="&#xe0a9;"></div>
                        Icons
                    </a>
                </li>
                <li>
                    <a href="tables.html">
                        <div class="fs1" aria-hidden="true" data-icon="&#xe14a;"></div>
                        Tables
                    </a>
                </li>
                <li>
                    <a href="media.html">
                        <div class="fs1" aria-hidden="true" data-icon="&#xe00d;"></div>
                        Media
                    </a>
                </li>
                <li>
                    <a href="calendar.html">
                        <div class="fs1" aria-hidden="true" data-icon="&#xe052;"></div>
                        Calendar
                    </a>
                </li>
                <li>
                    <a href="typography.html">
                        <div class="fs1" aria-hidden="true" data-icon="&#xe100;"></div>
                        Typography
                    </a>
                </li>
                <li>
                    <a href="edit-profile.html">
                        <div class="fs1" aria-hidden="true" data-icon="&#xe0aa;"></div>
                        Extras
                    </a>
                </li>
            </ul>
            <div class="clearfix">
            </div>
        </div>
        <div class="sub-nav">
            <ul>
                <li><a href="#" class="heading">Dashboard</a></li>
                <li>
                    <a href="#mailbox">
                        Mailbox
                    </a>
                </li>
                <li>
                    <a href="#todo">
                        ToDos
                    </a>
                </li>
                <li>
                    <a href="#chats">
                        Chats
                    </a>
                </li>
                <li>
                    <a href="#notifications">
                        Notifications
                    </a>
                </li>
            </ul>
            <input type="search" placeholder="Search" class="input-search hidden-phone" />
            <div class="btn-group pull-right">
                <button class="btn btn-warning2">
                    Main Navigation
                </button>
                <button data-toggle="dropdown" class="btn btn-warning2 dropdown-toggle">
                    <span class="caret">
                    </span>
                </button>
                <ul class="dropdown-menu pull-right">
                    <li>
                        <a href="index.html" data-original-title="">
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="forms.html" data-original-title="">
                            Forms
                        </a>
                    </li>
                    <li>
                        <a href="graphs.html" data-original-title="">
                            Graphs
                        </a>
                    </li>
                    <li>
                        <a href="ui-elements.html" data-original-title="">
                            UI Elements
                        </a>
                    </li>
                    <li>
                        <a href="icons.html" data-original-title="">
                            Icons
                        </a>
                    </li>
                    <li>
                        <a href="tables.html" data-original-title="">
                            Tables
                        </a>
                    </li>
                    <li>
                        <a href="media.html" data-original-title="">
                            Media
                        </a>
                    </li>
                    <li>
                        <a href="calendar.html" data-original-title="">
                            Calendar
                        </a>
                    </li>
                    <li>
                        <a href="typography.html" data-original-title="">
                            Typography
                        </a>
                    </li>
                    <li>
                        <a href="edit-profile.html" data-original-title="">
                            Edit Profile
                        </a>
                    </li>
                    <li>
                        <a href="invoice.html" data-original-title="">
                            Invoice
                        </a>
                    </li>
                    <li>
                        <a href="login.html" data-original-title="">
                            Login
                        </a>
                    </li>
                    <li>
                        <a href="404.html" data-original-title="">
                            404 Page
                        </a>
                    </li>
                    <li>
                        <a href="500.html" data-original-title="">
                            500 Page
                        </a>
                    </li>
                    <li>
                        <a href="help.html" data-original-title="">
                            Help
                        </a>
                    </li>
                </ul>
            </div>
        </div>-->