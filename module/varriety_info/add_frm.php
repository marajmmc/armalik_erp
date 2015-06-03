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
                            &nbsp;
                        </label>
                        <div class="controls">
                            <input type="radio" id="type" name="type" value="0" class="variety_type" checked/> &nbsp; ARM &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                            <input type="radio" id="type" name="type" value="1" class="variety_type" /> &nbsp; Competitor &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                            <input type="radio" id="type" name="type" value="2" class="variety_type" /> &nbsp; Upcoming &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="crop_id">
                            Crop
                        </label>
                        <div class="controls">
                            <select id="crop_id" name="crop_id" class="span5" placeholder="Crop" onchange="load_product_type()" validate="Require">
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
                        <label class="control-label" for="crop_id">
                            Product Type
                        </label>
                        <div class="controls">
                            <select id="product_type_id" name="product_type_id" class="span5" placeholder="Product Type" validate="Require">
                                <option value="">Select</option>
                            </select>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="varriety_name">
                            Variety
                        </label>
                        <div class="controls">
                            <input class="span9" type="text" name="varriety_name" id="varriety_name" placeholder="Variety" validate="Require">
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="hybrid">
                            F1 Hybrid/OP
                        </label>
                        <div class="controls">
                            <select id="hybrid" name="hybrid" class="span5" placeholder="Product Type" >
                                <option value="">Select</option>
                                <option value="F1 Hybrid">F1 Hybrid</option>
                                <option value="OP">OP</option>
                            </select>
                        </div>
                    </div>
                    <div class="control-group competitor">
                        <label class="control-label" for="company_name">
                            Company Name
                        </label>
                        <div class="controls">
                            <input class="span9" type="text" name="company_name" id="company_name" placeholder="Company Name">
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="order_variety">
                            Order
                        </label>
                        <div class="controls">
                            <input class="span3" type="text" name="order_variety" id="order_variety" placeholder="Order Variety"  onkeypress="return numbersOnly(event)" onblur="Existin_data(this, 'Add', 0,0,0)">
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="description">
                            Description
                        </label>
                        <div class="controls">
                            <textarea class="span9" name="description" id="description" placeholder="Description" ></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function ()
    {
        $(".competitor").hide();
        $(document).on("change", ".variety_type", function(event)
        {
            $(".competitor").hide();
            $("#company_name").attr('validate','');
            if($(this).val()==1)
            {
                $(".competitor").show();
                $("#company_name").attr('validate','Require');
            }
        });
    });
</script>