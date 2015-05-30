<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$tbl = _DB_PREFIX;

$editrow = $db->single_data($tbl . "pdo_product_characteristic_setting", "*", "prodcut_characteristic_id", $_POST['rowID']);
if($editrow['product_category']=="Self")
{
    $vstatus="block";
    $vtstatus="none";
    $validates="validate='Require'";
    $validatet="validate=''";
}
else if($editrow['product_category']=="Checked Variety")
{
    $vstatus="none";
    $vtstatus="block";
    $validates="validate=''";
    $validatet="validate='Require'";
}
else
{
    $vstatus="";
    $vtstatus="";
    $validates="";
    $validatet="";
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
<!--                    <div class="control-group">-->
<!--                        <label class="control-label" for="zone_id">-->
<!--                            Zone-->
<!--                        </label>-->
<!--                        <div class="controls">-->
<!--                            <select id="zone_id" name="zone_id" class="span5" placeholder="Zone" onchange="load_territory_fnc()">-->
<!--                                <option value="">Select</option>-->
<!--                                --><?php
//                                $sql_uesr_group = "select zone_id as fieldkey, zone_name as fieldtext from $tbl" . "zone_info WHERE status='Active' AND del_status='0'";
//                                echo $db->SelectList($sql_uesr_group, $editrow['zone_id']);
//                                ?>
<!--                            </select>-->
<!--                        </div>-->
<!--                    </div>-->
                    <div class="control-group">
                        <label class="control-label" for="">
                            Product
                        </label>
                        <div class="controls">
                            <input type="radio" name="product_category" id="product_category" value="Self" placeholder="Self" <?php if($editrow['product_category']=="Self"){echo "checked='checked'";}?> />
                            Self Variety
                            <input type="radio" name="product_category" id="product_category" value="Checked Variety" placeholder="Checked Variety" <?php if($editrow['product_category']=="Checked Variety"){echo "checked='checked'";}?> />
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
                                $sql_uesr_group = "select crop_id as fieldkey, crop_name as fieldtext from $tbl" . "crop_info where status='Active' ORDER BY $tbl" . "crop_info.order_crop";
                                echo $db->SelectList($sql_uesr_group, $editrow['crop_id']);
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
                                <?php
                                echo "<option value=''>Select</option>";
                                $sql_uesr_group = "select product_type_id as fieldkey, product_type as fieldtext from $tbl" . "product_type where status='Active' AND crop_id='".$editrow['crop_id']."'";
                                echo $db->SelectList($sql_uesr_group, $editrow['product_type_id']);
                                ?>
                            </select>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="district_id">
                            F1/OP
                        </label>
                        <div class="controls">
                            <select id="hybrid" name="hybrid" class="span5" placeholder="" validate="Require">
                                <option value="">Select</option>
                                <option value="F1 Hybrid" <?php if($editrow['hybrid']=="F1 Hybrid"){echo "selected='selected'";}?> >F1 Hybrid</option>
                                <option value="OP" <?php if($editrow['hybrid']=="OP"){echo "selected='selected'";}?> >OP</option>

                            </select>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group" id="div_variety_id" style="display: <?php echo $vstatus;?>" >
                        <label class="control-label" for="variety_id">
                            Variety Name
                        </label>
                        <div class="controls">
                            <select id="variety_id" name="variety_id" class="span5" placeholder="Select Product" <?php echo $validates;?>>
                                <?php
                                echo "<option value=''>Select</option>";
                                $sql_uesr_group = "select varriety_id as fieldkey, varriety_name as fieldtext from $tbl" . "varriety_info where status='Active' AND crop_id='".$editrow['crop_id']."'";
                                echo $db->SelectList($sql_uesr_group, $editrow['variety_id']);
                                ?>
                            </select>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group" id="div_variety_txt" style="display: <?php echo $vtstatus?>;" >
                        <label class="control-label" for="variety_name">
                            Competitor's Variety
                        </label>
                        <div class="controls">
                            <input type="text" name="variety_name_txt" id="variety_name_txt" value="<?php echo $editrow['variety_name_txt']?>" <?php echo $validatet;?> class="span5" placeholder="Variety Name"  />
                        </div>
                    </div>
                    <div class="control-group" id="div_company_name" style="display: <?php echo $vtstatus?>;" >
                        <label class="control-label" for="company_name">
                            Company Name
                        </label>
                        <div class="controls">
                            <input type="text" name="company_name" id="company_name" value="<?php echo $editrow['company_name']?>" <?php echo $validatet;?> class="span5" placeholder="Company Name"  />
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="cultivation_period">
                            Cultivation Period
                        </label>
                        <div class="controls">
                            <div class="input-append">
                                <input type="text" name="cultivation_period_start" id="cultivation_period_start" value="<?php echo $db->date_formate($editrow['cultivation_period_start'])?>" class="span9" placeholder="Start Date"  />
                                <span class="add-on" id="calcbtn_cultivation_period_start">
                                    <i class="icon-calendar"></i>
                                </span>
                            </div>
                            <div class="input-append">
                                <input type="text" name="cultivation_period_end" id="cultivation_period_end" value="<?php echo $db->date_formate($editrow['cultivation_period_end'])?>" class="span9" placeholder="End Date"  />
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
                            <input type="text" name="special_characteristics" id="special_characteristics" value="<?php echo $editrow['special_characteristics']?>" class="span9" placeholder="Special Characteristics"  />
                        </div>
                    </div>
                    <div class="control-group" >
                        <label class="control-label" for="remarks">
                            Remarks
                        </label>
                        <div class="controls">
                            <textarea name="remarks" id="remarks" class="span9" placeholder="Remarks"><?php echo $editrow['remarks']?></textarea>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label bottom-margin" for="image_url">
                            Picture
                        </label>
                        <div class="controls">
                            <?php if($editrow['image_url']==""){$img_url="../../system_images/blank_img.png";}else{$img_url="../../system_images/pdo_upload_image/pdo_product_characteristic/".$editrow['image_url'];}?>
                            <img src="<?php echo $img_url;?>" style="width: 77px; height: 77px;" id="blah" />
                            <input type="file" name="image_url" id="image_url" class="span9" placeholder="Upload Image" onchange="readURL(this)" />
                        </div>
                    </div>
                </div>
                <div class="widget-body">
                    <?php
                    $dbzone = new Database();
                    $sqlzone="SELECT
                                $tbl"."pdo_product_characteristic_setting_zone.pcsz_id,
                                $tbl"."pdo_product_characteristic_setting_zone.prodcut_characteristic_id,
                                $tbl"."pdo_product_characteristic_setting_zone.zone_id,
                                $tbl"."pdo_product_characteristic_setting_zone.cultivation_period_start,
                                $tbl"."pdo_product_characteristic_setting_zone.cultivation_period_end,
                                $tbl"."pdo_product_characteristic_setting_zone.special_characteristics,
                                $tbl"."pdo_product_characteristic_setting_zone.image_url,
                                $tbl"."pdo_product_characteristic_setting_zone.upload_date,
                                $tbl"."zone_info.zone_name
                              FROM `$tbl"."pdo_product_characteristic_setting_zone`
                              LEFT JOIN $tbl"."zone_info ON $tbl"."zone_info.zone_id = $tbl"."pdo_product_characteristic_setting_zone.zone_id
                              WHERE $tbl"."pdo_product_characteristic_setting_zone.status='Active'
                              AND $tbl"."pdo_product_characteristic_setting_zone.prodcut_characteristic_id='".$_POST['rowID']."'
                              AND $tbl"."pdo_product_characteristic_setting_zone.del_status=0";
                    if($dbzone->open())
                    {
                        $result=$dbzone->query($sqlzone);
                        while($row=$dbzone->fetchAssoc($result))
                        {
                            ?>
                            <div class="control-group" style="border-bottom: 1px solid #0daed3;">
                                <span class="label label label-info" style="cursor: pointer;" >
                                    <?php echo $row['zone_name'];?>
                                </span>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="cultivation_period">
                                    Cultivation Period
                                </label>
                                <div class="controls">
                                    <div class="input-append">
                                        <input readonly type="text" name="" id="" value="<?php echo @$db->date_formate($row['cultivation_period_start'])?>" class="span9" placeholder="Start Date"  />
                                    </div>
                                    <div class="input-append">
                                        <input readonly type="text" name="" id="" value="<?php echo @$db->date_formate($row['cultivation_period_end'])?>" class="span9" placeholder="End Date"  />
                                    </div>
                                </div>
                            </div>
                            <div class="control-group" >
                                <label class="control-label" for="special_characteristics">
                                    Special Characteristics
                                </label>
                                <div class="controls">
                                    <input readonly type="text" name="" id="" value="<?php echo $row['special_characteristics']?>" class="span9" placeholder="Special Characteristics"  />
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label bottom-margin" for="image_url">
                                    Picture
                                </label>
                                <div class="controls">
                                    <?php if($row['image_url']==""){$img_url="../../system_images/blank_img.png";}else{$img_url="../../system_images/pdo_upload_image/pdo_product_characteristic/".$row['image_url'];}?>
                                    <img src="<?php echo $img_url;?>" style="width: 77px; height: 77px;" id="blah" />
                                </div>
                            </div>
                            <?php
                        }
                    }
                    ?>
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
        $(document).on("change", "#product_category", function (event)
        {
            if(this.value=='Checked Variety')
            {
                $("#variety_id").attr('validate', '');
                $("#variety_name_txt").attr('validate', 'Require');
                $("#company_name").attr('validate', 'Require');
                $("#div_variety_id").slideUp();
                $("#div_variety_txt").slideDown();
                $("#div_company_name").slideDown();
            }
            else
            {
                $("#variety_id").attr('validate', 'Require');
                $("#variety_name_txt").attr('validate', '');
                $("#company_name").attr('validate', '');
                $("#div_variety_id").slideDown();
                $("#div_variety_txt").slideUp();
                $("#div_company_name").slideUp();
            }
        });
    })
</script>