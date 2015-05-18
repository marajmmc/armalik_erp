<?php

session_start();

require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$db = new Database();
$db_ins = new Database();
$db_up = new Database();
$db_data = new Database();
$user_id = $_SESSION['user_id'];
$ei_id = $_SESSION['employee_id'];
$tbl = _DB_PREFIX;

$sql = "select * from ait_product_purchase_order_bonus";
if ($db->open()) {
    $result = $db->query($sql);
    while ($row = $db->fetchAssoc($result)) {
//        echo $row['distributor_id'];
        $pid = $db_data->single_data_w($tbl . 'distributor_product_stock', "count(id) as product_id", "crop_id='" . $row['crop_id'] . "' AND product_type_id='" . $row['product_type_id'] . "' AND varriety_id='" . $row['varriety_id'] . "' AND pack_size='" . $row['pack_size'] . "' AND distributor_id='" . $row['distributor_id'] . "'");
        if ($pid['product_id'] != 0) {
            echo $mSQL_task = "update `$tbl" . "distributor_product_stock` set
                                `bonus_quantity`=bonus_quantity+'" . $row["quantity"] . "', 
				`current_stock_qunatity`=current_stock_qunatity+'" . $row["quantity"] . "', 
				`status`='Active', 
				`del_status`='0', 
				`entry_by`='" . $user_id . "', 
				`entry_date`='" . $db->ToDayDate() . "'
			where crop_id='" . $row['crop_id'] . "' AND product_type_id='" . $row['product_type_id'] . "' AND varriety_id='" . $row['varriety_id'] . "' AND pack_size='" . $row['pack_size'] . "' AND distributor_id='" . $row['distributor_id'] . "'
				";

            if ($db_ins->open()) {
                $db_ins->query($mSQL_task);
                $db_ins->freeResult();
            }
        } else {
            $rowfield = array(
                'distributor_id,' => "'" . $row["distributor_id"] . "',",
                'crop_id,' => "'" . $row["crop_id"] . "',",
                'product_type_id,' => "'" . $row["product_type_id"] . "',",
                'varriety_id,' => "'" . $row["varriety_id"] . "',",
                'pack_size,' => "'" . $row["pack_size"] . "',",
                'bonus_quantity,' => "bonus_quantity+'" . $row["quantity"] . "',",
                'current_stock_qunatity,' => "current_stock_qunatity+'" . $row["quantity"] . "',",
                'status,' => "'Active',",
                'del_status,' => "'0',",
                'entry_by,' => "'$user_id',",
                'entry_date' => "'" . $db->ToDayDate() . "'"
            );

            $db_up->data_insert($tbl . 'distributor_product_stock', $rowfield);
        }
    }
}
?>