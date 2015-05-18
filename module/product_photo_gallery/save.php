<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$user_id = $_SESSION['user_id'];
$employee_id = $_SESSION['employee_id'];
$tbl = _DB_PREFIX;
$employee_image_url = '';
$ext='';
$product_gallery='';

$maxID = "PG-" . $db->getMaxID_six_digit($tbl . 'product_photo_gallery', 'photo_gallery_id');

if (@$_FILES["photo"]['name'] != "") {
    @$ext = end(explode(".", @$_FILES["photo"]['name']));
    @$product_gallery = $maxID . "." . $ext;
    @copy(@$_FILES['photo']['tmp_name'], "../../system_images/product_gallery/$product_gallery");
}

$rowfield = array(
    'photo_gallery_id,' => "'$maxID',",
    'crop_id,' => "'" . $_POST["crop_id"] . "',",
    'product_type_id,' => "'" . $_POST["product_type_id"] . "',",
    'varriety_id,' => "'" . $_POST["varriety_id"] . "',",
    'location,' => "'" . $_POST["location"] . "',",
    'farmer_name,' => "'" . $_POST["farmer_name"] . "',",
    'phone_number,' => "'" . $_POST["phone_number"] . "',",
    'picture_date,' => "'" . $db->date_formate($_POST["picture_date"]) . "',",
    'photo,' => "'" . $product_gallery . "',",
    'status,' => "'Active',",
    'del_status,' => "'0',",
    'entry_by,' => "'$user_id',",
    'entry_date' => "'" . $db->ToDayDate() . "'"
);

$db->data_insert($tbl . 'product_photo_gallery', $rowfield);
$db->system_event_log('', $user_id, $employee_id, $maxID,'', $tbl . 'product_photo_gallery', 'Save', '');
?>
<script>
    window.location.href = "list_frm.php?menuID=<?php echo $_SESSION['sm_id']; ?>&buttonID=<?php echo $_SESSION['st_id']; ?>";
</script>