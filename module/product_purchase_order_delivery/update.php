<?php

session_start();
if ($_SESSION['logged'] != 'yes') {
    $_REQUEST["msg"] = "TimeoutC";
    header("location:../../index.php");
}
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$db = new Database();
$dbud = new Database();
$dbbn = new Database();
$user_id = $_SESSION['user_id'];
$employee_id = $_SESSION['employee_id'];
$warehouse_id = $_SESSION['warehouse_id'];
$year_id = $_POST['year_id'];

if(empty($warehouse_id))
{
    echo "Not_Successfully_Delivery";
    die();
}
if(empty($_POST['warehouse_id']))
{
    echo "Not_Successfully_Delivery";
    die();
}
if(empty($year_id))
{
    echo "Not_Successfully_Delivery";
    die();
}

$tbl = _DB_PREFIX;

$total_pack_price = '';
$total_quantity = '';
$total_price = '';

$maxID = "PC-" . $db->Get_CustMaxID($tbl . 'product_purchase_order_challan', 'challan_id', '8', '');

$count = count($_POST['id']);
for ($i = 0; $i < $count; $i++)
{
    $total_price = $total_price + $_POST["total_price"][$i];
    $rowfield = array
    (
        'challan_id,' => "'" . $maxID . "',",
        'year_id,' => "'" . $year_id . "',",
        'warehouse_id,' => "'" . $_POST['warehouse_id'] . "',",
        'purchase_order_id,' => "'" . $_POST['purchase_order_id'] . "',",
        'invoice_id,' => "'" . $_POST['invoice_id'] . "',",
        'challan_date,' => "'" . $db->date_formate($_POST["challan_date"]) . "',",
        'zone_id,' => "'" . $_POST["zone_id"] . "',",
        'territory_id,' => "'" . $_POST["territory_id"] . "',",
        'zilla_id,' => "'" . $_POST["zilla_id"] . "',",
        'distributor_id,' => "'" . $_POST["distributor_id"] . "',",
        'crop_id,' => "'" . $_POST["crop_id"][$i] . "',",
        'product_type_id,' => "'" . $_POST["product_type_id"][$i] . "',",
        'varriety_id,' => "'" . $_POST["varriety_id"][$i] . "',",
        'pack_size,' => "'" . $_POST["pack_size"][$i] . "',",
        'price,' => "'" . $_POST["price"][$i] . "',",
        'quantity,' => "'" . $_POST["quantity"][$i] . "',",
        'total_price,' => "'" . $_POST["total_price"][$i] . "',",
        'status,' => "'Pending',",
        'del_status,' => "'0',",
        'entry_by,' => "'$user_id',",
        'entry_date' => "'" . $db->ToDayDate() . "'"
    );

    $db->data_insert($tbl . 'product_purchase_order_challan', $rowfield);
    $db->system_event_log('', $user_id, $employee_id, $maxID, '', $tbl . 'product_purchase_order_challan', 'Save', '');
}

$updatesql = "UPDATE $tbl" . "product_purchase_order_invoice SET
                    status='Delivery'
                WHERE invoice_id='" . $_POST['invoice_id'] . "'";
if ($dbud->open()) {
    $result = $dbud->query($updatesql);
}
$updatebonus = "UPDATE $tbl" . "product_purchase_order_bonus SET
                    challan_id='$maxID'
                WHERE invoice_id='" . $_POST['invoice_id'] . "'";
if ($dbbn->open()) {
    $result = $dbbn->query($updatebonus);
}
echo "Successfully Delivery";
?>