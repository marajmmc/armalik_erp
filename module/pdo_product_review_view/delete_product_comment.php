<?php

session_start();

require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$db = new Database();
$user_id = $_SESSION['user_id'];
$ei_id = $_SESSION['employee_id'];
$tbl = _DB_PREFIX;
$maxID=$_POST['row_id'];
echo $sql="DELETE FROM $tbl"."pdo_photo_description WHERE id=$maxID";
if($db->open()){
    $db->query($sql);
}
        
?>
