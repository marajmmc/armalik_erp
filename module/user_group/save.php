<?php
session_start();

require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();

$tbl = _DB_PREFIX;

$maxID_group = 'UG-'.$db->getMaxID_six_digit($tbl.'user_group', 'ug_id');
for ($i = 0; $i < sizeof($_POST["hiddenStid"]); $i++) {

    $chkTaskAdd = $_POST["hiddenSmid"][$i] . "-" . $_POST["hiddenStid"][$i] . "-Add";
    $chkTaskSave = $_POST["hiddenSmid"][$i] . "-" . $_POST["hiddenStid"][$i] . "-Save";
    $chkTaskEdit = $_POST["hiddenSmid"][$i] . "-" . $_POST["hiddenStid"][$i] . "-Edit";
    $chkTaskDelete = $_POST["hiddenSmid"][$i] . "-" . $_POST["hiddenStid"][$i] . "-Delete";
    $chkTaskView = $_POST["hiddenSmid"][$i] . "-" . $_POST["hiddenStid"][$i] . "-View";
    $chkTaskReport = $_POST["hiddenSmid"][$i] . "-" . $_POST["hiddenStid"][$i] . "-Report";
    $chkTaskConfidentiality = $_POST["hiddenSmid"][$i] . "-" . $_POST["hiddenStid"][$i] . "-Confidentiality";
    if (($_POST["$chkTaskAdd"] != "") || ($_POST["$chkTaskSave"] != "") || ($_POST["$chkTaskEdit"] != "") || ($_POST["$chkTaskDelete"] != "") || ($_POST["$chkTaskView"] != "") || ($_POST["$chkTaskReport"] != "")) {
        echo $mSQL = "insert into `$tbl"."user_group` (`ug_id`, `ug_name`, `up_sm_id`, `up_st_id`, `up_eventadd`, `up_eventsave`, `up_eventedit`, `up_eventdelete`, `up_eventview`, `up_eventreport`, `up_confidentiality`, `ug_entry_date`) Values('$maxID_group','" . $_POST["textName"] . "','" . $_POST["hiddenSmid"][$i] . "','" . $_POST["hiddenStid"][$i] . "','" . $_POST["$chkTaskAdd"] . "','" . $_POST["$chkTaskSave"] . "','" . $_POST["$chkTaskEdit"] . "','" . $_POST["$chkTaskDelete"] . "','" . $_POST["$chkTaskView"] . "','" . $_POST["$chkTaskReport"] . "','','')";
        if ($db->open()) {
            $db->query($mSQL);
            $db->freeResult();
        }
    }
}
?>