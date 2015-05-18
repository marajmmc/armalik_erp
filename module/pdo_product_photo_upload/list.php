<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$tbl = _DB_PREFIX;
$user_id= $_SESSION['user_id'];
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
                                <th style="width:2%">
                                    Sl No
                                </th>
                                <th style="width:10%">
                                    Variety Name
                                </th>
                                <th style="width:5%">
                                    Checked Variety
                                </th>
                                <th style="width:10%">
                                    Farmer Name
                                </th>
                                <th style="width:10%">
                                    Upload Date
                                </th>
                                <th style="width:5%">
                                    Status
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT
                                        $tbl" . "pdo_photo_upload.upload_id,
                                        $tbl" . "varriety_info.varriety_name,
                                        CASE
                                                WHEN $tbl" . "varriety_info.type=0 THEN 'ARM'
                                                WHEN $tbl" . "varriety_info.type=1 THEN 'Check Variety'
                                                WHEN $tbl" . "varriety_info.type=2 THEN 'Upcoming'
                                        END as type,
                                        $tbl" . "farmer_info.farmer_name,
                                        $tbl" . "pdo_photo_upload.upload_date,
                                        $tbl" . "pdo_photo_upload.`status`
                                    FROM
                                        $tbl" . "pdo_photo_upload
                                        LEFT JOIN $tbl" . "varriety_info ON $tbl" . "varriety_info.varriety_id = $tbl" . "pdo_photo_upload.pdo_id
                                        LEFT JOIN $tbl" . "farmer_info ON $tbl" . "farmer_info.farmer_id = $tbl" . "pdo_photo_upload.farmer_id
                                    WHERE
                                        $tbl" . "pdo_photo_upload.del_status='0' AND $tbl" . "pdo_photo_upload.entry_by='$user_id'
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
                                    <tr class="<?php echo $rowcolor ?> pointer" id="tr_id<?php echo $i; ?>" onclick="get_rowID('<?php echo $result_array["upload_id"] ?>', '<?php echo $i; ?>')" ondblclick="details_form();">
                                        <td>
                                            <?php echo $i; ?>
                                        </td>
                                        <td><?php echo $result_array['varriety_name']; ?></td>
                                        <td><?php echo $result_array['type']; ?></td>
                                        <td><?php echo $result_array['farmer_name']; ?></td>
                                        <td><?php echo $db->date_formate($result_array['upload_date']); ?></td>
                                        <td><?php echo $result_array['status']; ?></td>
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
