<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$tbl = _DB_PREFIX;

$vrow = $db->single_data($tbl . "pdo_product_characteristic", "product_category", "prodcut_characteristic_id", $_POST['rowID']);
if($vrow['product_category']=="Self")
{
    $join="";
    $vstatus="block";
    $vtstatus="none";
}
else if($vrow['product_category']=="Checked Variety")
{
    $join="AND $tbl"."pdo_product_characteristic_setting_zone.prodcut_characteristic_id = $tbl"."pdo_product_characteristic.variety_name_txt";
    $vstatus="none";
    $vtstatus="block";
}
else
{
    $join="";
    $vstatus="";
    $vtstatus="";
}
$sql="
SELECT
    $tbl"."pdo_product_characteristic.prodcut_characteristic_id,
	$tbl"."pdo_product_characteristic.product_category, 
	$tbl"."pdo_product_characteristic.crop_id, 
	$tbl"."pdo_product_characteristic.product_type_id, 
	$tbl"."pdo_product_characteristic.variety_id, 
	$tbl"."pdo_product_characteristic.variety_name_txt, 
	$tbl"."pdo_product_characteristic.zone_id, 
	$tbl"."pdo_product_characteristic.district_id, 
	$tbl"."pdo_product_characteristic.upazilla_id, 
	$tbl"."pdo_product_characteristic.sales_quantity,

	$tbl"."pdo_product_characteristic_setting_zone.hybrid,
	$tbl"."pdo_product_characteristic_setting_zone.company_name,
	DATE_FORMAT($tbl"."pdo_product_characteristic_setting_zone.cultivation_period_start,'%d %M') as cultivation_period_start,
	DATE_FORMAT($tbl"."pdo_product_characteristic_setting_zone.cultivation_period_end,'%d %M') as cultivation_period_end,
	$tbl"."pdo_product_characteristic_setting_zone.special_characteristics,
	$tbl"."pdo_product_characteristic_setting_zone.image_url
FROM $tbl"."pdo_product_characteristic
LEFT JOIN $tbl"."pdo_product_characteristic_setting_zone ON
$tbl"."pdo_product_characteristic_setting_zone.zone_id = $tbl"."pdo_product_characteristic.zone_id AND
$tbl"."pdo_product_characteristic_setting_zone.crop_id = $tbl"."pdo_product_characteristic.crop_id AND
$tbl"."pdo_product_characteristic_setting_zone.product_type_id = $tbl"."pdo_product_characteristic.product_type_id AND
$tbl"."pdo_product_characteristic_setting_zone.variety_id = $tbl"."pdo_product_characteristic.variety_id $join
WHERE $tbl"."pdo_product_characteristic.prodcut_characteristic_id='".$_POST['rowID']."'
GROUP BY $tbl"."pdo_product_characteristic.prodcut_characteristic_id
";
if($db->open())
{
    $result=$db->query($sql);
    $editrow=$db->fetchArray($result);
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
    <label class="control-label" for="zone_id">
        Zone
    </label>
    <div class="controls">
        <select disabled id="zone_id" name="zone_id" class="span5" placeholder="Zone" validate="Require">
            <option value="">Select</option>
            <?php
            $sql_uesr_group = "select zone_id as fieldkey, zone_name as fieldtext from $tbl" . "zone_info WHERE status='Active' AND del_status='0'";
            echo $db->SelectList($sql_uesr_group, $editrow['zone_id']);
            ?>
        </select>
    </div>
</div>
<div class="control-group">
    <label class="control-label" for="">
        Product
    </label>
    <div class="controls">
        <input disabled type="radio" name="product_category" id="product_category" value="Self" placeholder="Self" <?php if($editrow['product_category']=="Self"){echo "checked='checked'";}?> />
        Self Variety
        <input disabled type="radio" name="product_category" id="product_category" value="Checked Variety" placeholder="Checked Variety" <?php if($editrow['product_category']=="Checked Variety"){echo "checked='checked'";}?> />
        Competitor's Variety
    </div>
</div>
<div class="control-group">
    <label class="control-label" for="crop_id">
        Crop
    </label>
    <div class="controls">
        <select disabled id="crop_id" name="crop_id" class="span5" placeholder="Select Crop" validate="Require" onchange="load_product_type()">
            <?php
            echo "<option value=''>Select</option>";
            $sql_uesr_group = "select crop_id as fieldkey, crop_name as fieldtext from $tbl" . "crop_info where status='Active'";
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
        <select disabled id="product_type_id" name="product_type_id" class="span5" placeholder="Product Type" onchange="load_pdo_variety()" validate="Require">
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

<div class="control-group" id="div_variety_id" style="display: <?php echo $vstatus?>">
    <label class="control-label" for="variety_id">
        Variety Name
    </label>
    <div class="controls">
        <select disabled id="variety_id" name="variety_id" class="span5" placeholder="Select Product" validate="Require" onchange="load_variety_txt(); load_company_name()">
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
<div class="control-group" id="div_variety_txt" style="display: <?php echo $vtstatus?>;">
    <label class="control-label" for="variety_name">
        Variety Name
    </label>
    <div class="controls">
        <select disabled name="variety_name_txt" id="variety_name_txt" class="span5"  onchange="load_company_name()">
            <?php
            echo "<option value=''>Select</option>";
            $sql_uesr_group = "
                                                    select
                                                    prodcut_characteristic_id as fieldkey,
                                                    variety_name_txt as fieldtext
                                                    from $tbl" . "pdo_product_characteristic_setting_zone
                                                    where status='Active'  AND
                                                    zone_id='".$editrow['zone_id']."' AND
                                                    product_category='Checked Variety' AND
                                                    crop_id='".$editrow['crop_id']."' AND
                                                    product_type_id='".$editrow['product_type_id']."' AND
                                                    variety_id='".$editrow['variety_id']."'
                                                    ";
            echo $db->SelectList($sql_uesr_group, $editrow['variety_name_txt']);
            ?>

        </select>
    </div>
</div>
<div class="control-group" id="div_company_name" style="display: <?php echo $vtstatus?>;">
    <label class="control-label" for="company_name">
        Company Name
    </label>
    <div class="controls">
        <input readonly type="text" name="company_name" id="company_name" value="<?php echo $editrow['company_name']?>" class="span5" placeholder="Company Name"  />
    </div>
</div>
<div class="control-group">
    <label class="control-label" for="hybrid">
        F1/OP
    </label>
    <div class="controls">
        <input readonly type="text" name="hybrid" id="hybrid" value="<?php echo $editrow['hybrid']?>" class="span5" placeholder="F1/OP"  />
    </div>
</div>
<div class="control-group">
    <label class="control-label" for="district_id">
        District
    </label>
    <div class="controls">
        <select disabled id="district_id" name="district_id" class="span5" placeholder="Distributor" validate="Require" onchange="load_upazilla_fnc()">
            <?php
            if($_SESSION['user_level']=="Zone")
            {
                $zone_id="AND $tbl" . "zone_assign_district.zone_id='".$_SESSION['zone_id']."'";
            }
            else
            {
                $zone_id="AND $tbl" . "zone_assign_district.zone_id='".$editrow['zone_id']."'";
            }

            echo "<option value=''>Select</option>";
            echo $sql_uesr_group = "SELECT
                            $tbl" . "zilla.zillaid as fieldkey,
                            $tbl" . "zilla.zillanameeng as fieldtext
                        FROM
                            $tbl" . "zone_assign_district
                            LEFT JOIN $tbl" . "zilla ON $tbl" . "zilla.zillaid = $tbl" . "zone_assign_district.zilla_id
                        WHERE
                            $tbl" . "zone_assign_district.del_status='0' AND $tbl" . "zone_assign_district.status='Active' $zone_id
";
            echo $db->SelectList($sql_uesr_group, $editrow['district_id']);
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
        <select disabled id="upazilla_id" name="upazilla_id" class="span5" placeholder="Distributor" validate="Require">
            <?php
            echo "<option value=''>Select</option>";
            echo $sql_uesr_group = "select upazilla_id as fieldkey, upazilla_name as fieldtext from $tbl" . "upazilla_new where   zilla_id='" . $editrow['district_id'] . "' ORDER BY upazilla_name";
            echo $db->SelectList($sql_uesr_group, $editrow['upazilla_id']);
            ?>

        </select>
                            <span class="help-inline">
                                *
                            </span>
    </div>
</div>
<div class="control-group">
    <label class="control-label" for="cultivation_period">
        Cultivation Period
    </label>
    <div class="controls">
        <input readonly type="text" name="cultivation_period_start" id="cultivation_period_start" value="<?php echo $editrow['cultivation_period_start']?>" class="span2" placeholder="Start Date"  />
        <input readonly type="text" name="cultivation_period_end" id="cultivation_period_end" value="<?php echo $editrow['cultivation_period_end']?>" class="span2" placeholder="End Date"  />
    </div>
</div>
<div class="control-group" >
    <label class="control-label" for="special_characteristics">
        Special Characteristics
    </label>
    <div class="controls">
        <input readonly type="text" name="special_characteristics" id="special_characteristics" value="<?php echo $editrow['special_characteristics']?>" class="span9" placeholder="Special Characteristics"  />
    </div>
</div>
<div class="control-group">
    <label class="control-label" for="sales_quantity">
        Sales Quantity
    </label>
    <div class="controls">
        <input disabled type="text" name="sales_quantity" id="sales_quantity" value="<?php echo $editrow['sales_quantity']?>" class="span2" placeholder="Sales Quantity"  />
                            <span class="help-inline">
                                Kg.
                            </span>
    </div>
</div>
<div class="control-group">
    <label class="control-label bottom-margin" for="image_url">
        Picture
    </label>
    <div class="controls">
        <?php if($editrow['image_url']==""){$img_url="../../system_images/profile.png";}else{$img_url="../../system_images/pdo_upload_image/pdo_product_characteristic/".$editrow['image_url'];}?>
        <img src="<?php echo $img_url;?>" style="width: 200px; height: 200px;" id="blah" />
    </div>
</div>

</div>
</div>
</div>
</div>
</div>
<script>
    //    var cal = Calendar.setup({
    //        onSelect: function(cal) { cal.hide() },
    //        fdow :0,
    //        minuteStep:1
    //    });
    //    cal.manageFields("calcbtn_upload_date", "upload_date", "%d-%m-%Y");

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
            $('#variety_id').prop('selectedIndex',0);
            $('#variety_name_txt').prop('selectedIndex',0);
            if(this.value=='Checked Variety')
            {
                $("#div_variety_txt").slideDown();
                $("#div_company_name").slideDown();


            }
            else
            {
                $("#div_variety_txt").slideUp();
                $("#div_company_name").slideUp();

                $("#company_name").val('');
                $("#cultivation_period_start").val('');
                $("#cultivation_period_end").val('');
                $("#image_url").attr('src','../../system_images/blank_img.png');
            }
        });
    });
</script>