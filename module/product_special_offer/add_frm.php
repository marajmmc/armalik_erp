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
                        <label class="control-label" for="product_type_id">
                            Product Type
                        </label>
                        <div class="controls">
                            <select id="product_type_id" name="product_type_id" class="span5" placeholder="Product Type" onchange="load_varriety_fnc()" validate="Require">
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
                            <select id="varriety_id" name="varriety_id" class="span5" placeholder="Select Variety" validate="Require" onchange="Existin_data()">
                                <option value="">Select</option>
                            </select>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <!--                    <div class="control-group">
                                            <label class="control-label" for="product_id">
                                                Select Pack Size(gm)
                                            </label>
                                            <div class="controls">
                                                <select id="pack_size" name="pack_size" class="span5" placeholder="Select Pack Size(gm)" validate="Require">
                                                    <option value="">Select</option>
                                                </select>
                                                <span class="help-inline">
                                                    *
                                                </span>
                                            </div>
                                        </div>-->
                    <div class="control-group">
                        <label class="control-label" for="quantity">
                            Qty(pieces)
                        </label>
                        <div class="controls">
                            <input class="span3" type="text" name="quantity" id="quantity" placeholder="Qty" validate="Require" onkeypress="return numbersOnly(event)" />
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="offer">
                            Offer
                        </label>
                        <div class="controls">
                            <input class="span5" type="text" name="offer" id="offer" placeholder="Offer" validate="Require"  />
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="start_date">
                            Start Date
                        </label>
                        <div class="controls">
                            <div class="input-append">
                                <input type="text" name="start_date" id="start_date" class="span9" placeholder="Date of birth"  />
                                <span class="add-on" id="calcbtn_start_date">
                                    <i class="icon-calendar"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="end_date">
                            End Date
                        </label>
                        <div class="controls">
                            <div class="input-append">
                                <input type="text" name="end_date" id="end_date" class="span9" placeholder="Date of birth"  />
                                <span class="add-on" id="calcbtn_end_date">
                                    <i class="icon-calendar"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="status">
                            Status
                        </label>
                        <div class="controls controls-row">
                            <select id="status" name="status" class="span3" placeholder="Group Name">
                                <option value="">Select</option>
                                <option value="Active" >Active</option>
                                <option value="In-Active" >In-Active</option>
                            </select>
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
    cal.manageFields("calcbtn_start_date", "start_date", "%d-%m-%Y");
    cal.manageFields("calcbtn_end_date", "end_date", "%d-%m-%Y");
</script>