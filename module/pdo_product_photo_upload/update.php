<?php
session_start();

require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$db = new Database();
$user_id = $_SESSION['user_id'];
$ei_id = $_SESSION['employee_id'];
$tbl = _DB_PREFIX;

$maxID = $_POST['rowID'];

if (@$_FILES["image_url"]['name'] != "") {
    $ext = end(explode(".", @$_FILES["image_url"]['name']));
    $upload_file = $maxID . "." . $ext;
    copy(@$_FILES['image_url']['tmp_name'], "../../system_images/pdo_upload_image/$upload_file");

    $rowfield = array(
        'pdo_id' => "'" . $_POST["pdo_id"] . "'",
        'crop_id' => "'" . $_POST["crop_id"] . "'",
        'product_type_id' => "'" . $_POST["product_type_id"] . "'",
        'farmer_id' => "'" . $_POST["farmer_id"] . "'",
        'upload_date' => "'" . $db->date_formate($_POST["upload_date"]) . "'",
        'description' => "'" . stripQuotes(removeBadChars($_POST["description"])) . "'",
        'image_url' => "'" . $upload_file . "'",
        'status' => "'" . $_POST["status"] . "'",
        'del_status' => "'0'",
        'entry_by' => "'$user_id'",
        'entry_date' => "'" . $db->ToDayDate() . "'"
    );
} else {
    $rowfield = array(
        'pdo_id' => "'" . $_POST["pdo_id"] . "'",
        'crop_id' => "'" . $_POST["crop_id"] . "'",
        'product_type_id' => "'" . $_POST["product_type_id"] . "'",
        'farmer_id' => "'" . $_POST["farmer_id"] . "'",
        'upload_date' => "'" . $db->date_formate($_POST["upload_date"]) . "'",
        'description' => "'" . stripQuotes(removeBadChars($_POST["description"])) . "'",
        'status' => "'" . $_POST["status"] . "'",
        'del_status' => "'0'",
        'entry_by' => "'$user_id'",
        'entry_date' => "'" . $db->ToDayDate() . "'"
    );
}


$wherefield = array('upload_id' => "'$maxID'");
$db->data_update($tbl . 'pdo_photo_upload', $rowfield, $wherefield);
$db->system_event_log('', $user_id, $ei_id, $maxID, '', $tbl . 'pdo_photo_upload', 'Save', '');
?>
<script>
    window.location.href = "list_frm.php?menuID=<?php echo $_SESSION['sm_id']; ?>&buttonID=<?php echo $_SESSION['st_id']; ?>";
</script>