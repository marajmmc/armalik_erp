<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$tbl = _DB_PREFIX;

$term = trim(strip_tags($_GET['term'])); //retrieve the search term that autocomplete sends

echo $sql_std = "SELECT
                invoice_id
            FROM
                $tbl"."product_purchase_order_invoice
            WHERE
                invoice_id like '" . $term . "%'
            
";
if ($db->open()) {
    $result = $db->query($sql_std);
    $data = array();
    while ($row_std = $db->fetchAssoc($result)) {
        $data[] = array(
            'label' => $row_std['invoice_id'],
            'value' => $row_std['invoice_id']
        );
    }
}
echo json_encode($data); //format the array into json data
flush();
?>