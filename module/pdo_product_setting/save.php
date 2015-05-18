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

$crop_id=$_POST['crop_id'];
$product_type_id=$_POST['product_type_id'];
$variety_id=$_POST['variety_id'];
$msg="";
if(!empty($crop_id) && !empty($product_type_id) && !empty($variety_id))
{
    $maxID = "CS-".$db->getMaxID_six_digit($tbl . 'pdo_product_characteristic_setting', 'prodcut_characteristic_id');

    if (@$_FILES["image_url"]['name'] != "")
    {
        @$ext = end(explode(".", @$_FILES["image_url"]['name']));
        @$upload_file = $maxID . "." . $ext;
        copy(@$_FILES['image_url']['tmp_name'], "../../system_images/pdo_upload_image/pdo_product_characteristic/$upload_file");
    }
    $rowfield = array
    (
        'prodcut_characteristic_id,' => "'$maxID',",
        'crop_id,' => "'" . $crop_id . "',",
        'product_type_id,' => "'" . $product_type_id . "',",
        'variety_id,' => "'" . $variety_id . "',",
        'cultivation_period_start,' => "'" . $db->date_formate($_POST["cultivation_period_start"]) . "',",
        'cultivation_period_end,' => "'" . $db->date_formate($_POST["cultivation_period_end"]) . "',",
        'special_characteristics,' => "'" . stripQuotes(removeBadChars($_POST["special_characteristics"])) . "',",
        'remarks,' => "'" . stripQuotes(removeBadChars($_POST["remarks"])) . "',",
        'image_url,' => "'" . $upload_file . "',",
        'upload_date,' => "'" . $db->ToDayDate() . "',",
        'status,' => "'Active',",
        'del_status,' => "'0',",
        'entry_by,' => "'$user_id',",
        'entry_date' => "'" . $db->ToDayDate() . "'"
    );

    $db->data_insert($tbl . 'pdo_product_characteristic_setting', $rowfield);
    $db->system_event_log('', $user_id, $ei_id, $maxID, '', $tbl . 'pdo_product_characteristic_setting', 'Save', '');


    $dbzone = new Database();
    $dbzonei = new Database();

    $sqlzone="SELECT
                    ait_zone_info.zone_id,
                    ait_zone_info.zone_name
                FROM `ait_zone_info`
                WHERE ait_zone_info.status='Active' AND ait_zone_info.del_status=0 ";
    if($dbzone->open())
    {
        $result=$dbzone->query($sqlzone);
        while($row=$dbzone->fetchAssoc($result))
        {
            $ZmaxID = "PZ-".$dbzonei->getMaxID_six_digit($tbl . 'pdo_product_characteristic_setting_zone', 'pcsz_id');
            $rowfield = array
            (
                'pcsz_id,' => "'$ZmaxID',",
                'prodcut_characteristic_id,' => "'$maxID',",
                'zone_id,' => "'" . $row["zone_id"] . "',",
                'crop_id,' => "'" . $crop_id . "',",
                'product_type_id,' => "'" . $product_type_id . "',",
                'variety_id,' => "'" . $variety_id . "',",
                'status,' => "'Active',",
                'del_status,' => "'0',",
                'entry_by,' => "'$user_id',",
                'entry_date' => "'" . $db->ToDayDate() . "'"
            );

            $db->data_insert($tbl . 'pdo_product_characteristic_setting_zone', $rowfield);
        }
    }
    $dbzone->close();
    ?>
    <script>
        alert ('Successfully Save');
        window.location.href = "list_frm.php?menuID=<?php echo $_SESSION['sm_id']; ?>&buttonID=<?php echo $_SESSION['st_id']; ?>";
    </script>
    <?php
}
else
{
    ?>
    <script>
        alert ('Not Successfully Save');
        window.location.href = "list_frm.php?menuID=<?php echo $_SESSION['sm_id']; ?>&buttonID=<?php echo $_SESSION['st_id']; ?>";
    </script>
<?php
}
?>


