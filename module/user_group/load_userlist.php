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
?>


<table class="table table-condensed table-striped table-hover table-bordered pull-left" id="data-table">

    <thead>
        <tr>
            <th style="width:5%">No</th>
            <th style="width:35%">User Name</th>
            <th style="width:35%">Employee Name</th>
            <th style="width:5%">Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
//                    $shop_code = $_SESSION['shop_code'];
//$tbl_prefix = 'shop_';
        $tbl = _DB_PREFIX;
        $db = new Database();
        $sql = "SELECT
                    $tbl" . "user_login.id,
                    user_id,
                    user_name,
                    employee_name,
                    $tbl" . "user_group.ug_name,
                    user_status
                FROM
                    $tbl" . "user_login
                    LEFT JOIN $tbl" . "user_group ON $tbl" . "user_group.ug_id=$tbl" . "user_login.user_group_id
                WHERE
                    $tbl" . "user_group.ug_id='" . $_POST['groupid'] . "'
                GROUP BY $tbl" . "user_group.ug_name, user_name
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
                ?>
                <tr id="tr_id<?php echo $i; ?>" class="<?php echo $rowcolor ?> pointer">
                    <td><?php echo $i; ?></td>
                    <td class="td_row"><?php echo $result_array['user_name']; ?></td>
                    <td><?php echo $result_array['employee_name']; ?></td>
                    <td>
                        <?php if ($result_array['user_status'] == "Active") { ?>
                            <span class="tools">
                                <a href="#myModal" role="button" class="btn btn-small" data-toggle="modal" onclick="fnc_delete_usergroup('<?php echo $result_array["id"] ?>', '<?php echo $i; ?>')">
                                    <i class="icon-ok-sign" data-original-title="Share"> </i>
                                </a>
                            </span>
                        <?php } else { ?>
                            <span class="tools">
                                <a href="#myModal" role="button" class="btn btn-small" data-toggle="modal" onclick="fnc_delete_usergroup('<?php echo $result_array["id"] ?>', '<?php echo $i; ?>')">
                                    <i class="icon-remove-sign" data-original-title="Share"> </i>
                                </a>
                            </span>
                        <?php } ?>
                    </td>
                    <?php
                    ++$i;
                }
            }
            ?>
    </tbody>
</table>
