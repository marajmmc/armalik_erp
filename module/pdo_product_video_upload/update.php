<?php

session_start();

require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$db = new Database();
$user_id = $_SESSION['user_id'];
$ei_id = $_SESSION['employee_id'];
$tbl = _DB_PREFIX;
$file_url='';
$maxID=$_POST['rowID'];

if (@$_FILES["file_url"]['name'] != "")
{
    $ext = end(explode(".", @$_FILES["file_url"]['name']));
    $file_url = $maxID . "." . $ext;
    copy(@$_FILES['file_url']['tmp_name'], "../../system_images/pdo_upload_image/crop_img_url/$file_url");
    $rowfield = array
    (
        'crop_id' => "'" . $_POST["crop_id"] . "'",
        'product_type_id' => "'" . $_POST["product_type_id"] . "'",
        'arm_variety_id' => "'" . $_POST["arm_variety_id"] . "'",
        'check_variety_id' => "'" . $_POST["check_variety_id"] . "'",
        'file_url' => "'" . $file_url . "'",
        'status' => "'Active'",
        'del_status' => "'0'",
        'entry_by' => "'$user_id'",
        'entry_date' => "'" . $db->ToDayDate() . "'"
    );
}
else
{
    $rowfield = array
    (
        'crop_id' => "'" . $_POST["crop_id"] . "'",
        'product_type_id' => "'" . $_POST["product_type_id"] . "'",
        'arm_variety_id' => "'" . $_POST["arm_variety_id"] . "'",
        'check_variety_id' => "'" . $_POST["check_variety_id"] . "'",
        'status' => "'Active'",
        'del_status' => "'0'",
        'entry_by' => "'$user_id'",
        'entry_date' => "'" . $db->ToDayDate() . "'"
    );
}

$where=array('vvu_id' => "'$maxID'");
$db->data_update($tbl . 'pdo_variety_video_upload', $rowfield, $where);
$db->system_event_log('', $user_id, $ei_id, $maxID, '', $tbl . 'pdo_variety_video_upload', 'Save', '');
?>
<script>
    window.location.href = "list_frm.php?menuID=<?php echo $_SESSION['sm_id']; ?>&buttonID=<?php echo $_SESSION['st_id']; ?>";
</script>