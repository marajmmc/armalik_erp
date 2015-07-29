<?php

require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$tbl = _DB_PREFIX;
$db = new Database();
session_start();

$year = $_POST['year'];
$from_month = $_POST['from_month'];
$to_month = $_POST['to_month'];
$user_zone = $_POST['zone_id'];

$Query = "SELECT start_month, end_month FROM $tbl" . "zi_tour_plan WHERE year='$year' AND zone_id='$user_zone' AND status=1 GROUP BY start_month, end_month";
$monthSpan = $db->return_result_array($Query);

if(is_array($monthSpan) && sizeof($monthSpan)>0)
{
    foreach($monthSpan as $month)
    {
        $start_month = $month['start_month'];
        $end_month = $month['end_month'];
    }

    if($start_month>0 && $end_month>0)
    {
        if(($from_month >= $start_month && $from_month <= $end_month) || ($to_month >= $start_month && $to_month <= $end_month))
        {
            echo 1;
        }
        else
        {
            echo 0;
        }
    }
}
else
{
    echo 0;
}
?>