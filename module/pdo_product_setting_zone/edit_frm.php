<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$tbl = _DB_PREFIX;

$sqlE="
SELECT
    $tbl"."pdo_product_characteristic_setting_zone.pcsz_id,
    $tbl"."pdo_product_characteristic_setting_zone.prodcut_characteristic_id,
    $tbl"."pdo_product_characteristic_setting_zone.zone_id,
    $tbl"."pdo_product_characteristic_setting_zone.cultivation_period_start,
    $tbl"."pdo_product_characteristic_setting_zone.cultivation_period_end,
    $tbl"."pdo_product_characteristic_setting_zone.special_characteristics,
    $tbl"."pdo_product_characteristic_setting_zone.image_url,
    $tbl"."crop_info.crop_name,
    $tbl"."crop_info.id,
    $tbl"."product_type.product_type,
    $tbl"."varriety_info.varriety_name,
    $tbl"."varriety_info.type,
    $tbl"."varriety_info.hybrid,
    $tbl"."varriety_info.company_name,
    $tbl"."varriety_info.order_variety,
    $tbl"."zone_info.zone_name,
    $tbl"."pdo_product_characteristic_setting.cultivation_period_start as cultivation_period_start_main,
    $tbl"."pdo_product_characteristic_setting.cultivation_period_end as cultivation_period_end_main,
    $tbl"."pdo_product_characteristic_setting.special_characteristics as special_characteristics_main,
    $tbl"."pdo_product_characteristic_setting.image_url as image_url_main
FROM
    $tbl"."pdo_product_characteristic_setting_zone
    LEFT JOIN $tbl"."crop_info ON $tbl"."crop_info.crop_id = $tbl"."pdo_product_characteristic_setting_zone.crop_id
    LEFT JOIN $tbl"."product_type ON $tbl"."product_type.crop_id = $tbl"."pdo_product_characteristic_setting_zone.crop_id AND $tbl"."product_type.product_type_id = $tbl"."pdo_product_characteristic_setting_zone.product_type_id
    LEFT JOIN $tbl"."varriety_info ON $tbl"."varriety_info.crop_id = $tbl"."pdo_product_characteristic_setting_zone.crop_id AND $tbl"."varriety_info.product_type_id = $tbl"."pdo_product_characteristic_setting_zone.product_type_id AND $tbl"."varriety_info.varriety_id = $tbl"."pdo_product_characteristic_setting_zone.variety_id
    LEFT JOIN $tbl"."zone_info ON $tbl"."zone_info.zone_id = $tbl"."pdo_product_characteristic_setting_zone.zone_id
    LEFT JOIN $tbl"."pdo_product_characteristic_setting ON $tbl"."pdo_product_characteristic_setting.prodcut_characteristic_id = $tbl"."pdo_product_characteristic_setting_zone.prodcut_characteristic_id
WHERE
    $tbl"."pdo_product_characteristic_setting_zone.status='Active'
    AND $tbl"."varriety_info.status='Active'
    AND $tbl"."crop_info.status='Active'
    AND $tbl"."product_type.status='Active'
    AND $tbl"."pdo_product_characteristic_setting_zone.pcsz_id='".$_POST['rowID']."'
";
$dbE=new Database();
if($dbE->open())
{
    $resultE=$dbE->query($sqlE);
    $edit_row=$dbE->fetchAssoc($resultE);
}
if($edit_row['type']==0)
{
    $vtstatus="none";
}
elseif($edit_row['type']==1)
{
    $vtstatus="block";
}
else
{
    $vtstatus="";
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
                        <label class="control-label" for="crop_id">
                            Crop
                        </label>
                        <div class="controls">
                            <input readonly type="text" name="" id="" value="<?php echo $edit_row['crop_name'];?>" class="span5" />
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="product_type_id" >
                            Product Type
                        </label>
                        <div class="controls">
                            <input readonly type="text" name="" id="" value="<?php echo $edit_row['product_type'];?>" class="span5" />
                        </div>
                    </div>
                    <div class="control-group" id="div_variety_id" >
                        <label class="control-label" for="variety_id">
                            Variety Name
                        </label>
                        <div class="controls">
                            <input readonly type="text" name="" id="" value="<?php echo $edit_row['varriety_name'];?>" class="span5" />
                        </div>
                    </div>
                    <div class="control-group" id="div_company_name" style="display: <?php echo $vtstatus?>;" >
                        <label class="control-label" for="company_name">
                            Company Name
                        </label>
                        <div class="controls">
                            <input readonly type="text" name="" id="" value="<?php echo $edit_row['company_name'];?>" class="span5" />
                        </div>
                    </div>

                </div>
                <div class="widget-body">
                    <div class="control-group" style="border-bottom: 1px solid #0daed3;">
                        <span class="label label label-info" style="cursor: pointer;" >
                            <?php echo $edit_row['zone_name'];?>
                        </span>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="cultivation_period">
                            Cultivation Period
                        </label>
                        <div class="controls">
                            <div class="input-append">
                                <input readonly type="text" name="cultivation_period_start" id="cultivation_period_start" value="<?php echo $db->date_formate($edit_row['cultivation_period_start'])?>" class="span9" placeholder="Start Date"  />
                                <span class="add-on" id="calcbtn_cultivation_period_start">
                                    <i class="icon-calendar"></i>
                                </span>
                            </div>
                            <div class="input-append">
                                <input readonly type="text" name="cultivation_period_end" id="cultivation_period_end" value="<?php echo $db->date_formate($edit_row['cultivation_period_end'])?>" class="span9" placeholder="End Date"  />
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
                            <input type="text" name="special_characteristics" id="special_characteristics" value="<?php echo $edit_row['special_characteristics']?>" class="span9" placeholder="Special Characteristics"  />
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label bottom-margin" for="image_url">
                            Picture
                        </label>
                        <div class="controls">
                            <?php if($edit_row['image_url']==""){$img_url="../../system_images/blank_img.png";}else{$img_url="../../system_images/pdo_upload_image/pdo_product_characteristic/".$edit_row['image_url'];}?>
                            <img src="<?php echo $img_url;?>" style="width: 77px; height: 77px;" id="blah" />
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
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function  ( e ) {
                $('#blah').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

</script>