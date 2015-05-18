<?php
session_start();

require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$db = new Database();
$user_id = $_SESSION['user_id'];
$ei_id = $_SESSION['employee_id'];
$tbl = _DB_PREFIX;

$total_pack_price='';
$total_quantity='';
$total_price='';
$start_date=$_POST["start_date"]."-01-01";
$maxID = "ST-" . $db->Get_CustMaxID($tbl . 'product_sale_target', 'sale_target_id','8','');

$count = count($_POST['id']);

for ($i = 0; $i < $count; $i++) {
    
    $rowfield = array(
        'sale_target_id,' => "'$maxID',",
        'zone_id,' => "'" . $_POST["zone_id"] . "',",
        'territory_id,' => "'" . $_POST["territory_id"] . "',",
        'distributor_id,' => "'" . $_POST["distributor_id"] . "',",
        'year_id,' => "'" .$_POST['year_id'] . "',",
//        'end_date,' => "'" .$db->date_formate($_POST["end_date"]) . "',",
        'crop_id,' => "'" . $_POST["crop_id"][$i] . "',",
        'product_type_id,' => "'" . $_POST["product_type_id"][$i] . "',",
        'varriety_id,' => "'" . $_POST["varriety_id"][$i] . "',",
        'price,' => "'" . $_POST["price"][$i] . "',",
        'quantity,' => "'" . $_POST["quantity"][$i] . "',",
        'value,' => "'" . $_POST["value"][$i] . "',",
        'channel,' => "'Distributor',",
        'status,' => "'Active',",
        'del_status,' => "'0',",
        'entry_by,' => "'$user_id',",
        'entry_date' => "'" . $db->ToDayDate() . "'"
    );

    $db->data_insert($tbl . 'product_sale_target', $rowfield);
    $db->system_event_log('', $user_id, $ei_id, $maxID, '', $tbl . 'product_sale_target', 'Save', '');
}

?>