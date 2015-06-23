<?php

require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$tbl = _DB_PREFIX;
$db = new Database();

if($_POST['territory_id']!="")
{
   $territory="AND territory_id='".$_POST['territory_id']."'";
}
else
{
    $territory="";
}

if($_POST['zilla_id']!="")
{
   $zilla_id="AND zilla_id='".$_POST['zilla_id']."'";
}
else
{
    $zilla_id="";
}

if($_POST['distributor_id']!="")
{
   $distributor_id = "AND distributor_id='".$_POST['distributor_id']."'";
}
else
{
    $distributor_id = "";
}

echo "<option value=''>Select</option>";
$sql = "select amount as fieldkey, amount as fieldtext from $tbl" . "distributor_add_payment
where del_status='0' $territory $zilla_id $distributor_id
order by id DESC LIMIT 3";
echo $db->SelectList($sql);
?>