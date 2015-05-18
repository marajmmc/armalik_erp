<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$tbl = _DB_PREFIX;
$crop=$_POST['crop_id'];
$product_type=$_POST['product_type_id'];
?>
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
                            AND crop_id='$crop' AND product_type_id='$product_type'
                            ORDER BY order_variety";
            echo $db->SelectList($sql_uesr_group);
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
                            AND crop_id='$crop' AND product_type_id='$product_type'
                            ORDER BY order_variety";
            echo $db->SelectList($sql_uesr_group);
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
        <input type="file" id="file_url" name="file_url" validate="Require"/>
        <span class="help-inline">
            *
        </span>
    </div>
</div>
