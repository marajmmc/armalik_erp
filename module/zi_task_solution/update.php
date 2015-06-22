<?php

session_start();

require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$db = new Database();
$user_id = $_SESSION['user_id'];
$employee_id = $_SESSION['employee_id'];
$user_zone = $_SESSION['zone_id'];
$user_division = $_SESSION['division_id'];
$tbl = _DB_PREFIX;


$solution = $_POST['solution'];

$data = array(
    'solution' => "'$solution'"
);

$maxID = $_POST['rowID'];

$whereField = array('id' => "'$maxID'");
$db->data_update($tbl . 'zi_task', $data, $whereField);
$db->system_event_log('', $user_id, $employee_id, $maxID, '', $tbl . 'zi_task', 'Update', '');

?>

<script>
    window.location.href = "list_frm.php?menuID=<?php echo $_SESSION['sm_id']; ?>&buttonID=<?php echo $_SESSION['st_id']; ?>";
</script>