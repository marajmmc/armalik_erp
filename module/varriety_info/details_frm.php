<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$tbl = _DB_PREFIX;
$editrow = $db->single_data($tbl . "varriety_info", "*", "varriety_id", $_POST['rowID']);
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
                            <input disabled type="radio" id="type" name="type" value="0" class="variety_type" <?php if($editrow['type']==0){echo "checked='checked'";}?> /> &nbsp; ARM &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                            <input disabled type="radio" id="type" name="type" value="1" class="variety_type" <?php if($editrow['type']==1){echo "checked='checked'";}?> /> &nbsp; Competitor &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                            <input disabled type="radio" id="type" name="type" value="2" class="variety_type" <?php if($editrow['type']==2){echo "checked='checked'";}?> /> &nbsp; Upcoming &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="crop_id">
                            Crop
                        </label>
                        <div class="controls">
                            <select id="crop_id" name="crop_id" disabled="" class="span5" placeholder="Crop" validate="Require">
                                <?php
                                $db_crop=new Database();
                                $db_crop->get_crop($editrow['crop_id'], $editrow['crop_id']);
                                ?>
                            </select>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="product_type_id">
                            Select Product Type
                        </label>
                        <div class="controls">
                            <select disabled="" id="product_type_id" name="product_type_id" class="span5" placeholder="Select Product Type" validate="Require">
                                <option value="">Select</option>
                                <?php
                                $sql_uesr_group = "select product_type_id as fieldkey, product_type as fieldtext from $tbl" . "product_type where status='Active' AND del_status='0' AND crop_id='".$editrow['crop_id']."'";
                                echo $db->SelectList($sql_uesr_group, $editrow['product_type_id']);
                                ?>
                                ?>
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
                            <input class="span9" type="text" disabled="" name="varriety_name" id="varriety_name" value="<?php echo $editrow['varriety_name']?>" placeholder="Variety Name" validate="Require">
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="hybrid">
                            F1/Hybrid
                        </label>
                        <div class="controls">
                            <select disabled id="hybrid" name="hybrid" class="span5" placeholder="Product Type" >
                                <option value="">Select</option>
                                <option value="F1 Hybrid" <?php if($editrow['hybrid']=="F1 Hybrid"){echo "selected='selected'";}?>>F1 Hybrid</option>
                                <option value="OP" <?php if($editrow['hybrid']=="OP"){echo "selected='selected'";}?>>OP</option>
                            </select>
                        </div>
                    </div>
                    <div class="control-group competitor" style='display:<?php if($editrow['type']!=1){echo "none";}?>'>
                        <label class="control-label" for="company_name">
                            Company Name
                        </label>
                        <div class="controls">
                            <input disabled class="span9" type="text" name="company_name" id="company_name" value="<?php echo $editrow['company_name'];?>" placeholder="Company Name">
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
                            <input disabled class="span3" type="text" name="order_variety" id="order_variety" value="<?php echo $editrow['order_variety'];?>" placeholder="Order Variety">
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
                            <textarea class="span9" disabled="" name="description" id="description" placeholder="Description" ><?php echo $editrow['description']?></textarea>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="blood_group">
                            Status
                        </label>
                        <div class="controls controls-row">
                            <select id="status" name="status" disabled="" class="span9" placeholder="Group Name">
                                <option value="">Select</option>
                                <option value="Active" <?php
                                if ($editrow['status'] == "Active") {
                                    echo "selected='selected'";
                                }
                                ?> >Active</option>
                                <option value="In-Active" <?php
                                        if ($editrow['status'] == "In-Active") {
                                            echo "selected='selected'";
                                        }
                                ?> >In-Active</option>

                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>