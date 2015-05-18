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
$sql = "SELECT
            $tbl" . "pdo_photo_upload.upload_id,
            $tbl" . "varriety_info.varriety_name,
            CASE
                    WHEN $tbl" . "varriety_info.type=0 THEN 'ARM'
                    WHEN $tbl" . "varriety_info.type=1 THEN 'Check Variety'
                    WHEN $tbl" . "varriety_info.type=2 THEN 'Upcoming'
            END as pdo_type,
            $tbl" . "farmer_info.farmer_name,
            $tbl" . "pdo_photo_upload.image_url,
            $tbl" . "pdo_photo_upload.description,
            $tbl" . "pdo_photo_upload.upload_date,
            $tbl" . "pdo_photo_upload.`status`
        FROM
            $tbl" . "pdo_photo_upload
            LEFT JOIN $tbl" . "varriety_info ON $tbl" . "varriety_info.varriety_id = $tbl" . "pdo_photo_upload.pdo_id
            LEFT JOIN $tbl" . "farmer_info ON $tbl" . "farmer_info.farmer_id = $tbl" . "pdo_photo_upload.farmer_id
        WHERE
            $tbl" . "pdo_photo_upload.del_status='0' AND
            $tbl" . "pdo_photo_upload.upload_id='" . $_POST['row_id'] . "'
    ";
if ($db->open()) {
    $result = $db->query($sql);
    $row = $db->fetchAssoc();
}
?>
<samp style="padding: 5px; font-weight: bold; float: right; cursor: pointer;" onclick="close_image()">X</samp>
<div class="image_full_width">
    <img src="../../system_images/pdo_upload_image/<?php echo $row['image_url']; ?>" height="100%" width="100%"/>
</div>

<div style="width: 40%; float: left; ">
    <h6>
        Variety Name: <span style="color: #990033"><?php echo $row['varriety_name']; ?></span>, <br />
        Type: <span style="color: #990033"><?php echo $row['pdo_type']; ?></span>, <br />
        Upload Date: <span style="color: #990033"><?php echo $db->date_formate($row['upload_date']); ?></span> <br />
        <br /><br />
        Farmer Name: <span style="color: #990033"><?php echo $row['farmer_name']; ?></span>
    </h6>
    <h6><u>Remark</u></h6>
    <p><?php echo $row['description']; ?></p>
</div>
<div style="width: 60%; float: left;">
    <h6><u>Description</u></h6>
    <p id="div_comment">
        
    </p>
<!--    <hr />
    <h6><u>Add Comment</u></h6>
    <textarea id="comment" name="comment" class="span12" rows="5" ></textarea>
    <input type="button" class="btn-info" value="Add" style="float: right;" onclick="add_comment_fnc('<?php echo $row['upload_id']?>')"/>-->
</div>
<script>
    $(document).ready(function(){
        load_comment_fnc('<?php echo $row['upload_id']?>');
    })
</script>