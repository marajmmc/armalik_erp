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
                            Year
                        </label>
                        <div class="controls">
                            <select id="pdo_year_id" name="pdo_year_id" class="span5" placeholder="" validate="Require">
                                <?php
                                echo "<option value=''>Select</option>";
                                $sql_uesr_group = "select pdo_year_id as fieldkey, pdo_year_name as fieldtext from $tbl" . "pdo_year where status='Active' ORDER BY $tbl"."pdo_year.pdo_year_name";
                                echo $db->SelectList($sql_uesr_group);
                                ?>
                            </select>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="farmer_id">
                            Season
                        </label>
                        <div class="controls">
                            <select id="pdo_season_id" name="pdo_season_id" class="span5" placeholder="" validate="Require">
                                <?php
                                echo "<option value=''>Select</option>";
                                $sql_uesr_group = "select pdo_season_id as fieldkey, pdo_season_name as fieldtext from $tbl" . "pdo_season_info where status='Active' ORDER BY $tbl"."pdo_season_info.pdo_season_name";
                                echo $db->SelectList($sql_uesr_group);
                                ?>
                            </select>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="farmer_id">
                            Farmer Name
                        </label>
                        <div class="controls">
                            <select id="farmer_id" name="farmer_id" class="span5" placeholder="Select Farmer" validate="Require">
                                <?php

                                if($_SESSION['user_level']=="Zone")
                                {
                                    $zone_id="AND $tbl" . "farmer_info.zone_id='".$_SESSION['zone_id']."'";
                                }
                                else
                                {
                                    $zone_id="";
                                }

                                echo "<option value=''>Select</option>";
                                $sql_uesr_group = "select farmer_id as fieldkey, farmer_name as fieldtext from $tbl" . "farmer_info where status='Active' $zone_id ORDER BY $tbl"."farmer_info.farmer_name";
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
                            <select id="product_type_id" name="product_type_id" class="span5" placeholder="Product Type" onchange="load_pdo_variety()" validate="Require">
                                <option value="">Select</option>
                            </select>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="crop_id">
                            Variety Name
                        </label>
                        <div class="controls">
                            <select id="variety_id" name="variety_id" class="span5" placeholder="Select Product" validate="Require">
                                <option value="">Select</option>
                            </select>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="crop_id">
                            Fruit Type
                        </label>
                        <div class="controls">
                            <select id="fruit_type" name="fruit_type" class="span5" placeholder="Select Product" validate="Require">
                                <option value="">Select</option>
                                <option value="Curd">Curd</option>
                                <option value="Head">Head</option>
                                <option value="Root">Root</option>
                                <option value="Fruit">Fruit</option>
                            </select>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="upload_date">
                            Showing Date
                        </label>
                        <div class="controls">
                            <div class="input-append">
                                <input readonly="" type="text" name="sowing_date" id="sowing_date" value="<?php echo $db->date_formate($db->ToDayDate())?>" class="span9" placeholder="Date" validate="Require"  />
                                <span class="add-on" id="calcbtn_sowing_date">
                                    <i class="icon-calendar"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="upload_date">
                            Transplanting Date
                        </label>
                        <div class="controls">
                            <div class="input-append">
                                <input readonly="" type="text" name="transplanting_date" id="transplanting_date" value="<?php echo $db->date_formate($db->ToDayDate())?>" class="span9" placeholder="Date" validate="Require"  />
                                <span class="add-on" id="calcbtn_transplanting_date">
                                    <i class="icon-calendar"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="description">
                            Fruit Set
                        </label>
                        <div class="controls">
                            <input readonly type="text" class="span1" name="fruit_set" id="fruit_set" placeholder="" validate="Require" value="1" />
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="description">
                            No of Image
                        </label>
                        <div class="controls">
                            <input type="text" class="span1" name="number_of_img" id="number_of_img" placeholder="" onblur="fnc_number_of_img()" validate="Require" />
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <div id="div_number_of_img" >

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
    cal.manageFields("calcbtn_sowing_date", "sowing_date", "%d-%m-%Y");
    cal.manageFields("calcbtn_transplanting_date", "transplanting_date", "%d-%m-%Y");
</script>
