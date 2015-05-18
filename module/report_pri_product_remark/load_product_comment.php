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
?>
<table width="100%" border="0">

    <?php
    $sl = 1;
    $sqlb = "SELECT
            $tbl" . "pdo_photo_remark.id,
            $tbl" . "pdo_photo_remark.description
        FROM `$tbl" . "pdo_photo_remark`
        WHERE $tbl" . "pdo_photo_remark.crop_img_upload_id='" . $_POST['row_id'] . "' AND
            $tbl" . "pdo_photo_remark.status='Active' AND
            $tbl" . "pdo_photo_remark.del_status='0' 
                    ";
    if ($db->open()) {
        $resultb = $db->query($sqlb);
        while ($rowb = $db->fetchAssoc($resultb)) {
            echo "<tr id='tr_id$rowb[id]' style='border:1px solid;'>
                    <td>
                        $sl . $rowb[description]
                    </td>
                </tr>";
            ++$sl;
        }
    }
    ?>
</table>
