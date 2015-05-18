<?php
session_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$db2 = new Database();
$tbl = _DB_PREFIX;
$okicon = "<a class='btn btn-inverse' href='#' data-original-title='' title='Access'>
                                        <i class='icon-white icon-ok' > 
            </i></a>";
$notokicon = "<a class='btn btn-inverse' href='#' data-original-title='' title='Not Access'>
                                        <i class='icon-white' style='background-position: -312px 0;'> 
            </i></a>";
?>

<div class="row-fluid">
    <div class="span12">
        <div class="widget">
            <div class="widget-header">
                <div class="title">
                    <a id="dynamicTable"><?php echo $db->Get_Auto_TaskName()?></a>
                    <span class="mini-title">
                        
                    </span>
                </div>
                <span class="tools">
                    <a class="btn btn-small" data-original-title="">
                        <i class="icon-list-alt" data-original-title="Share"> </i>
                    </a>
                </span>
                
            </div>
            <div class="widget-body">
                <div id="dt_example" class="example_alt_pagination">
                    <table class="table table-condensed table-striped table-hover table-bordered pull-left" id="data-table">

                        <thead>
                            <tr>
                                <th style="width:5%">
                                    Sl No
                                </th>
                                <th style="width:20%">
                                    Main Menu Name
                                </th>
                                <th style="width:16%">
                                    Sub Menu Name
                                </th>
                                <th style="width:5%" class="hidden-phone">
                                    New
                                </th>
                                <th style="width:5%" class="hidden-phone">
                                    Save
                                </th>
                                <th style="width:5%" class="hidden-phone">
                                    Edit
                                </th>
                                <th style="width:5%" class="hidden-phone">
                                    View
                                </th>
                                <th style="width:5%" class="hidden-phone">
                                    Del
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
                                $tbl" . "system_task.st_eventadd,
                                $tbl" . "system_task.st_eventsave,
                                $tbl" . "system_task.st_eventedit,
                                $tbl" . "system_task.st_eventdelete,
                                $tbl" . "system_task.st_eventview,
                                $tbl" . "system_task.st_eventreport
                            FROM
                                $tbl" . "system_task
                            LEFT JOIN $tbl" . "system_module ON $tbl" . "system_module.sm_id = $tbl" . "system_task.st_sm_id
                            WHERE st_status='Active' 
                            ORDER BY $tbl" . "system_module.sm_id
                            ";

                            if ($db->open()) {
                                $result = $db->query($sql);
                                $i = 1;
                                while ($result_array = $db->fetchAssoc()) {
                                    if ($i % 2 == 0) {
                                        $rowcolor = "gradeC";
                                    } else {
                                        $rowcolor = "gradeA success";
                                    }
                                    if ($result_array['sm_icon'] != '') {
                                        $module_icon = "<img src='../../system_images/module_icon/$result_array[sm_icon]' width=25 height=25 title='$result_array[sm_name] Icon' alt='$result_array[sm_name]'>";
                                    } else {
                                        $module_icon = '<a class="btn btn-small" data-original-title="No Icon" title="No Icon">
                                                            <i class="icon-warning-sign" data-original-title="Share"> </i>
                                                        </a>';
                                    }
                                    if ($result_array['st_icon'] != '') {
                                        $task_icon = "<img src='../../system_images/task_icon/$result_array[st_icon]' width=25 height=25 title='$result_array[st_icon] Icon' alt='$result_array[st_icon]'>";
                                    } else {
                                        $task_icon = '<a class="btn btn-small" data-original-title="No Icon" title="No Icon">
                                                            <i class="icon-warning-sign" data-original-title="Share"> </i>
                                                        </a>';
                                    }
                                    ?>
                                    <tr class="<?php echo $rowcolor ?> pointer" id="tr_id<?php echo $i; ?>" onclick="get_rowID('<?php echo $result_array["sm_id"] ?>', '<?php echo $i; ?>')" ondblclick="details_form();">
                                        <td>
                                            <?php echo $i; ?>
                                        </td>
                                        <td>
                                            <?php echo $module_icon; ?> <?php echo $result_array['sm_name']; ?>
                                        </td>
                                        <td>
                                            <?php echo $task_icon; ?> <?php echo $result_array['st_name']; ?>
                                        </td>
                                        <td class="hidden-phone">
                                            <?php
                                            if ($result_array['st_eventadd'] == 'add') {
                                                echo $okicon;
                                            } else {
                                                echo $notokicon;
                                            }
                                            ?>
                                        </td>
                                        <td class="hidden-phone">
                                            <?php
                                            if ($result_array['st_eventsave'] == 'save') {
                                                echo $okicon;
                                            } else {
                                                echo $notokicon;
                                            }
                                            ?>
                                        </td>
                                        <td class="hidden-phone">
                                            <?php
                                            if ($result_array['st_eventedit'] == 'edit') {
                                                echo $okicon;
                                            } else {
                                                echo $notokicon;
                                            }
                                            ?>
                                        </td>
                                        <td class="hidden-phone">
                                            <?php
                                            if ($result_array['st_eventview'] == 'details') {
                                                echo $okicon;
                                            } else {
                                                echo $notokicon;
                                            }
                                            ?>
                                        </td>
                                        <td class="hidden-phone">
                                            <?php
                                            if ($result_array['st_eventdelete'] == 'delete') {
                                                echo $okicon;
                                            } else {
                                                echo $notokicon;
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <?php
                                    ++$i;
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                    <div class="clearfix">
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<script type="text/javascript">
    //Data Tables
    $(document).ready(function () {
        $('#data-table').dataTable({
            "sPaginationType": "full_numbers"
        });
    });

    jQuery('.delete-row').click(function () {
        var conf = confirm('Continue delete?');
        if (conf) jQuery(this).parents('tr').fadeOut(function () {
            jQuery(this).remove();
        });
        return false;
    });
</script>