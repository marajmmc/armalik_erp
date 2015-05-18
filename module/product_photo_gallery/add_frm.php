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
                        <label class="control-label" for="crop_id">
                            Crop
                        </label>
                        <div class="controls">
                            <select id="crop_id" name="crop_id" class="span5" placeholder="Select Crop" validate="Require" onchange="load_product_type()">
                                <?php
                                echo "<option value=''>Select</option>";
                                $sql_uesr_group = "select crop_id as fieldkey, crop_name as fieldtext from $tbl" . "crop_info where status='Active'";
                                echo $db->SelectList($sql_uesr_group);
                                ?>
                            </select>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="product_type_id">
                            Product Type
                        </label>
                        <div class="controls">
                            <select id="product_type_id" name="product_type_id" class="span5" placeholder="Type" onchange="load_varriety_fnc()" validate="Require">
                                <option value="">Select</option>
                            </select>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="varriety_id">
                             Variety
                        </label>
                        <div class="controls">
                            <select id="varriety_id" name="varriety_id" class="span5" placeholder="Select Variety" validate="Require" >
                                <option value="">Select</option>
                            </select>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="location">
                            Location
                        </label>
                        <div class="controls">
                            <input class="span9" type="text" name="location" id="location" placeholder="Location" validate="Require">
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="farmer_name">
                            Farmer Name
                        </label>
                        <div class="controls">
                            <input type="text" name="farmer_name" id="farmer_name" class="span9" placeholder="Farmer Name"  validate="Require" />
                        </div>
                        <span class="help-inline">
                            *
                        </span>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="phone_number">
                            Farmer Phone Number
                        </label>
                        <div class="controls">
                            <input type="text" name="phone_number" id="phone_number" class="span9" placeholder="Farmer Phone Number"  validate="Mobile" maxlength="11" onkeypress="return numbersOnly(event)"/>
                        </div>
                        <span class="help-inline">
                            *
                        </span>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="picture_date">
                            Picture Date
                        </label>
                        <div class="controls">
                            <div class="input-append">
                                <input type="text" name="picture_date" id="picture_date" class="span9" placeholder="Picture Date"  />
                                <span class="add-on" id="calcbtn_picture_date">
                                    <i class="icon-calendar"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label bottom-margin" for="image_url">
                            Product Photo
                        </label>
                        <div class="controls">
                            <img src="../../system_images/profile.png" style="width: 77px; height: 77px;" id="blah" />
                            <input type="file" name="photo" id="photo" class="span9" placeholder="Profile Photo" onchange="readURL(this)" validate="Picture" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>    
    var cal = Calendar.setup({
        onSelect: function(cal) { cal.hide() },
        fdow :0,
        minuteStep:1
    });
    cal.manageFields("calcbtn_picture_date", "picture_date", "%d-%m-%Y");
        
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