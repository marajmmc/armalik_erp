<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$tbl = _DB_PREFIX;
$editrow = $db->single_data($tbl . "pdo_variety_video_upload", "*", "vvu_id", $_POST['rowID']);
?>
<div class="row-fluid">
    <div class="span12">
        <div class="widget span">
            <div class="widget-header">
                <div class="title">
                    <a id="dynamicTable"><?php echo $db->Get_Auto_TaskName() ?></a>
                    <span class="mini-title">

                    </span>
                </div>
                <span class="tools">
                    <a class="btn btn-small" data-original-title="">
                        <i class="icon-plus-sign" data-original-title="Share"> </i>
                    </a>
                </span>
            </div>
            <div class="form-horizontal no-margin">
                <div class="widget-body">
                    <div class="control-group">
                        <label class="control-label" for="designation_title_en">
                            Crop Name
                        </label>
                        <div class="controls">
                            <select id="crop_id" name="crop_id" class="span5" placeholder="Crop" validate="Require" onchange="load_product_type()">
                                <?php
                                echo "<option value=''>Select</option>";
                                $sql_uesr_group = "select crop_id as fieldkey, crop_name as fieldtext from $tbl" . "crop_info where status='Active' AND del_status='0'  ORDER BY $tbl" . "crop_info.order_crop";
                                echo $db->SelectList($sql_uesr_group, $editrow['crop_id']);
                                ?>
                            </select>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="designation_title_bn">
                            Product Type
                        </label>
                        <div class="controls">
                            <select id="product_type_id" name="product_type_id" class="span5" placeholder="Product Type" >
                                <?php
                                echo "<option value=''>Select</option>";
                                $sql_uesr_group = "select product_type_id as fieldkey, product_type as fieldtext from $tbl" . "product_type where status='Active' AND del_status='0' AND crop_id='".$editrow['crop_id']."' ORDER BY product_type";
                                echo $db->SelectList($sql_uesr_group, $editrow['product_type_id']);
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" >
                            ARM Variety
                        </label>
                        <div class="controls">
                            <select id="arm_variety_id" name="arm_variety_id" class="span5" placeholder="Product Type" validate="Require">
                                <?php
                                echo "<option value=''>Select</option>";
                                //            $sql_uesr_group = "select pdo_id as fieldkey, pdo_name as fieldtext from $tbl" . "pdo_product_info where pdo_type='Self' AND crop_id='$crop' AND product_type_id='$product_type' AND status='Active' ORDER BY $tbl"."pdo_product_info.pdo_name";
                                $sql_uesr_group = "
                            select
                            varriety_id as fieldkey,
                            CONCAT_WS(' - ', varriety_name,
                            CASE
                                    WHEN type=0 THEN 'ARM'
                                    WHEN type=1 THEN 'Check Variety'
                                    WHEN type=2 THEN 'Upcoming'
                            END, hybrid) as fieldtext
                            from $tbl" . "varriety_info
                            where
                            status='Active'
                            AND del_status=0
                            AND type=0
                            AND crop_id='".$editrow['crop_id']."' AND product_type_id='".$editrow['product_type_id']."'
                            ORDER BY order_variety";
                                echo $db->SelectList($sql_uesr_group, $editrow['arm_variety_id']);
                                ?>
                            </select>
                            <span class="help-inline">
                                                    *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">
                            Comparator Variety
                        </label>
                        <div class="controls">
                            <select id="check_variety_id" name="check_variety_id" class="span5" placeholder="" validate="Require">
                                <?php
                                echo "<option value=''>Select</option>";
                                //            $sql_uesr_group = "select pdo_id as fieldkey, pdo_name as fieldtext from $tbl" . "pdo_product_info where pdo_type='Checked Variety' AND crop_id='$crop' AND product_type_id='$product_type' AND status='Active' ORDER BY $tbl"."pdo_product_info.pdo_name";
                                $sql_uesr_group = "
                            select
                            varriety_id as fieldkey,
                            CONCAT_WS(' - ', varriety_name,
                            CASE
                                    WHEN type=0 THEN 'ARM'
                                    WHEN type=1 THEN 'Check Variety'
                                    WHEN type=2 THEN 'Upcoming'
                            END, hybrid) as fieldtext
                            from $tbl" . "varriety_info
                            where
                            status='Active'
                            AND del_status=0
                            AND type=1
                            AND crop_id='".$editrow['crop_id']."' AND product_type_id='".$editrow['product_type_id']."'
                            ORDER BY order_variety";
                                echo $db->SelectList($sql_uesr_group, $editrow['check_variety_id']);
                                ?>
                            </select>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">
                            Video Upload
                        </label>
                        <div class="controls">
                            <input type="file" id="file_url" name="file_url" />

                            <video width="400" controls>
                                <source src="../../system_images/pdo_upload_image/crop_img_url/<?php echo $editrow['file_url']; ?>" type="video/mp4">
                                <source src="../../system_images/pdo_upload_image/crop_img_url/<?php echo $editrow['file_url']; ?>" type="video/ogg">
                                Your browser does not support HTML5 video.
                            </video>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
