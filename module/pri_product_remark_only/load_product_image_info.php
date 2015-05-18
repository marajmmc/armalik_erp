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
            $tbl"."crop_info.crop_name,
            $tbl"."pdo_variety_setting_img_date.upload_date, 
            $tbl"."pdo_variety_setting_img_date.crop_img_url, 
            $tbl"."pdo_variety_setting_img_date.id,
            $tbl"."pdo_variety_setting_img_date.entry_date,
            $tbl"."product_type.product_type, 
            $tbl"."varriety_info.varriety_name,
            $tbl"."farmer_info.farmer_name,
            $tbl"."employee_basic_info.employee_name
        FROM $tbl"."pdo_variety_setting_img_date 
             LEFT JOIN $tbl"."pdo_variety_setting ON $tbl"."pdo_variety_setting.vs_id = $tbl"."pdo_variety_setting_img_date.vs_id
             LEFT JOIN $tbl"."farmer_info ON $tbl"."farmer_info.farmer_id = $tbl"."pdo_variety_setting.farmer_id
             LEFT JOIN $tbl"."crop_info ON $tbl"."crop_info.crop_id = $tbl"."pdo_variety_setting.crop_id
             LEFT JOIN $tbl"."product_type ON $tbl"."product_type.crop_id = $tbl"."pdo_variety_setting.crop_id AND $tbl"."product_type.product_type_id = $tbl"."pdo_variety_setting.product_type_id
             LEFT JOIN $tbl"."varriety_info ON  $tbl"."varriety_info.varriety_id = $tbl"."pdo_variety_setting.variety_id
             LEFT JOIN $tbl"."user_login ON $tbl"."user_login.user_id = $tbl"."pdo_variety_setting_img_date.entry_by
             LEFT JOIN $tbl"."employee_basic_info ON $tbl"."employee_basic_info.employee_id = $tbl"."user_login.employee_id
        WHERE
            $tbl" . "pdo_variety_setting_img_date.del_status='0' AND
            $tbl" . "pdo_variety_setting_img_date.id='" . $_POST['row_id'] . "'
    ";
if ($db->open())
{
    $result = $db->query($sql);
    $row = $db->fetchAssoc();
}
?>
<samp style="padding: 5px; font-weight: bold; float: right; cursor: pointer;" onclick="close_image()">X</samp>
<div class="image_full_width">
    <img src="../../system_images/pdo_upload_image/crop_img_url/<?php echo $row['crop_img_url']; ?>" height="100%" width="100%"/>
</div>

<div style="width: 40%; float: left; ">
    <h6>
        Variety Name: <span style="color: #990033"><?php echo $row['varriety_name']; ?></span>, <br />
        Upload Date: <span style="color: #990033"><?php echo $db->date_formate($row['upload_date']); ?></span> <br />
        Actual Upload Date: <span style="color: #990033"><?php echo $db->date_formate($row['entry_date']); ?></span> <br />
        Farmer Name: <span style="color: #990033"><?php echo $row['farmer_name']; ?></span><br />
        PDO's Name: <span style="color: #990033"><?php echo $row['employee_name']; ?></span>
    </h6>
</div>
<div style="width: 60%; float: left;">
<!--    <h6><u>Description</u></h6>-->
<!--    <p id="div_comment">-->
<!---->
<!--    </p>-->
    <hr />
    <h6><u>Add Comment</u></h6>
    <textarea id="comment" name="comment" class="span12" rows="5" ></textarea>
    <input type="button" class="btn-info" value="Add" style="float: right;" onclick="add_comment_fnc('<?php echo $row['id'] ?>')"/>
</div>
<script>
    $(document).ready(function(){
        //load_comment_fnc('<?php //echo $row['id'] ?>');
    })
</script>