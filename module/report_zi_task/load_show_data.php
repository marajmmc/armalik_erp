<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$tbl = _DB_PREFIX;

if ($_POST['division_id'] != "")
{
    $division_id = "AND $tbl"."zi_task.division_id='" . $_POST['division_id'] . "'";
}
else
{
    $division_id = "";
}
if ($_POST['zone_id'] != "")
{
    $zone_id = "AND $tbl"."zi_task.zone_id='" . $_POST['zone_id'] . "'";
}
else
{
    $zone_id = "";
}

if ($_POST['territory_id'] != "")
{
    $territory_id = "AND $tbl"."zi_task.territory_id='" . $_POST['territory_id'] . "'";
}
else
{
    $territory_id = "";
}
if ($_POST['zilla_id'] != "")
{
    $district_id = "AND $tbl"."zi_task.district_id='" . $_POST['zilla_id'] . "'";
}
else
{
    $district_id = "";
}
if ($_POST['distributor_id'] != "")
{
    $distributor_id = "AND $tbl"."zi_task.distributor_id='" . $_POST['distributor_id'] . "'";
}
else
{
    $distributor_id = "";
}

if($_POST['from_date'] != "" && $_POST['to_date'] != "")
{
    $between = "AND $tbl"."zi_task.task_entry_date BETWEEN '" . $db->date_formate($_POST['from_date']) . "' AND '" . $db->date_formate($_POST['to_date']) . "'";
}
else
{
    $between = "";
}

if($_POST['criteria'] == 1)
{
    $criteria = "AND ($tbl"."zi_task.activities != '' OR $tbl"."zi_task.activities_image != '')";
}
elseif($_POST['criteria'] == 2)
{
    $criteria = "AND ($tbl"."zi_task.problem != '' OR $tbl"."zi_task.problem_image != '')";
}
else
{
    $criteria = "";
}
?>

<a class="btn btn-small btn-success" data-original-title="" onclick="print_rpt()" style="float: right;">
    <i class="icon-print" data-original-title="Share"> </i> Print
</a>
<div id="PrintArea" style="background-color: white;" >
    <?php include_once '../../libraries/print_page/Print_header.php'; ?>
    <table class="table table-condensed table-striped table-hover table-bordered pull-left report" id="data-table">
        <thead>
            <tr>
                <th style="width:5%">
                    Date
                </th>
                <th style="width:5%">
                    Division
                </th>
                <th style="width:5%">
                    Zone
                </th>
                <th style="width:5%">
                    Territory
                </th>
                <th style="width:5%">
                    District
                </th>
                <th style="width:5%">
                    Distributor
                </th>
                <th style="width:5%">
                    PO
                </th>
                <th style="width:5%">
                    Collection
                </th>
                <th style="width:5%">
                    Activities Text
                </th>
                <th style="width:5%">
                    Activities Picture
                </th>
                <th style="width:7%">
                    Problem Text
                </th>
                <th style="width:7%">
                    Problem Picture
                </th>
                <th style="width:5%">
                    Recommendation
                </th>
                <th style="width:5%">
                    Solution
                </th>
            </tr>
        </thead>
        <tbody>
            <?php
            $division_name='';
            $zone_name='';
            $territory_name='';
            $sql="SELECT
                $tbl"."zi_task.*,
                $tbl"."division_info.division_name,
                $tbl"."zone_info.zone_name,
                $tbl"."territory_info.territory_name,
                $tbl"."zilla.zillanameeng,
                $tbl"."distributor_info.distributor_name

                FROM
                $tbl"."zi_task
                INNER JOIN $tbl"."zone_info ON $tbl"."zone_info.zone_id = $tbl"."zi_task.zone_id
                INNER JOIN $tbl"."division_info ON $tbl"."division_info.division_id = $tbl"."zi_task.division_id
                INNER JOIN $tbl"."territory_info ON $tbl"."territory_info.territory_id = $tbl"."zi_task.territory_id
                INNER JOIN $tbl"."zilla ON $tbl"."zilla.zillaid = $tbl"."zi_task.district_id
                INNER JOIN $tbl"."distributor_info ON $tbl"."distributor_info.distributor_id = $tbl"."zi_task.distributor_id
                WHERE
                $tbl"."zi_task.status='1'
                $division_id $zone_id $territory_id $district_id $distributor_id $between $criteria
		        ";

            if ($db->open())
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
                    <tr class="<?php echo $rowcolor; ?> pointer" id="tr_id<?php echo $i; ?>" onclick="get_rowID('<?php echo $result_array["id"] ?>', '<?php echo $i; ?>')">
                        <td><?php echo $db->date_formate($result_array['task_entry_date']); ?></td>
                        <td><?php echo $result_array['division_name']; ?></td>
                        <td><?php echo $result_array['zone_name']; ?></td>
                        <td><?php echo $result_array['territory_name']; ?></td>
                        <td><?php echo $result_array['zillanameeng']; ?></td>
                        <td><?php echo $result_array['distributor_name']; ?></td>
                        <td><?php echo $result_array['purchase_order']; ?></td>
                        <td><?php echo $result_array['collection']; ?></td>
                        <td><?php echo $result_array['activities']; ?></td>
                        <td>
                            <?php
                            if(isset($result_array['activities_image']) && strlen($result_array['activities_image'])>0)
                            {
                                ?>
                                <img height="70" width="70" src="../../system_images/zi_task/<?php echo $result_array['activities_image']?>" />
                            <?php
                            }
                            else
                            {
                                ?>
                                <img height="70" width="70" src="../../system_images/zi_task/no_image.jpg" />
                            <?php
                            }
                            ?>
                        </td>
                        <td><?php echo $result_array['problem']; ?></td>
                        <td>
                            <?php
                            if(isset($result_array['problem_image']) && strlen($result_array['problem_image'])>0)
                            {
                                ?>
                                <img height="70" width="70" src="../../system_images/zi_task/<?php echo $result_array['problem_image']?>" />
                            <?php
                            }
                            else
                            {
                                ?>
                                <img height="70" width="70" src="../../system_images/zi_task/no_image.jpg" />
                            <?php
                            }
                            ?>
                        </td>
                        <td><?php echo $result_array['recommendation']; ?></td>
                        <td><?php if(strlen($result_array['solution'])==0){echo 'Not Yet';}else{echo $result_array['solution'];} ?></td>
                    </tr>
                    <?php
                    ++$i;
                }
            }
            ?>
        </tbody>
    </table>
    <?php include_once '../../libraries/print_page/Print_footer.php'; ?>
</div>