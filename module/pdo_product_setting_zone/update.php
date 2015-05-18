<?php
session_start();

require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$db = new Database();
$user_id = $_SESSION['user_id'];
$ei_id = $_SESSION['employee_id'];
$tbl = _DB_PREFIX;
$upload_file='';
$maxID = $_POST['rowID'];


if (@$_FILES["image_url"]['name'] != "")
{
    @$ext = end(explode(".", @$_FILES["image_url"]['name']));
    @$upload_file = $maxID . "." . $ext;
    copy(@$_FILES['image_url']['tmp_name'], "../../system_images/pdo_upload_image/pdo_product_characteristic/$upload_file");

    $rowfield = array
    (
        'cultivation_period_start' => "'" . $db->date_formate($_POST["cultivation_period_start"]) . "'",
        'cultivation_period_end' => "'" . $db->date_formate($_POST["cultivation_period_end"]) . "'",
        'special_characteristics' => "'" . stripQuotes(removeBadChars($_POST["special_characteristics"])) . "'",
        'image_url' => "'" . $upload_file . "'",
        'upload_date' => "'" . $db->ToDayDate() . "'",
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
        'cultivation_period_start' => "'" . $db->date_formate($_POST["cultivation_period_start"]) . "'",
        'cultivation_period_end' => "'" . $db->date_formate($_POST["cultivation_period_end"]) . "'",
        'special_characteristics' => "'" . stripQuotes(removeBadChars($_POST["special_characteristics"])) . "'",
        'upload_date' => "'" . $db->ToDayDate() . "'",
        'status' => "'Active'",
        'del_status' => "'0'",
        'entry_by' => "'$user_id'",
        'entry_date' => "'" . $db->ToDayDate() . "'"
    );
}


$wherefield = array('pcsz_id' => "'$maxID'");
$db->data_update($tbl . 'pdo_product_characteristic_setting_zone', $rowfield, $wherefield);
$db->system_event_log('', $user_id, $ei_id, $maxID, '', $tbl . 'pdo_product_characteristic_setting_zone', 'Update', '');
?>
<script>
    window.location.href = "list_frm.php?menuID=<?php echo $_SESSION['sm_id']; ?>&buttonID=<?php echo $_SESSION['st_id']; ?>";
</script>