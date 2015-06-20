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
$crop_id=$_POST['crop_master_id'];
$product_type_id=$_POST['product_master_type_id'];

$db_pmsd=new Database();
$db_ppc=new Database();

if(!empty($zone_id) && !empty($territory_id) && !empty($district_id) && !empty($upazilla_id) || !empty($crop_id) || !empty($product_type_id))
{

    $group_maxID = $_POST['rowID'];
    $market_survey_count=sizeof($_POST['market_survey_id']);

    for($i=0; $i<$market_survey_count; $i++)
    {
        $pms_maxID = $_POST['market_survey_id'][$i];
        if(!empty($pms_maxID))
        {
            $rowfield = array
            (
                'wholesaler_name' => "'" . $_POST["wholesaler_name"][$i] . "'",
                'status' => "'Active'",
                'del_status' => "'0'",
                'entry_by' => "'$user_id'",
                'entry_date' => "'" . $db->ToDayDate() . "'"
            );
            $wherefield=array('market_survey_id'=>"'$pms_maxID'");
            $db->data_update($tbl . 'primary_market_survey', $rowfield, $wherefield);
            $db->system_event_log('', $user_id, $ei_id, $pms_maxID, '', $tbl . 'primary_market_survey', 'Update', '');

            $count=count($_POST['self_variety_id']);
            for($z=0; $z<$count; $z++)
            {
                $crop_id=$_POST["crop_id"][$z];
                $product_type_id=$_POST["product_type_id"][$z];
                $variety_id=$_POST["varriety_id"][$z];
                $sale_quantity=$_POST["sales_quantity"][$i][$z];
                $market_size=$_POST["market_size"][$i][$z];
                $sale_quantity_other=$_POST["sales_quantity_other"][$z];
                if(!empty($crop_id) && !empty($product_type_id) && !empty($variety_id))
                {
                    $sqlpmsd="UPDATE $tbl"."primary_market_survey_details SET
                            sales_quantity='".$sale_quantity."',
                            market_size='".$market_size."',
                            sales_quantity_other='".$sale_quantity_other."'
                        WHERE
                            crop_id='$crop_id'
                            AND product_type_id='$product_type_id'
                            AND varriety_id='$variety_id'
                            AND market_survey_group_id='$group_maxID'
                            AND market_survey_id='$pms_maxID'
                ";
                    if($db_pmsd->open())
                    {
                        $db_pmsd->query($sqlpmsd);
                    }
                    $db->system_event_log('', $user_id, $ei_id, $pms_maxID, '', $tbl . 'primary_market_survey_details', 'Save', '');


                    $sqlppc="UPDATE $tbl"."pdo_product_characteristic SET
                            sales_quantity='".$sale_quantity_other."'
                        WHERE
                            crop_id='$crop_id'
                            AND product_type_id='$product_type_id'
                            AND variety_id='$variety_id'
                            AND market_survey_group_id='$group_maxID'
                            AND zone_id='$zone_id'
                            AND territory_id='$territory_id'
                            AND district_id='$district_id'
                            AND upazilla_id='$upazilla_id'
                ";
                    if($db_ppc->open())
                    {
                        $db_ppc->query($sqlppc);
                    }
                    $db->system_event_log('', $user_id, $ei_id, $group_maxID, '', $tbl . 'pdo_product_characteristic', 'Save', '');
                }

            }
        }
        else
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
                $rowfield = array
                (
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
    }

    $msg="Successful";
}
else
{
    $msg="Not Successful";
}
echo $msg;
?>