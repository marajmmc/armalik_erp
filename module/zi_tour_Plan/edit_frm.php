<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$tbl = _DB_PREFIX;

$user_zone = $_SESSION['zone_id'];
$postData = explode('~', $_POST['rowID']);
$year = $postData[0];
$zone_id = $postData[1];
$start_month = $postData[2];
$end_month = $postData[3];

$sql = "SELECT
            *
            FROM `$tbl" . "zi_tour_plan`
            WHERE $tbl" . "zi_tour_plan.year='" . $year . "' AND
            $tbl" . "zi_tour_plan.zone_id='" . $zone_id . "' AND
            $tbl" . "zi_tour_plan.start_month='" . $start_month . "' AND
            $tbl" . "zi_tour_plan.end_month='" . $end_month . "'
";

$arranged_array = array();

if ($db->open())
{
    $result = $db->query($sql);

    while ($row = $db->fetchAssoc($result))
    {
        $year = $row['year'];
        $start_month = $row['start_month'];
        $end_month = $row['end_month'];

//        $arranged_array[$row['week_day']][$row['day_time']]['territory'] = $row['territory_id'];
//        $arranged_array[$row['week_day']][$row['day_time']]['district'] = $row['district_id'];
//        $arranged_array[$row['week_day']][$row['day_time']]['distributor'][] = $row['distributor_id'];

//        echo '<pre>';
//        print_r($arranged_array);
//        echo '</pre>';
    }
}
?>

<div class="row-fluid">
    <div class="span12">
        <div class="widget">
            <div class="widget-header">
                <div class="title">
                    <a id="dynamicTable"><?php echo $db->Get_Auto_TaskName(); ?></a>
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
                <table class="table table-condensed table-striped table-hover table-bordered pull-left" id="data-table">
                    <thead>
                    <tr>
                        <th style="width:10%" id="territory_th_caption">
                            Year
                        </th>
                        <th style="width:10%" id="territory_th_caption">
                            From Month
                        </th>
                        <th style="width:10%" id="distributor_th_caption">
                            To Month
                        </th>
                    </tr>
                    </thead>
                    <tr>
                        <td>
                            <div class="controls">
                                <select id="year" name="year" class="span12" disabled>
                                    <option value="">Select</option>
                                    <?php
                                    $sql = "select year_id as fieldkey, year_name as fieldtext from $tbl" . "year";
                                    echo $db->SelectList($sql, $year);
                                    ?>
                                </select>
                                <input type="hidden" name="year" value="<?php echo $year;?>">
                            </div>
                        </td>
                        <td>
                            <div class="controls">
                                <select id="from_month" name="from_month" class="span12" disabled>
                                    <option value="">Select</option>
                                    <?php
                                    $monthArray = $db->get_month_array();
                                    foreach($monthArray as $val=>$month)
                                    {
                                        ?>
                                        <option value="<?php echo $val;?>" <?php if($val==$start_month){echo 'selected';}?>><?php echo $month;?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                                <input type="hidden" name="from_month" value="<?php echo $start_month;?>">
                            </div>
                        </td>
                        <td>
                            <div class="controls">
                                <select id="to_month" name="to_month" class="span12" disabled>
                                    <option value="">Select</option>
                                    <?php
                                    $monthArray = $db->get_month_array();
                                    foreach($monthArray as $val=>$month)
                                    {
                                        ?>
                                        <option value="<?php echo $val;?>" <?php if($val==$end_month){echo 'selected';}?>><?php echo $month;?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                                <input type="hidden" name="to_month" value="<?php echo $end_month;?>">
                            </div>
                        </td>
                    </tr>
                </table>

                <div id="dt_example" class="example_alt_pagination">
                    <table class="table table-condensed table-striped table-hover table-bordered pull-left" id="data-table">
                        <thead>
                        <tr>
                            <th style="width:1%" id="territory_th_caption">

                            </th>
                            <th style="width:1%" id="territory_th_caption">

                            </th>
                            <th style="width:10%" id="territory_th_caption">
                                Territory
                            </th>
                            <th style="width:10%" id="territory_th_caption">
                                District
                            </th>
                            <th style="width:10%" id="distributor_th_caption">
                                Customer
                            </th>
                        </tr>
                        </thead>

                        <?php
                        $week = $db->get_week_days();
                        foreach($week as $val=>$day)
                        {
                            ?>
                            <tr>
                                <td>
                                    <label class="label label-info text-center"><?php echo $day;?></label>
                                </td>
                                <td>
                                    <label class="label label-warning text-center">Morning</label>
                                </td>
                                <td class="territory_td_elm">
                                    <select name="plan[<?php echo $val;?>][1][territory_id]" class="span12 territory_id" placeholder="Territory" onchange="" >
                                        <option value="">Select</option>
                                        <?php
                                        $territory = $db->single_data_w($tbl.'zi_tour_plan','territory_id', "year='$year' AND start_month=$start_month AND end_month=$end_month AND zone_id='$user_zone' AND week_day='$val' AND day_time=1 AND status=1");
                                        $sql = "select territory_id as fieldkey, territory_name as fieldtext from $tbl" . "territory_info where zone_id='$user_zone'";
                                        echo $db->SelectList($sql, $territory['territory_id']);
                                        ?>
                                    </select>
                                </td>
                                <td class="district_td_elm">
                                    <select name="plan[<?php echo $val;?>][1][district_id]" class="span12 district_id" placeholder="District" onchange="" >
                                        <option value="">Select</option>
                                        <?php
                                        $district = $db->single_data_w($tbl.'zi_tour_plan','district_id', "year='$year' AND start_month=$start_month AND end_month=$end_month AND zone_id='$user_zone' AND week_day='$val' AND day_time=1 AND status=1");
                                        $sql_user_group = "SELECT
                                            $tbl" . "zilla.zillaid as fieldkey,
                                            $tbl" . "zilla.zillanameeng as fieldtext
                                        FROM
                                            $tbl" . "territory_assign_district
                                            LEFT JOIN $tbl" . "zilla ON $tbl" . "zilla.zillaid = $tbl" . "territory_assign_district.zilla_id
                                        WHERE
                                            $tbl" . "territory_assign_district.del_status=0
                                            AND $tbl" . "zilla.visible=0
                                            AND $tbl" . "territory_assign_district.status='Active'
                                            AND $tbl" . "territory_assign_district.territory_id='".$territory['territory_id']."'
                                            ";
                                        echo $db->SelectList($sql_user_group, $district['district_id']);
                                        ?>
                                    </select>
                                </td>
                                <td class="distributor_td_elm">
                                    <select name="plan[<?php echo $val;?>][1][distributor_id][]" class="span12 distributor_id" multiple="multiple" placeholder="Distributor">
                                        <option value="">Select</option>
                                        <?php
                                        $distributorQuery = "SELECT distributor_id FROM $tbl" . "zi_tour_plan WHERE year='$year' AND start_month='$start_month' AND end_month='$end_month' AND zone_id='$user_zone' AND week_day='$val' AND day_time=1 AND status=1";
                                        $distributors = $db->return_result_array($distributorQuery);

                                        $customers = array();
                                        foreach($distributors as $distributor)
                                        {
                                            $customers[] = $distributor['distributor_id'];
                                        }

                                        $sql = "select distributor_id as fieldkey, distributor_name as fieldtext from $tbl" . "distributor_info where status='Active' AND del_status='0' AND zone_id='$user_zone' AND territory_id='".$territory['territory_id']."' AND zilla_id='".$district['district_id']."' order by distributor_name";
                                        $distributorDropDownArray = $db->return_result_array($sql);
                                        foreach($distributorDropDownArray as $DropDown)
                                        {
                                        ?>
                                            <option value="<?php echo $DropDown['fieldkey'];?>" <?php if(in_array($DropDown['fieldkey'], $customers) && isset($DropDown['fieldkey'])){echo 'selected';}?>><?php echo $DropDown['fieldtext'];?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td></td>
                                <td>
                                    <label class="label label-success text-center">Afternoon</label>
                                </td>
                                <td class="territory_td_elm">
                                    <select name="plan[<?php echo $val;?>][2][territory_id]" class="span12 territory_id" placeholder="Territory" onchange="" >
                                        <option value="">Select</option>
                                        <?php
                                        $territory = $db->single_data_w($tbl.'zi_tour_plan','territory_id', "year='$year' AND start_month=$start_month AND end_month=$end_month AND zone_id='$user_zone' AND week_day='$val' AND day_time=2 AND status=1");
                                        $sql = "select territory_id as fieldkey, territory_name as fieldtext from $tbl" . "territory_info where zone_id='$user_zone'";
                                        echo $db->SelectList($sql, $territory['territory_id']);
                                        ?>
                                    </select>
                                </td>
                                <td class="district_td_elm">
                                    <select name="plan[<?php echo $val;?>][2][district_id]" class="span12 district_id" placeholder="District" onchange="" >
                                        <option value="">Select</option>
                                        <?php
                                        $district = $db->single_data_w($tbl.'zi_tour_plan','district_id', "year='$year' AND start_month=$start_month AND end_month=$end_month AND zone_id='$user_zone' AND week_day='$val' AND day_time=2 AND status=1");
                                        $sql_user_group = "SELECT
                                            $tbl" . "zilla.zillaid as fieldkey,
                                            $tbl" . "zilla.zillanameeng as fieldtext
                                        FROM
                                            $tbl" . "territory_assign_district
                                            LEFT JOIN $tbl" . "zilla ON $tbl" . "zilla.zillaid = $tbl" . "territory_assign_district.zilla_id
                                        WHERE
                                            $tbl" . "territory_assign_district.del_status=0
                                            AND $tbl" . "zilla.visible=0
                                            AND $tbl" . "territory_assign_district.status='Active'
                                            AND $tbl" . "territory_assign_district.territory_id='".$territory['territory_id']."'
                                            ";
                                        echo $db->SelectList($sql_user_group, $district['district_id']);
                                        ?>
                                    </select>
                                </td>
                                <td class="distributor_td_elm">
                                    <select name="plan[<?php echo $val;?>][2][distributor_id][]" class="span12 distributor_id" multiple="multiple" placeholder="Distributor">
                                        <option value="">Select</option>
                                        <?php
                                        $distributorQuery = "SELECT distributor_id FROM $tbl" . "zi_tour_plan WHERE year='$year' AND start_month='$start_month' AND end_month='$end_month' AND zone_id='$user_zone' AND week_day='$val' AND day_time=2 AND status=1";

                                        $distributors = $db->return_result_array($distributorQuery);

                                        $customers=array();
                                        foreach($distributors as $distributor)
                                        {
                                            $customers[] = $distributor['distributor_id'];
                                        }

                                        $sql = "select distributor_id as fieldkey, distributor_name as fieldtext from $tbl" . "distributor_info where status='Active' AND del_status='0' AND zone_id='$user_zone' AND territory_id='".$territory['territory_id']."' AND zilla_id='".$district['district_id']."' order by distributor_name";
                                        $distributorDropDownArray = $db->return_result_array($sql);
                                        foreach($distributorDropDownArray as $DropDown)
                                        {
                                            ?>
                                            <option value="<?php echo $DropDown['fieldkey'];?>" <?php if(in_array($DropDown['fieldkey'], $customers) && isset($DropDown['fieldkey'])){echo 'selected';}?>><?php echo $DropDown['fieldtext'];?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </table>

                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        <div id="div_show_rpt"></div>
    </div>
</div>

<script>
    $(document).ready(function(){
        session_load_fnc();
        turn_off_trigger();

        $(document).on("change",".territory_id",function()
        {
            if($(this).val().length>0)
            {
                $(this).parents().next(".district_td_elm").find(".district_id").html('');
                var location = $(this).parents().next(".district_td_elm").find(".district_id");

                $.post("../../libraries/ajax_load_file/load_territory_assign_district.php",
                    {
                        zone_id:"", territory_id: $(this).val()
                    },
                    function(result)
                    {
                        if(result)
                        {
                            location.append(result);
                        }
                    });
            }
            else
            {
                $(this).parents().next(".district_td_elm").find(".district_id").html('');
                $(this).parents().next(".district_td_elm").next(".distributor_td_elm").find(".distributor_id").html('');
            }
        });

        $(document).on("change",".district_id",function()
        {
            if($(this).val()>0)
            {
                $(this).parents().next(".distributor_td_elm").find(".distributor_id").html('');

                var territory_id = $(this).parents().prev(".territory_td_elm").find(".territory_id").val();

                var location = $(this).parents().next(".distributor_td_elm").find(".distributor_id");

                $.post("../../libraries/ajax_load_file/load_distributor.php",
                    {
                        zone_id:"", territory_id: territory_id, zilla_id: $(this).val()
                    },
                    function(result)
                    {
                        if(result)
                        {
                            location.append(result);
                        }
                    });
            }
            else
            {
                $(this).parents().next(".distributor_td_elm").find(".distributor_id").html('');
            }
        });
    });

</script>