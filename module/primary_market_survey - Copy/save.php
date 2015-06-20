<?php

session_start();

require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$db = new Database();
$user_id = $_SESSION['user_id'];
$ei_id = $_SESSION['employee_id'];
$tbl = _DB_PREFIX;

$zone_id=$_POST['zone_id'];
$territory_id=$_POST['territory_id'];
$district_id=$_POST['district_id'];
$upazilla_id=$_POST['upazilla_id'];
$pdo_year_id=$_POST['pdo_year_id'];
$crop_id=$_POST['crop_master_id'];
$product_type_id=$_POST['product_master_type_id'];

if(!empty($zone_id) || !empty($territory_id) || !empty($district_id) || !empty($upazilla_id) || !empty($pdo_year_id) || !empty($crop_id) || !empty($product_type_id))
{

    $group_maxID = "GP-".$db->Get_CustMaxID($tbl . 'primary_market_survey','market_survey_group_id',6, '');
    $market_survey_count=sizeof($_POST['market_survey_id']);

    for($i=0; $i<$market_survey_count; $i++)
    {
        $pms_maxID = "MS-".$db->getMaxID_six_digit($tbl . 'primary_market_survey', 'market_survey_id');
        $rowfield = array(
            'market_survey_id,' => "'$pms_maxID',",
            'market_survey_group_id,' => "'$group_maxID',",
            'zone_id,' => "'" . $zone_id . "',",
            'territory_id,' => "'" . $territory_id . "',",
            'district_id,' => "'" . $district_id . "',",
            'upazilla_id,' => "'" . $upazilla_id . "',",
            'pdo_year_id,' => "'" . $pdo_year_id . "',",
            'crop_id,' => "'" . $crop_id . "',",
            'product_type_id,' => "'" . $product_type_id . "',",
            'wholesaler_name,' => "'" . $_POST["wholesaler_name"][$i] . "',",
            'status,' => "'Active',",
            'del_status,' => "'0',",
            'entry_by,' => "'$user_id',",
            'entry_date' => "'" . $db->ToDayDate() . "'"
        );

        $db->data_insert($tbl . 'primary_market_survey', $rowfield);
        $db->system_event_log('', $user_id, $ei_id, $pms_maxID, '', $tbl . 'primary_market_survey', 'Save', '');

        $count=count($_POST['self_variety_id']);
        for($z=0; $z<$count; $z++)
        {
            $rowfield = array(
                'market_survey_group_id,' => "'$group_maxID',",
                'market_survey_id,' => "'$pms_maxID',",
                //'type,' => "'" . $_POST["type"][$z] . "',",
                'crop_id,' => "'" . $_POST["crop_id"][$z] . "',",
                'product_type_id,' => "'" . $_POST["product_type_id"][$z] . "',",
                //'hybrid,' => "'" . $_POST["hybrid"][$z] . "',",
                'varriety_id,' => "'" . $_POST["varriety_id"][$z] . "',",
                'sales_quantity,' => "'" . $_POST["sales_quantity"][$i][$z] . "',",
                'market_size,' => "'" . $_POST["market_size"][$i][$z] . "',",
                'sales_quantity_other,' => "'" . $_POST["sales_quantity_other"][$z] . "',",
                'status,' => "'Active',",
                'del_status,' => "'0',",
                'entry_by,' => "'$user_id',",
                'entry_date' => "'" . $db->ToDayDate() . "'"
            );

            $db->data_insert($tbl . 'primary_market_survey_details', $rowfield);
            $db->system_event_log('', $user_id, $ei_id, $pms_maxID, '', $tbl . 'primary_market_survey_details', 'Save', '');
        }

    }
    $count_p=count($_POST['self_variety_id']);
    for($y=0; $y<$count_p; $y++)
    {
        $ppc_maxID = "PC-".$db->getMaxID_six_digit($tbl . 'pdo_product_characteristic', 'prodcut_characteristic_id');
        $rowfield = array
        (
            'market_survey_group_id,' => "'$group_maxID',",
            'prodcut_characteristic_id,' => "'$ppc_maxID',",
            //'product_category,' => "'" . $_POST["type"][$y] . "',",
            'crop_id,' => "'" . $_POST["crop_id"][$y] . "',",
            'product_type_id,' => "'" . $_POST["product_type_id"][$y] . "',",
            'variety_id,' => "'" . $_POST["varriety_id"][$y] . "',",
            //'hybrid,' => "'" . $_POST["hybrid"][$y] . "',",
            'zone_id,' => "'" . $zone_id . "',",
            'territory_id,' => "'" . $territory_id . "',",
            'district_id,' => "'" . $district_id . "',",
            'upazilla_id,' => "'" . $upazilla_id . "',",
            'sales_quantity,' => "'" . $_POST["sales_quantity_other"][$y] . "',",
            'upload_date,' => "'" . $db->ToDayDate() . "',",
            'status,' => "'Active',",
            'del_status,' => "'0',",
            'entry_by,' => "'$user_id',",
            'entry_date' => "'" . $db->ToDayDate() . "'"
        );
        $db->data_insert($tbl . 'pdo_product_characteristic', $rowfield);
        $db->system_event_log('', $user_id, $ei_id, $ppc_maxID, '', $tbl . 'pdo_product_characteristic', 'Save', '');
    }
    $msg="Successful";
}
else
{
    $msg="Not Successful";
}
echo $msg;
?>