<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$tbl = _DB_PREFIX;
$sql = "SELECT
            $tbl" . "weekly_tour_plan.id,
            $tbl" . "weekly_tour_plan.week_plan_id,
            $tbl" . "weekly_tour_plan.zone_id,
            $tbl" . "weekly_tour_plan.territory_id,
            $tbl" . "weekly_tour_plan.employee_id,
            $tbl" . "weekly_tour_plan.month_id,
            $tbl" . "weekly_tour_plan.week_id,
            $tbl" . "weekly_tour_plan.location,
            $tbl" . "weekly_tour_plan.visit_purpose,
            $tbl" . "weekly_tour_plan.plan_date,
            $tbl" . "weekly_tour_plan.purpose,
            $tbl" . "weekly_tour_plan.`status`,
            $tbl" . "weekly_tour_plan.del_status,
            $tbl" . "weekly_tour_plan.entry_by,
            $tbl" . "weekly_tour_plan.entry_date
        FROM
            $tbl" . "weekly_tour_plan
        WHERE $tbl" . "weekly_tour_plan.week_plan_id='" . $_POST['rowID'] . "'
";
if ($db->open()) {
    $result = $db->query($sql);
    while ($editrow = $db->fetchAssoc($result)) {
        $id[] = $editrow['id'];
        $week_plan_id = $editrow['week_plan_id'];
        $zone_id = $editrow['zone_id'];
        $territory_id = $editrow['territory_id'];
        $employee_id = $editrow['employee_id'];
        $month_id = $editrow['month_id'];
        $week_id = $editrow['week_id'];
        $purpose[] = $editrow['purpose'];
        $location[] = $editrow['location'];
        $visit_purpose[] = $editrow['visit_purpose'];
        $plan_date[] = $db->date_formate($editrow['plan_date']);
    }
}
?>
<div class="row-fluid">
    <div class="span12">
        <div class="widget span">
            <div class="widget-header">
                <div class="title">
                    <a id="dynamicTable"><?php echo $db->Get_Auto_TaskName() ?></a>
                    <span class="mini-title">

                    </span>
                </div>
                <span class="tools">
                    <a class="btn btn-small" data-original-title="">
                        <i class="icon-plus-sign" data-original-title="Share"> </i>
                    </a>
                </span>
            </div>
            <div class="form-horizontal no-margin">
                <div class="widget-body">
                    <div class="control-group">
                        <label class="control-label" for="employee_id">
                            Zone
                        </label>
                        <div class="controls">
                            <select disabled="" id="zone_id" name="zone_id" class="span5" placeholder="Zone" onchange="load_territory_fnc()" validate="Require">
                                <option value="">Select</option>
                                <?php
                                $sql_uesr_group = "select zone_id as fieldkey, zone_name as fieldtext from $tbl" . "zone_info WHERE status='Active' AND del_status='0' ".$db->get_zone_access($tbl. "zone_info")." ";
                                echo $db->SelectList($sql_uesr_group, $zone_id);
                                ?>
                            </select>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
<!--                    <div class="control-group">
                        <label class="control-label" for="employee_id">
                            Territory
                        </label>
                        <div class="controls">
                            <select disabled="" id="territory_id" name="territory_id" class="span5" placeholder="Territory" onchange="load_distributor_fnc()"  validate="Require">
                                <option value="">Select</option>
                                <?php
//                                $sql_uesr_group = "select territory_id as fieldkey, territory_name as fieldtext from $tbl" . "territory_info WHERE status='Active' AND del_status='0' AND zone_id='$zone_id'";
//                                echo $db->SelectList($sql_uesr_group, $territory_id);
                                ?>
                            </select>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>-->
                    <div class="control-group">
                        <label class="control-label" for="employee_id">
                            Employee Name
                        </label>
                        <div class="controls">
                            <select  disabled="" id="employee_id" name="employee_id" class="span5" placeholder="Employee Name" validate="Require">
                                <!--<option value="">Select</option>-->
                                <?php
                                $sql_uesr_group = "select employee_id as fieldkey, employee_name as fieldtext from $tbl" . "employee_basic_info where status='Active' AND del_status='0' AND employee_id='$employee_id'";
                                echo $db->SelectList($sql_uesr_group, $employee_id);
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="location">
                            Month
                        </label>
                        <div class="controls">
                            <select disabled="" id="month_id" name="month_id" class="span5" placeholder="Month Name" validate="Require">
                                <option value="">Select</option>
                                <?php
                                $sql_uesr_group = "select month_id as fieldkey, month_full_name as fieldtext from $tbl" . "month_info where status='Active' AND del_status='0'";
                                echo $db->SelectList($sql_uesr_group, $month_id);
                                ?>
                            </select>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="visit_purpose">
                            Week
                        </label>
                        <div class="controls">
                            <select disabled="" id="week_id" name="week_id" class="span5" placeholder="Week Name" validate="Require">
                                <option value="">Select</option>
                                <option value="1st Week" <?php
                                if ($week_id == "1st Week") {
                                    echo "selected='selected'";
                                }
                                ?> >1st Week</option>
                                <option value="2nd Week" <?php
                                        if ($week_id == "2nd Week") {
                                            echo "selected='selected'";
                                        }
                                ?> >2nd Week</option>
                                <option value="3rd Week" <?php
                                        if ($week_id == "3rd Week") {
                                            echo "selected='selected'";
                                        }
                                ?> >3rd Week</option>
                                <option value="4th Week" <?php
                                        if ($week_id == "4th Week") {
                                            echo "selected='selected'";
                                        }
                                ?> >4th Week</option>
                            </select>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                </div>
                <div class="widget-body">
                    <div class="wrapper">
                        <table class="table table-condensed table-striped table-bordered table-hover no-margin" id="TaskTable">
                            <thead>
                                <tr>
                                    <th style="width:10%">
                                        Location
                                    </th>
                                    <th style="width:10%">
                                        Visit Purpose
                                    </th>
                                    <th style="width:10%">
                                        Date
                                    </th>
                                    <th style="width:65%">
                                        Progress
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $DID = '0';
                                $count = count($location);
                                for ($i = 0; $i < $count; $i++) {
                                    if ($i % 2 == 0) {
                                        $rowcolor = "gradeC";
                                    } else {
                                        $rowcolor = "gradeA success";
                                    }
                                    echo "<tr class='$rowcolor'>
                                            <td>
                                                <input disabled='' type='text' name='location[]' value='$location[$i]' id='location_$i' class='span12'/>        
                                                <input type='hidden' id='id[]' name='id[]' value='$id[$i]'/>
                                            </td>
                                            <td>
                                                <input disabled='' type='text' name='visit_purpose[]' value='$visit_purpose[$i]' id='visit_purpose_$i' class='span12'/>        
                                            </td>
                                            <td>
                                                <input disabled='' type='text' name='plan_date[]' id='plan_date_$i' class='span12' value='$plan_date[$i]'/>
                                            <td>    
                                                $purpose[$i]
                                            </td>
                                        <tr>";

                                    ++$DID;
                                }
                                ?>
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        session_load_fnc()
    });
    
</script>