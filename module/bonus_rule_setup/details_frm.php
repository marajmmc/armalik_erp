<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$tbl = _DB_PREFIX;
$editrow = $db->single_data($tbl . "employee_designation", "*", "designation_id", $_POST['rowID']);
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
                        <label class="control-label" for="designation_title_en">
                            Crop
                        </label>
                        <div class="controls">
                            <select id="crop_id" name="crop_id" class="span5" placeholder="Select Crop" validate="Require" onchange="load_product_type()">
                                <?php
                                $db_crop=new Database();
                                $db_crop->get_crop();
                                ?>
                            </select>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="designation_title_en">
                            Product
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
                        <label class="control-label" for="designation_title_en">
                            Variety
                        </label>
                        <div class="controls">
                            <select id="varriety_id" name="varriety_id" class="span5" placeholder="Select Variety">
                                <option value="">Select</option>
                            </select>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="designation_title_bn">
                            From Qty(pieces)
                        </label>
                        <div class="controls">
                            <input class="span5" type="text" name="from_quantity" id="from_quantity" value="" placeholder="From Qty(pieces)" validate="Require" >
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="designation_title_bn">
                            To Qty(pieces)
                        </label>
                        <div class="controls">
                            <input class="span5" type="text" name="to_quantity" id="to_quantity" value="" placeholder="To Qty(pieces)" validate="Require" >
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                </div>
                <div class="widget-body">
                    <div class="control-group" id="div_pack_size">
                        <h3><u>Product Bonus Information</u></h3>

                        <table class="table table-condensed table-striped table-bordered table-hover no-margin" id="TaskTable_bonus">
                            <thead>
                            <tr>
                                <th style="width:10%">
                                    Crop
                                </th>
                                <th style="width:10%">
                                    Product Type
                                </th>
                                <th style="width:10%">
                                    Variety
                                </th>
                                <th style="width:10%">
                                    Pack Size(gm)
                                </th>
                                <th style="width:10%">
                                    Qty(pieces)
                                </th>
                            </tr>
                            </thead>
                            <tr>
                                <th>
                                    <select id="bonus_crop_id" name="bonus_crop_id" class="span12" placeholder="Crop" onchange="bonus_load_product_type()" validate="Require">
                                        <?php
                                        $db_crop=new Database();
                                        $db_crop->get_crop();
                                        ?>
                                    </select>
                                </th>
                                <th>
                                    <select id='bonus_product_type_id' name='bonus_product_type_id' class='span12' onchange='bonus_load_varriety_fnc()' validate='Require'>
                                        <option value=''>Select</option>
                                    </select>
                                </th>
                                <th>
                                    <select id='bonus_varriety_id' name='bonus_varriety_id' class='span12' onchange='bonus_load_pack_size_fnc()' validate='Require'>
                                        <option value=''>Select</option>
                                    </select>
                                </th>
                                <th>
                                    <select id='bonus_pack_size' name='bonus_pack_size' class='span12' validate='Require'>
                                        <option value=''>Select</option>
                                    </select>
                                </th>
                                <th>
                                    <input type='text' name='bonus_quantity' maxlength='50' id='bonus_quantity' class='span12' validate='Require' placeholder="Qty(pieces)" />
                                </th>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>