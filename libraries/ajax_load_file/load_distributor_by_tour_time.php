<?php

require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$tbl = _DB_PREFIX;
$db = new Database();

if($_POST['territory_id']!="")
{
   $territory = "AND ztp.territory_id='".$_POST['territory_id']."'";
}
else
{
    $territory = "";
}

if($_POST['district_id']!="")
{
   $district_id = "AND ztp.district_id='".$_POST['district_id']."'";
}
else
{
    $district_id="";
}

if($_POST['time_span']!="")
{
   $time_span = "AND ztp.day_time='".$_POST['time_span']."'";
}
else
{
    $time_span="";
}

echo "<option value=''>Select</option>";

$sql = "select
        ztp.distributor_id as fieldkey,
        adi.distributor_name as fieldtext

        from $tbl" . "zi_tour_plan ztp

        LEFT JOIN $tbl" . "distributor_info adi ON adi.distributor_id = ztp.distributor_id
        where ztp.status='1' AND ztp.del_status='0' $territory $district_id $time_span
        order by distributor_name";
echo $db->SelectList($sql);
?>