<?php

session_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$db = new Database();
$user_id = $_SESSION['user_id'];
$ei_id = $_SESSION['employee_id'];
$tbl = _DB_PREFIX;
$sample_qnty = '0';
$rnd_qnty = '0';

@$distributor_id = $_POST["distributor_id"];

$ws = $db->single_data_w($tbl . "product_stock", "id", "crop_id='" . $_POST['crop_id'] . "' AND product_type_id='" . $_POST['product_type_id'] . "' AND varriety_id='" . $_POST['varriety_id'] . "' AND pack_size='" . $_POST['pack_size'] . "' AND warehouse_id='" . $_POST['warehouse_id'] . "'");
if ($ws['id'] != "")
{

    if ($_POST['purpose'] == "Sample Purpose") {
        $sample_qnty = $_POST['stock_out'];
        $current_stock_qunatity = $_POST["current_stock_qunatity"] - $sample_qnty;
    } else if ($_POST['purpose'] == "R&D Purpose") {
        $rnd_qnty = $_POST['stock_out'];
        $current_stock_qunatity = $_POST["current_stock_qunatity"] - $rnd_qnty;
    } else {
        $sample_qnty = '0';
        $rnd_qnty = '0';
    }

    $pid = $db->single_data_w($tbl . 'product_stock', "count(id) as product_id", "crop_id='" . $_POST['crop_id'] . "' AND product_type_id='" . $_POST['product_type_id'] . "' AND varriety_id='" . $_POST['varriety_id'] . "' AND pack_size='" . $_POST['pack_size'] . "' AND warehouse_id='" . $_POST['warehouse_id'] . "'");
    if ($pid['product_id'] != 0)
    {
        //        echo "update";
        $mSQL_task = "update `$tbl" . "product_stock` set
				`current_stock_qunatity`='$current_stock_qunatity', 
				`sample_quantity`=sample_quantity+'" . $sample_qnty . "', 
				`rnd_quantity`=rnd_quantity+'" . $rnd_qnty . "', 
				`status`='Active', 
				`del_status`='0', 
				`entry_by`='" . $user_id . "', 
				`entry_date`='" . $db->ToDayDate() . "'
			where crop_id='" . $_POST['crop_id'] . "' AND product_type_id='" . $_POST['product_type_id'] . "' AND varriety_id='" . $_POST['varriety_id'] . "' AND pack_size='" . $_POST['pack_size'] . "' AND warehouse_id='" . $_POST['warehouse_id'] . "'
				";

        if ($db->open()) {
            $db->query($mSQL_task);
            $db->freeResult();
        }
        $db->system_event_log('', $user_id, $ei_id, '', '', $tbl . 'product_stock', 'Update', '');
    }
    else
    {
        //        echo "insert";
        $rowfield = array(
            'warehouse_id,' => "'" . $_POST["warehouse_id"] . "',",
            'crop_id,' => "'" . $_POST["crop_id"] . "',",
            'product_type_id,' => "'" . $_POST["product_type_id"] . "',",
            'varriety_id,' => "'" . $_POST["varriety_id"] . "',",
            'pack_size,' => "'" . $_POST["pack_size"] . "',",
            'current_stock_qunatity,' => "'" . $current_stock_qunatity . "',",
            'sample_quantity,' => "sample_quantity+'" . $sample_qnty . "',",
            'rnd_quantity,' => "rnd_quantity+'" . $rnd_qnty . "',",
            'status,' => "'Active',",
            'del_status,' => "'0',",
            'entry_by,' => "'$user_id',",
            'entry_date' => "'" . $db->ToDayDate() . "'"
        );

        $db->data_insert($tbl . 'product_stock', $rowfield);
        $db->system_event_log('', $user_id, $ei_id, '', '', $tbl . 'product_stock', 'Save', '');
    }

    $MaxID = "IT-" . $db->getMaxID_six_digit($tbl . 'product_inventory', 'inventory_id');
    $rowfield = array(
        'inventory_id,' => "'" . $MaxID . "',",
        'inventory_date,' => "'" . $db->date_formate($_POST["inventory_date"]) . "',",
        'warehouse_id,' => "'" . $_POST["warehouse_id"] . "',",
        'year_id,' => "'" . $_POST["year_id"] . "',",
        'crop_id,' => "'" . $_POST["crop_id"] . "',",
        'product_type_id,' => "'" . $_POST["product_type_id"] . "',",
        'varriety_id,' => "'" . $_POST["varriety_id"] . "',",
        'pack_size,' => "'" . $_POST["pack_size"] . "',",
        'current_stock_qunatity,' => "'" . $_POST["current_stock_qunatity"] . "',",
        'sample_quantity,' => "'" . $sample_qnty . "',",
        'rnd_quantity,' => "'" . $rnd_qnty . "',",
        'purpose,' => "'" . $_POST["purpose"] . "',",
        'distributor_id,' => "'" . $distributor_id . "',",
        'remark,' => "'" . $_POST["remark"] . "',",
        'status,' => "'Active',",
        'del_status,' => "'0',",
        'entry_by,' => "'$user_id',",
        'entry_date' => "'" . $db->ToDayDate() . "'"
    );

    $db->data_insert($tbl . 'product_inventory', $rowfield);
    $db->system_event_log('', $user_id, $ei_id, '', '', $tbl . 'product_inventory', 'Save', '');
    echo "Sucess";
}
else
{
    echo "Not Sucess";
}
?>