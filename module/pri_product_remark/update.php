<?php

session_start();

require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$db = new Database();
$user_id = $_SESSION['user_id'];
$employee_id = $_SESSION['employee_id'];
$tbl = _DB_PREFIX;

//$maxID = "VS-".$db->getMaxID_six_digit($tbl . 'pdo_variety_setting', 'vs_id');
$crop_img_url='';
$fruit_img_url='';

$count = count($_POST["crop_row_id"]);
$vs_id=$_POST['vs_id'];
for ($i = 0; $i < $count; $i++) {

    if (@$_FILES["crop_img_url"]['name'][$i] != "") {
        @$ext = end(explode(".", @$_FILES["crop_img_url"]['name'][$i]));
        $crop_img_url = $_POST["crop_row_id"][$i] . "." . $ext;
        copy(@$_FILES['crop_img_url']['tmp_name'][$i], "../../system_images/pdo_upload_image/crop_img_url/$crop_img_url");

        $rowfield = array
        (
            'crop_img_url' => "'" . $crop_img_url . "'",
            'entry_by' => "'$user_id'",
            'entry_date' => "'" . $db->ToDayDate() . "'"
        );
        $where = array('id'=> $_POST["crop_row_id"][$i]);
        $db->data_update($tbl . 'pdo_variety_setting_img_date', $rowfield, $where);
        $db->system_event_log('', $user_id, $employee_id, '', $_POST["crop_row_id"][$i], $tbl . 'pdo_variety_setting_img_date', 'Update', '');
    }

}


$count = count($_POST["fruit_row_id"]);

for ($j = 0; $j < $count; $j++)
{
    $FruitID = "FI-".$db->getMaxID_six_digit($tbl . 'pdo_variety_setting_fruit_img', 'id');

    if($_POST["fruit_row_id"][$j]=="")
    {

        if (@$_FILES["fruit_img_url"]['name'][$j] != "")
        {
            @$ext = end(explode(".", @$_FILES["fruit_img_url"]['name'][$j]));
            $fruit_img_url = $FruitID . "." . $ext;
            copy(@$_FILES['fruit_img_url']['tmp_name'][$j], "../../system_images/pdo_upload_image/fruit_img_url/$fruit_img_url");

            $rowfield = array(
                'id,' => "'$FruitID',",
                'vs_id,' => "'$vs_id',",
                'fruit_img_url,' => "'" . $fruit_img_url . "',",
                'fruit_caption,' => "'" . $_POST["fruit_caption"][$j] . "',",
                'upload_date,' => "'" . $db->ToDayDate() . "',",
                'status,' => "'Active',",
                'del_status,' => "'0',",
                'entry_by,' => "'$user_id',",
                'entry_date' => "'" . $db->ToDayDate() . "'"
            );

            $db->data_insert($tbl . 'pdo_variety_setting_fruit_img', $rowfield);
            $db->system_event_log('', $user_id, $employee_id, '', '', $tbl . 'pdo_variety_setting_fruit_img', 'Save', '');
        }
        else
        {
            $rowfield = array(
                'id,' => "'$FruitID',",
                'vs_id,' => "'$vs_id',",
                'fruit_caption,' => "'" . $_POST["fruit_caption"][$j] . "',",
                'upload_date,' => "'" . $db->ToDayDate() . "',",
                'status,' => "'Active',",
                'del_status,' => "'0',",
                'entry_by,' => "'$user_id',",
                'entry_date' => "'" . $db->ToDayDate() . "'"
            );

            $db->data_insert($tbl . 'pdo_variety_setting_fruit_img', $rowfield);
            $db->system_event_log('', $user_id, $employee_id, '', '', $tbl . 'pdo_variety_setting_fruit_img', 'Save', '');
        }
    }
    else
    {
        if (@$_FILES["fruit_img_url"]['name'][$j] != "")
        {
            @$ext = end(explode(".", @$_FILES["fruit_img_url"]['name'][$j]));
            $fruit_img_url1 = $_POST["fruit_row_id"][$j] . "." . $ext;
            copy(@$_FILES['fruit_img_url']['tmp_name'][$j], "../../system_images/pdo_upload_image/fruit_img_url/$fruit_img_url1");

            $rowfield = array
            (
                'vs_id' => "'" . $vs_id . "'",
                'fruit_img_url' => "'" . $fruit_img_url1 . "'",
                'fruit_caption' => "'" . $_POST["fruit_caption"][$j] . "'",
                'upload_date' => "'" . $db->ToDayDate() . "'",
                'entry_by' => "'$user_id'",
                'entry_date' => "'" . $db->ToDayDate() . "'"
            );
            $where = array('id'=> "'".$_POST["fruit_row_id"][$j]."'");
            $db->data_update($tbl . 'pdo_variety_setting_fruit_img', $rowfield, $where);
            $db->system_event_log('', $user_id, $employee_id, '', $_POST["fruit_row_id"][$j], $tbl . 'pdo_variety_setting_fruit_img', 'Update', '');
        }
        else
        {
            $rowfield = array
            (
                'vs_id' => "'" . $vs_id. "'",
                'fruit_caption' => "'" . $_POST["fruit_caption"][$j] . "'",
                'upload_date' => "'" . $db->ToDayDate() . "'",
                'entry_by' => "'$user_id'",
                'entry_date' => "'" . $db->ToDayDate() . "'"
            );
            $where = array('id'=> "'".$_POST["fruit_row_id"][$j]."'");
            $db->data_update($tbl . 'pdo_variety_setting_fruit_img', $rowfield, $where);
            $db->system_event_log('', $user_id, $employee_id, '', $_POST["fruit_row_id"][$j], $tbl . 'pdo_variety_setting_fruit_img', 'Update', '');
        }
    }

}
?>
<script>
    window.location.href = "list_frm.php?menuID=<?php echo $_SESSION['sm_id']; ?>&buttonID=<?php echo $_SESSION['st_id']; ?>";
</script>