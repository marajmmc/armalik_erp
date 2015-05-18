<?php
$db = new Database();
$tbl = _DB_PREFIX;
//$user_id = $_SESSION['user_id'];
$groupID = $_SESSION['user_group_id'];
?>
<style>
    /*    .right_popup_menu_css{
            display: none;
            position: fixed;
            border: 1px solid;
            background-color: #666666;
            width: 50px;
            height: 100px;
            z-index: 10000;
        }*/
</style>
<!--<div id="right_popup_menu" name="right_popup_menu" class="right_popup_menu_css" >
    allah is one
</div>-->
<div class="row-fluid">
    <div class="span12">
        <div class="widget">
            <div class="widget-body">
                <a class='btn btn-inverse bottom-margin' href='../../module/dashboard/dashboard.php'>
                    <i class='icon-white icon-home' ></i> Dashboard
                </a>
                <?php
                $sql_button = "SELECT * FROM $tbl" . "user_group WHERE ug_id='$groupID' and up_sm_id='" . $_GET['menuID'] . "' and up_st_id='" . $_GET['buttonID'] . "'";
                if ($db2->open()) {
                    $result_button = $db2->query($sql_button);
                    while ($result_array_button = $db2->fetchAssoc()) {
                        if ($result_array_button['up_eventadd'] == 'add') {
                            echo "
                                <a class='btn btn-inverse bottom-margin' href='#' id='add_btn' onclick='Load_form()'>
                                    <i class='icon-white icon-plus-sign' ></i> New
                                </a>
                                ";
                        } else {
                            
                        }
                        if ($result_array_button['up_eventsave'] == 'save') {
                            echo "
                                <a class='btn btn-inverse bottom-margin' href='#' id='save_btn' onclick='Save_Rec()'>
                                    <i class='icon-white icon-hdd' ></i> Save
                                </a>
                                ";
                        } else {
                            
                        }
                        if ($result_array_button['up_eventedit'] == 'edit') {
                            echo "
                                <a class='btn btn-inverse bottom-margin' href='#' id='edit_btn' onclick='edit_form()'>
                                    <i class='icon-white icon-edit' ></i> Edit
                                </a>
                                ";
                        } else {
                            
                        }
                        if ($result_array_button['up_eventview'] == 'details') {
                            echo "
                                <a class='btn btn-inverse bottom-margin' href='#' id='view_btn' onclick='details_form()'>
                                    <i class='icon-white icon-eye-open' ></i> View
                                </a>
                                ";
                        } else {
                            
                        }
                    }
                }
                ?>
                <a class='btn btn-inverse bottom-margin' href='#' id='refresh_btn' onclick='refresh_frm()'>
                    <i class='icon-white icon-refresh' ></i> Refresh
                </a>
                <a class='btn btn-inverse bottom-margin' href='#'  id='back_btn' onclick="back_list()" >
                    <i class='icon-white icon-hand-left' ></i> Back
                </a>
                <a class='btn btn-inverse bottom-margin' href='#' onclick='logout()'>
                    <i class='icon-white icon-off' ></i> Log Out
                </a>
            </div>
        </div>
    </div>
</div>