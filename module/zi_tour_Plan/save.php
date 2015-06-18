<?php

session_start();

require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$db = new Database();
$user_id = $_SESSION['user_id'];
$employee_id = $_SESSION['employee_id'];
$tbl = _DB_PREFIX;

$data = array();
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
                    $data['territory_id'] = $attribute['territory_id'];
                    $data['district_id'] = $attribute['district_id'];
                    $data['distributor_id'] = $distributor;

                    $rowfield = array(
                        'year,' => "'$year',",
                        'start_month,' => "'" . $from_month . "',",
                        'end_month,' => "'" . $to_month . "',",
                        'week_day,' => "'" . $day . "',",
                        'day_time,' => "'" . $time . "',",
                        'zone_id,' => "'" . $_SESSION['zone_id'] . "',",
                        'territory_id,' => "'" . $attribute['territory_id'] . "',",
                        'district_id,' => "'" . $attribute['district_id'] . "',",
                        'distributor_id,' => "'" . $distributor . "',",
                        'entry_by,' => "'$user_id',",
                        'entry_date' => "'" . $db->ToDayDate() . "'"
                    );

                    $db->data_insert($tbl . 'zi_tour_plan', $rowfield);
                    $db->system_event_log('', $user_id, $employee_id, '', '', $tbl . 'zi_tour_plan', 'Save', '');
                }
            }
        }
    }
}

?>