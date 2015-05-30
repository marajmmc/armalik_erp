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
                        <label class="control-label" for="dob">
                            Date of inventory
                        </label>
                        <div class="controls">
                            <div class="input-append">
                                <input type="text" name="inventory_date" id="inventory_date" class="span9" placeholder="Date of inventory" value="<?php // echo $db->date_formate($db->ToDayDate())?>" validate="Require" />
                                <span class="add-on" id="calcbtn_inventory_date">
                                    <i class="icon-calendar"></i>
                                </span>
                            </div>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="warehouse_id">
                            Select Warehouse
                        </label>
                        <div class="controls">
                            <!--<select id="warehouse_id" name="warehouse_id" class="span5" placeholder="Select Crop" validate="Require" onchange="load_varriety_fnc()">-->
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
                            <select id="varriety_id" name="varriety_id" class="span5" placeholder="Select Variety" validate="Require" onchange="load_pack_size_fnc()">
                                <option value="">Select</option>
                            </select>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="product_id">
                            Pack Size(gm)
                        </label>
                        <div class="controls">
                            <select id="pack_size" name="pack_size" class="span5" placeholder="Select Pack Size" validate="Require" onchange="load_current_stock_fnc()">
                                <option value="">Select</option>
                            </select>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="quantity_type">
                            Type
                        </label>
                        <div class="controls">
                            <select id="quantity_type" name="quantity_type" class="span5" placeholder="Select Pack Size" validate="Require" onchange="quantity_type_fnc()">
                                <option value="">Select</option>
                                <option value="Short Qty">Short Qty</option>
                                <option value="Access Qty">Access Qty</option>
                            </select>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="current_stock_qunatity">
                            Current Stock
                        </label>
                        <div class="controls">
                            <input class="span3" readonly="" type="text" name="current_stock_qunatity" id="current_stock_qunatity" placeholder="Current Stock" validate="Require" onkeypress="return numbersOnly(event)" />
                            <input class="span3" type="hidden" name="current_stock_qunatity_tmp" id="current_stock_qunatity_tmp" />
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group" id="div_damage_quantity" style="display: none">
                        <label class="control-label" for="damage_quantity">
                            Short Qty(pieces)
                        </label>
                        <div class="controls">
                            <input class="span3" type="text" name="damage_quantity" id="damage_quantity" placeholder="Short Qty" validate="Require"  onblur="calc_between_val_fnc()" onkeypress="return numbersOnly(event)" value="0" />
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group" id="div_access_quantity" style="display: none">
                        <label class="control-label" for="access_quantity">
                            Access Qty(pieces)
                        </label>
                        <div class="controls">
                            <input class="span3" type="text" name="access_quantity" id="access_quantity" placeholder="Access Qty" validate="Require" onkeypress="return numbersOnly(event)" value="0" />
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="remark">
                            Remark's
                        </label>
                        <div class="controls">
                            <textarea class="span9" type="text" name="remark" id="remark" placeholder="Remark's" ></textarea>
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
    cal.manageFields("calcbtn_inventory_date", "inventory_date", "%d-%m-%Y");
    
</script>

