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
<!--                    <div class="control-group">-->
<!--                        <label class="control-label" for="zone_id">-->
<!--                            Zone-->
<!--                        </label>-->
<!--                        <div class="controls">-->
<!--                            <select id="zone_id" name="zone_id" class="span5" placeholder="Zone" validate="Require">-->
<!--                                <option value="">Select</option>-->
<!--                                --><?php
//                                $sql_uesr_group = "select zone_id as fieldkey, zone_name as fieldtext from $tbl" . "zone_info WHERE status='Active' AND del_status='0' " . $db->get_zone_access($tbl . "zone_info") . "";
//                                echo $db->SelectList($sql_uesr_group);
//                                ?>
<!--                            </select>-->
<!--                            <span class="help-inline">-->
<!--                                *-->
<!--                            </span>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                    <div class="control-group">-->
<!--                        <label class="control-label" for="">-->
<!--                            Product-->
<!--                        </label>-->
<!--                        <div class="controls">-->
<!--                            <input type="radio" name="product_category" id="product_category" value="Self" placeholder="Self" checked />-->
<!--                            Self Variety-->
<!--                            <input type="radio" name="product_category" id="product_category" value="Checked Variety" placeholder="Checked Variety" />-->
<!--                            Competitor's Variety-->
<!--                        </div>-->
<!--                    </div>-->
                    <div class="control-group">
                        <label class="control-label" for="crop_id">
                            Crop
                        </label>
                        <div class="controls">
                            <select id="crop_id" name="crop_id" class="span5 reset_drop_down" placeholder="Select Crop" validate="Require" onchange="load_product_type()">
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
                            <select id="product_type_id" name="product_type_id" onchange="load_variety_fnc()" class="span5 reset_drop_down" placeholder="Product Type" validate="Require">
                                <option value="">Select</option>
                            </select>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <!--<div class="control-group">
                        <label class="control-label" for="district_id">
                            F1 Hybrid/OP
                        </label>
                        <div class="controls">
                            <select id="hybrid" name="hybrid" class="span5 reset_drop_down" onchange="load_variety_fnc()" validate="Require">
                                <option value="">Select</option>
                                <option value="F1 Hybrid">F1 Hybrid</option>
                                <option value="OP">OP</option>

                            </select>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>-->
                    <div class="control-group">
                        <label class="control-label" for="variety_id">
                            Variety Name
                        </label>
                        <div class="controls">
                            <select id="variety_id" name="variety_id" class="span5 reset_drop_down" placeholder="Select Product" onchange="hybrid_product_check()">
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
                            <input type="text" name="variety_name_txt" id="variety_name_txt" class="span5" placeholder="Variety Name"  />
                        </div>
                    </div>
                    <div class="control-group" id="div_company_name" style="display: none;">
                        <label class="control-label" for="company_name">
                            Company Name
                        </label>
                        <div class="controls">
                            <input type="text" name="company_name" id="company_name" class="span5" placeholder="Company Name"  />
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="cultivation_period">
                            Cultivation Period
                        </label>
                        <div class="controls">
                            <div class="input-append">
                                <input type="text" name="cultivation_period_start" id="cultivation_period_start" class="span9" placeholder="Start Date"  />
                                <span class="add-on" id="calcbtn_cultivation_period_start">
                                    <i class="icon-calendar"></i>
                                </span>
                            </div>
                            <div class="input-append">
                                <input type="text" name="cultivation_period_end" id="cultivation_period_end" class="span9" placeholder="End Date"  />
                                <span class="add-on" id="calcbtn_cultivation_period_end">
                                    <i class="icon-calendar"></i>
                                </span>
                            </div>

                        </div>
                    </div>
                    <div class="control-group" >
                        <label class="control-label" for="special_characteristics">
                            Special Characteristics
                        </label>
                        <div class="controls">
                            <input type="text" name="special_characteristics" id="special_characteristics" class="span9" placeholder="Special Characteristics"  />
                        </div>
                    </div>
                    <div class="control-group" >
                        <label class="control-label" for="special_characteristics">
                            Remarks
                        </label>
                        <div class="controls">
                            <textarea name="remarks" id="remarks" class="span9" placeholder="Remarks"></textarea>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label bottom-margin" for="image_url">
                            Picture
                        </label>
                        <div class="controls">
                            <img src="../../system_images/blank_img.png" style="width: 77px; height: 77px;" id="blah" />
                            <input type="file" name="image_url" id="image_url" class="span9" placeholder="Upload Image" onchange="readURL(this)" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<script>
    $(document).ready(function(){
        session_load_fnc()
    });

    var cal = Calendar.setup({
        onSelect: function(cal) { cal.hide() },
        fdow :0,
        minuteStep:1
    });
    cal.manageFields("calcbtn_cultivation_period_start", "cultivation_period_start", "%d-%m-%Y");
    cal.manageFields("calcbtn_cultivation_period_end", "cultivation_period_end", "%d-%m-%Y");

    function readURL(input) {
        if (input.files && input.files[0])
        {
            var reader = new FileReader();

            reader.onload = function  ( e )
            {
                $('#blah').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $(document).ready(function ()
    {
        $(document).on("change", ".reset_drop_down", function (event)
        {
            if($('#crop_id').val()!="")
            {
                if($("#product_type_id").val()=="")
                {
                    //$("#hybrid").prop('selectedIndex',0);
                    $("#variety_id").html("<option value=''>Select</option>");
                }
            }
            else
            {
                $("#product_type").html("<option value=''>Select</option>");
                //$("#hybrid").prop('selectedIndex',0);
                $("#variety_id").html("<option value=''>Select</option>");
            }
        });
    })
</script>

