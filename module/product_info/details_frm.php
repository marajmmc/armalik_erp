<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$tbl = _DB_PREFIX;
$editrow = $db->single_data($tbl . "product_purchase_info", "*", "product_id", $_POST['rowID']);
if ($_SESSION['warehouse_id'])
{
    $warehouse = "AND warehouse_id='" . $_SESSION['warehouse_id'] . "'";
    $warehouse_id = $_SESSION['warehouse_id'];
}
else
{
    $warehouse = "AND warehouse_id='" . $editrow['warehouse_id'] . "'";
    $warehouse_id = $editrow['warehouse_id'];
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
                            Select Year
                        </label>
                        <div class="controls">
                            <select id="year_id" name="year_id" class="span5" placeholder="Select Crop" validate="Require">
                                <?php
                                $db_fiscal_year=new Database();
                                $db_fiscal_year->get_fiscal_year($editrow['year_id'], $editrow['year_id']);
                                ?>
                            </select>
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
                            <select id="warehouse_id" name="warehouse_id" class="span5" placeholder="Select Crop" validate="Require">
                                <?php
                                //echo "<option value=''>Select</option>";
                                $sql_uesr_group = "select warehouse_id as fieldkey, warehouse_name as fieldtext from $tbl" . "warehouse_info where status='Active' $warehouse";
                                echo $db->SelectList($sql_uesr_group, $editrow['warehouse_id']);
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
                                $db_crop=new Database();
                                $db_crop->get_crop($editrow['crop_id'],$editrow['crop_id']);
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
                                <?php
                                $sql_uesr_group = "select product_type_id as fieldkey, product_type as fieldtext from $tbl" . "product_type where status='Active' AND crop_id='".$editrow['crop_id']."' AND product_type_id='".$editrow['product_type_id']."'";
                                echo $db->SelectList($sql_uesr_group, $editrow['product_type_id']);
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
                            <select id="varriety_id" name="varriety_id" class="span5" placeholder="Select Variety" validate="Require">
                                <?php
                                $sql_uesr_group = "select varriety_id as fieldkey, varriety_name as fieldtext from $tbl" . "varriety_info where status='Active' AND varriety_id='".$editrow['varriety_id']."'";
                                echo $db->SelectList($sql_uesr_group, $editrow['varriety_id']);
                                ?>
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
                                $sql_uesr_group = "select pack_size_id as fieldkey, pack_size_name as fieldtext from $tbl" . "product_pack_size where status='Active' AND pack_size_id='".$editrow['pack_size']."' order by pack_size_name";
                                echo $db->SelectList($sql_uesr_group, $editrow['pack_size']);
                                ?>
                            </select>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="quantity">
                            Purchase Qty(pieces)
                        </label>
                        <div class="controls">
                            <input readonly class="span3" type="text" name="quantity" id="quantity" value="<?php echo $editrow['quantity'];?>" placeholder="Qty" >
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="quantity">
                            Opening Balance (pieces)
                        </label>
                        <div class="controls">
                            <input readonly class="span3" type="text" name="opening_balance" id="opening_balance" value="<?php echo $editrow['opening_balance'];?>" placeholder="Qty" >
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="mfg_date">
                            Mfg Date
                        </label>
                        <div class="controls">
                            <div class="input-append">
                                <input readonly class="span9" type="text" name="mfg_date" id="mfg_date" value="<?php echo $db->date_formate($editrow['mfg_date']);?>" placeholder="Mfg Date" >
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
                                <input readonly class="span9" type="text" name="exp_date" id="exp_date" value="<?php echo $db->date_formate($editrow['exp_date']);?>" placeholder="Exp Date" >
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
