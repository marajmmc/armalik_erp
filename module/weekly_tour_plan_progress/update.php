<?php

session_start();

require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$db = new Database();
$user_id = $_SESSION['user_id'];
$employee_id = $_SESSION['employee_id'];
$tbl = _DB_PREFIX;

$maxID = $_POST['rowID'];

$count = count($_POST['id']);

for ($i = 0; $i < $count; $i++) {
    $rowfield = array(
        'purpose' => "'" . nl2br($_POST["purpose"][$i]) . "'"
    );
    $wherefield = array('id' => "'" . $_POST["id"][$i] . "'");
    $db->data_update($tbl . 'weekly_tour_plan', $rowfield, $wherefield);
    $db->system_event_log('', $user_id, $employee_id, $_POST["id"][$i], $maxID, $tbl . 'weekly_tour_plan', 'Update', '');
}
?>