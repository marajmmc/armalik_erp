<?php
session_start();
ob_start();
if ($_SESSION['logged'] != 'yes') {
    $_REQUEST["msg"] = "TimeoutC";
    header("location:../../index.php");
}
//echo $_SESSION['shop_name_eng']; 
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

//echo $_POST['rowID'];
$tbl = _DB_PREFIX;
$db = new Database();

if($_POST['event']=="Add")
{
    $where="AND order_type='" . $_POST['name'] . "'";
    $sql = "SELECT
                    order_type
                FROM
                    $tbl" . "product_type
                WHERE
                    del_status=0 $where
                    ";
}
if($_POST['event']=="Edit")
{
    $where="AND crop_id='".$_POST['crop_id']."' AND product_type_id!='".$_POST['id']."' AND order_type='" . $_POST['name'] . "'";
    $sql = "SELECT
                    order_type
                FROM
                    $tbl" . "product_type
                WHERE
                    del_status=0 $where
                    ";
}


if ($db->open())
{
    $result = $db->query($sql);
    $result_array = $db->fetchAssoc();
}

if($result_array['order_type']!=""){echo "Found";}else{echo "Not Found";};

?>