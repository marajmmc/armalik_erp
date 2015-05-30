<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$dbrow = new Database();
$tbl = _DB_PREFIX;
$editrow = $dbrow->single_data($tbl . "pdo_variety_setting", "*", "vs_id", $_POST['rowID']);

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
                        <label class="control-label" for="farmer_id">
                            Year
                        </label>
                        <div class="controls">
                            <select disabled id="pdo_year_id" name="pdo_year_id" class="span5" placeholder="" validate="Require">
                                <?php
                                echo "<option value=''>Select</option>";
                                $sql_uesr_group = "select pdo_year_id as fieldkey, pdo_year_name as fieldtext from $tbl" . "pdo_year where status='Active' ORDER BY $tbl"."pdo_year.pdo_year_name";
                                echo $db->SelectList($sql_uesr_group, $editrow['pdo_year_id']);
                                ?>
                            </select>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="farmer_id">
                            Season
                        </label>
                        <div class="controls">
                            <select disabled id="pdo_season_id" name="pdo_season_id" class="span5" placeholder="" validate="Require">
                                <?php
                                echo "<option value=''>Select</option>";
                                $sql_uesr_group = "select pdo_season_id as fieldkey, pdo_season_name as fieldtext from $tbl" . "pdo_season_info where status='Active' ORDER BY $tbl"."pdo_season_info.pdo_season_name";
                                echo $db->SelectList($sql_uesr_group, $editrow['pdo_season_id']);
                                ?>
                            </select>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="farmer_id">
                            Farmer Name
                        </label>
                        <div class="controls">
                            <select disabled id="farmer_id" name="farmer_id" class="span5" placeholder="Select Farmer" validate="Require">
                                <?php
                                //echo "<option value=''>Select</option>";
                                $sql_uesr_group = "select farmer_id as fieldkey, farmer_name as fieldtext from $tbl" . "farmer_info where status='Active' AND farmer_id='".$editrow['farmer_id']."'";
                                echo $db->SelectList($sql_uesr_group, $editrow['farmer_id']);
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
                            <select disabled id="crop_id" name="crop_id" class="span5" placeholder="Select Crop" validate="Require" onchange="load_product_type()">
                                <?php
                                //echo "<option value=''>Select</option>";
                                $sql_uesr_group = "select crop_id as fieldkey, crop_name as fieldtext from $tbl" . "crop_info where status='Active' AND crop_id='".$editrow['crop_id']."' ORDER BY $tbl" . "crop_info.order_crop";
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
                                //echo "<option value=''>Select</option>";
                                $sql_uesr_group = "select product_type_id as fieldkey, product_type as fieldtext from $tbl" . "product_type where status='Active' AND del_status='0' AND crop_id='".$editrow['crop_id']."' AND product_type_id='".$editrow['product_type_id']."'";
                                echo $db->SelectList($sql_uesr_group, $editrow['product_type_id']);
                                ?>
                            </select>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="crop_id">
                            Variety Name
                        </label>
                        <div class="controls">
                            <select disabled id="variety_id" name="variety_id" class="span5" placeholder="Select Product" validate="Require">
                                <?php
                                //echo "<option value=''>Select</option>";
//                                $sql_uesr_group = "select pdo_id as fieldkey, pdo_name as fieldtext from $tbl" . "pdo_product_info where status='Active' AND del_status='0' AND pdo_id='".$editrow['variety_id']."'";
                                $sql_uesr_group = "
                                                select
                                                    varriety_id as fieldkey,
                                                    CONCAT_WS(' - ', varriety_name,
                                                    CASE
                                                            WHEN type=0 THEN 'ARM'
                                                            WHEN type=1 THEN 'Check Variety'
                                                            WHEN type=2 THEN 'Upcoming'
                                                    END, hybrid) as fieldtext
                                                from $tbl" . "varriety_info
                                                where
                                                    status='Active'
                                                    AND del_status='0'
                                                    AND varriety_id='".$editrow['variety_id']."'
                                                ORDER BY order_variety";
                                echo $db->SelectList($sql_uesr_group, $editrow['variety_id']);
                                ?>
                            </select>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="upload_date">
                            Showing Date
                        </label>
                        <div class="controls">
                            <div class="input-append">
                                <input readonly="" type="text" name="sowing_date" id="sowing_date" value="<?php echo $db->date_formate($editrow['sowing_date'])?>" class="span9" placeholder="Date" validate="Require"  />
                                <span class="add-on" id="calcbtn_sowing_date">
                                    <i class="icon-calendar"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="upload_date">
                            Transplanting Date
                        </label>
                        <div class="controls">
                            <div class="input-append">
                                <input readonly="" type="text" name="transplanting_date" id="transplanting_date" value="<?php echo $db->date_formate($editrow['transplanting_date'])?>" class="span9" placeholder="Date" validate="Require"  />
                                <span class="add-on" id="calcbtn_transplanting_date">
                                    <i class="icon-calendar"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="crop_id">
                            Fruit Type
                        </label>
                        <div class="controls">
                            <select disabled id="fruit_type" name="fruit_type" class="span5" placeholder="Select Product" validate="Require">
                                <option value=""><?php echo $editrow['fruit_type'];?></option>

                            </select>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="description">
                            Fruit Set
                        </label>
                        <div class="controls">
                            <input readonly type="text" class="span1" name="fruit_set" id="fruit_set" value="<?php echo $editrow['fruit_set'];?>" placeholder="" validate="Require" />
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="description">
                            No of Image
                        </label>
                        <div class="controls">
                            <input readonly type="text" class="span1" name="number_of_img" id="number_of_img" value="<?php echo $editrow['number_of_img']?>" placeholder="" onblur="fnc_number_of_img()" validate="Require" />
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <div style="width: auto; overflow: auto;">
                            <table class="" width="" border="" >
                                <thead>
                                <tr>
                                    <th colspan="21" >Image Date</th>
                                </tr>

                                <tr>

                                    <?php
                                    $number_of_img= $editrow['number_of_img'];
                                    $sl=1;
                                    for($i=0; $i<$number_of_img; $i++)
                                    {
                                        $inc_day=($sl*7);
                                        $time = strtotime($editrow['sowing_date']);
                                        $final = date("d-m-Y", strtotime($inc_day." day", $time));
                                        ?>

                                        <th>
                                            <?php echo $sl;?>. <br />
                                            <img src="../../system_images/blank_img.png" width="100" style="border: 1px solid rgba(0, 0, 0, 0.33); padding: 5px 10px 5px 10px"/>
                                            <br />
                                            <div class="input-append">
                                                <input readonly="" type="text" name="upload_date[]" id="upload_date<?php echo $i;?>" value="<?php echo $final;?>" class="span10" placeholder="Select Date" validate="Require"  />
                                                <!--            <span class="add-on" id="calcbtn_upload_date--><?php //echo $i;?><!--">-->
                                                <!--                <i class="icon-calendar"></i>-->
                                                <!--            </span>-->
                                            </div>

                                            <script>
                                                //                        var cal = Calendar.setup({
                                                //                            onSelect: function(cal) { cal.hide() },
                                                //                            fdow :0,
                                                //                            minuteStep:1
                                                //                        });
                                                //                        cal.manageFields("calcbtn_upload_date<?php //echo $i;?>//", "upload_date<?php //echo $i;?>//", "%d-%m-%Y");
                                            </script>
                                        </th>

                                        <?php
                                        ++$sl;
                                    }
                                    ?>
                                </tr>
                                </thead>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
