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
    $where="AND order_crop='" . $_POST['name'] . "'";
    echo $sql = "SELECT
                    order_crop
                FROM
                    $tbl" . "crop_info
                WHERE
                    del_status=0 $where
                ORDER BY $tbl" . "crop_info.order_crop
                    ";
}
if($_POST['event']=="Edit")
{
    $where="AND crop_id!='".$_POST['id']."' AND order_crop='" . $_POST['name'] . "'";
    $sql = "SELECT
                    order_crop
                FROM
                    $tbl" . "crop_info
                WHERE
                    del_status=0 $where
                ORDER BY $tbl" . "crop_info.order_crop
                    ";
}


if ($db->open())
{
    $result = $db->query($sql);
    $result_array = $db->fetchAssoc();
}

if($result_array['order_crop']!=""){echo "Found";}else{echo "Not Found";};

?>