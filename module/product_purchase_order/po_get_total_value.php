<?php

session_start();

require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$db = new Database();
$db1 = new Database();

$tbl = _DB_PREFIX;

//$delsql="DELETE FROM $tbl" . "product_purchase_order_request WHERE id='".$_POST['elm_id']."'";
$sql="SELECT id, quantity, price, total_price, sum(quantity * price) AS total_p FROM `ait_product_purchase_order_request` GROUP BY id";
if($db->open())
{
    $result = $db->query($sql);
    while ($row = $db->fetchAssoc($result))
    {
        if($row['total_price']<$row['total_p'])
        {
            echo $row['id']."==".$row['total_price']."==".$row['total_p']."<br />";
//            $rowfield = array
//            (
//                'total_price' => "'" . $row['total_p'] . "'"
//            );
//            $wherefield = array('id' => "'" . $row['id'] . "'");
//            $db1->data_update($tbl . 'product_purchase_order_request', $rowfield, $wherefield);
        }
    }
}

?>