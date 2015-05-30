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
                        <label class="control-label" for="zone_id">
                            Zone
                        </label>
                        <div class="controls">
                            <select id="zone_id" name="zone_id" class="span5" placeholder="Zone" onchange="load_district()" validate="Require">
                                <option value="">Select</option>
                                <?php
                                $sql_uesr_group = "select zone_id as fieldkey, zone_name as fieldtext from $tbl" . "zone_info WHERE status='Active' AND del_status='0'";
                                echo $db->SelectList($sql_uesr_group);
                                ?>
                            </select>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="">
                            Product
                        </label>
                        <div class="controls">
                            <input type="radio" name="product_category" id="product_category" value="Self" placeholder="Self" checked />
                            Self Variety
                            <input type="radio" name="product_category" id="product_category" value="Checked Variety" placeholder="Checked Variety" />
                            Competitor's Variety
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
                                $sql_uesr_group = "select crop_id as fieldkey, crop_name as fieldtext from $tbl" . "crop_info where status='Active'  " . $db->get_zone_access($tbl . "zone_info") . " ORDER BY $tbl" . "crop_info.order_crop";
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
                            <select id="product_type_id" name="product_type_id" class="span5" placeholder="Product Type" validate="Require">
                                <option value="">Select</option>
                            </select>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>

                    <div class="control-group" id="div_variety_id" style="display: none">
                        <label class="control-label" for="variety_id">
                            Variety Name
                        </label>
                        <div class="controls">
                            <select id="variety_id" name="variety_id" class="span5" placeholder="Select Product" onchange="load_company_name()">
                                <option value="">Select</option>
                            </select>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group" id="div_variety_txt" style="display: none;">
                        <label class="control-label" for="variety_name">
                            Competitor's Variety
                        </label>
                        <div class="controls">
                            <select id="variety_name_txt" name="variety_name_txt" class="span5" placeholder="Select Product" onchange="load_company_name()">
                                <option value="">Select</option>
                            </select>
                        </div>
                    </div>
                    <div class="control-group" id="div_company_name" style="display: none;">
                        <label class="control-label" for="company_name">
                            Company Name
                        </label>
                        <div class="controls">
                            <input readonly type="text" name="company_name" id="company_name" class="span5" placeholder="Company Name"  />
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label" for="company_name">
                            F1/OP
                        </label>
                        <div class="controls">
                            <input readonly type="text" name="hybrid" id="hybrid" class="span5" placeholder="F1/OP"  />
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label" for="cultivation_period">
                            Cultivation Period
                        </label>
                        <div class="controls">
                            <input readonly type="text" name="cultivation_period_start" id="cultivation_period_start" value="" class="span2" placeholder="Start Date"  />
                            <input readonly type="text" name="cultivation_period_end" id="cultivation_period_end" value="" class="span2" placeholder="End Date"  />
                        </div>
                    </div>
                    <div class="control-group" >
                        <label class="control-label" for="special_characteristics">
                            Special Characteristics
                        </label>
                        <div class="controls">
                            <input readonly type="text" name="special_characteristics" id="special_characteristics" class="span9" placeholder="Special Characteristics"  />
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label bottom-margin" for="image_url">
                            Picture
                        </label>
                        <div class="controls">
                            <img src="../../system_images/blank_img.png" style="width: 77px; height: 77px;" id="image_url" />
                            <!--                            <input type="file" name="image_url" id="image_url" class="span9" placeholder="Upload Image" onchange="readURL(this)" />-->
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label" for="district_id">
                            District
                        </label>
                        <div class="controls">
                            <select id="district_id" name="district_id" class="span5" placeholder="Customer" validate="Require" onchange="load_upazilla_fnc()">
                                <option value="">Select</option>
                                <?php
//                                $sql_uesr_group = "select zillaid as fieldkey, zillanameeng as fieldtext from $tbl" . "zilla where visible=0 ORDER BY zillanameeng";
//                                echo $db->SelectList($sql_uesr_group);
                                ?>
                            </select>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="upazilla_id">
                            Upazila
                        </label>
                        <div class="controls">
                            <select id="upazilla_id" name="upazilla_id" class="span5" placeholder="" validate="Require">
                                <option value="">Select</option>

                            </select>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>

<!--                    <div class="control-group">-->
<!--                        <label class="control-label" for="total_market_size">-->
<!--                            Total Market Size-->
<!--                        </label>-->
<!--                        <div class="controls">-->
<!--                            <input type="text" name="total_market_size" id="total_market_size" value="" class="span2" placeholder="Total Market Size Kg"  />-->
<!--                        </div>-->
<!--                    </div>-->
                    <div class="control-group">
                        <label class="control-label" for="sales_quantity">
                            Sales Quantity
                        </label>
                        <div class="controls">
                            <input type="text" name="sales_quantity" id="sales_quantity" class="span2" placeholder="Sales Quantity" validate="Require" />
                            <span class="help-inline">
                                Kg.
                            </span>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        session_load_fnc();
        load_district()
    });
//    var cal = Calendar.setup({
//        onSelect: function(cal) { cal.hide() },
//        fdow :0,
//        minuteStep:1
//    });
//    cal.manageFields("calcbtn_maturity_date", "maturity_date", "%d-%m-%Y");
        
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function  ( e ) {
                $('#blah').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $(document).ready(function ()
    {
        $("#product_type_id").attr('onchange', 'load_pdo_variety()');
        $('#variety_id').attr('validate','Require');
        $('#variety_name_txt').attr('validate','');

        $("#company_name").val('');
        $("#cultivation_period_start").val('');
        $("#cultivation_period_end").val('');
        $("#image_url").attr('src','../../system_images/blank_img.png');

        $("#div_variety_id").slideDown();
        $("#div_variety_txt").slideUp();
        $("#div_company_name").slideUp();
        $(document).on("change", "#product_category", function (event)
        {
            $('#product_type_id').prop('selectedIndex',0);
            $('#variety_id').prop('selectedIndex',0);
            $('#variety_name_txt').prop('selectedIndex',0);
            if(this.value=='Checked Variety')
            {
                $("#product_type_id").attr('onchange', 'load_variety_txt_fnc()');
                $('#variety_id').attr('validate','');
                $('#variety_name_txt').attr('validate','Require');
                $("#div_variety_id").slideUp();
                $("#div_variety_txt").slideDown();
                $("#div_company_name").slideDown();
            }
            else
            {
                $("#product_type_id").attr('onchange', 'load_pdo_variety()');
                $('#variety_id').attr('validate','Require');
                $('#variety_name_txt').attr('validate','');
                $("#div_variety_id").slideDown();
                $("#div_variety_txt").slideUp();
                $("#div_company_name").slideUp();
            }
        });
    })
</script>

