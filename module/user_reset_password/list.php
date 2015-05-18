<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$tbl = _DB_PREFIX;
$user_id = $_SESSION['user_id'];
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
                                <th style="width:10%">
                                    User Name
                                </th>
                                <th style="width:10%" class="hidden-phone">
                                    Employee Name
                                </th>
                                <th style="width:10%">
                                    User Level
                                </th>
                                <th style="width:10%">
                                    Request Date
                                </th>
                                <th style="width:5%">
                                    Del
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT
                                        $tbl" . "password_reset_request.id,
                                        $tbl" . "password_reset_request.user_id,
                                        $tbl" . "password_reset_request.entry_date,
                                        $tbl" . "user_login.employee_name,
                                        $tbl" . "user_login.user_level,
                                        $tbl" . "user_login.user_name
                                    FROM
                                        $tbl" . "password_reset_request
                                        LEFT JOIN $tbl" . "user_login ON $tbl" . "user_login.user_id = $tbl" . "password_reset_request.user_id
                                    WHERE $tbl" . "password_reset_request.del_status='0' AND $tbl" . "password_reset_request.status='Request'
                        ";
                            if ($db->open()) {
                                $result = $db->query($sql);
                                $i = 1;
                                $z=0;
                                while ($result_array = $db->fetchAssoc()) {
                                    if ($i % 2 == 0) {
                                        $rowcolor = "gradeC";
                                    } else {
                                        $rowcolor = "gradeA success";
                                    }
                                    ?>
                                    <tr class="<?php echo $rowcolor ?> pointer" id="tr_id<?php echo $z; ?>" onclick="get_rowID('<?php echo $result_array["user_id"] ?>', '<?php echo $z; ?>')" ondblclick="details_form();">
                                        <td>
                                            <?php echo $i; ?>
                                        </td>
                                        <td><?php echo $result_array['user_name']; ?></td>
                                        <td><?php echo $result_array['employee_name']; ?></td>
                                        <td><?php echo $result_array['user_level']; ?></td>
                                        <td><?php echo $db->date_formate($result_array['entry_date']); ?></td>
                                        <td class="hidden-phone">
                                            <span class="tools">
                                                <a class='btn btn-warning2' data-original-title='' onclick="fnc_delete_request('<?php echo $z?>','<?php echo $result_array['id']?>')">
                                                    <i class='icon-white icon-trash'> </i>
                                                </a>
                                            </span>
                                        </td>
                                    </tr>
                                    <?php
                                    ++$i;
                                    ++$z;
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
