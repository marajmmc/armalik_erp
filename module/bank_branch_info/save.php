<?php

session_start();

require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$db = new Database();
$user_id = $_SESSION['user_id'];
$employee_id = $_SESSION['employee_id'];
$tbl = _DB_PREFIX;
$count = count($_POST['branch_id']);
for ($i = 0; $i < $count; $i++) {
    $maxID = "BB-" . $db->getMaxID_six_digit($tbl . 'bank_branch_info', 'branch_id');
    $rowfield = array(
        'branch_id,' => "'$maxID',",
        'bank_id,' => "'" . $_POST["bank_id"] . "',",
        'branch_name,' => "'" . $_POST["branch_name"][$i] . "',",
        'status,' => "'Active',",
        'del_status,' => "'0',",
        'entry_by,' => "'$user_id',",
        'entry_date' => "'" . $db->ToDayDate() . "'"
    );

    echo $db->data_insert($tbl . 'bank_branch_info', $rowfield);
    $db->system_event_log('', $user_id, $employee_id, $_POST["bank_id"], $maxID, $tbl . 'bank_branch_info', 'Save', '');
}
?>