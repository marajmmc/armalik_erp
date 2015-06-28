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
                                    Sl.
                                </th>
                                <th style="width:20%">
                                    Territory
                                </th>
                                <th style="width:20%">
                                    Date
                                </th>
                                <th style="width:20%">
                                    Year
                                </th>
                                <th style="width:20%">
                                    From Month
                                </th>
                                <th style="width:20%">
                                    To Month
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php

                            $sql = "SELECT
                                ztp.id,
                                ztp.year,
                                ztp.start_month,
                                ztp.end_month,
                                ztp.status,
                                ztp.del_status,
                                ztp.entry_by,
                                ztp.entry_date,
                                ztp.division_id,
                                ztp.zone_id,
                                ztp.territory_id,
                                ati.territory_name,
                                ay.year_name,
                                ztp.status

                                FROM
                                $tbl" . "zi_tour_plan ztp

                                LEFT JOIN $tbl" . "territory_info ati ON ati.territory_id = ztp.territory_id
                                LEFT JOIN $tbl" . "year ay ON ay.year_id = ztp.year

                                WHERE ztp.zone_id ='".$_SESSION['zone_id']."' AND ztp.status=1
                                GROUP BY ztp.year, ztp.start_month, ztp.end_month, ztp.division_id, ztp.zone_id
                                ";

                            if($db->open())
                            {
                                $result = $db->query($sql);
                                $i = 1;
                                while ($result_array = $db->fetchAssoc())
                                {
                                    if ($i % 2 == 0)
                                    {
                                        $rowcolor = "gradeC";
                                    }
                                    else
                                    {
                                        $rowcolor = "gradeA success";
                                    }
                                    ?>
                                        <tr class="<?php echo $rowcolor ?> pointer" id="tr_id<?php echo $i; ?>" onclick="get_rowID('<?php echo $result_array["year"].'~'. $result_array["division_id"].'~'.$result_array["zone_id"].'~'.$result_array["start_month"].'~'.$result_array["end_month"]?>', '<?php echo $i; ?>')" ondblclick="details_form();">
                                            <td>
                                                <?php echo $i; ?>
                                            </td>
                                            <td><?php echo $result_array['territory_name']; ?></td>
                                            <td><?php echo $result_array['entry_date']; ?></td>
                                            <td><?php echo $result_array['year_name']; ?></td>
                                            <td>
                                                <?php
                                                    $months = $db->get_month_array();
                                                    foreach($months as $val=>$month)
                                                    {
                                                        if($val==$result_array['start_month'])
                                                        {
                                                            echo $month;
                                                            break;
                                                        }
                                                    }
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $months = $db->get_month_array();
                                                foreach($months as $val=>$month)
                                                {
                                                    if($val==$result_array['end_month'])
                                                    {
                                                        echo $month;
                                                        break;
                                                    }
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
