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
            $tbl" . "farmer_info.farmer_name,
            $tbl" . "crop_info.crop_name,
            $tbl" . "product_type.product_type,
            $tbl" . "varriety_info.varriety_name,
            $tbl" . "product_pack_size.pack_size_name,
            $tbl" . "pdo_photo_upload.image_title,
            $tbl" . "pdo_photo_upload.description,
            $tbl" . "pdo_photo_upload.upload_date,
            $tbl" . "pdo_photo_upload.image_url,
            $tbl" . "pdo_photo_upload.`status`
        FROM
            $tbl" . "pdo_photo_upload
            LEFT JOIN $tbl" . "crop_info ON $tbl" . "crop_info.crop_id = $tbl" . "pdo_photo_upload.crop_id
            LEFT JOIN $tbl" . "product_type ON $tbl" . "product_type.product_type_id = $tbl" . "pdo_photo_upload.product_type_id
            LEFT JOIN $tbl" . "varriety_info ON $tbl" . "varriety_info.varriety_id = $tbl" . "pdo_photo_upload.varriety_id
            LEFT JOIN $tbl" . "product_pack_size ON $tbl" . "product_pack_size.pack_size_id = $tbl" . "pdo_photo_upload.pack_size
            LEFT JOIN $tbl" . "farmer_info ON $tbl" . "farmer_info.farmer_id = $tbl" . "pdo_photo_upload.farmer_id
        WHERE
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
<h6>
    <?php echo $row['image_title']; ?>
    (<?php echo $db->date_formate($row['upload_date']); ?>)
    <hr />
</h6>
<div style="width: 40%; float: left; ">
    <h6>
        Crop: <span style="color: #990033"><?php echo $row['crop_name']; ?></span>, <br />
        Product Type: <span style="color: #990033"><?php echo $row['product_type']; ?></span>, <br />
        Variety: <span style="color: #990033"><?php echo $row['varriety_name']; ?></span>, <br />
        Pack Size: <span style="color: #990033"><?php echo $row['pack_size_name']; ?></span>
        <br /><br />
        Farmer Name: <span style="color: #990033"><?php echo $row['farmer_name']; ?></span>
    </h6>
    <h6><u>Remark</u></h6>
    <p><?php echo $row['description']; ?></p>
</div>
<div style="width: 60%; float: left;">
    <h6><u>Description</u></h6>
    <p><?php echo $row['description']; ?></p>
</div>
