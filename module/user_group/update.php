<?php

/*if ($_SESSION['logged'] != 'yes') {
    $_REQUEST["msg"] = "TimeoutC";
    header("location:../../index.php");
}
*///echo $_SESSION['shop_name_eng']; 
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$db = new Database();
//$user_id = $_SESSION['user_id'];
$tbl = _DB_PREFIX;


$MaxID = $_POST["rowID"];
$mSQL = "DELETE FROM $tbl"."user_group WHERE `ug_id`='$MaxID'";
if ($db->open()) {
    $db->query($mSQL);
    $db->freeResult();
}

//$maxID_group = $db->MaxID($tbl.'user_group', 'ug_id', 'UG-00000');
for ($i = 0; $i < sizeof($_POST["hiddenStid"]); $i++) {

    $chkTaskAdd = $_POST["hiddenSmid"][$i] . "-" . $_POST["hiddenStid"][$i] . "-Add";
    $chkTaskSave = $_POST["hiddenSmid"][$i] . "-" . $_POST["hiddenStid"][$i] . "-Save";
    $chkTaskEdit = $_POST["hiddenSmid"][$i] . "-" . $_POST["hiddenStid"][$i] . "-Edit";
    $chkTaskDelete = $_POST["hiddenSmid"][$i] . "-" . $_POST["hiddenStid"][$i] . "-Delete";
    $chkTaskView = $_POST["hiddenSmid"][$i] . "-" . $_POST["hiddenStid"][$i] . "-View";
    $chkTaskReport = $_POST["hiddenSmid"][$i] . "-" . $_POST["hiddenStid"][$i] . "-Report";
    $chkTaskConfidentiality = $_POST["hiddenSmid"][$i] . "-" . $_POST["hiddenStid"][$i] . "-Confidentiality";
    if (($_POST["$chkTaskAdd"] != "") || ($_POST["$chkTaskSave"] != "") || ($_POST["$chkTaskEdit"] != "") || ($_POST["$chkTaskDelete"] != "") || ($_POST["$chkTaskView"] != "") || ($_POST["$chkTaskReport"] != "")) {
        $mSQL = "insert into $tbl"."user_group (
            `ug_id`, 
            `ug_name`, 
            `up_sm_id`, 
            `up_st_id`, 
            `up_eventadd`, 
            `up_eventsave`, 
            `up_eventedit`, 
            `up_eventdelete`, 
            `up_eventview`, 
            `up_eventreport`, 
            `up_confidentiality`,
            `ug_entry_date`
            ) Values(
            '$MaxID',
            '" . $_POST["textName"] . "',
            '" . $_POST["hiddenSmid"][$i] . "',
            '" . $_POST["hiddenStid"][$i] . "',
            '" . $_POST["$chkTaskAdd"] . "',
            '" . $_POST["$chkTaskSave"] . "',
            '" . $_POST["$chkTaskEdit"] . "',
            '" . $_POST["$chkTaskDelete"] . "',
            '" . $_POST["$chkTaskView"] . "',
            '" . $_POST["$chkTaskReport"] . "','','')";
        if ($db->open()) {
            $db->query($mSQL);
            $db->freeResult();
            echo "ok";
        }
    }
}
?>