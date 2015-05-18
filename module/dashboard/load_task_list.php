<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$db = new Database();
$db2 = new Database();
$tbl = _DB_PREFIX;
$module_id = $_POST['module_id'];
$user_group_id = $_SESSION['user_group_id'];
$task_icon='';
?>
<div class="wrapper">
    <ul class="month-income">
        <?php
        $sql_task = "Select
                $tbl" . "system_task.st_id,
                $tbl" . "system_task.st_name,
                $tbl" . "system_task.st_pram,
                $tbl" . "system_task.st_icon,
                $tbl" . "system_task.st_status,
                st_order
            FROM
                $tbl" . "user_group
                LEFT JOIN $tbl" . "system_task ON $tbl" . "system_task.st_id = $tbl" . "user_group.up_st_id
                where  ug_id='$user_group_id' and st_sm_id='$module_id' AND $tbl" . "system_task.st_status = 'Active'
                order by st_order
                        ";
        if ($db2->open()) {
            $result_task = $db2->query($sql_task);

//            while ($result_array_task = $db2->fetchAssoc()) {
//                echo "<li>
//                    
//                <a href='../../module/$result_array_task[st_pram]?menuID=$module_id&buttonID=$result_array_task[st_id]'>
//                    <h5>$result_array_task[st_name]</h5>
//                </a>
//            </li>
//            ";


            while ($result_array_task = $db2->fetchAssoc()) {
                if ($result_array_task['st_icon'] != '') {
                    $task_icon = "<div class = 'fs1' aria-hidden = 'true'>
                        <img src='../../system_images/task_icon/$result_array_task[st_icon]' width=25 height=25 title='$result_array_task[st_icon] Icon' alt='$result_array_task[st_icon]'>
                    </div>";
                } else {
                    $task_icon = '<div class = "fs1" aria-hidden = "true">
                        
                                                            <i class="icon-warning-sign" data-original-title="Share"> </i>
                                                        
                                                        </div>';
                }

                echo "<div class ='metro-nav-block nav-block-orange'>
                    
                <a class='btn-info' href='../../module/$result_array_task[st_pram]?menuID=$module_id&buttonID=$result_array_task[st_id]'>
                    
                        $task_icon 
                                        
                    <div class=''>$result_array_task[st_name]</div>
                </a>
            </div>
            ";
            }
        }
        ?>
    </ul>
</div>
