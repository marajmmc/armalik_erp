<?php

session_start();

require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$db = new Database();
$user_id = $_SESSION['user_id'];
$employee_id = $_SESSION['employee_id'];
$tbl = _DB_PREFIX;

$maxID = "VS-".$db->getMaxID_six_digit($tbl . 'pdo_variety_setting', 'vs_id');
$crop_img_url='';
$count = count($_POST["crop_row_id"]);

for ($i = 0; $i < $count; $i++) {

    if (@$_FILES["crop_img_url"]['name'][$i] != "") {
        $ext = end(explode(".", @$_FILES["crop_img_url"]['name'][$i]));
        $crop_img_url = $maxID . "." . $ext;
        copy(@$_FILES['crop_img_url']['tmp_name'][$i], "../../system_images/pdo_upload_image/crop_img_url/$crop_img_url");
    }

    $rowfield = array
        (
            'crop_img_url' => "'" . $crop_img_url . "'",
            'entry_by' => "'$user_id'",
            'entry_date' => "'" . $db->ToDayDate() . "'"
        );
    $where = array('id'=> $_POST["crop_row_id"][$i]);
    echo $db->data_update($tbl . 'pdo_variety_setting_img_date', $rowfield, $where);
    $db->system_event_log('', $user_id, $employee_id, '', $_POST["crop_row_id"][$i], $tbl . 'pdo_variety_setting_img_date', 'Update', '');

}

$count_disease=$_POST['disease_photo'];

for ($i = 0; $i < $count_disease; $i++) {

    if (@$_FILES["disease_photo"]['name'][$i] != "") {
        $ext = end(explode(".", @$_FILES["disease_photo"]['name'][$i]));
        $disease_photo = $maxID . "." . $ext;
        copy(@$_FILES['disease_photo']['tmp_name'][$i], "../../system_images/pdo_upload_image/disease_photo/$disease_photo");
    }

    $rowfield = array
        (
            'disease_img_url' => "'" . $disease_photo . "'",
            'entry_by' => "'$user_id'",
            'entry_date' => "'" . $db->ToDayDate() . "'"
        );
    $where = array('id'=> $_POST["crop_row_id"][$i]);
    echo $db->data_update($tbl . 'pdo_variety_setting_disease_img', $rowfield, $where);
    $db->system_event_log('', $user_id, $employee_id, '', $_POST["crop_row_id"][$i], $tbl . 'pdo_variety_setting_disease_img', 'Update', '');

}
?>
<script>
    //window.location.href = "list_frm.php?menuID=<?php echo $_SESSION['sm_id']; ?>&buttonID=<?php echo $_SESSION['st_id']; ?>";
</script>
