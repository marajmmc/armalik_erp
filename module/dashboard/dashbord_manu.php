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
<div class="row-fluid">
    <div class="span12">
        <div class="widget">
            <div class="widget-header">
                <div class="title">
                    Dashboard Menu
<!--                    <span class="mini-title">
                        Metro Navigation
                    </span>-->
                </div>
<!--                <span class="tools">
                    <a class="btn btn-info btn-small" href="#">Today</a>
                    <a class="btn btn-success btn-small" href="#">Yesterday</a>
                    <a class="btn btn-warning2 btn-small" href="#">Last week</a>
                </span>-->
            </div>
            <div class="widget-body">
                <div class="row-fluid">
                    <div class="metro-nav">
                        <?php
                        $sql_modules = "SELECT
                $tbl" . "system_module.sm_name, $tbl" . "system_module.sm_id, $tbl" . "system_module.sm_icon, count($tbl" . "user_group.up_st_id) as count_stid,$tbl" . "user_group.up_st_id
                FROM
                $tbl" . "user_group
                LEFT JOIN $tbl" . "system_module ON $tbl" . "system_module.sm_id = $tbl" . "user_group.up_sm_id
                where ug_id='$user_group_id'
                GROUP BY
                $tbl" . "system_module.sm_id 
                ORDER BY $tbl" . "system_module.sm_order asc 
                ";
                        if ($db->open()) {
                            $i = 0;
                            $result_mod = $db->query($sql_modules);
                            while ($result_array_mod = $db->fetchAssoc($result_mod)) {
                                if ($i % 2 == 0) {
                                    $rowcolor = "gradeC";
                                } else {
                                    $rowcolor = "gradeA success";
                                }
//                echo $result_array_mod['sm_name'];
                                if ($result_array_mod['sm_icon'] != '') {
                                    $module_icon = "<img src='../../system_images/module_icon/$result_array_mod[sm_icon]' width=25 height=25 alt=''>";
                                } else {
                                    $module_icon = '';
                                }
                                ?>

                                <div class = "metro-nav-block nav-block-orange">
                                    <!--<a href="#myModal" role="button" class="btn-info" data-toggle="modal" onclick="tasklist_fnc('<?php // echo $result_array_mod["sm_id"]  ?>', '<?php // echo $result_array_mod["sm_name"]  ?>')">-->
                                    <a class="btn-info" onclick="tasklist_box_fnc('<?php echo $result_array_mod["sm_id"] ?>', '<?php echo $result_array_mod["sm_name"] ?>', '<?php echo $result_array_mod['sm_icon']; ?>')">
                                        <div class = "fs1" aria-hidden = "true">
                                            <?php echo $module_icon; ?>
                                        </div>
                                        <div class = "info"><?php echo $result_array_mod['count_stid'] ?> Task</div>
                                        <div class = "brand"><?php echo $result_array_mod['sm_name'] ?></div>
                                    </a>
                                </div>





                                <!--                                                        <div class = "metro-nav-block nav-block-yellow">
                                                                                            <a href = "#">
                                                                                                <div class = "fs1" aria-hidden = "true" data-icon = "&#xe036;"></div>
                                                                                                <div class = "info">5345</div>
                                                                                                <div class = "brand">Sales</div>
                                                                                            </a>
                                                                                        </div>
                                                                                        <div class = "metro-nav-block nav-block-blue double">
                                                                                            <a href = "#">
                                                                                                <div class = "fs1" aria-hidden = "true" data-icon = "&#xe097;"></div>
                                                                                                <div class = "info">$9902</div>
                                                                                                <div class = "brand">Income</div>
                                                                                            </a>
                                                                                        </div>
                                                                                        <div class = "metro-nav-block nav-block-green">
                                                                                            <a href = "#">
                                                                                                <div class = "fs1" aria-hidden = "true" data-icon = "&#xe09c;"></div>
                                                                                                <div class = "info">$431</div>
                                                                                                <div class = "brand">Expenses</div>
                                                                                            </a>
                                                                                        </div>
                                                                                        <div class = "metro-nav-block nav-block-red">
                                                                                            <a href = "#">
                                                                                                <div class = "fs1" aria-hidden = "true" data-icon = "&#xe0fa;"></div>
                                                                                                <div class = "info">288</div>
                                                                                                <div class = "brand">Cancelled</div>
                                                                                            </a>
                                                                                        </div>
                                                                                        <div class = "metro-nav-block nav-block-yellow">
                                                                                            <a href = "#">
                                                                                                <div class = "fs1" aria-hidden = "true" data-icon = "&#xe0d6;"></div>
                                                                                                <div class = "info">193</div>
                                                                                                <div class = "brand">Signup</div>
                                                                                            </a>
                                                                                        </div>
                                                                                        <div class = "metro-nav-block nav-block-green double">
                                                                                            <a href = "#">
                                                                                                <div class = "fs1" aria-hidden = "true" data-icon = "&#xe038;"></div>
                                                                                                <div class = "info">$39432</div>
                                                                                                <div class = "brand">Stock</div>
                                                                                            </a>
                                                                                        </div>
                                                                                        <div class = "metro-nav-block nav-block-orange">
                                                                                            <a href = "#">
                                                                                                <div class = "fs1" aria-hidden = "true" data-icon = "&#xe1c5;"></div>
                                                                                                <div class = "info">434</div>
                                                                                                <div class = "brand">Messages</div>
                                                                                            </a>
                                                                                        </div>
                                                                                        <div class = "metro-nav-block nav-block-blue">
                                                                                            <a href = "#">
                                                                                                <div class = "fs1" aria-hidden = "true" data-icon = "&#xe169;"></div>
                                                                                                <div class = "info">985</div>
                                                                                                <div class = "brand">Posts</div>
                                                                                            </a>
                                                                                        </div>
                                                                                        <div class = "metro-nav-block nav-block-yellow">
                                                                                            <a href = "#">
                                                                                                <div class = "fs1" aria-hidden = "true" data-icon = "&#xe16d;"></div>
                                                                                                <div class = "info">199</div>
                                                                                                <div class = "brand">Tweets</div>
                                                                                            </a>
                                                                                        </div>-->
                                <?php
                                ++$i;
                            }
                        }
                        ?>
                        <!--                        <div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                                            ×
                                                        </button>
                                                        <h3 id="myModalLabel">
                                                            Task List
                                                        </h3>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p id="p_data_load">
                                                            One fine body…
                                                        </p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button class="btn" data-dismiss="modal" aria-hidden="true">
                                                            Close
                                                        </button>
                                                                                        <button class="btn btn-primary" >
                                                                                            Save changes
                                                                                        </button>
                                                    </div>
                                                </div>-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row-fluid" id="task_menu" style="display: none;">
    <div class="span12">
        <div class="widget">
            <div class="widget-header">
                <div class="title" id="module_title">

                    <!--Dashboard Menu-->
<!--                    <span class="mini-title">
                        Metro Navigation
                    </span>-->
                </div>
            </div>
            <div class="widget-body">
                <div class="row-fluid">
                    <div class="metro-nav" id="p_data_load">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    //    function tasklist_fnc(ModuleID, ModuelName, Images){
    //        $("#p_data_load").html('');
    //        $.post("load_task_list.php", {module_id:ModuleID}, function(result){
    //            if(result){
    //                $("#myModalLabel").html(ModuelName);
    //                $("#p_data_load").html(result);   
    //            }
    //        })
    //    }
    function tasklist_box_fnc(ModuleID, ModuelName, Images){
        $("#task_menu").hide();
        $("#module_title").html('');
        $("#p_data_load").html('');
        $.post("load_task_list.php", {module_id:ModuleID}, function(result){
            if(result){
                $("#task_menu").fadeIn()
                $("#module_title").html('<img src="../../system_images/module_icon/'+Images+'" width="25" height="25"  />' + ' ' +ModuelName);
                $("#p_data_load").html(result);   
            }
        })
    }
</script>