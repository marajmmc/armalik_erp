<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$tbl = _DB_PREFIX;
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
                                echo $db->SelectList($sql_uesr_group);
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
                                echo $db->SelectList($sql_uesr_group);
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
                                <option value="">Select</option>
                            </select>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="crop_id">
                            Variety Name
                        </label>
                        <div class="controls">
                            <select id="pdo_id" name="pdo_id" class="span5" placeholder="Select Product" validate="Require">
                                <option value="">Select</option>
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
                                <input readonly="" type="text" name="upload_date" id="upload_date" value="<?php echo $db->date_formate($db->ToDayDate())?>" class="span9" placeholder="Date" validate="Require"  />
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
                            <textarea class="span9" name="description" id="description" placeholder="Description" ></textarea>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label bottom-margin" for="image_url">
                            Upload File
                        </label>
                        <div class="controls">
                            <img src="../../system_images/profile.png" style="width: 77px; height: 77px;" id="blah" />
                            <input type="file" name="image_url" id="image_url" class="span9" placeholder="Upload Image" onchange="readURL(this)" />
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

