<?php

session_start();

require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$db = new Database();
$tbl = _DB_PREFIX;
$user_id = $_SESSION['user_id'];
$employee_id = $_SESSION['employee_id'];
$user_zone = $_SESSION['zone_id'];

$postData = explode('~', $_POST['rowID']);
$year = $postData[0];
$zone_id = $postData[1];
$start_month = $postData[2];
$end_month = $postData[3];

// initial update
$initial_update = array('status' => "'0'");

$initial_where = array(
    'year' => "'$year'",
    'zone_id' => "'$zone_id'",
    'start_month' => "'$start_month'",
    'end_month' => "'$end_month'"
);

$db->data_update($tbl . 'zi_tour_plan', $initial_update, $initial_where);

$year = $_POST['year'];
$from_month = $_POST['from_month'];
$to_month = $_POST['to_month'];

$planPost = $_POST['plan'];


foreach($planPost as $day=>$plan)
{
    foreach($plan as $time=>$attribute)
    {
        if(isset($attribute['distributor_id']))
        {
            $dataDistributor = $attribute['distributor_id'];

            if(is_array($dataDistributor) && sizeof($dataDistributor)>0)
            {
                foreach($dataDistributor as $distributor)
                {
                    $existingDistributorQuery = "SELECT distributor_id FROM $tbl" . "zi_tour_plan WHERE year='$year' AND zone_id='$zone_id' AND territory_id='".$attribute['territory_id']."' AND district_id='".$attribute['district_id']."' AND start_month='$start_month' AND end_month='$end_month' AND week_day='$day' AND day_time='$time'";

                    $existingDistributorsArray = $db->return_result_array($existingDistributorQuery);

                    $customers = array();
                    foreach($existingDistributorsArray as $existingDistributors)
                    {
                        $customers[] = $existingDistributors['distributor_id'];
                    }

                    if(in_array($distributor, $customers))
                    {
                        $idQuery = $db->single_data_w($tbl . 'zi_tour_plan', 'id',"year='$year' AND zone_id='$zone_id' AND start_month='$start_month' AND end_month='$end_month' AND week_day='$day' AND day_time='$time' AND territory_id='".$attribute['territory_id']."' AND district_id='".$attribute['district_id']."' AND distributor_id='$distributor'");
                        $update = array('status' => "'1'");

                        $update_where = array(
                            'id' => "'" . $idQuery['id'] . "'"
                        );

                        $db->data_update($tbl . 'zi_tour_plan', $update, $update_where);
                        $db->system_event_log('', $user_id, $employee_id, '', '', $tbl . 'zi_tour_plan', 'Update', '');
                    }
                    else
                    {
                        $field = array(
                            'year,' => "'$year',",
                            'start_month,' => "'" . $from_month . "',",
                            'end_month,' => "'" . $to_month . "',",
                            'week_day,' => "'" . $day . "',",
                            'day_time,' => "'" . $time . "',",
                            'zone_id,' => "'" . $user_zone . "',",
                            'territory_id,' => "'" . $attribute['territory_id'] . "',",
                            'district_id,' => "'" . $attribute['district_id'] . "',",
                            'distributor_id,' => "'" . $distributor . "',",
                            'entry_by,' => "'$user_id',",
                            'entry_date' => "'" . $db->ToDayDate() . "'"
                        );

                        $db->data_insert($tbl . 'zi_tour_plan', $field);
                        $db->system_event_log('', $user_id, $employee_id, '', '', $tbl . 'zi_tour_plan', 'Save', '');
                    }
                }
            }
        }
    }
}

?>