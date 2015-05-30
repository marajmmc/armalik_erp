<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$dbrow = new Database();
$tbl = _DB_PREFIX;
$editrow = $dbrow->single_data($tbl . "assign_variety_pri", "*", "id", $_POST['rowID']);
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
                            <select id="employee_id" name="employee_id" class="span5" placeholder="Select Farmer" validate="Require">
                                <?php
                                echo "<option value=''>Select</option>";
                                $sqlpri="SELECT ait_employee_basic_info.employee_id as fieldkey,
                                                ait_employee_basic_info.employee_name as fieldtext
                                            FROM ait_employee_basic_info
                                            WHERE ait_employee_basic_info.department='R&D Farm'";
                                echo $db->SelectList($sqlpri, $editrow['employee_id']);
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
                            <select id="product_type_id" name="product_type_id" class="span5" placeholder="Product Type" onchange="load_pdo_variety()">
                                <?php
                                echo "<option value=''>Select</option>";
                                $sql_uesr_group = "select product_type_id as fieldkey, product_type as fieldtext from $tbl" . "product_type where status='Active' AND del_status='0' AND crop_id='".$editrow['crop_id']."'";
                                echo $db->SelectList($sql_uesr_group, $editrow['product_type_id']);
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="control-group">
                        <div id="div_pdo_variety">
                            <table class="table table-condensed table-striped table-bordered table-hover no-margin">
                                <thead>
                                <tr>
                                    <th style="width:40%">
                                        Variety
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $dbzoneacs=new Database();
                                $crop_id="AND $tbl" . "varriety_info.crop_id='".$editrow['crop_id']."'";
                                $product_type_id="AND $tbl" . "varriety_info.product_type_id='".$editrow['product_type_id']."'";

                                $sql = "
                                        select
                                        varriety_id as pdo_id,
                                        CONCAT_WS(' - ', varriety_name,
                                        CASE
                                                WHEN $tbl" . "varriety_info.type=0 THEN 'ARM'
                                                WHEN $tbl" . "varriety_info.type=1 THEN 'Check Variety'
                                                WHEN $tbl" . "varriety_info.type=2 THEN 'Upcoming'
                                        END, hybrid) as varriety_name,
                                        $tbl" . "varriety_info.crop_id,
                                        $tbl" . "varriety_info.product_type_id
                                        from $tbl" . "varriety_info
                                        where
                                        $tbl" . "varriety_info.status='Active'
                                        AND $tbl" . "varriety_info.del_status='0'
                                        $crop_id
                                        $product_type_id
                                        ORDER BY order_variety";
                                $i = 0;
                                if ($db->open()) {
                                    $result = $db->query($sql);
                                    $tmp = '';
                                    while ($result_array = $db->fetchAssoc()) {
                                        if ($i % 2 == 0) {
                                            $rowcolor = "gradeC";
                                        } else {
                                            $rowcolor = "gradeA success";
                                        }
                                        $access = $dbzoneacs->single_data_w($tbl . "assign_variety_pri", "variety_id", "employee_id='$editrow[employee_id]' AND crop_id='$result_array[crop_id]' AND product_type_id='$result_array[product_type_id]' AND variety_id='$result_array[pdo_id]'");

                                        if ($result_array['pdo_id'] == $access['variety_id']) {
                                            $acs_check = "checked='checked'";
                                        } else {
                                            $acs_check = "";
                                        }
                                        echo "<tr class='row_hover' $rowcolor>
                                                    <td align='left'>
                                                        <input type='checkbox' $acs_check name='$result_array[pdo_id]' id='$result_array[pdo_id]' value='$result_array[pdo_id]'  />
                                                        $result_array[varriety_name]
                                                        <input type='hidden' id='elmIndex[]' name='elmIndex[]' value='$result_array[pdo_id]'/>
                                                    </td>
                                            </tr>";
                                        ++$i;
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
