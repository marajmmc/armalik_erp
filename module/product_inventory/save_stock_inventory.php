<?php

session_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$db = new Database();
$db_inv = new Database();
$user_id = "";
$ei_id = "";
$tbl = _DB_PREFIX;

$sql = "SELECT
$tbl" . "product_inventory.warehouse_id,
$tbl" . "product_inventory.crop_id,
$tbl" . "product_inventory.product_type_id,
$tbl" . "product_inventory.varriety_id,
$tbl" . "product_inventory.pack_size,
$tbl" . "product_inventory.current_stock_qunatity,
$tbl" . "product_inventory.damage_quantity,
$tbl" . "product_inventory.access_quantity
FROM
$tbl" . "product_inventory";

if ($db_inv->open()) {
    $result = $db_inv->query($sql);
    while ($row = $db_inv->fetchAssoc($result)) {
        $pid = $db->single_data_w($tbl . 'product_stock', "count(id) as product_id", "crop_id='" . $row['crop_id'] . "' AND product_type_id='" . $row['product_type_id'] . "' AND varriety_id='" . $row['varriety_id'] . "' AND pack_size='" . $row['pack_size'] . "' AND warehouse_id='" . $row['warehouse_id'] . "'");

        if ($pid['product_id'] != 0) {
            echo "update";
            echo $mSQL_task = "update `$tbl" . "product_stock` set
				`access_quantity`=access_quantity+'" . $row["access_quantity"] . "', 
				`status`='Active', 
				`del_status`='0', 
				`entry_by`='" . $user_id . "', 
				`entry_date`='" . $db->ToDayDate() . "'
			where crop_id='" . $row['crop_id'] . "' AND product_type_id='" . $row['product_type_id'] . "' AND varriety_id='" . $row['varriety_id'] . "' AND pack_size='" . $row['pack_size'] . "' AND warehouse_id='" . $row['warehouse_id'] . "'
				";

            if ($db->open()) {
                $db->query($mSQL_task);
                $db->freeResult();
            }
            $db->system_event_log('', $user_id, $ei_id, '', '', $tbl . 'product_stock', 'Update', '');
        } else {
            echo "insert";
            $rowfield = array(
                'warehouse_id,' => "'" . $row["warehouse_id"] . "',",
                'crop_id,' => "'" . $row["crop_id"] . "',",
                'product_type_id,' => "'" . $row["product_type_id"] . "',",
                'varriety_id,' => "'" . $row["varriety_id"] . "',",
                'pack_size,' => "'" . $row["pack_size"] . "',",
                'access_quantity,' => "access_quantity+'" . $row["access_quantity"] . "',",
                'status,' => "'Active',",
                'del_status,' => "'0',",
                'entry_by,' => "'$user_id',",
                'entry_date' => "'" . $db->ToDayDate() . "'"
            );

            $db->data_insert($tbl . 'product_stock', $rowfield);
            $db->system_event_log('', $user_id, $ei_id, '', '', $tbl . 'product_stock', 'Save', '');
        }
    }
}
?>