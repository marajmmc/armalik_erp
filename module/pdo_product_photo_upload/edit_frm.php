<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$tbl = _DB_PREFIX;
$editrow = $db->single_data($tbl . "pdo_photo_upload", "*", "upload_id", $_POST['rowID']);
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
                        <label class="control-label" for="farmer_id">
                            Farmer Name
                        </label>
                        <div class="controls">
                            <select id="farmer_id" name="farmer_id" class="span5" placeholder="Select Farmer" validate="Require">
                                <?php
                                echo "<option value=''>Select</option>";
                                $sql_uesr_group = "select farmer_id as fieldkey, farmer_name as fieldtext from $tbl" . "farmer_info where status='Active'";
                                echo $db->SelectList($sql_uesr_group, $editrow['farmer_id']);
                                ?>
                            </select>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="crop_id">
                            Crop
                        </label>
                        <div class="controls">
                            <select id="crop_id" name="crop_id" class="span5" placeholder="Select Crop" validate="Require" onchange="load_product_type()">
                                <?php
                                echo "<option value=''>Select</option>";
                                $sql_uesr_group = "select crop_id as fieldkey, crop_name as fieldtext from $tbl" . "crop_info where status='Active' ORDER BY $tbl" . "crop_info.order_crop";
                                echo $db->SelectList($sql_uesr_group, $editrow['crop_id']);
                                ?>
                            </select>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="product_type_id" >
                            Product Type
                        </label>
                        <div class="controls">
                            <select id="product_type_id" name="product_type_id" class="span5" placeholder="Product Type" onchange="load_pdo_variety()">
                                <?php
                                echo "<option value=''>Select</option>";
                                $sql_uesr_group = "select product_type_id as fieldkey, product_type as fieldtext from $tbl" . "product_type where status='Active' AND del_status='0' AND crop_id='".$editrow['crop_id']."'";
                                echo $db->SelectList($sql_uesr_group, $editrow['product_type_id']);
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="pdo_id">
                            Variety Name
                        </label>
                        <div class="controls">
                            <select id="pdo_id" name="pdo_id" class="span5" placeholder="Select Product" validate="Require">
                                <?php
                                echo "<option value=''>Select</option>";
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
                                                AND del_status='0'
                                                AND crop_id='".$editrow['crop_id']."'
                                                AND product_type_id='".$editrow['product_type_id']."'
                                                ORDER BY order_variety";
                                echo $db->SelectList($sql_uesr_group, $editrow['pdo_id']);
                                ?>
                            </select>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="upload_date">
                            Date
                        </label>
                        <div class="controls">
                            <div class="input-append">
                                <input readonly="" type="text" name="upload_date" id="upload_date" value="<?php echo $db->date_formate($editrow['upload_date']); ?>" class="span9" placeholder="Date" validate="Require"  />
                                <span class="add-on" id="calcbtn_upload_date">
                                    <i class="icon-calendar"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="description">
                            Remark
                        </label>
                        <div class="controls">
                            <textarea class="span9" name="description" id="description" placeholder="Description" > <?php echo $editrow['description']; ?></textarea>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label bottom-margin" for="image_url">
                            Upload File
                        </label>
                        <div class="controls">
                            <img src="../../system_images/pdo_upload_image/<?php echo $editrow['image_url']; ?>" style="width: 77px; height: 77px;" id="blah" />
                            <input type="file" name="image_url" id="image_url" class="span9" placeholder="Upload Image" onchange="readURL(this)" />
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="blood_group">
                            Status
                        </label>
                        <div class="controls controls-row">
                            <select id="status" name="status" class="span9" placeholder="Group Name">
                                <option value="">Select</option>
                                <option value="Active" <?php
                                if ($editrow['status'] == "Active") {
                                    echo "selected='selected'";
                                }
                                ?> >Active</option>
                                <option value="In-Active" <?php
                                        if ($editrow['status'] == "In-Active") {
                                            echo "selected='selected'";
                                        }
                                ?> >In-Active</option>

                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>    
    //    var cal = Calendar.setup({
    //        onSelect: function(cal) { cal.hide() },
    //        fdow :0,
    //        minuteStep:1
    //    });
    //    cal.manageFields("calcbtn_upload_date", "upload_date", "%d-%m-%Y");
        
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function  ( e ) {
                $('#blah').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>