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
        Farmer Name
    </label>
    <div class="controls">
        <select id="farmer_id" name="farmer_id" class="span5" placeholder="Select Farmer" validate="Require">
            <?php
            //                                echo "<option value=''>Select</option>";
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
        <select id="crop_id" name="crop_id" class="span5" placeholder="Select Crop" validate="Require" onchange="load_product_type()">
            <?php
            //                                echo "<option value=''>Select</option>";
            $sql_uesr_group = "select crop_id as fieldkey, crop_name as fieldtext from $tbl" . "crop_info where status='Active' AND crop_id='".$editrow['crop_id']."'";
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
            <!--                                <option value="">Select</option>-->
            <?php
            //                                echo "<option value=''>Select</option>";
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
        <select id="variety_id" name="variety_id" class="span5" placeholder="Select Product" validate="Require">
            <!--                                <option value="">Select</option>-->
            <?php
            //                                echo "<option value=''>Select</option>";
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

<div id="show_data">

</div>
<div id="shadow" onclick="close_image()"></div>

<div class="control-group">
    <div id="div_number_of_img" style="width: auto; overflow: auto;">
        <table class="table table-condensed table-striped table-bordered table-hover no-margin" width="" border="" >
            <thead>
            <tr>
                <th colspan="21" >Upload Product Photo</th>
            </tr>

            <tr>

                <?php
                $sl=1;
                $sql="select * from $tbl"."pdo_variety_setting_img_date where vs_id='".$editrow['vs_id']."'";
                if($db->open())
                {
                    $result=$db->query($sql);
                    while($row=$db->fetchArray($result))
                    {
                        $crop_row_id=$row['id'];
                        if($row['crop_img_url']=="")
                        {
                            $crop_blank_img= "<img src='../../system_images/blank_img.png' width='100' style='border: 1px solid rgba(0, 0, 0, 0.33); padding: 5px 10px 5px 10px'/>";
                        }
                        else
                        {
                            $crop_blank_img="<img src='../../system_images/pdo_upload_image/crop_img_url/$row[crop_img_url]' width='100' style='cursor: pointer; border: 1px solid rgba(0, 0, 0, 0.33); padding: 5px 10px 5px 10px' onclick='product_image_info($crop_row_id)'/>";
                        }
                        ?>

                        <th>
                            <?php echo $sl;?>.<br />
                            <?php echo $crop_blank_img;?>
                            <br />
                            <input readonly="" type="text" class="span12" value="<?php echo $db->date_formate($row['upload_date'])?>"/>
                            <br />
                            <textarea readonly class="span12" id="crop_remark[]" name="crop_remark[]" ><?php echo $row['crop_remark']; ?></textarea>
                        </th>

                        <?php
                        ++$sl;
                    }
                }
                ?>
            </tr>
            </thead>
        </table>
    </div>
</div>
<div class="control-group">
    <div id="div_number_of_fruit_img" style="width: auto; overflow: auto;">
        <table class="table table-condensed table-striped table-bordered table-hover no-margin" width="" border="" >
            <thead>
            <tr>
                <th colspan="21" >Upload Fruit Photo</th>
            </tr>

            <tr>

                <?php
                $sl=1;
                $j=0;
                $txtstr='';
                $sql="select * from $tbl"."pdo_variety_setting_fruit_img where vs_id='".$editrow['vs_id']."'";
                if($db->open())
                {
                    $result=$db->query($sql);
                    $fnumrow=$db->numRows($result);
                    if($fnumrow > 1)
                    {
                        while($row=$db->fetchArray($result))
                        {
                            $txtstr="Full Plot ";
                            if(($j%4)==1)
                            {
                                $txtstr="Single Plant";
                            }
                            else if(($j%4)==2)
                            {
                                $txtstr="Close ".$editrow['fruit_type'];
                            }
                            else if(($j%4)==3)
                            {
                                $txtstr="Several ".$editrow['fruit_type']." (After Harvest)";
                            }
                            $fruit_row_id=$row['id'];
                            ?>

                            <th>
                                <?php echo $sl;?>.<br />
                                <?php
                                if($row['fruit_img_url']=="")
                                {
                                    ?>
                                    <img src='../../system_images/blank_img.png' width='100' style='border: 1px solid rgba(0, 0, 0, 0.33); padding: 5px 10px 5px 10px'/>
                                <?php
                                }
                                else
                                {
                                    ?>
                                    <img src="../../system_images/pdo_upload_image/fruit_img_url/<?php echo $row['fruit_img_url']?>" width='100' style='border: 1px solid rgba(0, 0, 0, 0.33); cursor: pointer; padding: 5px 10px 5px 10px'  onclick="product_fruit_image_info('<?php echo $fruit_row_id?>')"/>
                                <?php
                                }
                                ?>
                                <br />
                                <input disabled type="text" id="fruit_caption[]" name="fruit_caption[]" class="span12" value="<?php echo $txtstr;?>" />
                                <br />
                                <textarea readonly id="fruit_remark[]" name="fruit_remark[]" class="span12" ><?php echo $row['fruit_remark']; ?></textarea>
                            </th>

                            <?php
                            ++$sl;
                            ++$j;
                        }

                    }
                    else
                    {
                        //echo "nai";
                    }

                }
                ?>
            </tr>
            </thead>
        </table>
    </div>
</div>
<div class="widget-body">

    <div class="wrapper" style="width: auto; overflow: auto;">

        <table class="table table-condensed table-striped table-bordered table-hover no-margin">
            <tr>
                <th colspan="21" >Disease Photo</th>
            </tr>
            <tbody>

            <?php
            $sl=1;
            //                                    $j=0;
            $disease_blank_img='';
            $txtstr='';
            $sql="select * from $tbl"."pdo_variety_setting_disease_img where vs_id='".$editrow['vs_id']."'";
            if($db->open())
            {
                $result = $db->query($sql);
                while($row=$db->fetchArray($result))
                {
                    $disease_id=$row['disease_id'];

                    ?>
                    <th>
                        <?php echo $sl;?>.<br />
                        <?php
                        if ($row['disease_img_url'] == "")
                        {
                            ?>
                            <img src='../../system_images/blank_img.png' width='100' style='border: 1px solid rgba(0, 0, 0, 0.33); padding: 5px 10px 5px 10px'/>
                            <?php
                        }
                        else
                        {
                            ?>
                            <img src='../../system_images/pdo_upload_image/disease_img_url/<?php echo $row['disease_img_url'];?>' width='50' style='cursor: pointer;border: 1px solid rgba(0, 0, 0, 0.33); padding: 5px 10px 5px 10px'  onclick="product_disease_image_info('<?php echo $disease_id?>')"/>
                            <?php
                        }
                        ?>
                        <br />
                        <input type="hidden" id="disease_id[]" name="disease_id[]" value="<?php echo $row['disease_id']?>" />
                        <br />
                        <input disabled type="text" class="span12" value="<?php echo $row['upload_date'];?>" />
                        <br />
                        <textarea readonly id="disease_remark[]" name="disease_remark[]" ><?php echo $row['disease_remark']; ?></textarea>
                    </th>

                    <?php
                    ++$sl;
                }
            }
            ?>

            </tbody>
        </table>
    </div>
</div>
</div>
</div>
</div>
</div>
</div>
