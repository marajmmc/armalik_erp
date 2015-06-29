<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$tbl = _DB_PREFIX;
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
                                    No
                                </th>
                                <th style="width:5%">
                                    Zone
                                </th>
                                <th style="width:20%">
                                    Territory
                                </th>
                                <th style="width:22%">
                                    Distributor
                                </th>
                                <th style="width:5%">
                                    PO
                                </th>
                                <th style="width:5%">
                                    Collection
                                </th>
                                <th style="width:5%">
                                    Date
                                </th>
                                <th style="width:20%">
                                    Activities
                                </th>
                                <th style="width:20%">
                                    Problem
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT
                                azt.*,
                                ati.territory_name,
                                azi.zone_name,
                                az.zillanameeng,
                                adi.distributor_name
                            FROM
                                $tbl" . "zi_task azt
                            LEFT JOIN $tbl" . "territory_info ati ON ati.territory_id = azt.territory_id
                            LEFT JOIN $tbl" . "zone_info azi ON azi.zone_id = azt.zone_id
                            LEFT JOIN $tbl" . "zilla az ON az.zillaid = azt.district_id
                            LEFT JOIN $tbl" . "distributor_info adi ON adi.distributor_id = azt.distributor_id
                            WHERE azt.zone_id ='".$_SESSION['zone_id']."'
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
                                    <tr class="<?php echo $rowcolor ?> pointer" id="tr_id<?php echo $i; ?>" onclick="get_rowID('<?php echo $result_array['id']; ?>', '<?php echo $i; ?>')" ondblclick="details_form();">
                                        <td>
                                            <?php echo $i; ?>
                                        </td>
                                        <td><?php echo $result_array['zone_name']; ?></td>
                                        <td><?php echo $result_array['territory_name']; ?></td>
                                        <td><?php echo $result_array['distributor_name']; ?></td>
                                        <td><?php echo $result_array['purchase_order']; ?></td>
                                        <td><?php echo $result_array['collection']; ?></td>
                                        <td><?php echo $result_array['task_entry_date']; ?></td>
                                        <td><?php echo $result_array['activities']; ?></td>
                                        <td><?php echo $result_array['problem']; ?></td>
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
