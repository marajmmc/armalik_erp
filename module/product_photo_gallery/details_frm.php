<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$tbl = _DB_PREFIX;
$editrow = $db->single_data($tbl . "product_photo_gallery", "*", "photo_gallery_id", $_POST['rowID']);
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
                            <select disabled="" id="crop_id" name="crop_id" class="span5" placeholder="Select Crop" validate="Require" onchange="load_varriety_fnc()">
                                <?php
                                echo "<option value=''>Select</option>";
                                $sql_uesr_group = "select crop_id as fieldkey, crop_name as fieldtext from $tbl" . "crop_info where status='Active'";
                                echo $db->SelectList($sql_uesr_group, $editrow['crop_id']);
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
                            <select disabled="" id="product_type_id" name="product_type_id" class="span5" placeholder="Select Product Type" validate="Require" onchange="load_varriety_fnc()">
                                <option value="">Select</option>
                                <?php
                                $sql_uesr_group = "select product_type_id as fieldkey, product_type as fieldtext from $tbl" . "product_type where status='Active' AND del_status='0' AND crop_id='".$editrow['crop_id']."'";
                                echo $db->SelectList($sql_uesr_group, $editrow['product_type_id']);
                                ?>
                                ?>
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
                            <select disabled="" id="varriety_id" name="varriety_id" class="span5" placeholder="Select Variety" validate="Require" >
                                <option value="">Select</option>
                                <?php
                                echo $sql_uesr_group = "select varriety_id as fieldkey, varriety_name as fieldtext from $tbl" . "varriety_info where status='Active' AND crop_id='" . $editrow['crop_id'] . "'";
                                echo $db->SelectList($sql_uesr_group, $editrow['varriety_id']);
                                ?>
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
                            <input disabled="" class="span9" type="text" name="location" id="location" value="<?php echo $editrow['location']; ?>" placeholder="Location" validate="Require">
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
                            <input disabled="" type="text" name="farmer_name" id="farmer_name" value="<?php echo $editrow['farmer_name']; ?>" class="span9" placeholder="Farmer Name"  validate="Require" />
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
                            <input disabled="" type="text" name="phone_number" id="phone_number" value="<?php echo $editrow['phone_number']; ?>" class="span9" placeholder="Farmer Phone Number"  validate="Mobile" maxlength="11" onkeypress="return numbersOnly(event)"/>
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
                                <input disabled="" type="text" name="picture_date" id="picture_date" value="<?php echo $db->date_formate($editrow['picture_date']); ?>" class="span9" placeholder="Picture Date"  />
                                <span class="add-on" id="calcbtn_picture_date">
                                    <i class="icon-calendar"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label bottom-margin" for="image_url">
                            Profile Photo
                        </label>
                        <div class="controls">
                            <?php if ($editrow['photo'] != "") { ?>
                                <img src="../../system_images/product_gallery/<?php echo $editrow['photo']; ?>" style="width: 77px; height: 77px;" id="blah" />
                            <?php } else { ?>
                                <img src="../../system_images/profile.png" style="width: 77px; height: 77px;" id="blah" />
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
