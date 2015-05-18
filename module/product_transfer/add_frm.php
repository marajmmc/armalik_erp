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
    $not_in="AND warehouse_id NOT IN ('$warehouse_id')";
} else {
    $warehouse = "";
    $warehouse_id = "";
    $not_in="";
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
                        <label class="control-label" for="transfer_date">
                            Date of Transfer
                        </label>
                        <div class="controls">
                            <div class="input-append">
                                <input type="text" name="transfer_date" id="transfer_date" class="span9" placeholder="Date of Transfer" value="<?php echo $db->date_formate($db->ToDayDate())?>"  />
                                <span class="add-on" id="calcbtn_transfer_date">
                                    <i class="icon-calendar"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="from_warehouse_id">
                            From Warehouse
                        </label>
                        <div class="controls">
                            <select id="from_warehouse_id" name="from_warehouse_id" class="span5" placeholder="Select Crop" validate="Require" onchange="load_varriety_fnc()">
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
                        <label class="control-label" for="to_warehouse_id">
                            To Warehouse
                        </label>
                        <div class="controls">
                            <select id="to_warehouse_id" name="to_warehouse_id" class="span5" placeholder="Select Crop" validate="Require">
                                <?php
                                echo "<option value=''>Select</option>";
                                $sql_uesr_group = "select warehouse_id as fieldkey, warehouse_name as fieldtext from $tbl" . "warehouse_info where status='Active' $not_in";
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
                            <select id="pack_size" name="pack_size" class="span5" placeholder="Select Pack Size(gm)" validate="Require" onchange="load_current_stock_fnc()">
                                <option value="">Select</option>
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
                            <input class="span3" type="text" name="quantity" id="quantity" placeholder="Qty" validate="Require" onkeypress="return numbersOnly(event)" onblur="calc_between_val_fnc()" />
                            <input class="span3" type="hidden" name="quantity_tmp" id="quantity_tmp"  />
                            <span class="help-inline">
                                *
                            </span>
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
    cal.manageFields("calcbtn_transfer_date", "transfer_date", "%d-%m-%Y");
    
</script>

