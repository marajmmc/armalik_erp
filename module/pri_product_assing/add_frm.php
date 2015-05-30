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
                        <label class="control-label" for="farmer_id">
                            PRI Name
                        </label>
                        <div class="controls">
                            <select id="employee_id" name="employee_id" class="span5" placeholder="Select Farmer" validate="Require">
                                <?php
                                echo "<option value=''>Select</option>";
                                $sqlpri="SELECT ait_employee_basic_info.employee_id as fieldkey,
                                                ait_employee_basic_info.employee_name as fieldtext
                                            FROM ait_employee_basic_info
                                            WHERE ait_employee_basic_info.department='R&D Farm'";
                                echo $db->SelectList($sqlpri);
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
                            <select id="product_type_id" name="product_type_id" class="span5" placeholder="Product Type" onchange="load_pdo_variety()">
                                <option value="">Select</option>
                            </select>
                        </div>
                    </div>
                    <div class="control-group">
                        <div id="div_pdo_variety">

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

