<?php
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();

?>
<div style="text-align: center;">
    <img src="../../system_images/logo/logo-wide.png" width="40%"/><br/>
    <b><u><?php echo $db->Get_Auto_TaskName() ?></u></b>
    <label style="float: right; font-size: 11px;">Print Date: <?php echo $db->date_formate($db->ToDayDate())?></label>
</div>