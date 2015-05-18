<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$tbl = _DB_PREFIX;
if ($_SESSION['warehouse_id']) {
    $warehouse = "AND warehouse_id='" . $_SESSION['warehouse_id'] . "'";
    $warehouse_id = $_SESSION['warehouse_id'];
} else {
    $warehouse = "";
    $warehouse_id = "";
}
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
                        <label class="control-label" for="warehouse_id">
                            Select Warehouse
                        </label>
                        <div class="controls">
                            <select id="warehouse_id" name="warehouse_id" class="span5" placeholder="Select Crop" validate="Require">
                                <?php
                                echo "<option value=''>Select</option>";
                                $sql_uesr_group = "select warehouse_id as fieldkey, warehouse_name as fieldtext from $tbl" . "warehouse_info where status='Active' $warehouse";
                                echo $db->SelectList($sql_uesr_group, $warehouse_id);
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
                                include_once '../../libraries/ajax_load_file/load_crop.php';
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
                            <select id="varriety_id" name="varriety_id" class="span5" placeholder="Select Variety" validate="Require">
                                <option value="">Select</option>
                            </select>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
<!--                    <div class="control-group">
                        <label class="control-label" for="product_type">
                            Select Type
                        </label>
                        <div class="controls">
                            <select id="product_type" name="product_type" class="span5" placeholder="Select Type" validate="Require">
                                <option value="">Select</option>
                                <option value="Marketing">Marketing</option>
                                <option value="RND">RND</option>
                            </select>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>-->
                    <div class="control-group">
                        <label class="control-label" for="pack_size">
                            Pack Size(gm)
                        </label>
                        <div class="controls">
                            <select id="pack_size" name="pack_size" class="span3" placeholder="Select Type" validate="Require">
                                <?php
                                echo "<option value=''>Select</option>";
                                $sql_uesr_group = "select pack_size_id as fieldkey, pack_size_name as fieldtext from $tbl" . "product_pack_size where status='Active'";
                                echo $db->SelectList($sql_uesr_group);
                                ?>
                            </select>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="quantity">
                            Qty(pieces)
                        </label>
                        <div class="controls">
                            <input class="span3" type="text" name="quantity" id="quantity" placeholder="Qty" >
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="mfg_date">
                            Mfg Date
                        </label>
                        <div class="controls">
                            <div class="input-append">
                                <input class="span9" type="text" name="mfg_date" id="mfg_date" placeholder="Mfg Date" >
                                <span class="add-on" id="calcbtn_mfg_date">
                                    <i class="icon-calendar"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="exp_date">
                            Exp Date
                        </label>
                        <div class="controls">
                            <div class="input-append">
                                <input class="span9" type="text" name="exp_date" id="exp_date" placeholder="Exp Date" >
                                <span class="add-on" id="calcbtn_exp_date">
                                    <i class="icon-calendar"></i>
                                </span>
                            </div>
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
    cal.manageFields("calcbtn_mfg_date", "mfg_date", "%d-%m-%Y");
    cal.manageFields("calcbtn_exp_date", "exp_date", "%d-%m-%Y");
    
</script>
